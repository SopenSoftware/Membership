<?php
class Membership_Backend_SoMemberFeeProgress extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_fee_progress';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_SoMemberFeeProgress';

    protected $paymentStateMap = array(
    	'OPEN' => 'TOBEPAYED',
    	'PARTLYOPEN' => 'PARTLYPAYED',
    	'DONE'	 => 'PAYED'
    );
    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// no ids searchable
    	// check if needed anywhere and modify if so
    	/*$recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		$feeProgressExtBackend = new Membership_Backend_SoMemberFeeProgressExt();
    		foreach($it as $key => $record){
    			if($record->__get('fee_progress_ext_id')){
    				$this->appendForeignRecordToRecord($record, 'fee_progress_ext_id', 'fee_progress_ext_id', 'id', $feeProgressExtBackend);
    			}
    		}
    	}
    	return $recordSet;*/
       	$recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    public function get($id, $_getDeleted = FALSE){
    	$record = parent::get($id, $_getDeleted);
    	$this->appendDependentRecords($record);
    	return $record;
    }
    
        /**
     * Append contacts by foreign key (record embedding)
     * 
     * @param Tinebase_Record_Abstract $record
     * @return void
     */
    protected function appendDependentRecords($record){
    	
    	$paymentState = $record->__get('payment_state');
    	if(!$paymentState){
    		$paymentState = 'NOTDUE';
    	}else{
    		
    		$paymentState = $this->paymentStateMap[$paymentState];
    	}
    	
    	$record->__set('payment_state', $paymentState);
    	
        if($record->__get('member_id')){
    		$this->appendForeignRecordToRecord($record, 'member_id', 'member_id', 'id', new Membership_Backend_SoMember());

            $member = $record->__get('member_id');
    		try{
    			if(is_object($member)){
    				$contactId = $member->__get('contact_id');
    			}else{
    				$contactId = $member->contact_id;
    			}
    			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    			if(is_object($member)){
    				$member->__set('contact_id',$contact->toArray());
    			}else{
    				$member->contact_id = $contact->toArray();
    			}
    		}catch(Exception $e){
    		}
			$record->__set('member_id',$member);
	   }
       if($record->__get('parent_member_id')){
    		$this->appendForeignRecordToRecord($record, 'parent_member_id', 'parent_member_id', 'id', new Membership_Backend_SoMember());

            $member = $record->__get('parent_member_id');
    		try{
    			if(is_object($member)){
    				$contactId = $member->__get('contact_id');
    			}else{
    				$contactId = $member->contact_id;
    			}
    			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    			if(is_object($member)){
    				$member->__set('contact_id',$contact->toArray());
    			}else{
    				$member->contact_id = $contact->toArray();
    			}
    		}catch(Exception $e){
    		}
			$record->__set('parent_member_id',$member);
	   }
     	if($record->__get('fee_definition_id')){
    		$this->appendForeignRecordToRecord($record, 'fee_definition_id', 'fee_definition_id', 'id', new Membership_Backend_FeeDefinition());
       }
   	   if($record->__get('fee_group_id')){
    		$this->appendForeignRecordToRecord($record, 'fee_group_id', 'fee_group_id', 'id', new Membership_Backend_FeeGroup());
       }
       if($record->__get('order_id')){
    		$this->appendForeignRecordToRecord($record, 'order_id', 'order_id', 'id', new Billing_Backend_Order());
       }
       if($record->__get('invoice_receipt_id')){
    		$this->appendForeignRecordToRecord($record, 'invoice_receipt_id', 'invoice_receipt_id', 'id', new Billing_Backend_Receipt());
       }
    	if($record->__get('cancellation_receipt_id')){
    		$this->appendForeignRecordToRecord($record, 'cancellation_receipt_id', 'cancellation_receipt_id', 'id', new Billing_Backend_Receipt());
       }
    }
    
    public function searchCount(Tinebase_Model_Filter_FilterGroup $_filter)
    {        
        $select = $this->_getSelect(array('count' => 'COUNT(*)'));
        $this->_addFilter($select, $_filter);
        
        // fetch complete row here
        $result = $this->_db->fetchRow($select);
        return $result;        
    } 
    
    protected function _getSelect($_cols = '*', $_getDeleted = FALSE)
    {    
    	
    	$select = $this->_db->select();    
        
        if (is_array($_cols) && isset($_cols['count'])) {
             $cols = array(
             	'count'             => 'COUNT(*)',
             	'sum'               => 'SUM(receipt.total_brutto)',
                'sum_preview'               => 'SUM(fee_to_calculate)',
             	'sum_open'               => 'SUM(op.open_sum)',
             	'sum_payed'               => 'SUM(op.payed_sum)'
            );
            	//$field ="IF((entry_year IS NOT NULL) AND (entry_year>0) ,($baseYear - entry_year),0)";
    
        }else{
        	$cols = (array)$_cols;
        	$aCols = array(
        	// op: payment_state (OPEN, PARTLYOPEN, DONE) -> maps (PAYED,PARTLYPAYED,TOBEPAYED)
        	//'payment_state' => "(IF(receipt.payment_state IS NULL,'NOTDUE',receipt.payment_state))",
        	'payment_state' => "op.state",
        	'sum_brutto' => 'receipt.total_brutto',
        	'open_sum' => 'op.open_sum',
        	'payed_sum' => 'op.payed_sum',
        	'is_cancelled' => 'op.is_cancelled',
        	'cancellation_receipt_id' => 'op.reversion_record_id',
        	'due_days'					=> 'DATEDIFF('.$this->_db->quote($this->getDueDate()->toString('yyyy-MM-dd')).',receipt.due_date)',
           	'payment_date' 				=> 'pay.payment_date',
        	'monition_stage'			=> 'op.monition_stage',
           	'fg_begin_datetime' => 'membership.begin_datetime',
        	'fg_termination_datetime' => 'membership.termination_datetime',
        	'fg_membership_status' => 'membership.membership_status',
        	'fg_member_nr' => 'membership.member_nr'
        	
        	
            );
            $cols = array_merge($cols, $aCols);
        }

        $select->from(array($this->_tableName => $this->_tablePrefix . $this->_tableName), $cols);
     
    	
    	$select->joinLeft(array('membership' => $this->_tablePrefix . 'membership'),
        	$this->_db->quoteIdentifier($this->_tableName . '.member_id') . ' = ' . $this->_db->quoteIdentifier('membership.id'),
        	array()); 
        	
        $select->joinLeft(array('receipt' => $this->_tablePrefix . 'bill_receipt'),
        	$this->_db->quoteIdentifier($this->_tableName . '.invoice_receipt_id') . ' = ' . $this->_db->quoteIdentifier('receipt.id'),
        	array()); 
        $select->joinLeft(array('op' => $this->_tablePrefix . 'bill_open_item'),
        	$this->_db->quoteIdentifier('op.receipt_id') . ' = ' . $this->_db->quoteIdentifier($this->_tableName . '.invoice_receipt_id')
        	,
        	array()); 
        	
        $select->joinLeft(array('pay' => $this->_tablePrefix . 'bill_payment'),
			$this->_db->quoteIdentifier('op.payment_id') . ' = ' . $this->_db->quoteIdentifier('pay.id'),
        array());  
        //echo $select->assemble();
        return $select;
    }
}
?>