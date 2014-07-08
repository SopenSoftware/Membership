<?php

/**
 * class to hold ActionHistory data
 *
 * @package     Membership
 */
class Membership_Model_ActionHistory extends Tinebase_Record_Abstract
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
       	'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'job_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'association_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'parent_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'child_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'old_data_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'data_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_progress_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'order_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'receipt_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_text'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_data'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_category'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'action_state'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'error_info'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'created_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'valid_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'to_process_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'process_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'created_by_user'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'processed_by_user'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_datetimeFields = array(
    // modlog
    'created_datetime',
    'valid_datetime',
    'to_process_datetime',
    'process_datetime'
    );
    
    public function getActionState(){
    	return $this->__get('action_state');
    }
    
    public function isError(){
    	return $this->getActionState()=='ERROR';
    }
    
    public function isDone(){
    	return $this->getActionState()=='DONE';
    }
    
    public function isOpen(){
    	return $this->getActionState()=='OPEN';
    }
    
    public function getAction(){
    	return $this->getForeignRecord('action_id', Membership_Controller_Action::getInstance());
    }
    
    public function getActionName(){
    	return $this->getAction()->__get('name');
    }
    
    public function getOldData(){
    	return $this->getForeignRecordBreakNull('old_data_id', Membership_Controller_MembershipData::getInstance());
    }
    
	public function getNewData(){
    	return $this->getForeignRecordBreakNull('data_id', Membership_Controller_MembershipData::getInstance());
    }
    
   	public function isAlreadyValidByDate($validDate){
   		if(!$validDate instanceof Zend_Date){
   			$validDate = new Zend_Date($validDate);
   		}
   		
   		$dataValidDate = new Zend_Date($this->__get('valid_datetime'));
   		
   		if($dataValidDate->isEarlier($validDate)){
   			return true;
   		}   

   		if($dataValidDate->equals($validDate)){
   			return true;
   		}  
   		
   		return false;
   	}
}