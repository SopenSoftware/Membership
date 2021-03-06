<?php
class Membership_Backend_OpenItem extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'bill_open_item';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_OpenItem';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
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
        if($record->__get('order_id')){
    		$this->appendForeignRecordToRecord($record, 'order_id', 'order_id', 'id', new Billing_Backend_Order());
        }
        if($record->__get('receipt_id')){
    		$this->appendForeignRecordToRecord($record, 'receipt_id', 'receipt_id', 'id', new Billing_Backend_Receipt());
        }
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
        if($record->__get('payment_method_id')){
    		$this->appendForeignRecordToRecord($record, 'payment_method_id', 'payment_method_id', 'id', new Billing_Backend_PaymentMethod());
        }  
    }
    
    public function get($id, $_getDeleted = FALSE){
    	$record = parent::get($id, $_getDeleted);
    	$this->appendDependentRecords($record);
    	return $record;
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
       	
        
        $select->joinLeft(array('rp' => $this->_tablePrefix . 'bill_receipt_position'),
			$this->_db->quoteIdentifier($this->_tableName . '.receipt_id') . ' = ' . $this->_db->quoteIdentifier('rp.receipt_id'),
        array());        
        
        $select->joinLeft(array('op' => $this->_tablePrefix . 'bill_order_position'),
          $this->_db->quoteIdentifier('op.id') . ' = ' . $this->_db->quoteIdentifier('rp.order_position_id'),
        array());
        
        $select->columns(array(
         	'total_netto'                  => 'SUM(op.total_netto)',
           	'total_brutto'                  => 'SUM(op.total_brutto)'
        ));
        
       	$select->where($this->_db->quoteIdentifier($this->_tableName . '.erp_context_id') . ' = ?', 'MEMBERSHIP');  
        
        $select->group(array('rp.receipt_id'));
       
        return $select;
    } 
    
    /**
     * Gets total count of search with $_filter
     * 
     * @param Tinebase_Model_Filter_FilterGroup $_filter
     * @return int
     */
    public function searchCount(Tinebase_Model_Filter_FilterGroup $_filter)
    {   
        if ($this->_useSubselectForCount) {
            // use normal search query as subselect to get count -> select count(*) from (select [...]) as count
            $select = parent::_getSelect($_cols, $_getDeleted);
			
        
            $this->_addFilter($select, $_filter);
            
            $select->where($this->_db->quoteIdentifier($this->_tableName . '.erp_context_id') . ' = ?', 'MEMBERSHIP'); 
        
            $countSelect = $this->_db->select()->from($select, array('count' => 'COUNT(*)'));
            //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . $countSelect->__toString());
            
            $result = $this->_db->fetchOne($countSelect);
        } else {
            $select = $select = parent::_getSelect(array('count' => 'COUNT(*)'));
            
            
            $this->_addFilter($select, $_filter);
            
            $select->where($this->_db->quoteIdentifier($this->_tableName . '.erp_context_id') . ' = ?', 'MEMBERSHIP'); 
        
            //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . $select->__toString());

            $result = $this->_db->fetchOne($select);
        }
        
        return $result;        
    }    
}
?>