<?php

/**
 * class to hold Committee data
 *
 * @package     Membership
 */
class Membership_Model_Committee extends Tinebase_Record_Abstract
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
        //'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	// -> dropped, as not needed anymore
        'committee_kind_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
    	'committee_level_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
        'committee_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'challenge'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'description'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'begin_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'end_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'jur_committee'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateFields = array(
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
//		if(empty($_data['member_id']) || $_data['member_id']=="" || $_data['member_id']==0){
//			$_data['member_id'] = null;
//		}		
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
//		if(empty($_data['member_id']) || $_data['member_id']=="" || $_data['member_id']==0){
//			$_data['member_id'] = null;
//		}	
	}
}