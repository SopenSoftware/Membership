<?php

/**
 * class to hold FeeDefinition data
 *
 * @package     Membership
 */
class Membership_Model_FeeVarOrderPos extends Tinebase_Record_Abstract
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
     	'order_pos_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'use_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'amount_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'price_netto_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'price_brutto_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'factor_fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
}