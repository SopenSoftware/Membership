<?php
class Membership_Backend_Vote extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_vote';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_Vote';

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
      	if($record->__get('member_id')){
       		$societyMember = $record->getForeignRecordBreakNull('member_id', Membership_Controller_SoMember::getInstance());
       		if($societyMember){
       			$record->__set('member_id', $societyMember->toArray(true));
       		}
    		//$this->appendForeignRecordToRecord($record, 'parent_member_id', 'parent_member_id', 'id', new Membership_Backend_SoMember());
    	}
    	if($record->__get('transfer_member_id')){
    		$societyMember = $record->getForeignRecordBreakNull('transfer_member_id', Membership_Controller_SoMember::getInstance());
       		if($societyMember){
       			$record->__set('transfer_member_id', $societyMember->toArray(true));
       		}
    		//$this->appendForeignRecordToRecord($record, 'parent_member_id', 'parent_member_id', 'id', new Membership_Backend_SoMember());
    	}
        if($record->__get('association_id')){
    		$this->appendForeignRecordToRecord($record, 'association_id', 'association_id', 'id', new Membership_Backend_Association());
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
    
     protected function _getCountSelect($_cols = '*', $_getDeleted = FALSE, $_filter)
    {
    	$select = parent::_getSelect();
		
    	$this->_addFilter($select, $_filter);
    	
		$select->columns(array(
			'count' => 'COUNT(*)'
		));
		
		return $select;
	}
	
    /**
     * get the basic select object to fetch records from the database
     *  
     * @param array|string|Zend_Db_Expr $_cols columns to get, * per default
     * @param boolean $_getDeleted get deleted records (if modlog is active)
     * @return Zend_Db_Select
     */
    protected function _getSelect($_cols = '*', $_getDeleted = FALSE)
    {
        $select = parent::_getSelect($_cols, $_getDeleted);
        
        $select->joinLeft(array('mem' => $this->_tablePrefix . 'membership'),
			$this->_db->quoteIdentifier('member_id') . ' = ' . $this->_db->quoteIdentifier('mem.id'),
			array()
		);      
        
        $select->columns(array(
			'member_nr' => 'mem.member_nr'
        ));
		
        return $select;
    } 
}
?>