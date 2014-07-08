<?php

/**
 * class to hold Message data
 *
 * @package     Membership
 */
class Membership_Model_MessageRead extends Tinebase_Record_Abstract
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
        'message_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'read_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL)
	);
	protected $_dateTimeFields = array(
		'read_datetime'
	);
	public function setFromArray(array $_data)
	{
		if(empty($_data['read_datetime']) || $_data['read_datetime']=="" || $_data['read_datetime']==0){
			$_data['read_datetime'] = null;
		}
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['read_datetime']) || $_data['read_datetime']=="" || $_data['read_datetime']==0){
			$_data['read_datetime'] = null;
		}
	}
}