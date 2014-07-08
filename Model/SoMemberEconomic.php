<?php

/**
 * class to hold SoEvent data
 *
 * @package     SoEventManager
 */
class Membership_Model_SoMemberEconomic extends Membership_Model_SoMember
{
	/**
     * list of zend validator
     *
     * this validators get used when validating user generated content with Zend_Input_Filter
     *
     * @var array
     *
     */
    protected $_extValidators = array(
        'debitor_id'                    => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'count_open_items'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	's_brutto' 		=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'h_brutto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'saldation'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'last_receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'last_receipt_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'last_receipt_netto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'last_receipt_brutto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_extDateFields = array(
        'last_receipt_date'
    );
    
	public function __construct($_data = NULL, $_bypassFilters = false, $_convertDates = true)
    {
    
       $this->_validators = array_merge($this->_validators, $this->_extValidators);
       $this->_dateFields = array_merge($this->_dateFields, $this->_extDateFields);
    	
       parent::__construct($_data, $_bypassFilters, $_convertDates);
       
        
    }
    
	public function setFromArray(array $_data)
	{
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		parent::_setFromJson($_data);
	}
	
	
	
}