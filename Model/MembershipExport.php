<?php

/**
 * class to hold MembershipExport data
 *
 * @package     Membership
 */
class Membership_Model_MembershipExport extends Tinebase_Record_Abstract
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
        'output_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'filter_set_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'calculation_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'classify_main_orga'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'classify_society'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'classify_fee_group'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'classify_mem_kind'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'result_source'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'result_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'output_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'begin_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'end_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'filter_main_orga'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'filter_society'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'filter_membership'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'assoc_sortfield1'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'assoc_sortfield1_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'assoc_sortfield2'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'assoc_sortfield2_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'society_sortfield1'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'society_sortfield1_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'society_sortfield2'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'society_sortfield2_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_sortfield1'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_sortfield1_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_sortfield2'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_sortfield2_dir'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
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