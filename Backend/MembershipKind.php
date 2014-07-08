<?php
class Membership_Backend_MembershipKind extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_kind';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_MembershipKind';

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
      	if($record->__get('invoice_template_id')){
    		$this->appendForeignRecordToRecord($record, 'invoice_template_id', 'invoice_template_id', 'id', new DocManager_Backend_Template());
        } 
        if($record->__get('begin_letter_template_id')){
    		$this->appendForeignRecordToRecord($record, 'begin_letter_template_id', 'begin_letter_template_id', 'id', new DocManager_Backend_Template());
        } 
        if($record->__get('insurance_letter_template_id')){
    		$this->appendForeignRecordToRecord($record, 'insurance_letter_template_id', 'insurance_letter_template_id', 'id', new DocManager_Backend_Template());
        } 
        if($record->__get('termination_letter_template_id')){
    		$this->appendForeignRecordToRecord($record, 'termination_letter_template_id', 'termination_letter_template_id', 'id', new DocManager_Backend_Template());
        } 
    	if($record->__get('membercard_letter_template_id')){
    		$this->appendForeignRecordToRecord($record, 'membercard_letter_template_id', 'membercard_letter_template_id', 'id', new DocManager_Backend_Template());
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