<?php

/**
 * class to hold MembershipAccount data
 *
 * @package     Membership
 */
class Membership_Model_MembershipAccount extends Tinebase_Record_Abstract
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
        'account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'account_loginname'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'account_emailadress'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'account_lastpasswordchange'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'account_lastlogin'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'contact_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'related_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'valid_from_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'valid_to_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL)
	);
	protected $_dateFields = array(
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
	}
}