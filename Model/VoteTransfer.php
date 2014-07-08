<?php

/**
 * class to hold VoteTransfer data
 *
 * @package     Membership
 */
class Membership_Model_VoteTransfer extends Tinebase_Record_Abstract
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
    	'from_member_nr'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
   		'from_member_id'              => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'transferred_votes'				=> array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
}