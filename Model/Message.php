<?php

/**
 * class to hold Message data
 *
 * @package     Membership
 */
class Membership_Model_Message extends Tinebase_Record_Abstract
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
	
	public function isSendMail(){
		return $this->__get('send_mail') == 1;
	}
	
	public function isReceiverGroup(){
		return $this->__get('receiver_type') == 'GROUP';
	}
	
	public function isReceiverUser(){
		return $this->__get('receiver_type') == 'USER';
	}
	
	public function isReceiverParentMember(){
		return $this->__get('receiver_type') == 'PARENTMEMBER';
	}
	
	public function isReceiverMember(){
		return $this->__get('receiver_type') == 'MEMBER';
	}
	
	public function isDirectionOut(){
		return $this->__get('direction') == 'OUT';
	}
	
	public function isDirectionIn(){
		return $this->__get('direction') == 'IN';
	}
	
	
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
        'receiver_group_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'receiver_account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'sender_account_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'parent_member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'member_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'receiver_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'send_mail'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'direction'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'subject'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'message'                  => array(Zend_Filter_Input::ALLOW_EMPTY => false),
		'ticket'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
		'created_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'expiry_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
		'read_datetime'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL)
	);
	protected $_dateTimeFields = array(
		'created_datetime',
		'expiry_datetime'
	);
	public function setFromArray(array $_data)
	{
		if(empty($_data['expiry_datetime']) || $_data['expiry_datetime']=="" || $_data['expiry_datetime']==0){
			$_data['expiry_datetime'] = null;
		}
		
		if( $_data['receiver_group_id']=="" || $_data['receiver_group_id']==0){
			unset($_data['receiver_group_id']);
		}
		/*if( $_data['receiver_account_id']=="" || $_data['receiver_account_id']==0){
			unset($_data['receiver_account_id']);
		}*/
		parent::setFromArray($_data);
	}

	protected function _setFromJson(array &$_data)
	{
		if(empty($_data['expiry_datetime']) || $_data['expiry_datetime']=="" || $_data['expiry_datetime']==0){
			$_data['expiry_datetime'] = null;
		}
	
		if( $_data['receiver_group_id']=="" || $_data['receiver_group_id']==0){
			unset($_data['receiver_group_id']);
		}
		if( $_data['receiver_account_id']=="" || $_data['receiver_account_id']==0){
			unset($_data['receiver_account_id']);
		}
	}
	
	public function getRawTicket(){
		return $this->__get('ticket');
	}
	
	public function getTicket(){
		return Zend_Json::decode($this->getRawTicket());
	}
	
	public function isValidTicket(){
		if(!is_array($this->getTicket())){
			return false;
		}
				
	}
	public function hasTicketItem($key){
		
	}
	
	public function hasTicketMember(){
		$ticket = $this->getTicket();
		
		
		
	}
}