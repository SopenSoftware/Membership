<?php
class Membership_Backend_Committee extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_committee';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_Committee';

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
//      	if($record->__get('member_id')){
//    		$this->appendForeignRecordToRecord($record, 'member_id', 'member_id', 'id', new Membership_Backend_SoMember());
//    	}
    	
    	if($record->__get('committee_kind_id')){
    		$this->appendForeignRecordToRecord($record, 'committee_kind_id', 'committee_kind_id', 'id', new Membership_Backend_CommitteeKind());
    	}
    	if($record->__get('committee_level_id')){
    		$this->appendForeignRecordToRecord($record, 'committee_level_id', 'committee_level_id', 'id', new Membership_Backend_CommitteeLevel());
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