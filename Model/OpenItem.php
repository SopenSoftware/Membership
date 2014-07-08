<?php

/**
 * class to hold OpenItem data
 *
 * @package     Billing
 */
class Membership_Model_OpenItem extends Tinebase_Record_Abstract
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
    protected $_application = 'Billing';
    
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
    	'member_id' => 			array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'erp_context_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'order_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'op_nr'						=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'debitor_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'payment_method_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'receipt_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'receipt_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'due_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'fibu_exp_date'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'total_netto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'total_brutto'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	// context: application context for further, app specific filtering
    	'context'          => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'banking_exp_date'          => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'state'          => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    'receipt_date',
    'due_date',
    'fibu_exp_date',
    'banking_exp_date'
    );
    
	public function setFromArray(array $_data)
	{
		if(empty($_data['fibu_exp_date']) || $_data['fibu_exp_date']==""){
			unset($_data['fibu_exp_date']);
		}
		
			if(empty($_data['banking_exp_date']) || $_data['banking_exp_date']==""){
			unset($_data['banking_exp_date']);
		}

		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
	if(empty($_data['fibu_exp_date']) || $_data['fibu_exp_date']==""){
			unset($_data['fibu_exp_date']);
		}
		
			if(empty($_data['banking_exp_date']) || $_data['banking_exp_date']==""){
			unset($_data['banking_exp_date']);
		}
	}
}