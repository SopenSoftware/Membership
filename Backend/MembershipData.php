<?php
class Membership_Backend_MembershipData extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_data';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_MembershipData';

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
//      	if($record->__get('contact_id')){
//    		$this->appendForeignRecordToRecord($record, 'contact_id', 'contact_id', 'id', Addressbook_Backend_Factory::factory(Addressbook_Backend_Factory::SQL));
//    	}
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
    
    /**
     * 
     * get the last membership data of history
     * @param string 	$memberId	Membership id
     * @param bool 		$doneOnly	Only done entries
     */
    public function getMaxIdForMember($memberId, $withPhantoms){
    	$select = $this->_db->select()
	    	->from(SQL_TABLE_PREFIX . 'membership_data', array('MAX(id)'))
    		->where( "member_id = '$memberId'")
    		->where( "phantom = '$withPhantoms'");
    	$stmt = $this->_db->query($select);
        $result = $stmt->fetchAll();
        return $result[0]['MAX(id)'];
    }
}
?>