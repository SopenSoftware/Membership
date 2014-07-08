<?php

/**
 * class to hold MembershipKind data
 *
 * @package     Membership
 */
class Membership_Model_MembershipKind extends Tinebase_Record_Abstract
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
        'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'parent_kind_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'dialog_text'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'dialog_text_assoc'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'dialog_text_member_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'dialog_text_member_ext_nr'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'subject_singular'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'subject_plural'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'is_default'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'uses_fee_progress'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'uses_member_fee_groups'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'identical_contact'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'fee_group_is_duty'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'invoice_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'has_functionaries'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'has_functions'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'addressbook_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		/** boolean ist default tab **/
		'default_tab'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'begin_letter_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'insurance_letter_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'termination_letter_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'membercard_letter_template_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
	);
	protected $_dateFields = array(
	);
}