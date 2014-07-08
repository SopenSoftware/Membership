<?php
class Membership_Controller_Message extends Tinebase_Controller_Record_Abstract
{
    /**
     * config of courses
     *
     * @var Zend_Config
     */
    protected $_config = NULL;
    
    /**
     * the constructor
     *
     * don't use the constructor. use the singleton
     */
    private function __construct() {
        $this->_applicationName = 'Membership';
        $this->_backend = new Membership_Backend_Message();
        $this->_modelName = 'Membership_Model_Message';
        $this->_currentAccount = Tinebase_Core::getUser();
        $this->_purgeRecords = FALSE;
        $this->_doContainerACLChecks = FALSE;
        $this->_config = isset(Tinebase_Core::getConfig()->brevetation) ? Tinebase_Core::getConfig()->brevetation : new Zend_Config(array());
    }
    
    private static $_instance = NULL;
    
    /**
     * the singleton pattern
     *
     * @return SoEventManager_Controller_SoEvent
     */
    public static function getInstance()
    {
        if (self::$_instance === NULL) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function getEmptyMessage(){
     	$emptyMessage = new Membership_Model_Message(null,true);
     	return $emptyMessage;
    }
    
     /**
     * get list of records
     *
     * @param Tinebase_Model_Filter_FilterGroup|optional $_filter
     * @param Tinebase_Model_Pagination|optional $_pagination
     * @param boolean $_getRelations
     * @param boolean $_onlyIds
     * @param string $_action for right/acl check
     * @return Tinebase_Record_RecordSet|array
     */
    public function publicSearch(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Record_Interface $_pagination = NULL, $_getRelations = FALSE, $_onlyIds = FALSE, $_action = 'get')
    {
    	$this->_checkRight($_action);
        $this->checkFilterACL($_filter, $_action);
        
        $result = $this->_backend->publicSearch($_filter, $_pagination, $_onlyIds);

        if (! $_onlyIds) {
            if ($_getRelations) {
                $result->setByIndices('relations', Tinebase_Relations::getInstance()->getMultipleRelations($this->_modelName, $this->_backend->getType(), $result->getId()));
            }
            if ($this->_resolveCustomFields) {
                Tinebase_CustomField::getInstance()->resolveMultipleCustomfields($result);
            }
        }
        
        return $result;    
    }
    
    /**
     * Gets total count of search with $_filter
     * 
     * @param Tinebase_Model_Filter_FilterGroup $_filter
     * @param string $_action for right/acl check
     * @return int
     */
    public function publicSearchCount(Tinebase_Model_Filter_FilterGroup $_filter, $_action = 'get') 
    {
        $this->checkFilterACL($_filter, $_action);

        $count = $this->_backend->publicSearchCount($_filter);
        
        return $count;
    }
    
	public function checkNewMessages(){
		$filters = array();
		
		$filters[] = array(
			'field' => 'read_datetime',
			'operator' => 'isnull',
			'value' => '',
			'alias' => 'message_read'
		);
		
		$filters[] = array(
			'field' => 'direction',
			'operator' => 'equals',
			'value' => 'OUT'
		);
		
		$sort = array('field' => 'created_datetime', 'dir' => 'DESC');
		
		return $this->searchNewMessages($filters, $sort);
	}
	
	public function searchNewMessages($_filter, $paging){		
		try{
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	$filter = new Membership_Model_MessageFilter(array());
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_MessageFilter(array());
	        }
	        
	        $filter->addFilter($filter->createFilter(
	        	'expiry_datetime',
	        	'afterAtOrNull',
	        	Zend_Date::now()->toString('yyyy-MM-dd')
	        ));

	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			
			$filterAccountGroups = clone $filter;
			$filterAccount = clone $filter;
			$filterParentMember = clone $filter;
			
			$groupIds = Tinebase_Group::factory(Tinebase_Group::SQL)->getGroupMemberships($this->_currentAccount->__get('accountId'));
			
			$filterAccountGroups->addFilter($filterAccountGroups->createFilter(
				'receiver_group_id',
				'in',
				$groupIds
			));
			
			$filterAccount->addFilter($filterAccount->createFilter(
				'receiver_account_id',
				'in',
				array($this->_currentAccount->getId())
			));
			
			$filterGroup = new Tinebase_Model_Filter_FilterGroup(array(),Tinebase_Model_Filter_FilterGroup::CONDITION_OR);
			$filterGroup->addFilterGroup($filterAccountGroups);
			$filterGroup->addFilterGroup($filterAccount);
			
			$messages = Membership_Controller_Message::getInstance()->search($filterGroup,$pagination);
			
			
			$messageData = array();
			$idMap = null;
			foreach($messages as $message){
				$messageData[] = $message->toArray(true);
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_Message::getInstance()->searchCount($filterGroup,$pagination),
				'results' => $messageData
			);
			
		}catch(Exception $e){
			return array(
   				'success' => false,
				'totalcount' => 0,
	   			'results' => array(),
				'errorInfo' => $e->__toString()
			);
		}
		
	}
	
			
    
    
    public function markMessageRead($messageId, $accountId = null){
    	try{
			if(is_null($accountId)){
				$accountId = Tinebase_Core::getUser()->getId();
			}
			
			$messageRead = Membership_Controller_MessageRead::getInstance()->getEmptyMessageRead();
			$messageRead->__set('account_id', $accountId);
			$messageRead->__set('message_id', $messageId);
			$messageRead->__set('read_datetime', Zend_Date::now());
			
			Membership_Controller_MessageRead::getInstance()->create($messageRead);
    	}catch(Exception $e){
    		
    	}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Tinebase_Controller_Record_Abstract::_inspectCreate()
	 */
    protected function _inspectCreate(Tinebase_Record_Interface $_record)
    {
        $_record->__set('sender_account_id',Tinebase_Core::getUser()->getId());
        $_record->__set('created_datetime',Zend_Date::now());
    }
    
 	/**
 	 * (non-PHPdoc)
 	 * @see Tinebase_Controller_Record_Abstract::_inspectUpdate()
 	 */
    protected function _inspectUpdate(Tinebase_Record_Interface $_record)
    {
        
    }
    /**
     * (non-PHPdoc)
     * @see Tinebase_Controller_Record_Abstract::_afterCreate()
     */
    protected function _afterCreate(Tinebase_Record_Interface $_record)
    {
        if($_record->isSendMail()){
        	$this->sendAsMail($_record);
        }
    }
    
    
    protected function _afterUpdate(Tinebase_Record_Interface $_record)
    {
        
    }
    
    public function sendAsMail($message){
    	
    	if($message->isDirectionOut()){
    		
	    	if($message->isReceiverGroup()){
	    		$groupId = $message->getForeignId('receiver_group_id');
	    		$aUserIds = Tinebase_Group::factory(Tinebase_Group::SQL)->getGroupMembers($groupId);
	    	}elseif($message->isReceiverUser()){
	    		$aUserIds = array($message->__get('receiver_account_id')->__get('accountId'));
	    	}elseif($message->isReceiverParentMember()){
	    		$parentMemberId = $message->getForeignId('parent_member_id');
	    		$aUserIds = array();
	    		$memAccounts = Membership_Controller_MembershipAccount::getInstance()->getByRelatedMemberId($parentMemberId);
	    		foreach($memAccounts as $memAccount){
	    			$aUserIds[] = $memAccount->__get('account_id');
	    		}
	    	}
	    	if(count($aUserIds)>0){
	    		// user backend
	    		$userBackend = Tinebase_User::factory(Tinebase_User::SQL);
	    		$mailReceivers = array();
	    		
	    		$mail = new Tinebase_Mail('UTF-8');
	        	$mail->setSubject($message->__get('subject'));
	        	$mail->setBodyText($message->__get('message'));
	        	$mail->addHeader('X-MailGenerator', 'sopen Online-Service');
	       	 	//$mail->setFrom('webmaster@vdst-service.de', 'VDST Webmaster');
	       	 	$mail->setFrom('sopen@nrw-stiftung.de', 'sopen NRW');
	       	 	
	    		foreach($aUserIds as $userId){
	    			try{
		    			$user = $userBackend->getFullUserById($userId);
		    			$mailReceivers[] = array(
		    				'name' => $user->__get('accountFullName'), 
		    				'mail' => $user->__get('accountEmailAddress')
		    			);
	    			}catch(Exception $e){
	    				// silent failure: if user can't be retrieved -> no mail to him
	    			}
	    		}
	    		
	    		
	    		if(count($mailReceivers)>0){
	    			
	    			foreach($mailReceivers as $mailReceiver){
	    				if($mailReceiver['mail'] != ''){
	    					$mail->addTo($mailReceiver['mail'], $mailReceiver['name']);
	    				}
	    			}
	    			if ($mail->send()) {
	    				return true;
	    			}
	    		}
	    	}
	    	
    	}else{
    		
    			$userId = Tinebase_Core::getUser()->getId();
    			$userBackend = Tinebase_User::factory(Tinebase_User::SQL);
    			$user = $userBackend->getFullUserById($userId);
    			
    			$mail = new Tinebase_Mail('UTF-8');
	        	$mail->setSubject($message->__get('subject'));
	        	$mail->setBodyText($message->__get('message'));
	        	$mail->addHeader('X-MailGenerator', 'sopen VDST-Online-Service');
	       	 	$mail->setFrom($user->__get('accountEmailAddress'), $user->__get('accountFullName'));
	       	 	
	    		$strReceiverMails = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::MEMBER_ONLINE_MAIL_RECEIVER);
    			
	    		$mailReceivers = explode(';', $strReceiverMails);
	    		
	    		if(count($mailReceivers)>0){
	    			foreach($mailReceivers as $mailReceiver){
	    				if($mailReceiver != ''){
	    					$mail->addTo($mailReceiver);
	    				}
	    			}
	    			
	    			if ($mail->send()) {
	    				return true;
	    			}
	    		}
    	}
    	
    	return false;
    }
    
	
}
?>