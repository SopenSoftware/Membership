<?php

/**
 * class to hold FilterResult data
 *
 * @package     Membership
 */
class Membership_Model_FilterResult extends Tinebase_Record_Abstract
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
        'id'                => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
     	'filter_set_id'     => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
   		'sort_order'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'key'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'type'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'sub_type'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'filters'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true)
    	'sum_category'                 => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'scalar_formula1'	               => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'scalar_formula2'	               => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
    public function getFilter(){
    	$aFilter = Zend_Json::decode($this->__get('filters'));
    	
    	$filter = new Membership_Model_SoMemberFilter(array());
    	$filter->setFromArrayInUsersTimezone($aFilter);
    	return $filter;
    }
}