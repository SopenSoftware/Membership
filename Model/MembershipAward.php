<?php

/**
 * class to hold MembershipAward data
 *
 * @package     Membership
 */
class Membership_Model_MembershipAward extends Tinebase_Record_Abstract
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
		'award_list_id'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'award_datetime'    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL)
	);
	protected $_dateTimeFields = array(
		'award_datetime'
	);
	
	public function setFromArray(array $_data)
	{
		if(empty($_data['award_datetime']) || $_data['award_datetime']=="" || $_data['award_datetime']==0){
			$_data['award_datetime'] = null;
		}		
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['award_datetime']) || $_data['award_datetime']=="" || $_data['award_datetime']==0){
			$_data['award_datetime'] = null;
		}	
	}
}