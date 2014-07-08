<?php
class Membership_Backend_FeeVar extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_fee_vars';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_FeeVar';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// don't forget third parameter -> causes nasty filter bugs!
        $recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    protected function appendDependentRecords($record){
    	$record->__set('value', '');
//        if($record->__get('fee_progress_id')){
//    		$this->appendForeignRecordToRecord($record, 'fee_progress_id', 'fee_progress_id', 'id', new Membership_Backend_SoMemberFeeProgress());
//    	}
        if($record->__get('fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'fee_var_config_id', 'fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
        	$record->__set('value',$record->getValue($record->__get('fee_var_config_id')));
        }
       
    }
}
?>