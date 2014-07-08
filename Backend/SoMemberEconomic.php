<?php
class Membership_Backend_SoMemberEconomic extends Membership_Backend_SoMember
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'mem_eco';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_SoMemberEconomic';
 
    
    /**
     * Append contacts by foreign key (record embedding)
     * 
     * @param Tinebase_Record_Abstract $record
     * @return void
     */
    protected function appendDependentRecords($record){
    	
    	parent::appendDependentRecords($record);
    	
    	if($record->__get('debitor_id')){
    		$this->appendForeignRecordToRecord($record, 'debitor_id', 'debitor_id', 'id', new Billing_Backend_Debitor());
    		$debitor = $record->__get('debitor_id');
    		try{
    			if(is_object($debitor)){
    				$contactId = $debitor->__get('contact_id');
    			}else{
    				$contactId = $debitor->contact_id;
    			}
    			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    			if(is_object($debitor)){
    				$debitor->__set('contact_id',$contact->toArray());
    			}else{
    				$debitor->contact_id = $contact->toArray();
    			}
    		}catch(Exception $e){
    		}
			$record->__set('debitor_id',$debitor);
        }
        
        $record->__set('saldation', (float)($record->__get('h_brutto') - $record->__get('s_brutto')));
       	
    }
    
    
    
   /* protected function _getSelect($_cols = '*', $_getDeleted = FALSE)
    {        
        //$outerSelect = $this->_db->select();
    	
    	$select = parent::_getSelect($_cols, $_getDeleted);
    	
        // join with timeaccounts to get combined is_billable / is_cleared
        $select->joinLeft(array('debitor' => $this->_tablePrefix . 'bill_debitor'),
                    $this->_db->quoteIdentifier($this->_tableName . '.contact_id') . ' = ' . $this->_db->quoteIdentifier('debitor.contact_id'),
                    array()); 
        
        $select->joinLeft(array('debitor_account' => $this->_tablePrefix . 'bill_debitor_account'),
          $this->_db->quoteIdentifier('debitor_account.debitor_id') . ' = ' . $this->_db->quoteIdentifier('debitor.id'),
        array());        

        $select->joinLeft(array('open_item' => $this->_tablePrefix . 'bill_open_item'),
          '(('.$this->_db->quoteIdentifier('open_item.debitor_id') . ' = ' . $this->_db->quoteIdentifier('debitor.id') .') AND ' .
          '('.$this->_db->quoteIdentifier('open_item.state') . ' = ' . "'OPEN'" .') AND ' .
          '('.$this->_db->quoteIdentifier('open_item.erp_context_id') . ' = ' . "'MEMBERSHIP'" .'))',
          
        array());   
        
        $select->columns(array(
         	'debitor_id' 			=> 'debitor.id',
        	's_brutto'              => 'ABS(SUM(debitor_account.s_brutto))',
        	'h_brutto'              => 'ABS(SUM(debitor_account.h_brutto))',
        	'saldation'              => 'ABS(SUM(debitor_account.h_brutto))-ABS(SUM(debitor_account.s_brutto))',
        	'last_receipt_id'		=> null,
        	'last_receipt_date' => 'MAX(open_item.receipt_date)',
			'last_receipt_netto' => 0,
        	'last_receipt_brutto' => 0,
           	'count_open_items'       => 'COUNT(open_item.id)'
        ));
        
        $select->group(array('debitor.id'));
                    
        //$outerSelect->from( array('membership' => $select) );
        return $select;
    }*/
    
	/**
     * Gets total count of search with $_filter
     * 
     * @param Tinebase_Model_Filter_FilterGroup $_filter
     * @return int
     */
   /* public function searchCount(Tinebase_Model_Filter_FilterGroup $_filter)
    {   
    	
	   	$select = $this->_getSelect('*');
   	 	$select->columns(array('COUNT(*)'));
        $this->_addFilter($select, $_filter);
		$result =  $this->_db->fetchAll($select);
		return $result[0]['COUNT(*)'];
		   
    }    */
}
?>