<?php
class Membership_Backend_FeeVarOrderPos extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_vars_order_pos';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_FeeVarOrderPos';

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
    	if($record->__get('use_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'use_fee_var_config_id', 'use_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
      	if($record->__get('amount_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'amount_fee_var_config_id', 'amount_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
        if($record->__get('price_netto_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'price_netto_fee_var_config_id', 'price_netto_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
        if($record->__get('price_brutto_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'price_brutto_fee_var_config_id', 'price_brutto_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
        if($record->__get('name_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'name_fee_var_config_id', 'name_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
    	}
        if($record->__get('factor_fee_var_config_id')){
    		$this->appendForeignRecordToRecord($record, 'factor_fee_var_config_id', 'factor_fee_var_config_id', 'id', new Membership_Backend_FeeVarConfig());
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
}
?>