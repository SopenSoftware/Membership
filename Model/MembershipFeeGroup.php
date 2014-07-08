<?php

/**
 * class to hold FeeGroup data
 *
 * @package     Membership
 */
class Membership_Model_MembershipFeeGroup extends Tinebase_Record_Abstract
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
        'member_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'fee_group_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'fee_group_key'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'article_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'valid_from_datetime'    => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'valid_to_datetime'    => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'price'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'category'		         => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'summarize'		         => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateTimeFields = array(
		'valid_from_datetime',
		'valid_to_datetime'
	);
	
	public function setFromArray(array $_data)
	{
		if(empty($_data['valid_from_datetime']) || $_data['valid_from_datetime']=="" || $_data['valid_from_datetime']==0){
			$_data['valid_from_datetime'] = null;
		}		
		if(empty($_data['valid_to_datetime']) || $_data['valid_to_datetime']=="" || $_data['valid_to_datetime']==0){
			$_data['valid_to_datetime'] = null;
		}	
		if($_data['member_id']==="" || $_data['member_id']===0){
			$_data['member_id'] = null;
		}
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['valid_from_datetime']) || $_data['valid_from_datetime']=="" || $_data['valid_from_datetime']==0){
			$_data['valid_from_datetime'] = null;
		}		
		if(empty($_data['valid_to_datetime']) || $_data['valid_to_datetime']=="" || $_data['valid_to_datetime']==0){
			$_data['valid_to_datetime'] = null;
		}
		if($_data['member_id']=="" || $_data['member_id']==0){
			$_data['member_id'] = null;
		}
	}
}