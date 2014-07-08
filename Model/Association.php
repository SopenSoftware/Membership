<?php

/**
 * class to hold Association data
 *
 * @package     Membership
 */
class Membership_Model_Association extends Tinebase_Record_Abstract
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
        'contact_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
    	'association_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'short_name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'is_default'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateFields = array(
	);
	
	public function setFromArray(array $_data)
	{
		if(empty($_data['contact_id']) || $_data['contact_id']=="" || $_data['contact_id']==0){
			$_data['contact_id'] = null;
		}		
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['contact_id']) || $_data['contact_id']=="" || $_data['contact_id']==0){
			$_data['contact_id'] = null;
		}	
	}
}