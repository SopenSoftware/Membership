<?php
class Membership_Backend_MembershipAccount extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_account';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_MembershipAccount';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// no ids searchable
    	// check if needed anywhere and modify if so
        $recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    /**
     * Append contacts by foreign key (record embedding)
     * 
     * @param Tinebase_Record_Abstract $record
     * @return void
     */
    protected function appendDependentRecords($record){
    	Tinebase_User::getInstance()->resolveUsers($record, 'account_id');
        
    	$user = Tinebase_User::getInstance()->getFullUserById($record->__get('account_id'));
    	$record->__set('account_loginname', $user->__get('accountLoginName'));
    	$record->__set('account_emailadress', $user->__get('accountEmailAddress'));
    	$record->__set('account_lastpasswordchange', $user->__get('accountLastPasswordChange'));
    	$record->__set('account_lastlogin', $user->__get('accountLastLogin'));
    	
    	
    	if($record->__get('contact_id')){
	    	$this->appendForeignRecordToRecord($record, 'contact_id', 'contact_id', 'id', Addressbook_Backend_Factory::factory(Addressbook_Backend_Factory::SQL));
	    }
    	if($record->__get('member_id')){
    		$this->appendForeignRecordToRecord($record, 'member_id', 'member_id', 'id', new Membership_Backend_SoMember());

            $member = $record->__get('member_id');
    		try{
    			if(is_object($member)){
    				$contactId = $member->__get('contact_id');
    			}else{
    				$contactId = $member->contact_id;
    			}
    			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    			if(is_object($member)){
    				$member->__set('contact_id',$contact->toArray());
    			}else{
    				$member->contact_id = $contact->toArray();
    			}
    		}catch(Exception $e){
    		}
			$record->__set('member_id',$member);
	   	}
    	if($record->__get('related_member_id')){
    		$this->appendForeignRecordToRecord($record, 'related_member_id', 'related_member_id', 'id', new Membership_Backend_SoMember());

            $member = $record->__get('related_member_id');
    		try{
    			if(is_object($member)){
    				$contactId = $member->__get('contact_id');
    			}else{
    				$contactId = $member->contact_id;
    			}
    			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    			if(is_object($member)){
    				$member->__set('contact_id',$contact->toArray());
    			}else{
    				$member->contact_id = $contact->toArray();
    			}
    		}catch(Exception $e){
    		}
			$record->__set('related_member_id',$member);
	   	}
    }
    /**
     * Get Membership record by id (with embedded dependent contacts)
     * 
     * @param int $id
     */
    public function get($id, $_getDeleted = FALSE){
    	$record = parent::get($id, $_getDeleted);
    	$this->appendDependentRecords($record);
    	return $record;
    }    
}
?>