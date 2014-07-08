<?php
class Membership_Controller_ClubService extends Tinebase_Controller_Record_Abstract
{
	//const MEMBERS_CONTACT_CONTAINER = 20;
	//const CLUB_MEMBERSHIP_TYPE = 'VIASOCIETY';
	
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
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_soMemberController = Membership_Controller_SoMember::getInstance();
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

	public function getContactTitles(){
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Addressbook_Controller_SoContactTitle::getInstance()->getSoContactTitles()) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}
		return $result;
	}

	public function getSalutations()
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Addressbook_Controller_Salutation::getInstance()->getSalutations('name')) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}
	
	public function getAllowedMemberships(){
		
	}
	
	/**
	 * Select from list of allowed memberships
	 */
	public function selectMembership($memberId){
		
	}
	
	
	public function getFeeGroups()
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Membership_Controller_FeeGroup::getInstance()->getAllFeeGroups('key')) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}
	
	public function getPaymentMethods()
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Billing_Controller_PaymentMethod::getInstance()->getAllPaymentMethods()) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}
	
	public function getCommitteeFunctions()
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Membership_Controller_CommitteeFunction::getInstance()->getAllCommitteeFunctions('name')) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}
	
	public function getCommitteeFunctionsByCommitteeName($committeeName)
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Membership_Controller_CommitteeFunction::getInstance()->getByCommitteeName($committeeName)) {
			
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}
	
	public function getTerminationReasons()
	{
		$result = array(
            'results'     => array(),
            'totalcount'  => 0
		);

		if($rows = Membership_Controller_TerminationReason::getInstance()->getAllTerminationReasons()) {
			$rows->translate();
			$result['results']      = $rows->toArray();
			$result['totalcount']   = count($result['results']);
		}

		return $result;
	}	

	private function getAccountMembership(){

		$memberships = Membership_Controller_MembershipAccount::getInstance()->getByAccountId($this->_currentAccount->__get('accountId'));
		
		if(count($memberships)==0){
			throw new Exception('No allowed memberships');
		}
		
		$memAccount = $memberships->getFirstRecord();
		return $memAccount->getForeignRecord('related_member_id', Membership_Controller_SoMember::getInstance());
		
		//return Membership_Controller_SoMember::getInstance()->getMembershipByAccountId($this->_currentAccount->__get('accountId'));
	}
	
	private function getContactIdFromAccount(){
		return $this->_currentAccount->__get('contact_id');
	}

	private function getClubNumberFromAccount(){
		return $this->_currentAccount->__toString();
	}
	
	private function getAccountGroupIds(){
		$accountId = $this->_currentAccount->__get('accountId');
		return Tinebase_Group::factory(Tinebase_Group::SQL)->getGroupMemberships($accountId);
	}
	
	private function getAccountId(){
		return $this->_currentAccount->__get('accountId');
	}
	
	private function getClubMemberId(){
		return $this->getAccountMembership()->getId();
	}
	
	public function printLabels($filter){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($filter) ? $filter : Zend_Json::decode($filter);
	        if (is_array($decodedFilter)) {
	        	$decodedFilter[] = array(
		        	'field'=>'parent_member_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'equals',
	        			'value' => $membership->getId()
	        		))
		        );
		        $decodedFilter[] = array(
		        	'field' => 'membership_type',
		        	'operator' => 'equals',
		        	'value' => 'VIASOCIETY'	
		        );
		        $year = strftime('%Y');
		        $year -=1;
		        $month = strftime('%m');
		        $day = strftime('%d');
		        $date = $year.'-'.$month.'-'.$day;
		        
		        $decodedFilter[] = array(
		        	'field' => 'termination_datetime',
		        	'operator' => 'afterAtOrNull',
		        	'value' => $date
		        
		        );
	        }
	        
	        Membership_Controller_SoMember::getInstance()->printLabels($decodedFilter);
	        
		}catch(Exception $e){
			echo $e->__toString();
		}
	
	}
	
	
	public function printMembersList($filter){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($filter) ? $filter : Zend_Json::decode($filter);
	       
			if (is_array($decodedFilter)) {
	        	$decodedFilter[] = array(
		        	'field'=>'parent_member_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'equals',
	        			'value' => $membership->getId()
	        		))
		        );
		        $decodedFilter[] = array(
		        	'field' => 'membership_type',
		        	'operator' => 'equals',
		        	'value' => 'VIASOCIETY'	
		        
		        );
		        
		        $year = strftime('%Y');
		        $year -=1;
		        $month = strftime('%m');
		        $day = strftime('%d');
		        $date = $year.'-'.$month.'-'.$day;
		        
		        $decodedFilter[] = array(
		        	'field' => 'termination_datetime',
		        	'operator' => 'afterAtOrNull',
		        	'value' => $date
		        
		        );
	        }
	       
	        Membership_Controller_SoMember::getInstance()->printMembersList($decodedFilter);
	        
		}catch(Exception $e){
			echo $e->__toString();
		}
	
	}
	
	public function exportAsCsv($filter, $className=null){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($filter) ? $filter : Zend_Json::decode($filter);
	        if (is_array($decodedFilter)) {
	        	$decodedFilter[] = array(
		        	'field'=>'parent_member_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'equals',
	        			'value' => $membership->getId()
	        		))
		        );
		        $decodedFilter[] = array(
		        	'field' => 'membership_type',
		        	'operator' => 'equals',
		        	'value' => 'VIASOCIETY'	
		        
		        );
		        
		        $year = strftime('%Y');
		        $year -=1;
		        $month = strftime('%m');
		        $day = strftime('%d');
		        $date = $year.'-'.$month.'-'.$day;
		        
		        $decodedFilter[] = array(
		        	'field' => 'termination_datetime',
		        	'operator' => 'afterAtOrNull',
		        	'value' => $date
		        
		        );
	        }
	        
	        Membership_Controller_Export::getInstance()->exportAsCsv($decodedFilter, $className);
	        
		}catch(Exception $e){
			echo $e->__toString();
		}
	
	}

	public function searchPublicSoMembers($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	$decodedFilter[] = array(
		        	'field'=>'parent_member_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'equals',
	        			'value' => $membership->getId()
	        		))
		        );
		        
		        $decodedFilter[] = array(
		        	'field' => 'membership_type',
		        	'operator' => 'equals',
		        	'value' => 'VIASOCIETY'	
		        
		        );
		        
		        $year = strftime('%Y');
		        $year -=1;
		        $month = strftime('%m');
		        $day = strftime('%d');
		        $date = $year.'-'.$month.'-'.$day;
		        
		        $decodedFilter[] = array(
		        	'field' => 'termination_datetime',
		        	'operator' => 'afterAtOrNull',
		        	'value' => $date
		        
		        );
		        $filter = new Membership_Model_SoMemberFilter(array(),'AND');
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_SoMemberFilter(array(),'AND');
	        }
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			$memberships = Membership_Controller_SoMember::getInstance()->search($filter,$pagination);
			$memberData = array();
			$idMap = null;
			foreach($memberships as $membership){
				$soMember = Membership_Controller_SoMember::getInstance()->getSoMember($membership->getId());
				$memberData[] = $this->extractMembershipFromRecord($soMember);	
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_SoMember::getInstance()->searchCount($filter,$pagination),
				'results' => $memberData
			);
		}catch(Exception $e){
			return array(
   				'success' => false,
				'totalcount' => 0,
	   			'results' => array(),
				'errorMessage' => 'Neue Nachrichten konnten nicht abgefragt werden',
				'errorInfo' => $e->__toString()
			);
		}
	}
	
	public function publicCheckNewMessages(){
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
		
		return $this->searchPublicMessages($filters, $sort);
	}
	
	public function publicSendMessage($messageData){
			try{
			$messageData = Zend_Json::decode($messageData);
			
			$message = Membership_Controller_Message::getInstance()->getEmptyMessage();
			$message->setFromArray($messageData);
			$message->__set('sender_account_id', Tinebase_Core::getUser()->getId());
			$message->__set('direction','IN');
			$message->__set('send_mail',1);
			
			$message->__set('parent_member_id', $this->getClubMemberId());
			
			Membership_Controller_Message::getInstance()->create($message);
		}catch(Exception $e){
			throw $e;
		}
		
	}
	
	public function searchPublicMessages($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
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
	        
	        /*$filter->addFilter($filter->createFilter(
	        	'read_datetime',
	        	'afterAtOrNull',
	        	Zend_Date::now()->toString('yyyy-MM-dd H:m:s'),
	        	'message_read'
	        ));*/
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			
			$filterAccountGroups = clone $filter;
			$filterAccount = clone $filter;
			$filterParentMember = clone $filter;
			
			$filterAccountGroups->addFilter($filterAccountGroups->createFilter(
				'receiver_group_id',
				'in',
				$this->getAccountGroupIds()
			));
			
			$filterAccount->addFilter($filterAccount->createFilter(
				'receiver_account_id',
				'in',
				array($this->getAccountId())
			));
			
			$filterParentMember->addFilter($filterAccount->createFilter(
				'parent_member_id',
				'in',
				array($this->getClubMemberId())
			));
			
			$filterGroup = new Tinebase_Model_Filter_FilterGroup(array(),Tinebase_Model_Filter_FilterGroup::CONDITION_OR);
			$filterGroup->addFilterGroup($filterAccountGroups);
			$filterGroup->addFilterGroup($filterAccount);
			$filterGroup->addFilterGroup($filterParentMember);
			
			$messages = Membership_Controller_Message::getInstance()->publicSearch($filterGroup,$pagination);
			
			
			$messageData = array();
			$idMap = null;
			foreach($messages as $message){
				$messageData[] = $this->extractMessageFromRecord($message);	
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_Message::getInstance()->publicSearchCount($filterGroup,$pagination),
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
	
	public function searchPublicFuncs($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	//$aCommitteeFuncFilter = array();
	        	$decodedFilter[] = array(
		        	'field'=>'parent_member_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'equals',
	        			'value' => $membership->getId()
	        		))
		        );
		        //$committeeFilter = new Membership_Model_CommitteeFuncFilter(array());
	            //$committeeFilter->setFromArrayInUsersTimezone($aCommitteeFilter);
		        // -> get committe ids only
	            //$cIds = Membership_Controller_Committee::getInstance()->search($committeeFilter,null, false, true);
		        /*$decodedFilter[] = array(
		        	'field'=>'committee_id',
		        	'operator' => 'AND',
		        	'value' => array(array(
	        			'field' => 'id',
	        			'operator' => 'in',
	        			'value' => $cIds
	        		))
		        );*/
		        $filter = new Membership_Model_CommitteeFuncFilter(array());
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_CommitteeFuncFilter(array());
	        }
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			$funcs = Membership_Controller_CommitteeFunc::getInstance()->search($filter,$pagination);
			
			$funcData = array();
			$idMap = null;
			foreach($funcs as $func){
				$funcData[] = $this->extractFuncFromRecord($func);	
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_CommitteeFunc::getInstance()->searchCount($filter,$pagination),
				'results' => $funcData
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
	
	
	public function searchPublicAwards($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	$filter = new Membership_Model_MembershipAwardFilter(array());
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_MembershipAwardFilter(array());
	        }
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			$awards = Membership_Controller_MembershipAward::getInstance()->search($filter,$pagination);
			
			$awardData = array();
			$idMap = null;
			foreach($awards as $award){
				$awardData[] = $this->extractAwardFromRecord($award);	
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_MembershipAward::getInstance()->searchCount($filter,$pagination),
				'results' => $awardData
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
	
	public function searchPublicMemberFuncs($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	$filter = new Membership_Model_CommitteeFuncFilter(array());
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_CommitteeFuncFilter(array());
	        }
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			$awards = Membership_Controller_CommitteeFunc::getInstance()->search($filter,$pagination);
			
			$awardData = array();
			$idMap = null;
			foreach($awards as $award){
				$awardData[] = $this->extractFuncFromRecord($award);	
			}
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_CommitteeFunc::getInstance()->searchCount($filter,$pagination),
				'results' => $awardData
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
	
	public function searchPublicMemberHistorys($_filter,$_paging){
		try{
			// get account membership: parent member = club etc.
			$membership = $this->getAccountMembership();
			
			$decodedFilter = is_array($_filter) || strlen($_filter) == 40 ? $_filter : Zend_Json::decode($_filter);
	        $decodedPagination = is_array($_paging) ? $_paging : Zend_Json::decode($_paging);
	        if (is_array($decodedFilter)) {
	        	$filter = new Membership_Model_ActionHistoryFilter(array());
	            $filter->setFromArrayInUsersTimezone($decodedFilter);
	        } else if (!empty($decodedFilter) && strlen($decodedFilter) == 40) {
	            $filter = Tinebase_PersistentFilter::getFilterById($decodedFilter);
	        } else {
	            // filter is empty
	            $filter = new Membership_Model_ActionHistoryFilter(array());
	        }
	        
	        $pagination = new Tinebase_Model_Pagination($decodedPagination);
			$aHistories = Membership_Controller_ActionHistory::getInstance()->search($filter,$pagination);
			
			$aHistData = array();
			$idMap = null;
			foreach($aHistories as $history){
				if($this->extractHistoryFromRecord($history, &$aExtract)){
					$aHistData[] = $aExtract;
				}	
			}
			
			return array(
	   			'success' => true,
				'totalcount' => Membership_Controller_ActionHistory::getInstance()->searchCount($filter,$pagination),
				'results' => $aHistData
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
	
	public function savePublicFunc($recordData, $committeeName){
		try{
			$func = new Membership_Model_CommitteeFunc($recordData);
			$membership = $this->getAccountMembership();
			
			$committees = Membership_Controller_Committee::getInstance()->getClubCommittee($committeeName);
			$committee = $committees->getFirstRecord(); 
			$func->__set('committee_id', $committee->getId());
			
			$func->__set('parent_member_id', $membership->getId());
			$func->__set('association_id', $membership->getForeignId('association_id'));			
			
			if(!array_key_exists('id', $recordData) || (strlen($recordData['id']) == 1)){
				$func = Membership_Controller_CommitteeFunc::getInstance()->create($func);
			}else{
				$func = Membership_Controller_CommitteeFunc::getInstance()->get($recordData['id']);
				$func->setFromArray($recordData);
				$func->__set('committee_id', $committee->getId());
				$func = Membership_Controller_CommitteeFunc::getInstance()->update($func);
			}
			
			///$func->flatten();
			
			return array(
	   			'success' => true,
	   			'data' => $this->extractFuncFromRecord($func),
				'info' => $data
			);
		}catch(Exception $e){
			return array(
   				'success' => false,
   				'info' => $e->__toString(),
   				'debug' => array($recordData),
	   			'data' => array()
			);
		}
	}
	
	public function saveClubContactData($masterData){
		try{
			$membership = $this->getAccountMembership();
			$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
			$this->extractContactFromArray($contact, $masterData);

			Addressbook_Controller_Contact::getInstance()->update($contact);
			
		
			$this->extractMembershipFromArray($membership, $masterData);
			Membership_Controller_SoMember::getInstance()->update($membership);			
			
			return array(
	   			'success' => true,
	   			'data' => $this->contactToArray($contact,$membership)
			);
			
		}catch(Exception $e){
			echo $e->__toString();
			return array(
   				'success' => false,
   				'info' => $e->__toString(),
   				'debug' => array($contactId, $masterData),
	   			'data' => array()
			);
		}
	}
	
	private function extractContactFromArray(&$contact, $contactData){
		$contact->__set('salutation_id',$contactData['salutation_id']);
		$contact->__set('n_prefix',$contactData['n_prefix']);
		$contact->__set('n_given',$contactData['n_given']);
		$contact->__set('n_family',$contactData['n_family']);
		if($contactData['bday']){
			$contact->__set('bday',$contactData['bday']);
		}
		$contact->__set('letter_salutation',$contactData['letter_salutation']);
		$contact->__set('adr_one_street2',$contactData['adr_one_street2']);
		$contact->__set('adr_one_street',$contactData['adr_one_street']);
		$contact->__set('adr_one_postalcode',$contactData['adr_one_postalcode']);
		$contact->__set('adr_one_locality',$contactData['adr_one_locality']);
		$contact->__set('adr_one_countryname',$contactData['adr_one_countryname']);
		$contact->__set('tel_work',$contactData['tel_work']);
		$contact->__set('tel_cell',$contactData['tel_cell']);
		$contact->__set('tel_fax',$contactData['tel_fax']);
		$contact->__set('email',$contactData['email']);
		$contact->__set('url',$contactData['url']);
		
		$contact->__set('email_home',$contactData['email_home']);
		$contact->__set('email3',$contactData['email3']);
		$contact->__set('tel_home',$contactData['tel_home']);
		$contact->__set('tel3',$contactData['tel3']);
		//$contact->__set('tel4',$contactData['tel4']);
		
		
		if(array_key_exists('membership_sportdiver', $contactData)){
			$customFields = $contact->__get('customfields');
			$customFields['vdstSportDiver'] = $contactData['membership_sportdiver'];
			$contact->__set('customfields', $customFields);
		}
		
	}

	private function contactToArray($contact, $membership){
		$customFields = $contact->__get('customfields');
		$sportDiver = 0;
		if(array_key_exists('vdstSportDiver', $customFields)){
			$sportDiver = (int) $customFields['vdstSportDiver'];
		}
		
		return array(

	   			'club_contact_id' => $membership->__get('member_nr'),
	   			'foundation_date' => $contact->__get('bday'),
	   			'club_name' => $contact->__get('org_name'),
	   			'salutation_id' => $contact->__get('salutation_id'),
	   			'n_prefix' => $contact->__get('n_prefix'),
	   			'n_given' => $contact->__get('n_given'),
	   			'n_family' => $contact->__get('n_family'),
	   			'letter_salutation' => $contact->__get('letter_salutation'),
	   			'adr_one_street2' => $contact->__get('adr_one_street2'),
	   			'adr_one_street' => $contact->__get('adr_one_street'),
	   			'adr_one_postalcode' => $contact->__get('adr_one_postalcode'),
	   			'adr_one_locality' => $contact->__get('adr_one_locality'),
	   			'adr_one_countryname' => $contact->__get('adr_one_countryname'),
				'membership_sportdiver' => $sportDiver,

	   			'bank_account_number' => $membership->__get('bank_account_nr'),
	   			'bank_code' => $membership->__get('bank_code'),
	   			'bank_account_name' => $membership->__get('account_holder'),
	   			'bank_name' => $membership->__get('bank_name'),
	   			'tel_work' => $contact->__get('tel_work'),
	   			'tel_cell' => $contact->__get('tel_cell'),
	   			'tel_fax' => $contact->__get('tel_fax'),
	   			'email' => $contact->__get('email'),
	   			'url' => $contact->__get('url'),
	   			'tel_home' => $contact->__get('tel_home'),
	   			'tel3' => $contact->__get('tel3'),
				//'tel4' => $contact->__get('tel4'),
	   			'email_home' => $contact->__get('email_home'),
	   			'email3' => $contact->__get('email3')

		);
	}
	
	private function extractFuncFromRecord($func){
		
		$membership = $func->getForeignRecord('member_id', Membership_Controller_SoMember::getInstance());
		$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		//$soMember = Membership_Controller_SoMember::getInstance()->getSoMember($membership->getId());
		
		$objFunc = $func->getForeignRecord('committee_function_id', Membership_Controller_CommitteeFunction::getInstance());
		$funcName = $objFunc->__get('name');
		
		return array(
		   	'id' => $func->__get('id'),
			'member_id' => $membership->getId(),
			'committee_function_id' => $objFunc->getId(),
			'member_nr' => $membership->__get('member_nr'),
			'n_given' => $contact->__get('n_given'),
			'n_family' => $contact->__get('n_family'),
			'name' => $funcName,
			'begin_datetime' => substr($func->__get('begin_datetime'),0,10),
			'end_datetime' => ($func->__get('end_datetime')?substr($func->__get('end_datetime'),0,10):'')		
		);
	}
	
	private function extractAwardFromRecord($award){
		
		$membership = $award->getForeignRecord('member_id', Membership_Controller_SoMember::getInstance());
		$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		//$soMember = Membership_Controller_SoMember::getInstance()->getSoMember($membership->getId());
		
		$objAward = $award->getForeignRecord('award_list_id', Membership_Controller_AwardList::getInstance());
		$awardName = $objAward->__get('name');
		
		return array(
		   	'id' => $award->__get('id'),
			'name' => $awardName,
			'award_datetime' => substr($award->__get('award_datetime'),0,10)
		);
	}
	
	private function extractMessageFromRecord($message){
		$sender = 'VDST-Zentrale';
		
		if($message->isDirectionIn()){
			$res = $message->__get('sender_account_id');
			
			$sender = $res->__get('accountFullName');
		}
		return array(
		   	'id' => $message->__get('id'),
			'external_sender' => $sender,
			'direction' => $message->__get('direction'),
		   	'subject' => $message->__get('subject'),
			'message' => $message->__get('message'),
			'created_datetime' => $message->__get('created_datetime'),
			'expiry_datetime' => $message->__get('expiry_datetime'),
			'read_datetime' => $message->__get('read_datetime')
		);
	}
	
	private function extractHistoryFromRecord($history, &$aExtract){
		$aExtract = array();
		
		$membership = $history->getForeignRecord('member_id', Membership_Controller_SoMember::getInstance());
		$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		
		$actionId = $history->getForeignId('action_id');
		
		if(!array_key_exists($actionId,array(
			'CREATE' => true,
			'TERMINATION' => true,
			'MEMSTATECHANGE' => true
		))){
			return false;
		}
		
		$aTextAction = array(
			'CREATE' => 'Zugang',
			'TERMINATION' => 'Austritt',
			'MEMSTATECHANGE' => 'Neuer Status'
		);
		$action = $aTextAction[$actionId];
		
		$value = '';
		if($actionId == 'MEMSTATECHANGE'){
			$newData = $history->getNewData();
			$value = $newData->tellMembershipStatus();
		}
		
		$aExtract = array(
		   	'id' => $history->__get('id'),
			'action' => $action,
			'text' => $value,
			'created_datetime' => $history->__get('created_datetime')->toString('yyyy-MM-dd'),
			'valid_datetime' => $history->__get('valid_datetime')->toString('yyyy-MM-dd')
		);
		return true;
	}

	public function getClubContactData(){
		try{
			$contactId = $this->getContactIdFromAccount();
			//$memberNr = $this->getClubNumberFromAccount();
			$membership = $this->getAccountMembership();
			$contact = Addressbook_Controller_Contact::getInstance()->get($membership->getForeignId('contact_id'));
			$contactData = $this->contactToArray($contact,$membership);

			$contactData['foundation_date'] = $membership->__get('birth_date');

			return array(
	   			'success' => true,
	   			'data' => $contactData
			);
		}catch(Exception $e){
			return array(
   				'success' => false,
	   			'data' => array(),
				'errorInfo' => $e->__toString()
			);
		}
	}

	private function extractMembershipFromRecord($membership){
		try{
			$feeGroup = $membership->getForeignRecord('fee_group_id',Membership_Controller_FeeGroup::getInstance());
			$feeGroup = $feeGroup->getId();
		}catch(Exception $e){
			$feeGroup = null;
		}
		try{
			$terminationReason = $membership->getForeignRecord('termination_reason_id',Membership_Controller_TerminationReason::getInstance());
			$terminationReason = $terminationReason->getId();
		}catch(Exception $e){
			$terminationReason = null;
		}
		
		$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		//$soMember = Membership_Controller_SoMember::getInstance()->getSoMember($membership->getId());
		$parts = array();
		$partsII = array();
		$sumParts1 = 0;
		$sumParts2 = 0;
		
		$feePartsTotal = $membership->__get('feegroup_prices');
		if($feePartsTotal){
		
			$feeParts = $feePartsTotal['items'];
			$feeSums = $feePartsTotal['sums'];
			
			foreach($feeParts as $feePart){
				if($feePart['category'] == 'I'){
					$parts[] = array(
						$feePart['label'],
						$feePart['value']
					);
					$sumParts1 += $feePart['value'];
				}elseif($feePart['category'] == 'II'){
					$partsII[] = array(
						$feePart['label'],
						$feePart['value']
					);
					$sumParts2 += $feePart['value'];
				}
			}
			
			$parts[] = array(
				'Zusatzbeitrag',
				($membership->__get('additional_fee')?$membership->__get('additional_fee'):0)
			);
			$sumParts1 += ($membership->__get('additional_fee')?$membership->__get('additional_fee'):0);
			$parts[] = array(
				'Spende',
				($membership->__get('donation')?$membership->__get('donation'):0)
			);
			$sumParts1 += ($membership->__get('donation')?$membership->__get('donation'):0);
		}		
		$feePaymentMethodId = $membership->getForeignId('fee_payment_method');
		
		$customFields = $contact->__get('customfields');
		$sportDiver = 0;
		if(array_key_exists('vdstSportDiver', $customFields)){
			$sportDiver = (int) $customFields['vdstSportDiver'];
		}
		$bday='';
		if($contact->__get('bday')){
			$bdayDate = new Zend_Date($contact->__get('bday'));
			$bday = $bdayDate->toString('yyyy-MM-dd');
		}
	
		return array(
			'id' => $membership->__get('id'),
		   	'member_nr' => $membership->__get('member_nr'),
			'member_ext_nr' => $membership->__get('member_ext_nr'),
			'membership_type' => $membership->__get('membership_type'),
			'membership_status' => $membership->__get('membership_status'),
			'begin_datetime' => $membership->__get('begin_datetime'),
//			'begin_datetime' => ($membership->__get('begin_datetime')?$membership->__get('begin_datetime')->get('Y-M-d'):''),
//   			'termination_datetime' => ($membership->__get('termination_datetime')?$membership->__get('termination_datetime')->get('Y-M-d'):''),
			'termination_datetime' => $membership->__get('termination_datetime'),
			'termination_reason_id' => $terminationReason,
			'bday' => $bday,
   			'fee_group_id' => $feeGroup,
			'fee_parts' => $parts,	
			'fee_parts_2' => $partsII,
			'sum_parts_1' => $sumParts1,
			'sum_parts_2' => $sumParts2,
   			'salutation_id' => $contact->__get('salutation_id'),
   			'n_prefix' => $contact->__get('n_prefix'),
   			'n_given' => $contact->__get('n_given'),
   			'n_family' => $contact->__get('n_family'),
   			'letter_salutation' => $contact->__get('letter_salutation'),
   			'adr_one_street2' => $contact->__get('adr_one_street2'),
   			'adr_one_street' => $contact->__get('adr_one_street'),
   			'adr_one_postalcode' => $contact->__get('adr_one_postalcode'),
   			'adr_one_locality' => $contact->__get('adr_one_locality'),
   			'adr_one_countryname' => $contact->__get('adr_one_countryname'),
   			'tel_work' => $contact->__get('tel_work'),
			'tel_home' => $contact->__get('tel_home'),
			'tel3' => $contact->__get('tel3'),
			//'tel4' => $contact->__get('tel4'),
   			'tel_cell' => $contact->__get('tel_cell'),
   			'tel_fax' => $contact->__get('tel_fax'),
   			'email' => $contact->__get('email'),
			'email_home' => $contact->__get('email_home'),
			'email3' => $contact->__get('email3'),
   			'url' => $contact->__get('url'),
			'person_age' => $membership->__get('person_age'),
			'member_age' => $membership->__get('member_age'),
			'donation' => $membership->__get('donation'),
			'additional_fee' => $membership->__get('additional_fee'),
			'individual_yearly_fee' => $membership->__get('individual_yearly_fee'),
			'membership_sportdiver' => $sportDiver,
			'fee_payment_method' => $feePaymentMethodId,
			'bank_code' => $membership->__get('bank_code'),
			'bank_name' => $membership->__get('bank_name'),
			'bank_account_nr' => $membership->__get('bank_account_nr'),
			'account_holder' => $membership->__get('account_holder'),
			'public_comment' => $membership->__get('public_comment')
		);
	}
	
	private function extractMembershipFromArray($membership, $memberData){
		$membership->__set('fee_group_id',$memberData['fee_group_id']);
		$membership->__set('membership_status',$memberData['membership_status']);
		$membership->__set('membership_type',$memberData['membership_type']);
		$membership->__set('public_comment',$memberData['public_comment']);
		
		if($memberData['begin_datetime']){
			$membership->__set('begin_datetime',$memberData['begin_datetime']);
		}
		if($memberData['termination_datetime']){
			$membership->__set('termination_datetime',$memberData['termination_datetime']);
		}
		
		if($memberData['bank_code']){
			$membership->__set('bank_code',$memberData['bank_code']);
		}
		if($memberData['bank_name']){
			$membership->__set('bank_name',$memberData['bank_name']);
		}
		
		if($memberData['bank_account_nr']){
			$membership->__set('bank_account_nr',$memberData['bank_account_nr']);
		}
		if($memberData['account_holder']){
			$membership->__set('account_holder',$memberData['account_holder']);
		}
		
		if($memberData['fee_payment_method']){
			$membership->__set('fee_payment_method',$memberData['fee_payment_method']);
		}
		
		if(array_key_exists('donation', $memberData)){
			$membership->__set('donation',$memberData['donation']);
		}
		
		if(array_key_exists('additional_fee', $memberData)){
			$membership->__set('additional_fee',$memberData['additional_fee']);
		}
		
		if(array_key_exists('individual_yearly_fee', $memberData)){
			$membership->__set('individual_yearly_fee',$memberData['individual_yearly_fee']);
		}
		
		if(array_key_exists('bday', $memberData)){
			$membership->__set('birth_date',$memberData['bday']);
		}
		
	}
	
	public function getClubMembers($idList = array()){
		try{
			$membership = $this->getAccountMembership();
			
			$memberships = Membership_Controller_SoMember::getInstance()->getByParentMemberId($membership->getId());
			$memberData = array();
			$idMap = null;
			if(!empty($idList)){
				$idMap = array_flip($idList);
			}
$count = 0;
			foreach($memberships as $membership){
				if($count++>30){
					break;
				}
				if(!$idMap || ($idMap && array_key_exists($membership->__get('member_nr'), $idMap))){
					$memberData[] = $this->extractMembershipFromRecord($membership);	
				}
			}

			return array(
	   			'success' => true,
				'totalcount' => count($memberData),
	   			'results' => $memberData
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
	
	public function addClubMember($memberData){
		$db = Tinebase_Core::getDb();
		$tm = Tinebase_TransactionManager::getInstance();
		// start transaction
		$tId = $tm->startTransaction($db);
		
		try{
			
			$clubMembership = $this->getAccountMembership();
			$associationId = $clubMembership->getForeignId('association_id');
			
			$contact = new Addressbook_Model_Contact(array(	
					'n_given'=>$memberData['n_given'],
					'n_family'=>$memberData['n_family']
			));
			$this->extractContactFromArray($contact, $memberData);
			$contact->__set('container_id',Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::ADDRESSBOOK_CLUBMEMBERS));
			$contact = Addressbook_Controller_Contact::getInstance()->create($contact);
			
			$membership = new Membership_Model_SoMember();
			$this->extractMembershipFromArray($membership, $memberData);
			//$membership->__set('member_nr',$memberNr);
			$membership->__set('contact_id', $contact->getId());
			$membership->__set('parent_member_id', $clubMembership->getId());
			$membership->__set('association_id', $associationId);
			//$membership->__set('membership_type',self::CLUB_MEMBERSHIP_TYPE);
			$membership = Membership_Controller_SoMember::getInstance()->create($membership);
			
			$result = Membership_Controller_SoMember::getInstance()->getSoMember($membership->getId());
			
			$tm->commitTransaction($tId);
			
			return $result;
			
		}catch(Exception $e){
			
			$tm->rollBack($tId);
			
			throw $e;
		}
			
	}
	
	public function updateClubMember($memberNr, $memberData){
		$db = Tinebase_Core::getDb();
		$tm = Tinebase_TransactionManager::getInstance();
		// start transaction
		$tId = $tm->startTransaction($db);
		
		try{
			$clubMembership = $this->getAccountMembership();
			
			$membership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);
			$memberContactId = $membership->__get('contact_id')->getId();
			$memberId = $membership->getId();
			
			$memberContact = Addressbook_Controller_Contact::getInstance()->get($memberContactId);
			$contactId = $memberContact->getId();
			$this->extractContactFromArray($memberContact, $memberData);
			
			$this->extractMembershipFromArray($membership, $memberData);
			
			Addressbook_Controller_Contact::getInstance()->update($memberContact);
			$membership->flatten();
			Membership_Controller_SoMember::getInstance()->update($membership);
			
			// update delivers full membership including foreign records embedded
			$result =  Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
			$tm->commitTransaction($tId);
			
			return $result;
			
		}catch(Exception $e){
			
			$tm->rollBack($tId);
			
			throw $e;
		}
	}
	
	
	public function saveClubMemberData($memberData){
		
		$userMessage = 'Es ist ein Fehler aufgetreten. Die Daten wurden nicht gespeichert.';
			
		try{
			SPIncludeManager::requireClass(CSopen::instance()->getLibPath()."Sopen/util/array/ArrayHelper.class.php",'ArrayHelper');
    		$memberNr = ArrayHelper::getKeyPathBreak('member_nr',$memberData);
    		
			if($memberNr != 0){
				$membership = $this->updateClubMember($memberNr,$memberData);
			}else{
				
				/*$contactIds = array();
				$count = 0;
				
				if(Addressbook_Custom_Contact::getDuplicateSearchFilter($memberData, &$filter)){
					$count = Addressbook_Controller_Contact::getInstance()->searchCount($filter);
					if($count>0){
						// -> get only ids!! (param4: true)
						$contactIds = Addressbook_Controller_Contact::getInstance()->search($filter,$paging,false,true);
					}
				}
				
				if($count>0){
					$userMessage = 'Der Kontaktdatensatz existiert bereits. Bitte senden Sie eine Meldung an den VDST-Service.';
					throw new Exception('Der Kontaktdatensatz existiert bereits');
				}*/
				
				$membership = $this->addClubMember($memberData);
			}
			
			$resultArray = $this->extractMembershipFromRecord($membership);
						
			return array(
	   			'success' => true,
	   			'data' => $resultArray
			);
		}catch(Exception $e){
			
			return array(
   				'success' => false,
				'info' => $e->__toString(),
				'userMessage' => $userMessage,
	   			'data' => array()
			);
		}
	}
	
	public function requestClubMemberStateChange($changeData){
		try{
			if(!is_array($changeData)){
				$changeData = Zend_Json::decode($changeData);
			}
			
			$memberId = $changeData['memberId'];
			$currentState = $changeData['currentState'];
			$newState = $changeData['newState'];
			$validDate = new Zend_Date($changeData['validDate']);
			
			try{
				$member = Membership_Controller_SoMember::getInstance()->get($memberId);
				
				if(!$member->parentMemberEquals($this->getAccountMembership())){
					throw new Exception('access not allowed');
				}
			}catch(Exception $e){
				throw new Membership_Exception('Access to given membership not allowed or given id invalid',0,$e);
			}
			
			$data = array();
			$data['membership_status'] = 'PASSIVE';
			
			$actionHistory = Membership_Controller_SoMember::getInstance()->requestMemberDataChange($memberId, $data, $validDate, Membership_Controller_ActionHistory::MEM_CHANGE_REQUEST_STATE);
			$isEarlier = false;
			// check whether the action is already ready for execution today
			// if so: perform the data change (in this case only the change of state to ACTIVE to PASSIVE allowed)
			// might be different in other orgas
			// TODO: make configurable or push to customize layer
			if($actionHistory->isAlreadyValidByDate(new Zend_Date())){
				$isEarlier = true;
				Membership_Controller_SoMember::getInstance()->performDataChange($actionHistory->getId(), $actionHistory->getForeignId('action_id'));
			}
			
			/*$dt = new Zend_Date($actionHistory->__get('valid_datetime'));
			$validDate = new Zend_Date($validDate);
			
			echo $dt->toString('dd.MM.yyyy H:m:i');
			echo $validDate->toString('dd.MM.yyyy H:m:i');
			
			$a = $dt->isEarlier($validDate);
			$b = $validDate->isEarlier($dt);
			
			$c = array($a,$b);
			ob_start();
			var_dump($c);
			$c = ob_get_clean();
			
			$resultArray = array(
			'earlier' => ($isEarlier?'JA':'NEIN'),
				'dt' => $dt->toString('dd.MM.yyyy H:m:i'),
				'validDate' => $validDate->toString('dd.MM.yyyy H:m:i'),
				'c' => $c
				
			);
			*/
			$resultArray = array();
			return array(
	   			'success' => true,
	   			'data' => $resultArray
			);
		}catch(Exception $e){
			return array(
   				'success' => false,
				'info' => $e->__toString(),
	   			'data' => array()
			);
		}
	}
}

?>