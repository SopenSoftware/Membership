<?php

/**
 * class to hold FeeDefFilter data
 *
 * @package     Membership
 */
class Membership_Model_FeeDefFilter extends Tinebase_Record_Abstract
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
     	'fee_definition_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'is_invoice_component' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'related_membership'       => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'filters'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
}