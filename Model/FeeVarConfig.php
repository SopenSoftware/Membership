<?php

/**
 * class to hold FeeVarConfig data
 *
 * @package     Membership
 */
class Membership_Model_FeeVarConfig extends Tinebase_Record_Abstract
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
    	'feedef_dfilters_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'label'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'description'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'vartype'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'floatvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'intvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'textvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'dataobject' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare1' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value1' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value1' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare2' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value2' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value2' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'compare3' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value3' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value3' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'compare4' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value4' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value4' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'compare5' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value5' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value5' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'compare6' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value6' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value6' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        'compare7' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'compare_value7' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_value7' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'transform1' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
	    'transform2' => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
}