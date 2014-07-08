<?php
class Membership_Backend_FeeVarConfig extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_fee_var_config';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_FeeVarConfig';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
            	// TODO HH: no ids searchable
    	// check if needed anywhere and modify if so
    	$recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( $withDep && ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
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
      	if($record->__get('feedef_dfilters_id')){
    		$this->appendForeignRecordToRecord($record, 'feedef_dfilters_id', 'feedef_dfilters_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
        }
    
    /**
     * (non-PHPdoc)
     * @see Tinebase_Backend_Sql_Abstract::get()
     */
    public function get($id, $_getDeleted = FALSE, $getDependent = true){
    	$record = parent::get($id, $_getDeleted);
    	if($getDependent){
    		$this->appendDependentRecords($record);
    	}
    	return $record;
    }
    
    public function getByDefFilterId($id){
    	return $this->getMultipleByProperty($id, 'feedef_dfilters_id');
    }
}
?>