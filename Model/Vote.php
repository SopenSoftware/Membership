<?php

/**
 * class to hold Vote data
 *
 * @package     Membership
 */
class Membership_Model_Vote extends Tinebase_Record_Abstract
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
        'id'                => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
     	'member_id'     => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'member_nr'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
   		'on_site'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'transfer_member_id'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_id'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'original_votes'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'become_votes'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'transferred_votes'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'total_votes'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'vote_permission'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'order_votes'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'active_members'       	=> array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
}