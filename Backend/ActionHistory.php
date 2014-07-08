<?php
class Membership_Backend_ActionHistory extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_action_history';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_ActionHistory';

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
    	if( !$_onlyIds && ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
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
    	Tinebase_User::getInstance()->resolveUsers($record, 'created_by_user');
    	Tinebase_User::getInstance()->resolveUsers($record, 'processed_by_user');
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
    	if($record->__get('parent_member_id')){
    		$this->appendForeignRecordToRecord($record, 'parent_member_id', 'parent_member_id', 'id', new Membership_Backend_SoMember());

            $member = $record->__get('parent_member_id');
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
			$record->__set('parent_member_id',$member);
	   	}
        if($record->__get('association_id')){
    		$this->appendForeignRecordToRecord($record, 'association_id', 'association_id', 'id', new Membership_Backend_Association());
    	}   
       	if($record->__get('action_id')){
    		$this->appendForeignRecordToRecord($record, 'action_id', 'action_id', 'id', new Membership_Backend_Action());
    	}
       if($record->__get('order_id')){
    		$this->appendForeignRecordToRecord($record, 'order_id', 'order_id', 'id', new Billing_Backend_Order());
       }
       if($record->__get('receipt_id')){
    		$this->appendForeignRecordToRecord($record, 'receipt_id', 'receipt_id', 'id', new Billing_Backend_Receipt());
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