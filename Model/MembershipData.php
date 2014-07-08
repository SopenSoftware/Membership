<?php

/**
 * class to hold MembershipData data
 *
 * @package     Membership
 */
class Membership_Model_MembershipData extends Tinebase_Record_Abstract
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
       	'previous_data_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'parent_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_group_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'membership_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'membership_status'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_payment_interval'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'fee_payment_method'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'bank_code'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'bank_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'bank_account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'bank_account_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'account_holder'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'individual_yearly_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'donation'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'additional_fee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'valid_from'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'birth_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'custom_data'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'contact_data'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'contact_custom_data'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'additional_data'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    
    	// -> something like a hidden flag: ActionHistory entries which are not yet finalized
    	// must reference data, which is not respected by Membership_Backend_MembershipData::getMaxIdForMember()
	    'phantom'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'valid_state'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
    public function getContact(){
    	return $this->getMember()->getForeignRecordBreak('contact_id', Addressbook_Controller_Contact::getInstance());
    }
    
    public function getMember(){
    	return $this->getForeignRecordBreak('member_id', Membership_Controller_SoMember::getInstance());
    }
    
    public function getParentMember(){
    	return $this->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
    }
    
    public function getAssociation(){
    	return $this->getForeignRecordBreakNull('association_id', Membership_Controller_Association::getInstance());
    }
    
    public function hasPrevious(){
    	$result = $this->__get('previous_data_id');
    	return !empty($result);
    }
    
    public function isFirst(){
    	if($this->hasPrevious()){
    		return false;
    	}
    	return ($this->getId() == $this->getForeignId('previous_data_id'));
    }
    
    public function getPrevious(){
    	$this->getForeignRecordBreakNull('previous_data_id', Membership_Controller_MembershipData::getInstance());
    }
    
    public function getDescriptiveData(){
    	$member = $this->getMember();
    	$contact = $this->getContact();
    	$parentMember = $this->getParentMember();
    	$parentMemberName = null;
    	if($parentMember){
    		$parentMemberContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
    		$parentMemberName = $parentMember->__get('member_nr').' '.$parentMemberContact->__get('org_name').' '.$parentMemberContact->__get('company2');
    	}
    	$association = $this->getAssociation();
    	
    	$feeGroup = $this->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
    	$feeGroupName = null;
    	if($feeGroup){
    		$feeGroupName = $feeGroup->__get('key').' '.$feeGroup->__get('name');
    	}
    	
    	$memType = $this->getForeignRecordBreakNull('membership_type', Membership_Controller_MembershipKind::getInstance());
    	$memTypeName = null;
    	if($memType){
    		$memTypeName = $memType->__get('name');
    	}
    	
    	$paymentMethod = $this->getForeignRecordBreakNull('fee_payment_method', Billing_Controller_PaymentMethod::getInstance());
    	$paymentMethodName = null;
    	if($paymentMethod){
    		$paymentMethodName = $paymentMethod->__get('name');
    	}
    
    	$birthDate = null;
    	if($this->__get('birth_date')){
    		$birthDate = new Zend_Date($this->__get('birth_date'));
    		$birthDate = $birthDate->format('dd.mm.yyyy');
    	}
    	$aData = array(
    		'parent_member' => array(
    			'value' => $parentMemberName,
    			'label' => 'Verein/Ortsgruppe'),
    		'association' => array(
    			'value' => $association->__get('association_nr'). ' '. $association->__get('association_name'),
    			'label' => 'Verband/Gau'),
    		
    		'fee_group' => array(
    			'value' => $feeGroupName,
    			'label' => 'Beitragsgruppe'),
    	
    		'membership_type' => array(
    			'value' => $memType,
    			'label' => 'Mitgliedsart'),
    	
    		'membership_status' => array(
    			'value' => $this->tellMembershipStatus(),
    			'label' => 'Status'),
    	
    		'fee_payment_method' => array(
    			'value' => $paymentMethodName,
    			'label' => 'Zahlungsart'),
    		'fee_payment_interval' => array(
    			'value' => $this->tellPaymentInterval(),
    			'label' => 'Zahlungsintervall'),
    		'bank_code' => array(
    			'value' => $this->__get('bank_code'),
    			'label' => 'BLZ'),
    		'bank_name' => array(
    			'value' => $this->__get('bank_name'),
    			'label' => 'Bank'),
    		'bank_account_nr' => array(
    			'value' => $this->__get('bank_account_nr'),
    			'label' => 'Kto.Nummer'),
    		'account_holder' => array(
    			'value' => $this->__get('account_holder'),
    			'label' => 'Kontoinhaber'),	
    		'birth_date' => array(
    			'value' => $birthDate,
    			'label' => 'Kontoinhaber'),	
    	);
    	
    	$contactDescriptiveData = array();
    	$contactTrackFields = Membership_Custom_SoMember::getDescriptiveContactTrackFields();
    	foreach($contactTrackFields as $fieldName => $label){
    		$value = null;
    		if($this->getContactValue($fieldName, &$value)){
	    		$contactDescriptiveData[$fieldName] = array(
	    			'label' => $label,
	    			'value' => $value
	    		);
    		}
    	}
    	
    	return array_merge($aData, $contactDescriptiveData);
    }
    
    public function getContactValue($fieldName, &$value){
    	$contactData = $this->getContactData();
    	if(array_key_exists($fieldName, $contactData)){
    		$value = $contactData[$fieldName];
    		return true;
    	}
    	return false;
    }
    
    public function getDataAsDescriptiveArray(){
    	
    }
    
    public function getDescriptiveArray(){
    	
    }
    
    public function tellMembershipStatus(){
		switch($this->__get('membership_status')){
			case Membership_Model_SoMember::MEMBERSHIP_STATUS_ACTIVE:
				return 'aktiv';
			case Membership_Model_SoMember::MEMBERSHIP_STATUS_PASSIVE:
				return 'passiv';
			case Membership_Model_SoMember::MEMBERSHIP_STATUS_DISCHARCHED:
				return 'gekündigt';
			case Membership_Model_SoMember::MEMBERSHIP_STATUS_TERMINATED:
				return 'ausgetreten';
		}
	}
	

    public function tellPaymentInterval(){
		switch($this->__get('fee_payment_interval')){
			case 'NOVALUE':
				return '';
			case 'YEAR':
				return 'jährlich';
			case 'QUARTER':
				return 'quartalsweise';
			case 'MONTH':
				return 'monatlich';
		}
	}
	
	/**
     * 
     * Set array of contact data json encoded
     * @param array $data
     */
    public function setContactData(array $data){
    	
    	$this->__set('contact_data', Zend_Json::encode($data));
    }
    
    /**
     * 
     * Get Contact data as array (json decoded)
     * @return	array
     */
    public function getContactData(){
    	return Zend_Json::decode($this->__get('contact_data'));
    }
    
	/**
     * 
     * Set array of additional data json encoded
     * @param array $data
     */
    public function setAdditionalData(array $data){
    	$this->__set('additional_data', Zend_Json::encode($data));
    }
    
    /**
     * 
     * Get Additional data as array (json decoded)
     * @return	array
     */
    public function getAdditionalData(){
    	return Zend_Json::decode($this->__get('additional_data'));
    }
}