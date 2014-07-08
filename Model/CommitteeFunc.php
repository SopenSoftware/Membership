<?php

/**
 * class to hold CommitteeFunc data
 *
 * @package     Membership
 */
class Membership_Model_CommitteeFunc extends Tinebase_Record_Abstract
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
        'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
    	'parent_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'committee_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
        'committee_function_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'description'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'begin_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'end_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'management_mail'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'treasure_mail'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateTimeFields = array(
		'begin_datetime',
		'end_datetime'
	);
	public function setFromArray(array $_data)
	{
		if(empty($_data['begin_datetime']) || $_data['begin_datetime']=="" || $_data['begin_datetime']==0){
			$_data['begin_datetime'] = null;
		}	
		if(empty($_data['end_datetime']) || $_data['end_datetime']=="" || $_data['end_datetime']==0){
			$_data['end_datetime'] = null;
		}		
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['begin_datetime']) || $_data['begin_datetime']=="" || $_data['begin_datetime']==0){
			$_data['begin_datetime'] = null;
		}
		if(empty($_data['end_datetime']) || $_data['end_datetime']=="" || $_data['end_datetime']==0){
			$_data['end_datetime'] = null;
		}		
	}
	
}