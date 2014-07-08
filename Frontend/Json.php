<?php
/**
 * 
 * Enter description here ...
 * @author hhartl
 *
 */
class Membership_Frontend_Json extends Tinebase_Frontend_Json_Abstract{
    protected $_controller = NULL;

    protected $_config = NULL;
    protected $_userTimezone = null;
    protected $_serverTimezone = null;
    
    /**
     * the constructor
     *
     */
    public function __construct()
    {
        $this->_applicationName = 'Membership';
        $this->_soMemberController = Membership_Controller_SoMember::getInstance();
        $this->_soMemberFeeProgressController = Membership_Controller_SoMemberFeeProgress::getInstance();
        $this->_feeDefinitionController = Membership_Controller_FeeDefinition::getInstance();
        
        $this->_feeDefFilterController = Membership_Controller_FeeDefFilter::getInstance();
        $this->_feeVarConfigController = Membership_Controller_FeeVarConfig::getInstance();
        $this->_feeVarController = Membership_Controller_FeeVar::getInstance();
        $this->_feeGroupController = Membership_Controller_FeeGroup::getInstance();
        $this->_membershipFeeGroupController = Membership_Controller_MembershipFeeGroup::getInstance();
        $this->_membershipKindController = Membership_Controller_MembershipKind::getInstance();
        $this->_feeVarOrderPosController = Membership_Controller_FeeVarOrderPos::getInstance();
        $this->_associationController = Membership_Controller_Association::getInstance();
        $this->_actionController = Membership_Controller_Action::getInstance();
        $this->_actionHistoryController = Membership_Controller_ActionHistory::getInstance();
        $this->_membershipDataController = Membership_Controller_MembershipData::getInstance();
        $this->_membershipExportController = Membership_Controller_MembershipExport::getInstance();
        
        //$this->_Controller = Membership_Controller_::getInstance();
        $this->_awardListController = Membership_Controller_AwardList::getInstance();
        $this->_membershipAwardController = Membership_Controller_MembershipAward::getInstance();
        $this->_committeeController = Membership_Controller_Committee::getInstance();
        $this->_committeeKindController = Membership_Controller_CommitteeKind::getInstance();
        $this->_committeeLevelController = Membership_Controller_CommitteeLevel::getInstance();
        $this->_committeeFunctionController = Membership_Controller_CommitteeFunction::getInstance();
        $this->_committeeFuncController = Membership_Controller_CommitteeFunc::getInstance();
        $this->_jobController = Membership_Controller_Job::getInstance();
        
        $this->_entryReasonController = Membership_Controller_EntryReason::getInstance();
        $this->_terminationReasonController = Membership_Controller_TerminationReason::getInstance();
        
        $this->_filterSetController = Membership_Controller_FilterSet::getInstance();
        $this->_filterResultController = Membership_Controller_FilterResult::getInstance();
        $this->_membershipAccountController = Membership_Controller_MembershipAccount::getInstance();
    }
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getPublicSoMember($id){
    	if(!$id ) {
            $member = $this->_soMemberController->getEmptySoMember();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_soMemberController->getSoMember($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
	public function publicChangePassword($oldPassword, $newPassword){
	 	try {
            Tinebase_Controller::getInstance()->changePassword($oldPassword, $newPassword);
            return array(
            	'success' => true
            );
        } catch (Tinebase_Exception $e) {
            $response = array(
                'success'      => FALSE,
                'errorMessage' => "New password could not be set! Error: " . $e->getMessage()
            );   
        }
	}
    
    public function searchPublicSoMembers($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicSoMembers($filter,$paging);
    }
    
    public function deletePublicSoMembers($ids){
    	//return Membership_Controller_ClubService::getInstance()->searchPublicSoMembers($filter,$paging);
    }
    
    public function savePublicSoMember($recordData){
//    	$member = new Membership_Model_SoMember();
//        $member->setFromArray($recordData);
//        
//        if (!$member['id']) {
//            $member = $this->_soMemberController->create($member);
//        } else {
//            $member = $this->_soMemberController->update($member);
//        }
//        
//        return $member->toArray();
    }
    
    
	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getPublicMessage($id){
    	if(!$id ) {
            $member = $this->_funcController->getEmptyMessage();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_funcController->getMessage($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchPublicMessages($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicMessages($filter,$paging);
    }
    
	public function deletePublicMessages($ids){
    	//return Membership_Controller_ClubService::getInstance()->searchPublicMessages($filter,$paging);
    }
    
    public function savePublicMessage($recordData){
    	$committeeName = $recordData['committee_name'];
		return Membership_Controller_ClubService::getInstance()->savePublicMessage($recordData, $committeeName);
    }
    
 	public function publicMarkMessageRead($messageId){
    	try{
    		Membership_Controller_Message::getInstance()->markMessageRead($messageId, Tinebase_Core::getUser()->getId());
    		return array('success' => true, 'result' => null);
    	}catch(Exception $e){
    		return array('success' => false, 'result' => null);
    	}
    }
    
    public function markMessageUnread($messageId, $accountId){
    	
    }
    
	public function publicCheckNewMessages(){
    	try{
    		return Membership_Controller_ClubService::getInstance()->publicCheckNewMessages();
    	}catch(Exception $e){
    		return array('success' => false, 'result' => null);
    	}
    }
    
	public function publicSendMessage($messageData){
    	try{
    		Membership_Controller_ClubService::getInstance()->publicSendMessage($messageData);
    		return array('success' => true, 'result' => null);
    	}catch(Exception $e){
    		return array('success' => false, 'result' => null, 'errorInfo' => $e->__toString(), 'errorMessage' => 'Versenden der Nachricht fehlgeschlagen.');
    	}
    }
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getPublicFunc($id){
    	if(!$id ) {
            $member = $this->_funcController->getEmptyFunc();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_funcController->getFunc($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchPublicFuncs($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicFuncs($filter,$paging);
    }
    
	public function deletePublicFuncs($ids){
    	//return Membership_Controller_ClubService::getInstance()->searchPublicFuncs($filter,$paging);
    }
    
    public function savePublicFunc($recordData){
    	$committeeName = $recordData['committee_name'];
		return Membership_Controller_ClubService::getInstance()->savePublicFunc($recordData, $committeeName);
    }
    
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getPublicAward($id){
    	if(!$id ) {
            $member = $this->_awardController->getEmptyAward();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_awardController->getAward($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchPublicAwards($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicAwards($filter,$paging);
    }
    
    public function deletePublicAwards($ids){
    	//return Membership_Controller_ClubService::getInstance()->searchPublicAwards($filter,$paging);
    }
    
    public function savePublicAward($recordData){
//    	$member = new Membership_Model_Award();
//        $member->setFromArray($recordData);
//        
//        if (!$member['id']) {
//            $member = $this->_awardController->create($member);
//        } else {
//            $member = $this->_awardController->update($member);
//        }
//        
//        return $member->toArray();
    }
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getPublicMemberFunc($id){
    	if(!$id ) {
            $member = $this->_memberFuncController->getEmptyMemberFunc();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_memberFuncController->getMemberFunc($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchPublicMemberFuncs($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicMemberFuncs($filter,$paging);
    }
    
    public function deletePublicMemberFuncs($ids){
    	//return Membership_Controller_ClubService::getInstance()->searchPublicMemberFuncs($filter,$paging);
    }
    
    public function savePublicMemberFunc($recordData){
//    	$member = new Membership_Model_MemberFunc();
//        $member->setFromArray($recordData);
//        
//        if (!$member['id']) {
//            $member = $this->_memberFuncController->create($member);
//        } else {
//            $member = $this->_memberFuncController->update($member);
//        }
//        
//        return $member->toArray();
    }
    
    public function publicGetCommitteeFunctions(){
		return Membership_Controller_ClubService::getInstance()->getCommitteeFunctions();
    }
    
	public function publicGetCommitteeFunctionsByCommitteeName($committeeName){
		return Membership_Controller_ClubService::getInstance()->getCommitteeFunctionsByCommitteeName($committeeName);
    }
    
     public function publicGetPaymentMethods(){
		return Membership_Controller_ClubService::getInstance()->getPaymentMethods();
    }
    
    /**
     * mirrored method to get contact titles (originally hosted in Addressbook)
     * 
     */
    public function publicGetContactTitles(){
		return Membership_Controller_ClubService::getInstance()->getContactTitles();
    }
    
    /**
     * mirrored method to get contact salutation (originally hosted in Addressbook)
     * 
     */
    public function publicGetSalutations(){
		return Membership_Controller_ClubService::getInstance()->getSalutations();
    }
    /**
     * 
     * Enter description here ...
     */
    public function publicGetCountryList()
    {
    	return Tinebase_Translation::getCountryList();
    }
    /**
     * 
     * Enter description here ...
     */
    public function publicGetClubMembers()
    {
    	return Membership_Controller_ClubService::getInstance()->getClubMembers();
    }
    
 	public function publicGetFeeGroups()
    {
    	return Membership_Controller_ClubService::getInstance()->getFeeGroups();
    }
    
 	public function publicGetTerminationReasons()
    {
    	return Membership_Controller_ClubService::getInstance()->getTerminationReasons();
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $memberData
     */
    public function publicSaveClubMemberData($memberData){
    	return Membership_Controller_ClubService::getInstance()->saveClubMemberData($memberData);
    }
    /**
     * 
     * Enter description here ...
     */
    public function publicGetClubMasterData(){
    	return Membership_Controller_ClubService::getInstance()->getClubContactData();
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $masterData
     */
    public function publicSaveClubMasterData($masterData){
    	return Membership_Controller_ClubService::getInstance()->saveClubContactData($masterData);
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $changeData
     */
	public function publicRequestClubMemberStateChange($changeData){
    	return Membership_Controller_ClubService::getInstance()->requestClubMemberStateChange($changeData);
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function publicGetMyMasterData(){
    	return Membership_Controller_MyService::getInstance()->getMyContactData();
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $masterData
     */
    public function publicSaveMyMasterData($masterData){
    	return Membership_Controller_MyService::getInstance()->saveMyContactData($masterData);
    }
    
	public function searchPublicMemberHistorys($filter,$paging){
    	return Membership_Controller_ClubService::getInstance()->searchPublicMemberHistorys($filter,$paging);
    }
     
	public function checkMemberData($contactId, $relatedMemberId){
		try{
			/*$brmController = Membership_Controller_SoMember::getInstance();
			$brMaster = $brmController->get($memberId);
			$contactId = $brMaster->__get('contact_id');*/
			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
			$email = $contact->__get('email');
			
			$existingAccounts = Membership_Controller_MembershipAccount::getInstance()
				->getByRelatedMemberId($relatedMemberId);
			$firstRecordArray = array();
			if(count($existingAccounts)>0){
				$firstRecord = $existingAccounts->getFirstRecord();
				$firstRecordArray = $firstRecord->toArray();
			}
			
			return array(
				'success' => true,
				'existingAccounts' => count($existingAccounts),
				'firstAccount' => $firstRecordArray,
				'data' => array(
					'email' => $email,
					'lastname' => $contact->__get('n_family'),
					'forename' => $contact->__get('n_given')
				)
			);
		}catch(Exception $e){
			return array(
				'success' => false,
				'data' => array(
					'info' => $e->__toString()
				)
			);		
		}
	}
    public function resendAccountData($id, $accountId){
    	try{
    		$user = Tinebase_User::getInstance()->getFullUserById($accountId);
    		$onlineServiceLink = \Tinebase_Config::getInstance()->getConfig('ClubMemberOnlineService', NULL, TRUE)->value;
			Tinebase_User_Registration::getInstance()->setOnlineServiceLink($onlineServiceLink);
    		$success = Tinebase_User_Registration::getInstance()->renewUserRegistration($accountId);
		    return array(
				'success' => true,
				'data' => array()
			);	
    	}catch(Exception $e){
			return array(
				'success' => false,
				'data' => array(
					'text' => $e->getMessage()
				)
			);		
		}
			
    }
    
	public function createMemberAccount($data){
		try{
			$brmController = Membership_Controller_SoMember::getInstance();
			if(!array_key_exists('contactId', $data) || !$data['contactId']){
				throw new Exception('No contact given');
			}
			if(!array_key_exists('relatedMemberId', $data) || !$data['relatedMemberId']){
				throw new Exception('No related member given');
			}
			$relatedMemberId = $data['relatedMemberId'];
			$contactId = $data['contactId'];
			$email = $data['email'];
			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
			
			$contactMail = $contact->__get('email');
			if(!$contactMail){
				$contact->__set('email', $email);
				$contact = Addressbook_Controller_Contact::getInstance()->update($contact);
			}
			
			$primaryGroupId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::MEMBER_ONLINE_GROUP);
			
			if($data['userEmailAsLoginName']){
				$username = $email;
			}else{
				$username = $data['userName'];
			}
			
			
			$regData = array(
				'contactId' => $contact->getId(),
				'accountFirstName' => $contact->__get('n_given'),
				'accountLastName' => $contact->__get('n_family'), 
				'accountEmailAddress' => $email,
				'accountLoginName' => $username,
				'accountPrimaryGroup' => $primaryGroupId
			);
			$onlineServiceLink = \Tinebase_Config::getInstance()->getConfig('ClubMemberOnlineService', NULL, TRUE)->value;
			Tinebase_User_Registration::getInstance()->setOnlineServiceLink($onlineServiceLink);
			$success = Tinebase_User_Registration::getInstance()->registerUser($regData,true);
			
			if($success){
				$user = Tinebase_User::getInstance()->getFullUserByLoginName($username);
				$accountId = $user->__get('accountId');
				
				$membershipAccount = new Membership_Model_MembershipAccount(null, true);
				$membershipAccount->__set('account_id', $accountId);
				$membershipAccount->__set('contact_id', $contactId);
				$membershipAccount->__set('related_member_id', $relatedMemberId);
				$memberId = $data['memberId'];
				if($memberId){
					$membershipAccount->__set('member_id', $memberId);
				}
				Membership_Controller_MembershipAccount::getInstance()->create($membershipAccount);
			}
			return array(
				'success' => $success,
				'data' => array(
					'email' => $email,
					'lastname' => $contact->__get('n_family'),
					'forename' => $contact->__get('n_given')
				)
			);
		}catch(Tinebase_User_Exception_UsernameAlreadyExists $e){
			
			return array(
				'success' => false,
				'errorState' => 'USERNAME_ALREADY_EXISTS',
				'data' => array(
					'info' => $e->__toString()
				)
			);		
		}catch(Tinebase_User_Exception_InvalidEmailAddress $e){
			
			return array(
				'success' => false,
				'errorState' => 'INVALID_EMAIL_ADDRESS',
				'data' => array(
					'info' => $e->__toString()
				)
			);		
		}catch(Exception $e){
			
			return array(
				'success' => false,
				'errorState' => 'ERROR',
				'data' => array(
					'info' => $e->__toString()
				)
			);		
		}
	}
	
	public function importTD($files, $importOptions){
		return $this->_soMemberController->importTDFiles($files, $importOptions);
	}
	
	/**
	 * Create fee progress or in a second step fee invoice
	 * Determine action: 
	 *  1) create fee progress records for fee year and filter
	 *  2) create fee invoices records for fee year and filter
	 *  
	 * @param string $filters	Json encoded filter string -> produces array of filters
	 * @param string $feeYear	The year for which the action must be performed
	 * @param string $action	FEEPROGRESS|FEEINVOICE (FEEINVOICE only can be produced if fee progress has been performed)
	 */
	public function batchCreateFeeInvoice($filters, $feeYear, $action){
		return $this->_soMemberController->batchCreateFeeInvoice(Zend_Json::decode($filters), $feeYear, $action);
	}
	
	public function createFeeInvoiceForFeeProgress($feeProgressId, $mode){
		return $this->_soMemberController->createFeeInvoiceForFeeProgress($feeProgressId, $mode);
	}
	
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getSoMember($id){
    	if(!$id ) {
            $member = $this->_soMemberController->getEmptySoMember();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_soMemberController->getSoMember($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchSoMembers($filter,$paging){
    	return $this->_search($filter,$paging,$this->_soMemberController,'Membership_Model_SoMemberFilter');
    }
    
    public function deleteSoMembers($ids){
    	return $this->_delete($ids, $this->_soMemberController);
    }
    
    public function saveSoMember($recordData){
    	$member = new Membership_Model_SoMember();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_soMemberController->create($member);
        } else {
            $member = $this->_soMemberController->update($member);
        }
        
        return $member->toArray();
    }
    
    public function getDebitor($memberNr){
    	try{
    		$debitor = $this->_soMemberController->getDebitorByMemberNr($memberNr);
    		
    		return array(
    			'success' => true,
    			'debitor' => $debitor->toArray(true)
    		);
    		
    	}catch(Exception $e){
    		return array(
    			'success' => false,
    			'debitor' => null,
    			'errorInfo' => $e->getMessage(),
    			'trace' => $e->getTrace()
    		);
    	}
    }
    
	public function getSoMemberEconomic($id){
    	if(!$id ) {
            $member = Membership_Controller_SoMemberEconomic::getInstance()->getEmptySoMemberEconomic();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = Membership_Controller_SoMemberEconomic::getInstance()->getSoMemberEconomic($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchSoMemberEconomics($filter,$paging){
    	return $this->_search($filter,$paging,Membership_Controller_SoMemberEconomic::getInstance(),'Membership_Model_SoMemberEconomicFilter');
    }
    
    public function deleteSoMemberEconomics($ids){
    	return $this->_delete($ids, Membership_Controller_SoMemberEconomic::getInstance());
    }
    
    public function saveSoMemberEconomic($recordData){
    	$member = new Membership_Model_SoMemberEconomic();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = Membership_Controller_SoMemberEconomic::getInstance()->create($member);
        } else {
            $member = Membership_Controller_SoMemberEconomic::getInstance()->update($member);
        }
        
        return $member->toArray();
    }
    
 	public function reverseInvoice($memberId, $feeProgressId, $receiptId){
    	return $this->_soMemberController->reverseFeeInvoice($memberId, $feeProgressId, $receiptId);
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getSoMemberFeeProgress($id){
    	if(!$id ) {
            $memberFeeProgress = $this->_soMemberFeeProgressController->getEmptySoMemberFeeProgress();
        } else {
            $memberFeeProgress = $this->_soMemberFeeProgressController->get($id);
        }
        $memberFeeProgressData = $memberFeeProgress->toArray();
        
        return $memberFeeProgressData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchSoMemberFeeProgresss($filter,$paging){
    	$result = $this->_search($filter,$paging,$this->_soMemberFeeProgressController,'Membership_Model_SoMemberFeeProgressFilter');
    	$result['sum'] = $result['totalcount']['sum'];
    	$result['sum_preview'] = $result['totalcount']['sum_preview'];
    	$result['sum_open'] = $result['totalcount']['sum_open'];
    	$result['sum_payed'] = $result['totalcount']['sum_payed'];
        $result['totalcount'] = $result['totalcount']['count'];
        return $result;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteSoMemberFeeProgresss($ids){
    	//return $this->_delete($ids, $this->_soMemberFeeProgressController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveSoMemberFeeProgress($recordData){
    	$memberFeeProgress = new Membership_Model_SoMemberFeeProgress();
        $memberFeeProgress->setFromArray($recordData);
        
        if (empty($memberFeeProgress->id)) {
            $memberFeeProgress = $this->_soMemberFeeProgressController->create($memberFeeProgress);
        } else {
            $memberFeeProgress = $this->_soMemberFeeProgressController->update($memberFeeProgress);
        }
        
        $result =  $this->getSoMemberFeeProgress($memberFeeProgress->getId());
        return $result;
    }
    
    /** fee definitions **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getFeeDefinition($id){
    	if(!$id ) {
            $member = $this->_feeDefinitionController->getEmptyFeeDefinition();
        } else {
            $member = $this->_feeDefinitionController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchFeeDefinitions($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeDefinitionController,'Membership_Model_FeeDefinitionFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteFeeDefinitions($ids){
    	 return $this->_delete($ids, $this->_feeDefinitionController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveFeeDefinition($recordData){
    	$member = new Membership_Model_FeeDefinition();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_feeDefinitionController->create($member);
        } else {
            $member = $this->_feeDefinitionController->update($member);
        }
        
        $result =  $this->getFeeDefinition($member->getId());
        return $result;
    } 

    /** fee articles **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getFeeDefFilter($id){
    	if(!$id ) {
            $member = $this->_feeDefFilterController->getEmptyFeeDefFilter();
        } else {
            $member = $this->_feeDefFilterController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchFeeDefFilters($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeDefFilterController,'Membership_Model_FeeDefFilterFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteFeeDefFilters($ids){
    	 return $this->_delete($ids, $this->_feeDefFilterController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveFeeDefFilter($recordData){
    	$member = new Membership_Model_FeeDefFilter();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_feeDefFilterController->create($member);
        } else {
            $member = $this->_feeDefFilterController->update($member);
        }
        
        $result =  $this->getFeeDefFilter($member->getId());
        return $result;
    }  
    
	/** fee articles **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getFeeVarConfig($id){
    	if(!$id ) {
            $member = $this->_feeVarConfigController->getEmptyFeeVarConfig();
        } else {
            $member = $this->_feeVarConfigController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchFeeVarConfigs($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeVarConfigController,'Membership_Model_FeeVarConfigFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteFeeVarConfigs($ids){
    	 return $this->_delete($ids, $this->_feeVarConfigController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveFeeVarConfig($recordData){
    	$member = new Membership_Model_FeeVarConfig();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_feeVarConfigController->create($member);
        } else {
            $member = $this->_feeVarConfigController->update($member);
        }
        
        $result =  $this->getFeeVarConfig($member->getId());
        return $result;
    }  
    
	/** fee articles **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getFeeVar($id){
    	if(!$id ) {
            $member = $this->_feeVarController->getEmptyFeeVar();
        } else {
            $member = $this->_feeVarController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchFeeVars($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeVarController,'Membership_Model_FeeVarFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteFeeVars($ids){
    	 return $this->_delete($ids, $this->_feeVarController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveFeeVar($recordData){
    	$member = new Membership_Model_FeeVar();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_feeVarController->create($member);
        } else {
            $member = $this->_feeVarController->update($member);
        }
        
        $result =  $this->getFeeVar($member->getId());
        return $result;
    }  
    
    /** fee articles **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getMessage($id){
    	if(!$id ) {
            $message = Membership_Controller_Message::getInstance()->getEmptyMessage();
        } else {
            $message = Membership_Controller_Message::getInstance()->get($id);
        }
        $messageData = $message->toArray();
        
        return $messageData;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchMessages($filter,$paging){
    	return $this->_search($filter,$paging,Membership_Controller_Message::getInstance(),'Membership_Model_MessageFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteMessages($ids){
    	 return $this->_delete($ids, Membership_Controller_Message::getInstance());
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveMessage($recordData){
    	$message = new Membership_Model_Message();
        $message->setFromArray($recordData);
        
        if (!$message['id']) {
            $message = Membership_Controller_Message::getInstance()->create($message);
        } else {
            $message = Membership_Controller_Message::getInstance()->update($message);
        }
        
        $result =  $this->getMessage($message->getId());
        return $result;
    }  
    
	public function checkNewMessages(){
    	try{
    		return Membership_Controller_Message::getInstance()->checkNewMessages();
    	}catch(Exception $e){
    		return array('success' => false, 'result' => null);
    	}
    }
    
	public function markMessageRead($messageId){
    	try{
    		Membership_Controller_Message::getInstance()->markMessageRead($messageId, Tinebase_Core::getUser()->getId());
    		return array('success' => true, 'result' => null);
    	}catch(Exception $e){
    		return array('success' => false, 'result' => null);
    	}
    }
    
/** fee articles **/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */ 
    public function getFeeVarOrderPos($id){
    	if(!$id ) {
            $member = $this->_feeVarOrderPosController->getEmptyFeeVarOrderPos();
        } else {
        	try{
            	$member = $this->_feeVarOrderPosController->get($id);
        	}catch(Exception $e){
        		$member = $this->_feeVarOrderPosController->getEmptyFeeVarOrderPos();
        		$member->__set('id', $id);
        		$member->__set('order_pos_id', $id);
        		$member = $this->_feeVarOrderPosController->create($member);
        	}
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function getVarConfOrderPosByOrderPosId($orderPosId){
    	return $this->_feeVarOrderPosController->getByOrderPosId($orderPosId);
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $filter
     * @param unknown_type $paging
     */
    public function searchFeeVarOrderPoss($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeVarOrderPosController,'Membership_Model_FeeVarOrderPosFilter');
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $ids
     */
    public function deleteFeeVarOrderPoss($ids){
    	 return $this->_delete($ids, $this->_feeVarOrderPosController);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $recordData
     */
    public function saveFeeVarOrderPos($recordData){
    	$member = new Membership_Model_FeeVarOrderPos();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
        	$member['id'] = $member['order_pos_id'];
            $member = $this->_feeVarOrderPosController->create($member);
        } else {
            $member = $this->_feeVarOrderPosController->update($member);
        }
        
        $result =  $this->getFeeVarOrderPos($member->getId());
        return $result;
    }  
    
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getMembershipKind($id){
    	if(!$id ) {
            $member = $this->_membershipKindController->getEmptyMembershipKind();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipKindController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchMembershipKinds($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipKindController,'Membership_Model_MembershipKindFilter');
    }
    
    public function deleteMembershipKinds($ids){
    	return $this->_delete($ids, $this->_membershipKindController);
    }
    
    public function saveMembershipKind($recordData){
    	$obj = new Membership_Model_MembershipKind();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_membershipKindController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_membershipKindController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_membershipKindController->create($obj);
        }

        return $obj->toArray();
    }
    
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getMembershipAccount($id){
    	if(!$id ) {
            $member = $this->_membershipAccountController->getEmptyMembershipAccount();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipAccountController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchMembershipAccounts($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipAccountController,'Membership_Model_MembershipAccountFilter');
    }
    
    public function deleteMembershipAccounts($ids){
    	return $this->_delete($ids, $this->_membershipAccountController);
    }
    
    public function saveMembershipAccount($recordData){
    	$obj = new Membership_Model_MembershipAccount();
	$objId =$recordData['id'];
        //try{
        	if($objId!=0){
        		$obj = $this->_membershipAccountController->get($objId);
			unset($recordData['account_id']);
        		$obj->setFromArray($recordData);
        		$obj->flatten();
        		$obj = $this->_membershipAccountController->update($obj);
        	}else{
			$obj->setFromArray($recordData);
        		$obj = $this->_membershipAccountController->create($obj);	
        	}
        //}catch(Exception $e){
       // 	Tinebase_Core::getLogger()->warn(__METHOD__ . '::' . __LINE__ . ' ' . $e->__toString());
        	
        //}

        return $obj->toArray();
    }
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getAssociation($id){
    	if(!$id ) {
            $member = $this->_associationController->getEmptyAssociation();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_associationController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchAssociations($filter,$paging){
    	return $this->_search($filter,$paging,$this->_associationController,'Membership_Model_AssociationFilter');
    }
    
    public function deleteAssociations($ids){
    	return $this->_delete($ids, $this->_associationController);
    }
    
    public function saveAssociation($recordData){
    	$obj = new Membership_Model_Association();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_associationController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_associationController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_associationController->create($obj);
        }

        return $obj->toArray();
    }
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getAction($id){
    	if(!$id ) {
            $member = $this->_actionController->getEmptyAction();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_actionController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchActions($filter,$paging){
    	return $this->_search($filter,$paging,$this->_actionController,'Membership_Model_ActionFilter');
    }
    
    public function deleteActions($ids){
    	return $this->_delete($ids, $this->_actionController);
    }
    
    public function saveAction($recordData){
    	$obj = new Membership_Model_Action();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_actionController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_actionController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_actionController->create($obj);
        }

        return $obj->toArray();
    }
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getEntryReason($id){
    	if(!$id ) {
            $member = $this->_entryReasonController->getEmptyEntryReason();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_entryReasonController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchEntryReasons($filter,$paging){
    	return $this->_search($filter,$paging,$this->_entryReasonController,'Membership_Model_EntryReasonFilter');
    }
    
    public function deleteEntryReasons($ids){
    	return $this->_delete($ids, $this->_entryReasonController);
    }
    
    public function saveEntryReason($recordData){
    	$obj = new Membership_Model_EntryReason();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_entryReasonController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_entryReasonController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_entryReasonController->create($obj);
        }

        return $obj->toArray();
    }
    
    
/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getTerminationReason($id){
    	if(!$id ) {
            $member = $this->_terminationReasonController->getEmptyTerminationReason();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_terminationReasonController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchTerminationReasons($filter,$paging){
    	return $this->_search($filter,$paging,$this->_terminationReasonController,'Membership_Model_TerminationReasonFilter');
    }
    
    public function deleteTerminationReasons($ids){
    	return $this->_delete($ids, $this->_terminationReasonController);
    }
    
    public function saveTerminationReason($recordData){
    	$obj = new Membership_Model_TerminationReason();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_terminationReasonController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_terminationReasonController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_terminationReasonController->create($obj);
        }

        return $obj->toArray();
    }
    
    //// jobs
    public function requestBillingJob($filters, $feeYear, $action, $dueDate){
    	$data = array(
    		'filters' 	=> Zend_Json::decode($filters),
    		'feeYear' 	=> $feeYear,
    		'action'	=> $action,
    		'dueDate'   => $dueDate,
    		'class'		=> 'Membership_Controller_SoMember',
    		'method'	=> 'batchCreateFeeInvoice'
    	);
    	
    	$categories = array(
    		'FEEPROGRESS' 	=> Membership_Model_Job::CATEGORY_FEEPROGRESS,
    		'FEEINVOICECURRENT' => Membership_Model_Job::CATEGORY_FEEINVOICECURRENT,
    		'FEEINVOICE'	=> Membership_Model_Job::CATEGORY_FEEINVOICE
    	);
    	$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob($categories[$action], null, null, $data);
    	//Membership_Api_JobManager::getInstance()->runJob($job->getId());
    	return $job->toArray();
    }
    
    public function requestBillingJobForSelectedMembers($memberIds, $feeYear, $action, $dueDate){
    	if(	
    		count($memberIds)==0
    		|| empty($feeYear)
    		|| empty($action)
    	){
    		throw new Zend_Exception('No members selected'); 
    	}
    	
    	$filterSelectedMembers = array(array(
			'field' => 'id',
			'operator' => 'in',
			'value' => $memberIds
		));
		
    	$data = array(
    		'filters' 	=> $filterSelectedMembers,
    		'feeYear' 	=> $feeYear,
    		'action'	=> $action,
    		'dueDate'   => $dueDate,
    		'class'		=> 'Membership_Controller_SoMember',
    		'method'	=> 'batchCreateFeeInvoice'
    	);
    	
    	$categories = array(
    		'FEEPROGRESS' 		=> Membership_Model_Job::CATEGORY_FEEPROGRESS,
    		'FEEINVOICECURRENT' => Membership_Model_Job::CATEGORY_FEEINVOICECURRENT,
    		'FEEINVOICE'		=> Membership_Model_Job::CATEGORY_FEEINVOICE,
    		'FEEINVOICECOMPLETE'		=> Membership_Model_Job::CATEGORY_FEEINVOICE
    	);
    	$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob($categories[$action], null, null, $data);
    	//Membership_Api_JobManager::getInstance()->runJob($job->getId());
    	return $job->toArray();
    }
    
    public function requestCustomExportAsCsvJob($filters, $exportClassName, $jobName1, $jobName2, $forFeeProgress){
    	$data = array(
    		'filters' 	=> Zend_Json::decode($filters),
    		'exportClassName' 	=> $exportClassName,
    		'forFeeProgress' => $forFeeProgress
    	);

    	$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob('MANUALEXPORT', $jobName1, $jobName2, $data);
    	//Membership_Api_JobManager::getInstance()->runJob($job->getId());
    	return $job->toArray();
    }
    
    public function requestPrintInvoiceJob($parentJobId){
    	$data = array(
    		'printType' => 'INVOICE'
    	);
 		$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob(Membership_Model_Job::CATEGORY_PRINT, null, null, $data);
    	Membership_Api_JobManager::getInstance()->updateJobFromArray(array(
    		'job_id' => $parentJobId
    	));
 		
 		//Membership_Api_JobManager::getInstance()->runJob($job->getId());
    	return $job->toArray();
    }
    
    public function requestPrintVerificationListJob($parentJobId){
    	$data = array(
    		'printType' => 'VERIFICATION'
    	);
 		$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob(Membership_Model_Job::CATEGORY_PRINT, null, null, $data);
    	Membership_Api_JobManager::getInstance()->updateJobFromArray(array(
    		'job_id' => $parentJobId
    	));
 		
 		//Membership_Api_JobManager::getInstance()->runJob($job->getId());
    	return $job->toArray();
    }
    
 	public function requestPrintMultiLettersJob($name, $description, $filters,$sort,$dir, $templateId, $data){
 		
    	$data = array(
    		'printType' => 'MULTILETTER',
    		'filters' 	=> Zend_Json::decode($filters),
    		'sort' => $sort,
    		'dir' => $dir,
    		'templateId' => $templateId,
    		'data' => Zend_Json::decode($data)
    	);
 		$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob(Membership_Model_Job::CATEGORY_PRINT, $name, $description, $data);
    	
 		return $job->toArray();
    }
    
	public function requestPrintDueMemberLettersJob($name, $description, $filters, $data){
 		/*
 		 var data = {
			sort: {
				fields: [ sort1, sort2],
				dir: dir1
			}	
		}
 		 * 
 		 */
		$data = Zend_Json::decode($data);
		
    	$paramData = array(
    		'printType' => 'DUEMEMBERLETTERS',
    		'filters' 	=> Zend_Json::decode($filters),
    		'sort' => Zend_Json::encode($data['sort']['fields']),
    		'dir' => 'ASC',
    		'data' => $data
    	);
 		$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob(Membership_Model_Job::CATEGORY_PRINT, $name, $description, $paramData);
    	
 		return $job->toArray();
    }
    
	public function requestDueTasksJob($validDate, $action, $jobName2){
 		/*
 		 *
 		client params:
 		
 		method: 'Membership.requestDueTasksJob',
		validDate: Ext.getCmp('valid_date').getValue(),
		action: Ext.getCmp('action').getValue(),
		jobName2: ''
 		  
 		 * 
 		 */
		
		$validActions = array(
			Membership_Controller_Action::FEEGROUPCHANGE => 'Beitragsruppenwechsel durchführen',
			Membership_Controller_Action::TERMINATION => 'Austritte finalisieren',
			Membership_Controller_Action::PARENTCHANGE => 'Vereinswechsel durchführen'
		);
		
		if(!array_key_exists($action, $validActions)){
			throw new Zend_Exception('Invalid action given for '. __FUNCTION__);
		}
		
    	$data = array(
    		'action' => $action,
    		'validDate' => $validDate
    	);
 		$job = Membership_Api_JobManager::getInstance()->requestRuntimeJob(Membership_Model_Job::CATEGORY_DUETASKS, $validActions[$action], $jobName2, $data);
    	
 		return $job->toArray();
    }
    
   
    
	
	public function runJob($jobId){
		ignore_user_abort(true);
		set_time_limit(0);
		Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' run job (id: '.$jobId.')');
		$job = Membership_Api_JobManager::getInstance()->runJob($jobId);
		return $job->toArray();
	}
    
 	public function getJob($id){
    	if(!$id ) {
            $member = $this->_jobController->getEmptyJob();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_jobController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchJobs($filter,$paging){
    	return $this->_search($filter,$paging,$this->_jobController,'Membership_Model_JobFilter');
    }
    
    public function deleteJobs($ids){
    	return $this->_delete($ids, $this->_jobController);
    }
    
    public function saveJob($recordData){
    	$obj = new Membership_Model_Job();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_jobController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_jobController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_jobController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function purgePrintJobStorage(){
    	Membership_Controller_Job::getInstance()->purgePrintJobStorage();
    }
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getActionHistory($id){
    	if(!$id ) {
            $member = $this->_actionHistoryController->getEmptyActionHistory();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_actionHistoryController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchActionHistorys($filter,$paging){
    	return $this->_search($filter,$paging,$this->_actionHistoryController,'Membership_Model_ActionHistoryFilter');
    }
    
    public function deleteActionHistorys($ids){
    	return $this->_delete($ids, $this->_actionHistoryController);
    }
    
    public function saveActionHistory($recordData){
    	$obj = new Membership_Model_ActionHistory();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_actionHistoryController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_actionHistoryController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_actionHistoryController->create($obj);
        }

        return $obj->toArray();
    }
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getMembershipData($id){
    	if(!$id ) {
            $member = $this->_membershipDataController->getEmptyMembershipData();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipDataController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchMembershipDatas($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipDataController,'Membership_Model_MembershipDataFilter');
    }
    
    public function deleteMembershipDatas($ids){
    	return $this->_delete($ids, $this->_membershipDataController);
    }
    
    public function saveMembershipData($recordData){
    	$obj = new Membership_Model_MembershipData();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_membershipDataController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_membershipDataController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_membershipDataController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function requestMemberDataChange($memberId, $data, $validDate, $changeSet){
    	$this->_soMemberController->requestMemberDataChange($memberId, $data, $validDate, $changeSet);
    }
    
	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getMembershipExport($id){
    	if(!$id ) {
            $member = $this->_membershipExportController->getEmptyMembershipExport();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipExportController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchMembershipExports($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipExportController,'Membership_Model_MembershipExportFilter');
    }
    
    public function deleteMembershipExports($ids){
    	return $this->_delete($ids, $this->_membershipExportController);
    }
    
    public function saveMembershipExport($recordData){
    	$obj = new Membership_Model_MembershipExport();
        $obj->setFromArray($recordData);
        try{
        	$objName = $obj->__get('name');
        	$obj = $this->_membershipExportController->getByName($objName);
        	$obj->setFromArray($recordData);
        	$obj = $this->_membershipExportController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_membershipExportController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getAwardList($id){
    	if(!$id ) {
            $member = $this->_awardListController->getEmptyAwardList();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_awardListController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchAwardLists($filter,$paging){
    	return $this->_search($filter,$paging,$this->_awardListController,'Membership_Model_AwardListFilter');
    }
    
    public function deleteAwardLists($ids){
    	return $this->_delete($ids, $this->_awardListController);
    }
    
    public function saveAwardList($recordData){
    	$obj = new Membership_Model_AwardList();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_awardListController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_awardListController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_awardListController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getMembershipAward($id){
    	if(!$id ) {
            $member = $this->_membershipAwardController->getEmptyMembershipAward();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipAwardController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchMembershipAwards($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipAwardController,'Membership_Model_MembershipAwardFilter');
    }
    
    public function deleteMembershipAwards($ids){
    	return $this->_delete($ids, $this->_membershipAwardController);
    }
    
    public function saveMembershipAward($recordData){
    	$obj = new Membership_Model_MembershipAward();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_membershipAwardController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_membershipAwardController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_membershipAwardController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getCommittee($id){
    	if(!$id ) {
            $member = $this->_committeeController->getEmptyCommittee();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_committeeController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchCommittees($filter,$paging){
    	return $this->_search($filter,$paging,$this->_committeeController,'Membership_Model_CommitteeFilter');
    }
    
    public function deleteCommittees($ids){
    	return $this->_delete($ids, $this->_committeeController);
    }
    
    public function saveCommittee($recordData){
    	$obj = new Membership_Model_Committee();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_committeeController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_committeeController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_committeeController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getCommitteeFunc($id){
    	if(!$id ) {
            $member = $this->_committeeFuncController->getEmptyCommitteeFunc();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_committeeFuncController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchCommitteeFuncs($filter,$paging){
    	return $this->_search($filter,$paging,$this->_committeeFuncController,'Membership_Model_CommitteeFuncFilter');
    }
    
    public function deleteCommitteeFuncs($ids){
    	return $this->_delete($ids, $this->_committeeFuncController);
    }
    
    public function saveCommitteeFunc($recordData){
    	$obj = new Membership_Model_CommitteeFunc();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_committeeFuncController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_committeeFuncController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_committeeFuncController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getCommitteeFunction($id){
    	if(!$id ) {
            $member = $this->_committeeFunctionController->getEmptyCommitteeFunction();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_committeeFunctionController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchCommitteeFunctions($filter,$paging){
    	return $this->_search($filter,$paging,$this->_committeeFunctionController,'Membership_Model_CommitteeFunctionFilter');
    }
    
    public function deleteCommitteeFunctions($ids){
    	return $this->_delete($ids, $this->_committeeFunctionController);
    }
    
    public function saveCommitteeFunction($recordData){
    	$obj = new Membership_Model_CommitteeFunction();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_committeeFunctionController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_committeeFunctionController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_committeeFunctionController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getCommitteeKind($id){
    	if(!$id ) {
            $member = $this->_committeeKindController->getEmptyCommitteeKind();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_committeeKindController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchCommitteeKinds($filter,$paging){
    	return $this->_search($filter,$paging,$this->_committeeKindController,'Membership_Model_CommitteeKindFilter');
    }
    
    public function deleteCommitteeKinds($ids){
    	return $this->_delete($ids, $this->_committeeKindController);
    }
    
    public function saveCommitteeKind($recordData){
    	$obj = new Membership_Model_CommitteeKind();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_committeeKindController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_committeeKindController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_committeeKindController->create($obj);
        }

        return $obj->toArray();
    }
    
    public function getCommitteeLevel($id){
    	if(!$id ) {
            $member = $this->_committeeLevelController->getEmptyCommitteeLevel();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_committeeLevelController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchCommitteeLevels($filter,$paging){
    	return $this->_search($filter,$paging,$this->_committeeLevelController,'Membership_Model_CommitteeLevelFilter');
    }
    
    public function deleteCommitteeLevels($ids){
    	return $this->_delete($ids, $this->_committeeLevelController);
    }
    
    public function saveCommitteeLevel($recordData){
    	$obj = new Membership_Model_CommitteeLevel();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_committeeLevelController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_committeeLevelController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_committeeLevelController->create($obj);
        }

        return $obj->toArray();
    }
    
   public function getFilterSet($id){
    	if(!$id ) {
            $member = $this->_filterSetController->getEmptyFilterSet();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_filterSetController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchFilterSets($filter,$paging){
    	return $this->_search($filter,$paging,$this->_filterSetController,'Membership_Model_FilterSetFilter');
    }
    
    public function deleteFilterSets($ids){
    	return $this->_delete($ids, $this->_filterSetController);
    }
    
    public function saveFilterSet($recordData){
    	$obj = new Membership_Model_FilterSet();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_filterSetController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_filterSetController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_filterSetController->create($obj);
        }

        return $obj->toArray();
    }
    
    
   public function getFilterResult($id){
    	if(!$id ) {
            $member = $this->_filterResultController->getEmptyFilterResult();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_filterResultController->get($id);
        }
        $memberExport = $member->toArray();
        
        return $memberExport;
    }
    
    public function searchFilterResults($filter,$paging){
    	return $this->_search($filter,$paging,$this->_filterResultController,'Membership_Model_FilterResultFilter');
    }
    
    public function deleteFilterResults($ids){
    	return $this->_delete($ids, $this->_filterResultController);
    }
    
    public function saveFilterResult($recordData){
    	$obj = new Membership_Model_FilterResult();
        $obj->setFromArray($recordData);
        try{
        	$objId = $obj->getId();
        	$obj = $this->_filterResultController->get($objId);
        	$obj->setFromArray($recordData);
        	$obj = $this->_filterResultController->update($obj);
        }catch(Exception $e){
        	$obj = $this->_filterResultController->create($obj);
        }

        return $obj->toArray();
    }
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getFeeGroup($id){
    	if(!$id ) {
            $member = $this->_feeGroupController->getEmptyFeeGroup();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_feeGroupController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchFeeGroups($filter,$paging){
    	return $this->_search($filter,$paging,$this->_feeGroupController,'Membership_Model_FeeGroupFilter');
    }
    
    public function deleteFeeGroups($ids){
    	return $this->_delete($ids, $this->_feeGroupController);
    }
    
    public function saveFeeGroup($recordData){
    	$member = new Membership_Model_FeeGroup();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_feeGroupController->create($member);
        } else {
            $member = $this->_feeGroupController->update($member);
        }
        
        return $member->toArray();
    }
    
    // Vote
    
    public function buildMemberVotes($id){
    	return Membership_Controller_Vote::getInstance()->buildMemberVotes();
    }
    
 	public function getVote($id){
    	if(!$id ) {
            $member = Membership_Controller_Vote::getInstance()->getEmptyVote();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = Membership_Controller_Vote::getInstance()->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchVotes($filter,$paging){
    	return $this->_search($filter,$paging,Membership_Controller_Vote::getInstance(),'Membership_Model_VoteFilter');
    }
    
    public function deleteVotes($ids){
    	return $this->_delete($ids, Membership_Controller_Vote::getInstance());
    }
    
    public function saveVote($recordData){
    	$member = new Membership_Model_Vote();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = Membership_Controller_Vote::getInstance()->create($member);
        } else {
            $member = Membership_Controller_Vote::getInstance()->update($member);
        }
        
        return $member->toArray();
    }
    
	public function getVoteTransfer($id){
    	if(!$id ) {
            $member = Membership_Controller_VoteTransfer::getInstance()->getEmptyVoteTransfer();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = Membership_Controller_VoteTransfer::getInstance()->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchVoteTransfers($filter,$paging){
    	return $this->_search($filter,$paging,Membership_Controller_VoteTransfer::getInstance(),'Membership_Model_VoteTransferFilter');
    }
    
    public function deleteVoteTransfers($ids){
    	return $this->_delete($ids, Membership_Controller_VoteTransfer::getInstance());
    }
    
    public function saveVoteTransfer($recordData){
    	$member = new Membership_Model_VoteTransfer();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = Membership_Controller_VoteTransfer::getInstance()->create($member);
        } else {
            $member = Membership_Controller_VoteTransfer::getInstance()->update($member);
        }
        
        return $member->toArray();
    }
    
 	/**
     * 
     * Enter description here ...
     * @param unknown_type $id
     */
    public function getMembershipFeeGroup($id){
    	if(!$id ) {
            $member = $this->_membershipFeeGroupController->getEmptyMembershipFeeGroup();
        } else {
        	// -> get dependent records (contacts) embedded
            $member = $this->_membershipFeeGroupController->get($id);
        }
        $memberData = $member->toArray();
        
        return $memberData;
    }
    
    public function searchMembershipFeeGroups($filter,$paging){
    	return $this->_search($filter,$paging,$this->_membershipFeeGroupController,'Membership_Model_MembershipFeeGroupFilter');
    }
    
    public function deleteMembershipFeeGroups($ids){
    	return $this->_delete($ids, $this->_membershipFeeGroupController);
    }
    
    public function saveMembershipFeeGroup($recordData){
    	$member = new Membership_Model_MembershipFeeGroup();
        $member->setFromArray($recordData);
        
        if (!$member['id']) {
            $member = $this->_membershipFeeGroupController->create($member);
        } else {
            $member = $this->_membershipFeeGroupController->update($member);
        }
        
        return $member->toArray();
    }
    
    
// OpenItem
	public function getOpenItem($id){
		if(!$id ) {
			$obj = Membership_Controller_OpenItem::getInstance()->getEmptyOpenItem();
		} else {
			$obj = Membership_Controller_OpenItem::getInstance()->get($id);
		}
		$objData = $obj->toArray();

		return $objData;
	}

	public function searchOpenItems($filter,$paging){
		return $this->_search($filter,$paging,Membership_Controller_OpenItem::getInstance(),'Membership_Model_OpenItemFilter');
	}

	public function deleteOpenItems($ids){
		return $this->_delete($ids, Membership_Controller_OpenItem::getInstance());
	}

	public function saveOpenItem($recordData){
		return $this->_save($recordData, Membership_Controller_OpenItem::getInstance(), 'OpenItem');
	}
    
    
    /**
     * Returns registry data of Membership
     * @see Tinebase_Application_Json_Abstract
     * 
     * @return mixed array 'variable name' => 'data'
     */
    public function getRegistryData()
    {
    	$registryData = array(
    		'MembershipKinds' => array(
    			'full' => $this->getMembershipKinds(),
    			'simple' => $this->getMembershipKindsAsSimpleArray(),
    			'dependencies' => $this->getMembershipDependencies()
    		),
    		'MembershipExports' => $this->getMembershipExportsAsSimpleArray(),
    		'FeeGroups' => $this->getFeeGroupsAsSimpleArray(),
    		'Actions' => $this->getActionsAsSimpleArray(),
    		'Associations' => $this->getAssociations(),
    		'AwardLists' => $this->getAwardLists(),
    		'Committees' => $this->getCommitteesAsSimpleArray(),
    		'CommitteeFunctions' => $this->getCommitteeFunctionsAsSimpleArray(),
    		'CommitteeKinds' => $this->getCommitteeKinds(),
    		'CommitteeLevels' => $this->getCommitteeLevels(),
    		'EntryReasons' => $this->getEntryReasonsAsSimpleArray(),
    		'TerminationReasons' => $this->getTerminationReasonsAsSimpleArray()
        );        
        return $registryData;    
    }
    
    public function getMembershipKinds(){
    	return $this->getRegistryRecords($this->_membershipKindController->getAllMembershipKinds());
    }
    
    public function getFeeGroupsAsSimpleArray(){
    	return $this->_feeGroupController->getFeeGroupsAsSimpleArray();
    }
    
    public function getMembershipKindsAsSimpleArray(){
    	return $this->_membershipKindController->getMembershipKindsAsSimpleArray();
    }
    
    public function getMembershipExportsAsSimpleArray(){
    	return $this->_membershipExportController->getMembershipExportsAsSimpleArray();
    }
    public function getMembershipDependencies(){
    	return $this->_membershipKindController->getMembershipDependencies();
    }
    
    public function getAssociations(){
    	return $this->getRegistryRecords($this->_associationController->getAllAssociations());
    }
    
    public function getAwardLists(){
    	return $this->getRegistryRecords($this->_awardListController->getAllAwardLists());
    }
    
    public function getCommitteeFunctions(){
    	return $this->getRegistryRecords($this->_committeeFunctionController->getAllCommitteeFunctions());
    }
    
    public function getCommitteeFunctionsAsSimpleArray(){
    	return $this->_committeeFunctionController->getCommitteeFunctionsAsSimpleArray();
    }
    
    public function getCommittees(){
    	return $this->getRegistryRecords($this->_committeeController->getAllCommittees());
    }
    
    public function getCommitteesAsSimpleArray(){
    	return $this->_committeeController->getCommitteesAsSimpleArray();
    }
    
    public function getCommitteeKinds(){
    	return $this->getRegistryRecords($this->_committeeKindController->getAllCommitteeKinds());
    }
    
    public function getCommitteeLevels(){
    	return $this->getRegistryRecords($this->_committeeLevelController->getAllCommitteeLevels());
    }
    
    public function getActionsAsSimpleArray(){
    	return $this->_actionController->getActionsAsSimpleArray();
    }
    
    public function getEntryReasonsAsSimpleArray(){
    	return $this->_entryReasonController->getEntryReasonsAsSimpleArray();
    }
    
    public function getTerminationReasonsAsSimpleArray(){
    	return $this->_terminationReasonController->getTerminationReasonsAsSimpleArray();
    }
       
    /**
     * 
     * Get recordset for registry data
     * @param Tinebase_Record_RecordSet $rows
     */
    protected function getRegistryRecords(Tinebase_Record_RecordSet $rows){
    
    	$result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if($rows) {
            $rows->translate();
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }
        return $result;
    }
}

?>