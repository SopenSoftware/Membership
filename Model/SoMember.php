<?php

/**
 * class to hold SoEvent data
 *
 * @package     SoEventManager
 */
class Membership_Model_SoMember extends Tinebase_Record_Abstract
{
	const MEMBERSHIP_STATUS_ACTIVE = 'ACTIVE';
	const MEMBERSHIP_STATUS_PASSIVE = 'PASSIVE';
	const MEMBERSHIP_STATUS_DISCHARCHED = 'DISCHARGED';
	const MEMBERSHIP_STATUS_TERMINATED = 'TERMINATED';
	
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
        'contact_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'parent_member_id' 		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_group_id'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'account_id'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_nr_numeric'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_ext_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'affiliate_contact_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'begin_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'discharge_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'termination_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_from_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_to_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'entry_reason_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true,Zend_Filter_Input::DEFAULT_VALUE => 0),
    	'termination_reason_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true,Zend_Filter_Input::DEFAULT_VALUE => 0),
    	'exp_membercard_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_notes'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'invoice_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'membership_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'membership_status'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'society_sopen_user'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
    	'fee_payment_interval'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
    	'fee_payment_method'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'debit_auth_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_code'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_account_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'account_holder'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'is_online_user'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
      	'individual_admission_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'pays_admission_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
      	'admission_fee_payed'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
      	'has_individual_yearlyfee'            => array(Zend_Filter_Input::ALLOW_EMPTY => true),
      	'individual_yearly_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
      	'age_current_period'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'begin_progress_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'birth_date' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'birth_day' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'birth_month' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'birth_year' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'entry_year' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    
    	'member_age' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'person_age' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'sex' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    
    	'ext_system_username' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'ext_system_modified' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'print_reception_date' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'print_discharge_date' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'print_confirmation_date' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_card_year' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    // fields from contact
    	'account_id'					 => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'has_account'					 => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'additional_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'donation'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
   		'feegroup_prices'          => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => array()),
		// custom fields
    	'customfields'          => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => array()),
    	'public_comment'          => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
    
    	'status_due_date'					 => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'is_affiliator'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'affiliate_contact_id'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'affiliator_provision'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'affiliator_provision_date'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'is_affiliated'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'count_magazines'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'count_additional_magazines'  		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	
        'sepa_mandate_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'bank_account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_account_usage_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bic'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'iban'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_account_number'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'bank_account_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
       	'bank_account_bank_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'sepa_mandate_ident'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'sepa_signature_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
  
    );
    protected $_dateFields = array(
    // modlog
        'begin_datetime',
        'discharge_datetime',
        'termination_datetime',
    	'exp_membercard_datetime',
    	'fee_from_date',
    	'fee_to_date'
    );
    
	public function setFromArray(array $_data)
	{
		if(empty($_data['affiliate_contact_id']) || $_data['affiliate_contact_id']=="" || $_data['affiliate_contact_id']==0){
			$_data['affiliate_contact_id'] = null;
		}		
		if(empty($_data['begin_datetime']) || $_data['begin_datetime']=="" || $_data['begin_datetime']==0){
			$_data['begin_datetime'] = null;
		}			
		if(empty($_data['discharge_datetime']) || $_data['discharge_datetime']=="" || $_data['discharge_datetime']==0){
			$_data['discharge_datetime'] = null;
		}
		if(empty($_data['termination_datetime']) || $_data['termination_datetime']=="" || $_data['termination_datetime']==0){
			$_data['termination_datetime'] = null;
		}
		if(empty($_data['fee_from_date']) || $_data['fee_from_date']=="" || $_data['fee_from_date']==0){
			$_data['fee_from_date'] = null;
			//unset($_data['fee_from_date']);
		}
		if(empty($_data['fee_to_date']) || $_data['fee_to_date']=="" || $_data['fee_to_date']==0){
			//unset($_data['fee_to_date']);
			$_data['fee_to_date'] = null;
		}		
		if(empty($_data['birth_date']) || $_data['birth_date']=="" || $_data['birth_date']==0){
			//unset($_data['fee_to_date']);
			$_data['birth_date'] = null;
		}	
		if(empty($_data['debit_auth_date']) || $_data['debit_auth_date']=="" || $_data['debit_auth_date']==0){
			//unset($_data['fee_to_date']);
			$_data['debit_auth_date'] = null;
		}	
		if(empty($_data['exp_membercard_datetime']) || $_data['exp_membercard_datetime']=="" || $_data['exp_membercard_datetime']==0){
			$_data['exp_membercard_datetime'] = null;
		}
		
		if(empty($_data['ext_system_modified']) || $_data['ext_system_modified']=="" || $_data['ext_system_modified']==0){
			$_data['ext_system_modified'] = null;
		}
	
		if(empty($_data['print_reception_date']) || $_data['print_reception_date']=="" || $_data['print_reception_date']==0){
			$_data['print_reception_date'] = null;
		}
		if(empty($_data['print_discharge_date']) || $_data['print_discharge_date']=="" || $_data['print_discharge_date']==0){
			$_data['print_discharge_date'] = null;
		}
		if(empty($_data['print_confirmation_date']) || $_data['print_confirmation_date']=="" || $_data['ext_system_modified']==0){
			$_data['print_confirmation_date'] = null;
		}
		if(empty($_data['age_current_period']) || $_data['age_current_period']=="" || $_data['age_current_period']==0){
			unset($_data['age_current_period']);
		}	
		if(empty($_data['member_card_year']) || $_data['member_card_year']=="" || $_data['member_card_year']==0){
			unset($_data['member_card_year']);
		}	
		if(empty($_data['bank_account_id']) || $_data['bank_account_id']=="" || $_data['bank_account_id']==0){
			$_data['bank_account_id'] = null;
		}	
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['affiliate_contact_id']) || $_data['affiliate_contact_id']=="" || $_data['affiliate_contact_id']==0){
			$_data['affiliate_contact_id'] = null;
		}	
		if(empty($_data['begin_datetime']) || $_data['begin_datetime']=="" || $_data['begin_datetime']==0){
			$_data['begin_datetime'] = null;
		}			
		if(empty($_data['discharge_datetime']) || $_data['discharge_datetime']=="" || $_data['discharge_datetime']==0){
			$_data['discharge_datetime'] = null;
		}
		if(empty($_data['termination_datetime']) || $_data['termination_datetime']=="" || $_data['termination_datetime']==0){
			$_data['termination_datetime'] = null;
		}	
		if(empty($_data['fee_from_date']) || $_data['fee_from_date']=="" || $_data['fee_from_date']==0){
			$_data['fee_from_date'] = null;
			//unset($_data['fee_from_date']);
		}
		if(empty($_data['fee_to_date']) || $_data['fee_to_date']=="" || $_data['fee_to_date']==0){
			//unset($_data['fee_to_date']);
			$_data['fee_to_date'] = null;
		}	
		if(empty($_data['birth_date']) || $_data['birth_date']=="" || $_data['birth_date']==0){
			//unset($_data['fee_to_date']);
			$_data['birth_date'] = null;
		}	
		if(empty($_data['debit_auth_date']) || $_data['debit_auth_date']=="" || $_data['debit_auth_date']==0){
			//unset($_data['fee_to_date']);
			$_data['debit_auth_date'] = null;
		}	
		/*if(empty($_data['fee_from_date']) || $_data['fee_from_date']=="" || $_data['fee_from_date']==0){
			unset($_data['fee_from_date']);
		}
		if(empty($_data['fee_to_date']) || $_data['fee_to_date']=="" || $_data['fee_to_date']==0){
			unset($_data['fee_to_date']);
		}*/		
		if(empty($_data['exp_membercard_datetime']) || $_data['exp_membercard_datetime']=="" || $_data['exp_membercard_datetime']==0){
			$_data['exp_membercard_datetime'] = null;
		}		
		if(empty($_data['age_current_period']) || $_data['age_current_period']=="" || $_data['age_current_period']==0){
			unset($_data['age_current_period']);
		}	
		if(empty($_data['ext_system_modified']) || $_data['ext_system_modified']=="" || $_data['ext_system_modified']==0){
			$_data['ext_system_modified'] = null;
		}
		if(empty($_data['print_reception_date']) || $_data['print_reception_date']=="" || $_data['print_reception_date']==0){
			$_data['print_reception_date'] = null;
		}
		if(empty($_data['print_discharge_date']) || $_data['print_discharge_date']=="" || $_data['print_discharge_date']==0){
			$_data['print_discharge_date'] = null;
		}
		if(empty($_data['print_confirmation_date']) || $_data['print_confirmation_date']=="" || $_data['ext_system_modified']==0){
			$_data['print_confirmation_date'] = null;
		}
		if(empty($_data['member_card_year']) || $_data['member_card_year']=="" || $_data['member_card_year']==0){
			unset($_data['member_card_year']);
		}	
		if(empty($_data['bank_account_id']) || $_data['bank_account_id']=="" || $_data['bank_account_id']==0){
			$_data['bank_account_id'] = null;
		}	
	}
	
	public function hasValidBankAccount(){
		
	}
	
	public function getBankDataFromContact(){
		$contact = $this->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		$this->__set('bank_account_nr', $contact->__get('bank_account_number'));
		$this->__set('bank_code', $contact->__get('bank_code'));
		$this->__set('account_holder', $contact->__get('bank_account_name'));
		$this->__set('bank_name', $contact->__get('bank_name'));
	}
	
	public function tellEntryReason(){
		$rec = $this->getForeignRecordBreakNull('entry_reason_id', Membership_Controller_EntryReason::getInstance());
		if($rec instanceof Membership_Model_EntryReason){
			return $rec->__get('name');
		}
		return null;
	}
	
	public function tellTerminationReason(){
		$rec = $this->getForeignRecordBreakNull('termination_reason_id', Membership_Controller_TerminationReason::getInstance());
		if($rec instanceof Membership_Model_TerminationReason){
			return $rec->__get('name');
		}
		return null;
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
	
	public function tellMembershipStatus(){
		switch($this->__get('membership_status')){
			case self::MEMBERSHIP_STATUS_ACTIVE:
				return 'aktiv';
			case self::MEMBERSHIP_STATUS_PASSIVE:
				return 'passiv';
			case self::MEMBERSHIP_STATUS_DISCHARCHED:
				return 'gekündigt';
			case self::MEMBERSHIP_STATUS_TERMINATED:
				return 'ausgetreten';
		}
	}
	
	public function tellMemberKind(){
		$memKind = $this->getForeignRecord( 'membership_type', Membership_Controller_MembershipKind::getInstance() );
		return $memKind->__get('name');
	}
	
	public function tellPaymentMethod(){
		$paymentMethod = $this->getForeignRecord( 'fee_payment_method', Billing_Controller_PaymentMethod::getInstance() );
		return $paymentMethod->__get('name');
	}
	
	public function getFeeGroupPriceSumByCategory($category){
		$fgp = $this->__get('feegroup_prices');

		if($fgp && array_key_exists('sums', $fgp)){
			$fgpSums = $fgp['sums'];
			if(array_key_exists($category, $fgpSums)){
				return $fgpSums[$category];
			}		
		}
		return 0;
		//throw new Membership_Exception('No fee group prices defined');
	}
	
	public function getAwards(){
		return Membership_Controller_MembershipAward::getInstance()->getByMemberId($this->getId());
	}
	
	public function parentMemberEquals( $parentMember ){
		if($parentMember instanceof Membership_Model_SoMember){
			$memberId = $parentMember->getId();
		}elseif(is_object($parentMember) || is_array($parentMember)){
			throw new Membership_Exception('parentMemberEquals expects either an object instance of Membership_Model_SoMember or a valid member id');
		}else{
			$memberId = $parentMember;
		}
		
		return $this->getForeignIdBreakNull('parent_member_id') == $memberId;
		
	}
	
}