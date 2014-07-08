<?php
class Membership_Backend_SoMemberFeeProgressExt extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_fee_progress_ext';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_SoMemberFeeProgressExt';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function get($id, $getDeleted = false){
    	$record = parent::get($id,$getDeleted);
    	$this->appendDependentContacts($record);
    	return $record;
    }
    
    protected function appendDependentContacts($record){
      	if($record->__get('mc_procure_contact_id')){
    		$this->appendForeignRecordToRecord($record, 'mc_procure_contact_id', 'mc_procure_contact_id', 'id', Addressbook_Backend_Factory::factory(Addressbook_Backend_Factory::SQL));
    	}
    }
}
?>