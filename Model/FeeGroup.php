<?php

/**
 * class to hold MembershipFeeGroup data
 *
 * @package     Membership
 */
class Membership_Model_FeeGroup extends Tinebase_Record_Abstract
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
        'membership_kind_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'article_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'key'	=> array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'fee_class' => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'text1' => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'text2' => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'is_default'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'customfields'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateFields = array(
	);
	
	public function setFromArray(array $_data)
	{
//		if(empty($_data['key']) || $_data['key']=="" || $_data['key']==0){
//			$_data['key'] = 'todo';
//		}		
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
//		if(empty($_data['key']) || $_data['key']=="" || $_data['key']==0){
//			$_data['key'] = 'todo';
//		}	
	}
	
}