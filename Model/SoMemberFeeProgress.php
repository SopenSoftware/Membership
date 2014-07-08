<?php

/**
 * class to hold SoMemberFeeProgress data
 *
 * @package     Membership
 */
class Membership_Model_SoMemberFeeProgress extends Tinebase_Record_Abstract
{
    /**
     * key in $_validators/$_properties array for the filed which
     * represents the identifier
     *
     * @var string
     */
    protected $_identifier = 'id';
    
    /**
     * application the record belongs to
     *
     * @var string
     */
    protected $_application = 'Membership';
    
    /**
     * list of zend validator
     *
     * this validators get used when validating user generated content with Zend_Input_Filter
     *
     * @var array
     *
     */
    protected $_validators = array(
        'id'                    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
        'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'parent_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_definition_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'fee_group_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'progress_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'order_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'invoice_receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'cancellation_receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'fee_from_datetime'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_to_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_year'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'is_calculation_approved'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_period_notes'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_calc_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'amount_admission_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'individual_yearly_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'age'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'fee_units'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'is_first'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'deb_summation'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_to_calculate'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'sum_brutto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'payment_state'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'is_cancelled'	=>  array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'open_sum'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'payed_sum'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'payment_date'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'monition_stage'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'due_days'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fg_begin_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fg_termination_datetime'  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fg_membership_status'	=>  array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fg_member_nr'  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
//       	'pm_order_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
//       	'pm_invoice_receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
//       	'pm_fee_calc_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
     	'fee_from_datetime',
    	'fee_to_datetime',
    	'fee_calc_datetime'
    );
    
public function setFromArray(array $_data)
	{
		if(empty($_data['fee_to_datetime']) || $_data['fee_to_datetime']==""){
			$_data['fee_to_datetime'] = null;
		}	
		if(empty($_data['fee_calc_datetime']) || $_data['fee_calc_datetime']=="" ){
			$_data['fee_calc_datetime'] = null;
		}	
		if(empty($_data['age']) || $_data['age']=="" ){
			$_data['age'] = null;
		}		
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['fee_to_datetime']) || $_data['fee_to_datetime']=="" ){
			$_data['fee_to_datetime'] = null;
		}		
		if(empty($_data['fee_calc_datetime']) || $_data['fee_calc_datetime']=="" ){
			$_data['fee_calc_datetime'] = null;
		}	
		if(empty($_data['begin_datetime']) || $_data['begin_datetime']=="" ){
			$_data['begin_datetime'] = null;
		}		
		if(empty($_data['age']) || $_data['age']=="" ){
			$_data['age'] = null;
		}	
	}
	
	public function tellFeeGroupName(){
		$feeGroup = $this->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
		if($feeGroup instanceof Membership_Model_FeeGroup){
			return $feeGroup->__get('name');
		}
		return null;
	}
	
	public function tellFeeGroupKey(){
		$feeGroup = $this->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
		if($feeGroup instanceof Membership_Model_FeeGroup){
			return $feeGroup->__get('key');
		}
		return null;
	}
	
	public function tellFeeDefinitionName(){
		$feeDefinition = $this->getForeignRecordBreakNull('fee_definition_id', Membership_Controller_FeeDefinition::getInstance());
		if($feeDefinition instanceof Membership_Model_FeeDefinition){
			return $feeDefinition->__get('name');
		}
		return null;
	}
	
	public function tellPaymentState(){
		$aMap = array(
			'NOTDUE' => 'noch nicht fällig/berechnet',
			'TOBEPAYED' => 'offen',
			'PARTLYPAYED' => 'teilbezahlt',
			'PAYED' => 'bezahlt'
		);
		return $aMap[$this->__get('payment_state')];
	}
	
	public function getReceiptText(){
		if($receipt = $this->getForeignRecordBreakNull('invoice_receipt_id', Billing_Controller_Receipt::getInstance())){
			return $receipt->getReceiptText();
		}
		return '';
	}
	
	public function getCancellationReceiptText(){
		if($receipt = $this->getForeignRecordBreakNull('cancellation_receipt_id', Billing_Controller_Receipt::getInstance())){
			return $receipt->getReceiptText();
		}
		return '';
	}
    
}