<?php
/**
 * 
 * Enter description here ...
 * @author hhartl
 *
 */
class Membership_Controller_SoMember extends Tinebase_Controller_Record_Abstract
{
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	const LETTER_TYPE_BEGIN = 1;
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	const LETTER_TYPE_INSURANCE = 2;
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	const LETTER_TYPE_TERMINATION = 3;
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */
	const LETTER_TYPE_MEMBERCARD = 4;
	
	/**123
	 * config of courses
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;

	/**
     * Due date for queries (for some queries, like for age (calculated) values
     * we need an input parameter Date as basis. This can be the actual date (default)
     * or any other set by the controller from outside
     */
    private $dueDate = null;
    
    /**
     * Due date for queries (for some queries, like for age (calculated) values
     * we need an input parameter Date as basis. This can be the actual date (default)
     * or any other set by the controller from outside
     */
    private $beginDate = null;
    
    /**
     * End date for queries (for some queries, like for age (calculated) values
     * we need an input parameter Date as basis. This can be the actual date (default)
     * or any other set by the controller from outside
     */
    private $endDate = null;
	
    private $afterCreateMap = array();
    private $afterUpdateMap = array();
    
	/**
	 * the constructor
	 *
	 * don't use the constructor. use the singleton
	 */
	private function __construct() {
		$this->_applicationName = 'Membership';
		$this->_backend = new Membership_Backend_SoMember();
		$this->_modelName = 'Membership_Model_SoMember';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->soorders : new Zend_Config(array());
		$this->_resolveCustomFields = TRUE;
		// set due/begin/end date: default to current date
		$this->setDueDate(new Zend_Date());
		$this->setBeginDate(new Zend_Date());
		$this->setEndDate(new Zend_Date());
		$this->setBaseDate(new Zend_Date());
		
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
	
	/**
	 * 
	 * Get all memberships by contact id
	 * @param integer $contactId
	 */
	public function getByContactId($contactId){
		return $this->_backend->getMultipleByProperty($contactId, 'contact_id');
	}
	
	/**
	 * 
	 * Get all memberships by contact id
	 * @param integer $contactId
	 */
	public function getByContactIdAndMemberNumber($contactId, $memberNumber){
		return $this->_backend->getByPropertySet(array(
			'contact_id' => $contactId,
			'member_nr' => $memberNumber
		));
	}
	
	public function getContactIdByMemberNr($memberNr){
		return $this->_backend->getFieldByProperty('contact_id', $memberNr, 'member_nr', false, true);
	}
	
	
    public function getDebitorIdByMemberNr($memberNr){
    	$contactId = $this->getContactIdByMemberNr($memberNr);
    	$debitor = Billing_Controller_Debitor::getInstance()->getByContactOrCreate($contactId);
    	return $debitor->getId(); 
    }
    
	public function getDebitorByMemberNr($memberNr){
    	$contactId = $this->getContactIdByMemberNr($memberNr);
    	return Billing_Controller_Debitor::getInstance()->getByContactOrCreate($contactId);
    }
    
    public function getIdByMemberNr($memberNr){
    	return $this->_backend->getIdByProperty($memberNr, 'member_nr');
    }
    
	public function getMembershipByAccountId($accountId){
		return $this->_backend->getByProperty($accountId, 'account_id');
	}
	
 /**
     * Get membership record by id (with embedded dependent contacts)
     * 
     * @param int $id
     */
    public function getSoMember($id){
    	$record = $this->_backend->get($id, false, true);
    	if($record->getForeignId('fee_group_id')){
    		$feeGroupId = $record->getForeignId('fee_group_id');
    		$dueDate = $this->getDueDate();
    		$dateFilter = $dueDate->toString('yyyy-MM-dd');//.' 00:00:00';
    	
    		
    		$fgFilter = new Membership_Model_MembershipFeeGroupFilter(array(
	            array('field' => 'no_member',   'operator' => 'isnull', 'value' => null),
	            array('field' => 'valid_from_datetime',   'operator' => 'beforeOrAt', 'value' => $dateFilter),
	            array('field' => 'valid_to_datetime',   'operator' => 'afterAtOrNull', 'value' => $dateFilter),
	            array('field' => 'fee_group_id', 'operator'=> 'AND', 'value' => array(array('field'=>'id','operator'=>'equals','value'=>$feeGroupId)))
	        ),'AND');
	        $memFeeGroups = Membership_Controller_MembershipFeeGroup::getInstance()->search($fgFilter);
	        $catSums = array();
	        foreach($memFeeGroups as $mFeeGroup){
	        	$article = $mFeeGroup->getForeignRecord('article_id', Billing_Controller_Article::getInstance());
	        	$value = $mFeeGroup->__get('price');
	        	$label = $article->__get('name');
	        	$category = $mFeeGroup->__get('category');
	        	$summarize = $mFeeGroup->__get('summarize');
	        	$data = array('label' => $label, 'value' => $value, 'category' => $category, 'summarize' => $summarize);
	        	$result[] = $data;
	        	if(!array_key_exists($category, $catSums)){
	        		$catSums[$category] = 0;
	        		$catSums['X'.$category] = 0;
	        		$catSums['Y'.$category] =0;
	        	}
	        	if($summarize){
	        		$catSums[$category] += $value;
	        		$catSums['X'.$category] += $value;
	        	}
	        }
	        
	        if($parentMemberId = $record->getForeignId('parent_member_id')){
	        	if($memFeeGroup = Membership_Controller_MembershipFeeGroup::getInstance()->getForFeeGroupAndMember($parentMemberId, $feeGroupId)){
		        	$article = $memFeeGroup->getForeignRecord('article_id', Billing_Controller_Article::getInstance());
		        	$value = $memFeeGroup->__get('price');
		        	$label = $article->__get('name');
		        	$category = $memFeeGroup->__get('category');
	        		$summarize = $memFeeGroup->__get('summarize');
	        		$data = array('label' => $label, 'value' => $value, 'category' => $category, 'summarize' => $summarize);
	        		$result[] = $data;
		        	if(!array_key_exists($category, $catSums)){
		        		$catSums[$category] = 0;
		        		
		        	}
		        	if($summarize){
		        		$catSums[$category] += $value;
		        		$catSums['Y'.$category] += $value;
		        	}
	        	}
	        }
	        $record->__set('feegroup_prices', array(
	        	'items' => $result,
	        	'sums' => $catSums
	        ));
    	}
    	return $record;
    }
    /**
     * 
     * Enter description here ...
     */
	public function getEmptySoMember(){
		$emptyOrder = new Membership_Model_SoMember(null,true);
		return $emptyOrder;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberNr
	 */
	public function getSoMemberByMemberNr($memberNr){
		return $this->_backend->getSoMemberByNumber($memberNr, 'member_nr');
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $clubId
	 */
	public function getSoMembersByClubId($clubId){
		return $this->_backend->getSoMembersByClubId($clubId);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $parentMemberId
	 */
	public function getByParentMemberId($parentMemberId){
		return $this->_backend->getMultipleByProperty($parentMemberId, 'parent_member_id');
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberId
	 * @param unknown_type $memberKind
	 */
	public function getClubMaxMemberNr($memberId, $memberKind = null){
		return $this->_backend->getClubMaxMemberNr($memberId, $memberKind);
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
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Record_Interface $_pagination = NULL, $_getRelations = FALSE, $_onlyIds = FALSE, $_action = 'get', $withDep = true)
    {
//    	$this->_checkRight($_action);
//        $this->checkFilterACL($_filter, $_action);
//        
        $result = $this->_backend->search($_filter, $_pagination, $_onlyIds, $withDep, $_property);
    	if (! $_onlyIds) {
            if ($this->_resolveCustomFields) {
                Tinebase_CustomField::getInstance()->resolveMultipleCustomfields($result);
            }
        }
        return $result;    
    }
    
	public function searchProperty(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Record_Interface $_pagination = NULL,  $_property)
    {
        return $this->_backend->searchProperty($_filter, $_pagination,$_property);
    }

	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberId
	 * @param unknown_type $articleId
	 */
	public function getSpecialArticlePrice($memberId, $articleId){
		return Membership_Controller_MembershipFeeGroup::getInstance()->getArticlePriceForMember($memberId, $articleId);
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberId
	 * @param unknown_type $articleId
	 */
	public function getSpecialFeeGroupArticlePrice($memberId, $feeGroupId, $articleId){
		return Membership_Controller_MembershipFeeGroup::getInstance()->getFeeGroupArticlePriceForMember($memberId, $feeGroupId, $articleId);
	}
	
	public function getFeeGroupPriceSumByCategory($memberId, $feeGroupId, $category ){
		return Membership_Controller_MembershipFeeGroup::getInstance()->getFeeGroupPriceSumByCategory($memberId, $feeGroupId, $category );
	}
	
	public function getIdsForFilter(Membership_Model_SoMemberFilter $filter, array $sort=null){
		if(is_null($sort)){
			$sort = array('sort' => 'member_nr', 'dir' => 'ASC');
		}
		return $this->search(
			$filter,
			new Tinebase_Model_Pagination($sort),
			false, // don't resolve relations
			true // get ids only
		);
	}
	
	public function getNormalizedIdSetForFilters(array $filters){
		$result = array();
		foreach($filters as $filter){
			$ids = $this->getIdsForFilter($filter);
			$result = array_merge($result, $ids);
		}
		$result = array_unique($result);
		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $_files
	 * @param unknown_type $_options
	 */
	public function importTDFiles($_files, $_options = array()){
	    // extend execution time and close session
        Tinebase_Core::setExecutionLifeTime(7200); // 2 hours
        //Zend_Session::writeClose(true);
        
        // TODO importer is da TD importer
        $importer = null;
        // import files
        $result = array(
            'results'           => array(),
            'totalcount'        => 0,
            'failcount'         => 0,
            'duplicatecount'    => 0,
            'status'            => 'success'
        );
        foreach ($_files as $file) {
            $importResult = Membership_Import_TD::import($file['path']);
            $result['results']           = array();
            $result['totalcount']       += $importResult['totalcount'];
            $result['failcount']        += $importResult['failcount'];
            $result['duplicatecount']   += $importResult['duplicatecount'];
        }
        
        $result['status']= $importResult['status'];
        //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($result, true));
        
        return $result;
	}
	
	public function hasRequestedMemberDataChange($memberId, $validDate, $changeSet){
		Membership_Controller_ActionHistory::getInstance();
	}
	
	public function repairActionHistory(){
set_time_limit(0);
ignore_user_abort(true);
		ob_start();
		$filters[] = array(
			'field' => 'membership_type',
			'operator' => 'equals',
			'value' => 'VIASOCIETY'
		);
		
		$filters[] = array(
			'field' => 'membership_status',
			'operator' => 'equals',
			'value' => 'TERMINATED'
		);
		
		$filters[] = array(
			'field' => 'status_due_date',
			'operator' => 'equals',
			'value' => 'PASSIVE'
		);
		
		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
		
		$membershipIds = $this->getIdsForFilter($filters,	array( 'sort' => 'id', 'dir' => 'ASC'));
		
		
		echo count($membershipIds);
		
		
		
		$count = 0;
		// 1a) each for ACTIVE and for PASSIVE
		foreach($membershipIds as $memberId){
			if(!Membership_Controller_ActionHistory::getInstance()->hasOpenForMemberAndAction($memberId, Membership_Controller_Action::TERMINATION)){
				$member = $this->get($memberId);
				if($member->__get('termination_datetime')){
					echo $member->__get('member_nr')." ".$member->__get('termination_datetime')."\r\n";
					$count++;
					
					$date = new Zend_Date($member->__get('termination_datetime'));
					Membership_Controller_SoMember::getInstance()->requestMemberDataChange($memberId, array(), $date, 'Termination');
				}	
			}
		}
			
		// 1b)
		/*Membership_Controller_ActionHistory::getInstance()->setOmmitTracks();
		foreach($membershipIds as $memberId){
				$member = $this->get($memberId);
				if($member->__get('termination_datetime')){
					echo $member->__get('member_nr')." ".$member->__get('termination_datetime')."\r\n";
					$count++;
					$member->__set('membership_status', 'ACTIVE');
					Membership_Controller_SoMember::getInstance()->update($member);
				}	
			
		}*/
		
		echo "count: $count";
		
		return ob_get_clean();
		exit;
		
	}
	
	public function requestMemberDataChange($memberId, $data, $validDate, $changeSet){
    	if(!is_array($data)){
    		$data = Zend_Json::decode($data);
    	}
		
		$actCon = Membership_Controller_Action::getInstance();
		$ahCon = Membership_Controller_ActionHistory::getInstance();
    	$mdCon = Membership_Controller_MembershipData::getInstance();
    	
    	$member = $this->get($memberId);
    	
    	$actionHistory = $ahCon->getEmptyActionHistory();
		$actionHistory->setFromArray(
			array(
				'member_id' => $member->getId(),
				'association_id' => $member->getForeignId('association_id'),
				'parent_member_id' => $member->getForeignId('parent_member_id'),
				'child_member_id' => null,
				'created_datetime' => new Zend_Date(),
				'valid_datetime' => new Zend_Date($validDate),
				'to_process_datetime' => new Zend_Date($validDate),
				'process_datetime' => new Zend_Date($validDate),
				'created_by_user' => Tinebase_Core::get(Tinebase_Core::USER)->getId(),
				'valid_state' => 'PENDING'
			)
		);
		
		$memberData = $mdCon->getEmptyMembershipData();
		
		// set foreign ids
		$memberData->__set('member_id', $member->getId());
		$memberData->__set('parent_member_id', $member->getForeignId('parent_member_id'));
		$memberData->__set('association_id', $member->getForeignId('association_id'));
		$memberData->__set('fee_group_id', $member->getForeignId('fee_group_id'));
		
		$memberData->__set('membership_type', $member->getForeignId('membership_type'));
		$memberData->__set('membership_status', $member->getForeignId('membership_status'));
		$memberData->__set('fee_payment_interval', $member->getForeignId('fee_payment_interval'));
		$memberData->__set('fee_payment_method', $member->getForeignId('fee_payment_method'));
		$memberData->__set('bank_account_id', $member->getForeignId('bank_account_id'));
		$memberData->__set('individual_yearly_fee', $member->getForeignId('individual_yearly_fee'));
		$memberData->__set('donation', $member->getForeignId('donation'));
		$memberData->__set('fee_group_id', $member->getForeignId('fee_group_id'));
		$memberData->__set('additional_fee', $member->getForeignId('additional_fee'));
				
		$memberData->__set('valid_from', new Zend_Date($validDate));
		
		switch($changeSet){
			
			case Membership_Controller_ActionHistory::MEM_CHANGE_REQUEST_FEE_GROUP:
				$action = $actCon->get(Membership_Controller_Action::FEEGROUPCHANGE);
				$memberData->__set('fee_group_id', $data['fee_group_id']);
				break;
			
			case Membership_Controller_ActionHistory::MEM_CHANGE_REQUEST_STATE:
				$action = $actCon->get(Membership_Controller_Action::MEMSTATECHANGE);
				$memberData->__set('membership_status', $data['membership_status']);
				break;
				
			case Membership_Controller_ActionHistory::MEM_CHANGE_REQUEST_PARENT_MEMBER:
				$action = $actCon->get(Membership_Controller_Action::PARENTCHANGE);
				$memberData->__set('parent_member_id', $data['parent_member_id']);
				break;
				
			case Membership_Controller_ActionHistory::MEM_CHANGE_REQUEST_TERMINATION:
				$action = $actCon->get(Membership_Controller_Action::TERMINATION);
				$memberData->__set('membership_status', 'TERMINATED');
				break;
				
		}
		
    	$actionHistory->__set('action_id', $action->__get('id'));
    	$actionHistory->__set('action_category', 'DATA');
    	$actionHistory->__set('action_type', 'AUTO');
    	$actionHistory->__set('action_state', 'OPEN');
    	
    	$memberData = $mdCon->create($memberData);
    	$memberDataId = $memberData->__get('id');
    	
    	$actionHistory->__set('data_id', $memberDataId);
    	return $ahCon->create($actionHistory);
    	
    }
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $membershipExportId
	 * @param unknown_type $exportDefOptions
	 * @param unknown_type $actionHistoryFilter
	 */
	public function runPredefinedExportActionHistory($membershipExportId, $exportDefOptions, $actionHistoryFilter){
		try{
		
			$objMembershipExport = Membership_Controller_MembershipExport::getInstance()->get($membershipExportId);
			$expProcessor = Membership_Custom_Export_Processor::createWithOptions($objMembershipExport, $exportDefOptions, $actionHistoryFilter);
			$expProcessor->export();
		
		}catch(Exception $e){
			echo $e->__toString();
		}
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $membershipExport
	 */
	public function runPredefinedExport($membershipExport){
		try{
			
			$objMembershipExport = new Membership_Model_MembershipExport(null,true);
			$objMembershipExport->setFromArray(Zend_Json::decode($membershipExport));
			$expProcessor = Membership_Custom_Export_Processor::create($objMembershipExport);
			$expProcessor->export();
			
		}catch(Exception $e){
			echo $e->__toString();
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $filter
	 */
	public function printLabels($filters){
		
		Tinebase_Core::setExecutionLifeTime(360);

		
		if(!is_array($filters)){
			$filters = Zend_Json::decode($filters);
		}
		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
		$pagination = new Tinebase_Model_Pagination(
			array( 'sort' => 'member_nr', 'dir' => 'ASC')
		);
		// onlyIds: false, withDep: false (-> don't load embedded foreignrecords)
		$memberships = $this->search($filters, $pagination, false, false, 'get', false);
//		$contactIds = $memberships->__get('contact_id');
//		$cPagination = new Tinebase_Model_Pagination(
//			array( 'sort' => 'n_fileas', 'dir' => 'ASC')
//		);
//		print_r($contactIds);
//		$contactFilter = new Addressbook_Model_ContactFilter(array());
//		$contactFilter->addFilter(new Tinebase_Model_Filter_Id('id', 'in', $contactIds));
//        $contacts = Addressbook_Controller_Contact::getInstance()->search($contactFilter, $cPagination, FALSE, TRUE);
//        $memDta = array();
        foreach($memberships as $membership){
        	
        	// TODO: take care! if multiple memberships are associated with one contact
        	// find will only deliver the first record!
        	//$membership = $memberships->find('contact_id', $contact->getId());
        	$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
        	
        	$pmNr = null;
        	$parentMember = null;
        	if($membership->__get('parent_member_id')){
        		$parentMember = $membership->getForeignRecord('parent_member_id', $this);
        		$pmNr = $parentMember->__get('member_nr');
        	}
        	$memDta[$contact->__get('n_fileas').$membership->__get('member_nr')] = array(
        		'nr1' => $membership->__get('member_nr'),
        		'nr2' => $contact->getId(),
        		'pnr' => $pmNr,
        		'adress' => $contact->getLetterDrawee()->suppressCountries(array('DE'))->setLineBreak('<text:line-break/>')->toText()
        	);
        	ksort($memDta);
        }
        
        $data = array(
        	'ETIKETT_TABLE' => $memDta
        );
        
        Addressbook_Controller_PrintAdressLabels::getInstance()->printLabels($data);

	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberIds
	 */
	public function printBeginLetter($memberIds, $data){
		$this->printMemberLetter($memberIds, self::LETTER_TYPE_BEGIN, $data);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberIds
	 */
	public function printInsuranceLetter($memberIds, $data){
		$this->printMemberLetter($memberIds, self::LETTER_TYPE_INSURANCE, $data);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberIds
	 */
	public function printTerminationLetter($memberIds, $data){
		$this->printMemberLetter($memberIds, self::LETTER_TYPE_TERMINATION, $data);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberIds
	 */
	public function printMemberCardLetter($memberIds, $data){
		$this->printMemberLetter($memberIds, self::LETTER_TYPE_MEMBERCARD, $data);
	}
	
	public function printDueMemberLetters($letterType, $reprintDate=null, $filters = null, $additionalFilters = null, $data = null){
		try{
			if($reprintDate == ''){
				$reprintDate = null;
			}
			if(is_null($filters)){
				$filters = array();
			}elseif(!is_array($filters)){
				$filters = Zend_Json::decode($filters);
			}
			if(is_null($reprintDate)){
				switch($letterType){
	
					case self::LETTER_TYPE_BEGIN:
						$filters[] = array(
							'field' => 'print_reception_date',
							'operator' => 'isnull',
							'value' => ''
						);
						break;
						
					case self::LETTER_TYPE_INSURANCE:
						$filters[] = array(
							'field' => 'print_confirmation_date',
							'operator' => 'isnull',
							'value' => ''
						);
						break;
						
					case self::LETTER_TYPE_TERMINATION:
						$filters[] = array(
							'field' => 'print_discharge_date',
							'operator' => 'isnull',
							'value' => ''
						);
				
						break;
					
					case self::LETTER_TYPE_MEMBERCARD:
						$year = $data['memberYear'];
						/*$filters[] = array(
							'field' => 'exp_membercard_datetime',
							'operator' => 'isnull',
							'value' => ''
						);
						*/
						$filters[] = array(
							'field' => 'member_card_year',
							'operator' => 'less',
							'value' => $year
						);
						
						$filters[] = array(
			    			'field' => 'termination_datetime',
			    			'operator' => 'afterAtOrNull',
			    			'value' => $year.'-12-31'
			    		);
			    		
						break;
				}
			}else{
				
				$reprintDate = new Zend_Date($reprintDate);
				switch($letterType){
	
					case self::LETTER_TYPE_BEGIN:
						$filters[] = array(
							'field' => 'print_reception_date',
							'operator' => 'equals',
							'value' => $reprintDate->toString('yyyy-MM-dd')
						);
						break;
						
					case self::LETTER_TYPE_INSURANCE:
						$filters[] = array(
							'field' => 'print_confirmation_date',
							'operator' => 'equals',
							'value' => $reprintDate->toString('yyyy-MM-dd')
						);
						break;
						
					case self::LETTER_TYPE_TERMINATION:
						$filters[] = array(
							'field' => 'print_discharge_date',
							'operator' => 'equals',
							'value' => $reprintDate->toString('yyyy-MM-dd')
						);
						break;
						
					case self::LETTER_TYPE_MEMBERCARD:
						$filters[] = array(
							'field' => 'exp_membercard_datetime',
							'operator' => 'equals',
							'value' => $reprintDate->toString('yyyy-MM-dd')
						);
						break;						
				}
			}
			
			$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
			if(!array_key_exists('sort', $data)){
				$sort = 'member_nr';
			}else{
				$sort = $data['sort']['fields'];
			}
			$membershipIds = $this->getIdsForFilter($filters,	array( 'sort' => $sort, 'dir' => 'ASC'));
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' memberIds DUE: '. print_r($membershipIds,true));
					
			return $this->printMemberLetter($membershipIds, $letterType, $data, $reprintDate);		
		}catch(Exception $e){
			$a = $e->__toString();
			throw $e;
			//Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' except: DUE: '. print_r($membershipIds,true));
			
			
			//exit;
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberIds
	 * @param unknown_type $letterType
	 */
	public function printMemberLetter($memberIds, $letterType, $data, $reprintDate = null){
		try{
			
			
			// omitt action history tracks for printing members!!!
			// update should not trigger state changes etc.
			Membership_Controller_ActionHistory::getInstance()->setOmmitTracks();
			
			$contacts = array();
			$members = array();
			// get data json string to assoc array
			if(!is_array($data)){
				$data = Zend_Json::decode($data);
			}
			
			$additionalData = array();
			$memberCardYear = null;
			if(array_key_exists('memberYear', $data)){
				$date = new Zend_Date($data['memberYear']);
				$memberCardYear = $date->toString('yyyy');
				$additionalData['LY'] = $memberCardYear;
			}
			
			$extractor = new Membership_Custom_Export_ExtractorSoMember();
			$extractor->setIndexMode(Membership_Custom_Export_ExtractorSoMember::INDEX_MODE_CONTACT_ID);
			$db = Tinebase_Core::getDb();
			$tm = Tinebase_TransactionManager::getInstance();
			$tId = $tm->startTransaction($db);
			$templateId = null;
			
			foreach($memberIds as $memberId){
			
				$membership = $this->getSoMember($memberId);

				if($letterType == self::LETTER_TYPE_MEMBERCARD && (!Membership_Custom_SoMember::canPrintMemberCard($membership))){
					continue;
				}
				
				$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
				$contacts[$contact->getId()] = $contact->getId();
				
				$extractor->addMemberData($membership, $additionalData);
				
				$membershipKind = $membership->getForeignRecord('membership_type', Membership_Controller_MembershipKind::getInstance());
				
				switch((int)$letterType){
					
					case self::LETTER_TYPE_BEGIN:
						if(is_null($templateId)){
							$templateId = $membershipKind->getForeignId('begin_letter_template_id');
						}
						$membership->__set('print_reception_date',new Zend_Date());
						break;
					
					case self::LETTER_TYPE_INSURANCE:
						if(is_null($templateId)){
							$templateId = $membershipKind->getForeignId('insurance_letter_template_id');
						}
						$membership->__set('print_confirmation_date',new Zend_Date());
						break;
						
					case self::LETTER_TYPE_TERMINATION:
						if(is_null($templateId)){
							$templateId = $membershipKind->getForeignId('termination_letter_template_id');
						}
						$membership->__set('print_discharge_date',new Zend_Date());
						break;
						
					case self::LETTER_TYPE_MEMBERCARD:
						if(is_null($templateId)){
							$templateId = $membershipKind->getForeignId('membercard_letter_template_id');
						}
						if(is_null($reprintDate)){
							$membership->__set('exp_membercard_datetime',new Zend_Date());
							$membership->__set('member_card_year', $memberCardYear);
						}else{
							$membership->__set('exp_membercard_datetime',new Zend_Date($reprintDate));
						}
						break;
				}
				$this->update($membership);
			}
			if(is_null($templateId) && count($memberIds)>0){
			
				throw new Membership_Exception_NotFound('No template id found for member letters. Please add a reference to in membership kind memberkind: '.$log);
			}else{
				$printController = Addressbook_Controller_Print::getInstance();
				$printController->printLetterForContactIds(
					$templateId, 
					$contacts, 
					$extractor->getData(),
					array('Membership_Custom_Template')
				);
			}
			// commit transaction
			$tm->commitTransaction($tId);
			
			// unset: omitt action history tracks for printing members!!!
			// update should not trigger state changes etc.
			Membership_Controller_ActionHistory::getInstance()->unsetOmmitTracks();
			return $printController;
		}catch(Exception $e){
		
			$tm->rollback($tId);
			/*echo $e->__toString();
			exit;*/
			// unset: omitt action history tracks for printing members!!!
			// update should not trigger state changes etc.
			Membership_Controller_ActionHistory::getInstance()->unsetOmmitTracks();
			throw $e;
		}
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $filters
	 * @param unknown_type $sort
	 */
	public function printMembersList($filters, $sort = array('n_family','n_given','member_nr')){
		// assume parent member filter no set
		$parentMemberFilterSet = false;
		$parentMember = null;
		
		if(!is_array($filters)){
			$filters = Zend_Json::decode($filters);
		}
		$stateFilters = $filters;
		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
		$pagination = new Tinebase_Model_Pagination(
			array( 'sort' => $sort, 'dir' => 'ASC')
		);
		$membershipIds = $this->getIdsForFilter($filters,	array( 'sort' => $sort, 'dir' => 'ASC'));
		
		$aActive = array(
			'field' => 'membership_status',
			'operator' => 'equals',
			'value' => 'ACTIVE'
		);
		$activeStateFilters = $stateFilters;
		$activeStateFilters[] = $aActive;
		$activeFilters =  new Membership_Model_SoMemberFilter($activeStateFilters, 'AND');
		$countActive = Membership_Controller_SoMember::getInstance()->searchCount($activeFilters);
		$aActive = array(
			'field' => 'membership_status',
			'operator' => 'equals',
			'value' => 'PASSIVE'
		);
		
		$countAll = count($membershipIds);
		$ageTotal = 0;
		$summarize = array();
		$buffer = array();
		
		$extractor = new Membership_Custom_Export_ExtractorSoMember();
		$extractor->setCountTotal($countAll);
		
		// check whether parent member filter is set -> retrieve parent member in this case,
		// in order to provide parent member data to list
		if($filters->isFilterSet('parent_member_id') || $filters->isFilterSet('parent_member_nr')){
			$parentMemberFilterSet = true;
		}
		$hasParentMember = false;
		$hasDiffParentMember = false;
		
		foreach($membershipIds as $memberId){
        	$member = Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
			$contact = $member->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
        	$extractor->addMemberData($member);
        	
        	if(!$hasParentMember){
        		$parentMember = $member->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
        		if($parentMember){
        			$hasParentMember = true;
        		}
        	}
        	/*if($parentMemberFilterSet && is_null($parentMember)){
        		$parentMember = $member->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
        	}*/
        	Membership_Custom_SoMember::inspectPrintMember(
        		array(
        			'contact' => $contact,
        			'parentMember' => $parentMember,
        			'member' => $member
        		), 
        		$buffer,
        		$extractor->getSummarize()
        	);
        }
        
        $averageAge = $ageTotal/$countAll;
        
        $data = array(
        	'DATE' => \org\sopen\app\util\format\Date::format(strftime('%d.%m.%Y')),
        	'count_active' => $countActive,
        	'count_passive' => $countPassive,
        	'count_total' => $countAll,
        	'average_age' => $averageAge,
        	'club_name' => '',
        	'club_number' => '',
        	'POS_TABLE' => $extractor->getData(),
        	'LIST_TABLE' => $extractor->getData()
        );
        
        if($parentMember){
        	$parentContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
        	$data['club_name'] = $parentContact->__get('org_name').' '.$parentContact->__get('company2');
        	$data['club_number'] = $parentMember->__get('member_nr');
        }
        
        Membership_Custom_SoMember::addAdditionalDataPrintMember(
        	$extractor->getSummarize(),
        	$data
        );
        
        Membership_Controller_PrintMembersList::getInstance()->doPrint($data);
	}
	
	/**
	 * Create fee progress or in a second step fee invoice
	 * Determine action: 
	 *  1) create fee progress records for fee year and filter
	 *  2) create fee invoices records for fee year and filter
     * @param string $filters	Json encoded filter string -> produces array of filters
	 * @param string $feeYear	The year for which the action must be performed
	 * @param string $action	FEEPROGRESS|FEEINVOICE|FEEINVOICECURRENT (FEEINVOICE only can be produced if fee progress has been performed)
	 */
	public function batchCreateFeeInvoice($filters, $feeYear, $action, $dueDate){
		// set due date within Membership_Controller_SoMember for correct
		// calculation of age and mem_years
		// very important!!!
		Membership_Controller_SoMember::getInstance()->setDueDate($dueDate);
		
		switch($action){
			case 'FEEPROGRESS':
				set_time_limit(0);
					
				$jobManager = Membership_Api_JobManager::getInstance();
				$jobManager->updateAddJob(
					'Beitragsverläufe erzeugen',
					'Beitragsjahr: '.$feeYear
				);
				try{
					//$jobManager->startJob();
					$result = $this->createFeeProgressesByFilter($filters, $feeYear);
					$jobManager->finish();
				}catch(Exception $e){
					$jobManager->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				return $result;
				
			case 'FEEINVOICE':
				
				set_time_limit(0);
					
				$jobManager = Membership_Api_JobManager::getInstance();
				$jobManager->updateAddJob(
					'Hauptabrechnung',
					'Beitragsjahr: '.$feeYear
				);

				try{
					$result = $this->createFeeInvoicesByFilter($filters, $feeYear);
					$jobManager->finish();
					
				}catch(Exception $e){
					$jobManager->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				return $result;
				
			case 'FEEINVOICECOMPLETE':
				
				set_time_limit(0);
					
				$jobManager = Membership_Api_JobManager::getInstance();
				$jobManager->updateAddJob(
					'Hauptabrechnung inkl. Beitragsverläufe',
					'Beitragsjahr: '.$feeYear
				);

				try{
					$result = $this->createFeeProgressesByFilter($filters, $feeYear);
					$result = $this->createFeeInvoicesByFilter($filters, $feeYear);
					$jobManager->finish();
					
				}catch(Exception $e){
					$jobManager->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				return $result;
				
			case 'FEEINVOICECURRENT':
				
				set_time_limit(0);
					
				$jobManager = Membership_Api_JobManager::getInstance();
				$jobManager->updateAddJob(
					'Folgeabrechnung',
					'Beitragsjahr: '.$feeYear
				);
				try{
					
					$result = $this->createFeeInvoicesByFilter($filters, $feeYear, true);
					$jobManager->finish();
					
				}catch(Exception $e){
					$jobManager->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				return $result;
				
				
			default: throw new Membership_Exception("Unknown action $action for batch create fee invoice.");
		}
	}
	
	public function getUnbilledMemberIdsForFeeYear($feeYear){
		return $this->_backend->getUnbilledMemberIdsForFeeYear($feeYear);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $feeProgressId
	 * @param unknown_type $mode
	 */
	public function createFeeInvoiceForFeeProgress($feeProgressId, $mode){
		$fpController = Membership_Controller_SoMemberFeeProgress::getInstance();
		$feeProgress = $fpController->get($feeProgressId);
		$membership = $feeProgress->getForeignRecord('member_id',$this);
		$progressNr = $feeProgress->__get('progress_nr');
		$feeYear = $feeProgress->__get('fee_year');
		switch($mode){
			case 'progressive':
				$feeProgress = Membership_Custom_SoMember::createFeeProgress(
					$membership, 
					$feeYear,
					Membership_Custom_SoMember::CALCULATION_MODE_RECALCULATION,
					$progressNr+1
				);
				
				$result = Membership_Custom_SoMember::createFeeInvoice(
					$membership, 
					$feeYear,
					Membership_Custom_SoMember::CALCULATION_MODE_RECALCULATION,
					$progressNr,
					$progressNr+1
				);
				
				break;
		}
	}
	/**
	 * 
	 * Enter description here ...
	 * @param array $filters
	 * @param unknown_type $feeYear
	 * @throws Exception
	 */
	private function createFeeProgressesByFilter(array $filters, $feeYear){
		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
		// search memberships: result will be one record in maximum
		$membershipIds =  $this->search(
			$filters,
			new Tinebase_Model_Pagination(array('sort' => 'id', 'dir' => 'ASC')),
			false,
			true
		);
		$failCount = 0;
		$successCount = 0;
		$success = true;
		$failInfo = array();
		
		Membership_Api_JobManager::getInstance()->setTaskCount(count($membershipIds));
		
		foreach($membershipIds as $memberId){
			try{
				$membership = $this->getSoMember($memberId);
				Membership_Custom_SoMember::createFeeProgress($membership, $feeYear);
				$successCount++;
				Membership_Api_JobManager::getInstance()->countOk();
			}catch(Exception $e){
				$success = false;
				$failCount++;
				$failInfo[] = array(
					'memberId' => $membership->getId(),
					'membershipType' => $membership->__get('membership_type')
				);
				throw $e;
			}
			
			Membership_Api_JobManager::getInstance()->notifyTaskDoneOk();
		}
		return array(
			'success' => $success,
			'info' => array(
				'failCount' => $failCount,
				'successCount' => $successCount,
				'failInfo' => $failInfo
			)
		);
		
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $filters
	 * @param unknown_type $feeYear
	 * @throws Exception
	 */
	private function createFeeInvoicesByFilter($filters, $feeYear, $modeRecalculation = false){
		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');

		// search memberships: result will be one record in maximum
		
		$membershipIds =  $this->search(
			$filters,
			new Tinebase_Model_Pagination(array('sort' => 'id', 'dir' => 'ASC')),
			false,
			true
		);
		if(count($membershipIds)==0){
			throw new Exception('Filter for creating invoices returned zero members');
		}
		$failCount = 0;
		$successCount = 0;
		$success = true;
		$failInfo = array();
		
		Membership_Api_JobManager::getInstance()->setTaskCount(count($membershipIds));
		
		
		foreach($membershipIds as $memberId){
			$membership = $this->getSoMember($memberId);
			try{
				if(Membership_Custom_SoMember::createFeeInvoice($membership, $feeYear, $modeRecalculation)){
					$successCount++;
					Membership_Api_JobManager::getInstance()->notifyTaskDoneOk();
				}
			}catch(Exception $e){
				$success = false;
				$failCount++;
				$failInfo[] = array(
					'memberId' => $membership->getId(),
					'membershipType' => $membership->__get('membership_type')
				);
				Membership_Api_JobManager::getInstance()->notifyTaskDoneError();
				throw $e;
			}			
		}
		return array(
			'success' => $success,
			'info' => array(
				'failCount' => $failCount,
				'successCount' => $successCount,
				'failInfo' => $failInfo
			)
		);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $memberId
	 * @param unknown_type $feeProgressId
	 * @param unknown_type $invoiceId
	 */
	public function reverseFeeInvoice($memberId, $feeProgressId, $invoiceId){
		$creditReceipt = Billing_Controller_Order::getInstance()->reverseInvoice($invoiceId);
		$creditNr = $creditReceipt->__get('credit_nr');
		$invoiceNr = $creditReceipt->__get('invoice_nr');
		$member = $this->getSoMember($memberId);
		Membership_Controller_ActionHistory::getInstance()->logAction(
			Membership_Controller_Action::BILLMEMBERREVERT, 
			$member,
			array(
				'fee_progress_id' => $feeProgressId,
				'receipt_id' => $creditReceipt->getId(),
				'order_id' => $creditReceipt->getForeignId('order_id'),
				'action_text' => '#'. $member->__get('member_nr') . ' Re.Nr '. $invoiceNr . ' -> Gutschrift.Nr: '. $creditNr
			)
		);
		return $creditReceipt->toArray();
	}
	
	private function createFeeProgressForMember($membership, $feeDefinition){
		
	}
	
	/**
	 * 
	 * Create fee invoice based on a given membership and a fee definition
	 * @param Membership_Model_SoMember $membership
	 * @param Membership_Model_FeeDefinition $feeDefinition
	 */
	private function createFeeInvoiceForMember($membership, $feeDefinition){
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $parentMemberId
	 */
	public function exportDTACurrent($parentMemberId){
	 	if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::exportDTACurrent($parentMemberId);
    	}
	}
	
	/**
	 * 
	 * 
	 * @param Membership_Model_Job $job
	 */
	public function printJob(Membership_Model_Job $job){
		//$parentJob = $job->getParentJob();
		$parentJobId = $job->__get('job_id');
		$data = $job->getData();
		$type = $data['printType'];
		
		switch($type){
			case 'INVOICE':
				try{
					set_time_limit(0);
					$prController = Billing_Controller_Print::getInstance();
					$pagination = new Tinebase_Model_Pagination();
					$filters = array();
					$filters[] = array(
	    				'field' => 'job_id',
	    				'operator' => 'equals',
	    				'value' => $parentJobId
		    		);
		    		$filters[] = array(
	    				'field' => 'action_id',
	    				'operator' => 'equals',
	    				'value' => 'BILLMEMBER'
		    		);
					$filter = new Membership_Model_ActionHistoryFilter($filters, 'AND');
					$actionHistoryIds = Membership_Controller_ActionHistory::getInstance()->search(
						$filter,
						$pagination,
						false,
						true
					);
	
					$receiptIds = array();
					foreach($actionHistoryIds as $actionHistoryId){
						$actionHistory = Membership_Controller_ActionHistory::getInstance()->get($actionHistoryId);
						// there might be error actions take only done actions
						// skip these open or error actions ->
						if($actionHistory->isDone()){
							$memberId = $actionHistory->getForeignId('member_id');
							$receiptId = $actionHistory->getForeignId('receipt_id');
							$member = $this->getSoMember($memberId);
							if(Membership_Custom_SoMember::isReceiptToPrint($member)){
								$receiptIds[$memberId] = $receiptId;
							}
						}
					}
					
					unset($actionHistoryIds);
					
					// add sorting: dirty hack @todo: include sorting in job creation dialog
					// done for NRW-Stiftung!!
					/*$parentJob = Membership_Controller_Job::getInstance()->get($parentJobId);
					$parentJobData = $parentJob->getData();
					$parentFilters = $parentJobData['filters'];
					$parentFilter = new Membership_Model_SoMemberFilter($parentFilters,'AND');
					$sort = array(
						'sort' => array( 'adr_one_postalcode', 'member_nr'),
						'dir' => 'ASC'
					);
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ .  ' parent filters' . print_r($parentFilters,true));
			
					$memberIds = $this->getIdsForFilter($parentFilter, $sort);
					$resultReceiptIds = array();
					$usedMemberIds = array();
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ .  ' member ids'  . print_r($memberIds,true));
			
					foreach($memberIds as $memberId){
						if(!array_key_exists($memberId, $receiptIds)){
							continue;
							//throw new Exception("Member with id: $memberId not within receipts of action history");
						}
						$resultReceiptIds[] = $receiptIds[$memberId];
						$usedMemberIds[] = $memberId;
					}
					unset($receiptIds);
					unset($memberIds);
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ .  ' result receipt ids'  . print_r($resultReceiptIds,true));
			*/
					/*
					 * hack
					 * do address label export
					 */
					
					/*
			$export = new Membership_Export_AdressLabelsMemberCardCsv();
			$aFilter = array(
				array(
					'field' =>'id',
					'operator' => 'in',
					'value' => $usedMemberIds
				)
			);
			
			$outFile = $export->generate($aFilter);
			
			$contentType = $export->getDownloadContentType();
			
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: export finished Membership_Controller_Export(outfile: '.$outFile.')');
			if($export->hasErrors()){
				Membership_Api_JobManager::getInstance()->jobAddData('exportErrorFileName',$export->getErrorFileName());
			}
			Membership_Api_JobManager::getInstance()->jobAddData('exportFileName',$outFile);
			Membership_Api_JobManager::getInstance()->jobAddData('exportFileContentType',$contentType);
			Membership_Api_JobManager::getInstance()->jobAddData('downloadFileName',$job->__get('job_name1').'-'.strftime('%d%m%Y-%H%S%M').'.csv');
			
			
			return true;*/
					//  <---
					
					$prController->printReceipts($receiptIds, false);
					
					$printJobStorage = $prController->getPrintJobStorage();
					$data['printJobStorageId'] = $printJobStorage->getId();
					/*$addressLabelData = array(
						'model' => 'Membership_Model_SoMember',
						'ids' => $memberIds
					);*/
					
					Membership_Api_JobManager::getInstance()->jobAddData('printJobStorageId',$printJobStorage->getId());
					//Membership_Api_JobManager::getInstance()->jobAddData('addressLabelData',$addressLabelData);
					
					Membership_Api_JobManager::getInstance()->finish();
				
				}catch(Exception $e){
					Membership_Api_JobManager::getInstance()->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				break;
				
			case 'MULTILETTER':
				try{
					set_time_limit(0);
					$contacts = array();
					$members = array();
					$filters = $data['filters'];
					$sort = $data['sort'];
					$dir = $data['dir'];
					$templateId = $data['templateId'];
					$data = $data['data'];
					
					if(!is_array($filters)){
						$filters = Zend_Json::decode($filters);
					}
					$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
					
					if(!is_array($data)){
						$data = Zend_Json::decode($data);
					}
					
					$membershipIds = $this->getIdsForFilter($filters,	array( 'sort' => $sort, 'dir' => $dir));
					$extractor = new Membership_Custom_Export_ExtractorSoMember();
					$extractor->setIndexMode(Membership_Custom_Export_ExtractorSoMember::INDEX_MODE_CONTACT_ID);
					
					foreach($membershipIds as $memberId){
						$membership = $this->getSoMember($memberId);
						$contact = $membership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
						$contacts[$contact->getId()] = $contact;
						
						$extractor->addMemberData($membership);
					}
					if(!$templateId){
						throw new Membership_Exception_NotFound('No template id found for member letters. Please add a reference to in membership kind');
					}
					$printController = Addressbook_Controller_Print::getInstance();
					
					if(count($data) == 0){
						$printController->printLetterForContacts($templateId, $contacts, $extractor->getData());
					}else{
						$printController->printEditableLetterForContacts($templateId, $contacts, $extractor->getData(), $data);
					}
					
					$printJobStorage = $printController->getPrintJobStorage();
					$data['printJobStorageId'] = $printJobStorage->getId();
					Membership_Api_JobManager::getInstance()->jobAddData('printJobStorageId',$printJobStorage->getId());
					Membership_Api_JobManager::getInstance()->jobAddData('storagePath','//out/result/Multi/Letter/pdf/final');
					Membership_Api_JobManager::getInstance()->finish();
				}catch(Exception $e){
					Membership_Api_JobManager::getInstance()->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				break;
				
				
			case 'DUEMEMBERLETTERS':
				try{
					set_time_limit(0);
					ini_set('memory_limit','4096M');
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' data DUE: '. print_r($data,true));
					
					$filters = $data['filters'];
					
					$sort = $data['sort'];
					$dir = $data['dir'];
					
					$data = $data['data'];
					$letterType = $data['letterType'];
					if(!is_array($sort) && !is_object($sort)){
						$sort = Zend_Json::decode($sort);
					}
					
					$reprintDate = null;
					if(array_key_exists('reprintDate', $data)){
						if($data['reprintDate'] != ''){
							$reprintDate = new Zend_Date($data['reprintDate']);
						}
					}
					
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' sort DUE: '. print_r($sort,true));
					
					if(!is_array($filters)){
						$filters = Zend_Json::decode($filters);
					}
					//$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
					/*if(!is_array($data)){
						$data = Zend_Json::decode($data);
					}*/
					
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' FILTERS DUE: '. print_r($filters,true));
			
					
					$printController = $this->printDueMemberLetters($letterType, $reprintDate, $filters, null, $data);
					
					
					$printJobStorage = $printController->getPrintJobStorage();
					$data['printJobStorageId'] = $printJobStorage->getId();
					Membership_Api_JobManager::getInstance()->jobAddData('printJobStorageId',$printJobStorage->getId());
					Membership_Api_JobManager::getInstance()->jobAddData('storagePath','//out/result/Multi/Letter/pdf/final');
					Membership_Api_JobManager::getInstance()->finish();
				}catch(Exception $e){
					Membership_Api_JobManager::getInstance()->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				break;
				
			case 'VERIFICATION':
				try{
					set_time_limit(0);
					$prController = Membership_Controller_PrintJobVerificationList::getInstance();
					$pagination = new Tinebase_Model_Pagination();
					$filters = array();
					$filters[] = array(
	    				'field' => 'job_id',
	    				'operator' => 'equals',
	    				'value' => $parentJobId
		    		);
		    		$filters[] = array(
	    				'field' => 'action_id',
	    				'operator' => 'equals',
	    				'value' => 'BILLPARENTMEMBER'
		    		);
					$filter = new Membership_Model_ActionHistoryFilter($filters, 'AND');
					$actionHistoryIds = Membership_Controller_ActionHistory::getInstance()->search(
						$filter,
						$pagination,
						false,
						true
					);
					
					$processArray = array();
					$assocIds = array();
					$parentMemberIds = array();
					$memberIds = array();
					foreach($actionHistoryIds as $actionHistoryId){
						$actionHistory = Membership_Controller_ActionHistory::getInstance()->get($actionHistoryId);
						$memberId = $actionHistory->getForeignId('member_id');
						$memberIds[] = $memberId;
						$assoc = $actionHistory->getForeignId('association_id');
						$parentMemberId = $actionHistory->getForeignId('parent_member_id');
						if(!array_key_exists($assoc, $processArray)){
							$processArray[$assoc] = array();
						}
						if(!array_key_exists($parentMemberId, $processArray[$assoc])){
							$processArray[$assoc][$parentMemberId] = array();
						}
						$processArray[$assoc][$parentMemberId][] = $memberId;
						$assocIds[] = $assoc;
						$parentMemberIds[] = $parentMemberId;
					}
					
					if(Tinebase_Core::isLogLevel(Zend_Log::DEBUG)){
						Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Got member count form action history: '.count($memberIds));
					}
					$assocIds = array_unique($assocIds);
					$parentMemberIds = array_unique($parentMemberIds);
					$prController->setJobId($job->__get('job_id'));
					$prController->printVerificationList($processArray, $assocIds, $parentMemberIds);
					
					$printJobStorage = $prController->getPrintJobStorage();
					$data['printJobStorageId'] = $printJobStorage->getId();
					Membership_Api_JobManager::getInstance()->jobAddData('printJobStorageId',$printJobStorage->getId());
					Membership_Api_JobManager::getInstance()->finish();
				}catch(Exception $e){
					Membership_Api_JobManager::getInstance()->finishError($e->getMessage(). ' line: ' . $e->getLine(). ' ' . $e->getFile() . ' ' . $e->getTraceAsString() );
				}
				break;
				
		}
	}
	
	/**
	 * execute due tasks with predefined action
	 * 
	 * @param Membership_Model_Job $job
	 */
	public function execDueTasks(Membership_Model_Job $job){
		
		set_time_limit(0);
		
		$db = Tinebase_Core::getDb();
		$tm = Tinebase_TransactionManager::getInstance();

		$tId = $tm->startTransaction($db);
		
			try{
			$data = $job->getData();
			$action = $data['action'];
			$validDate = $data['validDate'];
			
			$objValidDate = new Zend_Date($validDate);
					
			$pagination = new Tinebase_Model_Pagination();
			$filters = array();
			$filters[] = array(
				'field' => 'action_state',
	    		'operator' => 'equals',
	    		'value' => 'OPEN'
		    );
		    $filters[] = array(
	    		'field' => 'action_id',
	    		'operator' => 'equals',
	    		'value' => $action
		    );
		    $filters[] = array(
	    		'field' => 'valid_datetime',
	    		'operator' => 'beforeOrAt',
	    		'value' => $objValidDate->toString('yyyy-MM-dd').' 00:00:00'
		    );
		    //$filters[] = array('field' => 'member_id', 'operator'=> 'AND', 'value' => array(array('field'=>'id','operator'=>'equals','value'=>'000059e66c5ac6bc63bba5b1f01befc12e88d89c')));
		    
		     
		    
		    // test for member: 000059e66c5ac6bc63bba5b1f01befc12e88d89c
		    
		    
			$filter = new Membership_Model_ActionHistoryFilter($filters, 'AND');
			
			// set ommit tracks on action history controller, as the following actions may not be tracked
			// automatically
			$actionHistoryIds = Membership_Controller_ActionHistory::getInstance()->setOmmitTracks()
				->search(
					$filter,
					$pagination,
					null,
					true
			);
			
			Membership_Api_JobManager::getInstance()->setTaskCount(count($actionHistoryIds));
			
			foreach($actionHistoryIds as $actionHistoryId){
				
				$this->performDataChange($actionHistoryId, $action, $job);
				
			}
			
			$tm->commitTransaction($tId);

			

		}catch(Exception $e){
			$tm->rollback($tId);
			Membership_Api_JobManager::getInstance()->finishError($e->__toString());
			Membership_Controller_ActionHistory::getInstance()->unsetOmmitTracks();
			exit;
		}
		// do not forget to unset ommit tracks!!
		Membership_Controller_ActionHistory::getInstance()->unsetOmmitTracks();

		Membership_Api_JobManager::getInstance()->finish();
		
	}
	
	public function performDataChange($actionHistoryId, $action, $job = null){
		try{
			$db = Tinebase_Core::getDb();
			$tm = Tinebase_TransactionManager::getInstance();
			
			// do not track changes made here... as the actions would be multiplied
			Membership_Controller_ActionHistory::getInstance()->setOmmitTracks();
			
			$tIdInner = $tm->startTransaction($db);
						
			$actionHistory = Membership_Controller_ActionHistory::getInstance()->get($actionHistoryId);
			$member = $actionHistory->getForeignRecord('member_id', Membership_Controller_SoMember::getInstance());
			$memberId = $member->getId();
			
			// get the last valid membership data of history which is no phantom
			$currentData = Membership_Controller_MembershipData::getInstance()->getLastForMember($memberId);
			$currentDataId = $currentData->getId();
			
			// get the previous dataset -> maybe equal to current data if current data is the initial set
			$previousData = $currentData->getPrevious();
			
			// open action history items do not have old data referenced... as this would make no sense
			// because there can be changes between definition and execution
			// -> not necessary (hopefully): $oldData = $actionHistory->getOldData();
			
			$newData = $actionHistory->getNewData();
			if(!$newData){
				continue;
			}
			
			if( $newData->getId()<$currentDataId){
				$newDataRecreate = clone $newData;
				$newDataRecreate->__set('id',null);
				//$newDataRecreateId = $currentDataId + 1;
				//$newDataRecreate->__set('id',$newDataRecreateId);
				$newDataRecreate = Membership_Controller_MembershipData::getInstance()->create($newDataRecreate);
			}else{
				$newDataRecreate = $newData;
			}
			$actionHistory->__set('data_id', $newDataRecreate->getId());
			$actionHistory->__set('old_data_id', $currentDataId);
			$actionHistory->__set('action_state','DONE');
			$actionHistory->__set('process_datetime',new Zend_Date());
			$actionHistory->__set('processed_by_user', Tinebase_Core::get(Tinebase_Core::USER)->getId());
			if(!$job && Membership_Api_JobManager::getInstance()->hasJob()){
				$job = Membership_Api_JobManager::getInstance()->getJob();
			}
			if($job){
				$actionHistory->__set('job_id', $job->getId());
			}
			switch($action){
				case 'TERMINATION':
					$member->__set('membership_status', 'TERMINATED');
					break;
					
				case 'FEEGROUPCHANGE':
					$member->__set('fee_group_id', $newData->__get('fee_group_id'));
					break;
					
				case 'PARENTCHANGE':
					$member->__set('parent_member_id', $newData->__get('parent_member_id'));
					break;
				
				case 'MEMSTATECHANGE':
					$member->__set('membership_status', $newData->__get('membership_status'));
					break;
				default:
					throw new Membership_Exception("Unknown action type '$action' for change reqeust");
			}
			
			// ommit change tracks in ActionHistory controller
			// and update the membership
	
			$this->update($member);
			//Membership_Controller_MembershipData::getInstance()->delete($newData->getId());
			
			Membership_Controller_ActionHistory::getInstance()->update($actionHistory);
			
			$newDataRecreate->__set('valid_state', 'DONE');
			Membership_Controller_MembershipData::getInstance()->update($newDataRecreate);
			
			Membership_Api_JobManager::getInstance()->notifyTaskDoneOk();
			
			$tm->commitTransaction($tIdInner);
			
		}catch(Exception $e){
			
			$tm->rollback($tIdInner);
			
			$actionHistory = Membership_Controller_ActionHistory::getInstance()->get($actionHistory->getId());
			
			$actionHistory->__set('action_state','OPEN');
			$validActions = array(
				Membership_Controller_Action::FEEGROUPCHANGE => ' Beitragsruppenwechsel ',
				Membership_Controller_Action::TERMINATION => ' Austritt ',
				Membership_Controller_Action::PARENTCHANGE => ' Vereinswechsel '
			);
			//$actionHistory->__set('action_state','ERROR');
			$errorInfo = array(
				'message' => 'Fehler für Mitglied-Nr ' . $member->__get('member_nr') . ' Datum: '. $validDate . $validActions[$action],
				'debug' => $e->__toString()
								
			);
			
			$actionHistory->__set('error_info', $log);
			$actionHistory->__set('job_id', $job->getId());
			$actionHistory->flatten();
			
			Membership_Controller_ActionHistory::getInstance()->update($actionHistory);
			/*ob_start();
			$a = $actionHistory->toArray();
			print_r($a);
			$log = ob_get_clean();
			try{
				Membership_Controller_ActionHistory::getInstance()->update($actionHistory);
			}catch(Exception $e){
				throw new Exception('ERROR: '. $log);
			}
			*/
			
			Membership_Api_JobManager::getInstance()->notifyTaskDoneError();
			
			Membership_Controller_ActionHistory::getInstance()->unsetOmmitTracks();
			//throw $e;
			
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $printJobId
	 */
	public function getPrintJobResult($printJobId){
		ini_set('display_errors','on');
		try{
			$printJob = Membership_Controller_Job::getInstance()->get($printJobId);
			$data = $printJob->getData();
			$storageId = $data['printJobStorageId'];
			//createFromFileSystem($id, $basePath)
			$storageConf = \Tinebase_Config::getInstance()->getConfig('printjobs', NULL, TRUE)->value;
			$basePath = $storageConf['storagepath'];
			$printJobStorage =  \org\sopen\app\api\filesystem\storage\FileProcessStorage::createFromFileSystem(
				'',
				$basePath,
				$storageId
			);
			$printJobStorage->addProcessLines(array('in','convert','out'));
				
			header("Pragma: public");
	        header("Cache-Control: max-age=0");
	        header('Content-Disposition: attachment; filename=print.pdf');
	        header("Content-Description: Pdf Datei");  
	       // readfile($outFile);
			header('Content-Type: application/pdf');
			
			$path = "//out/result/merge/pdf/final";
			if(array_key_exists('storagePath', $data)){
				$path = $data['storagePath'];
			}

			echo $printJobStorage->getFileContent($path);
		}catch(Exception $e){
			echo $e->__toString();
		}
	}
	
	public function getPrintJobSinglePage($printJobId, $memberId){
		
	}
	

   

 /**
     * (non-PHPdoc)
     * @see Tinebase_Controller_Record_Abstract::create()
     */
    public function create(Tinebase_Record_Interface $_record){
    	
    	if($_record->__get('iban') && is_null($_record->getForeignIdBreakNull('bank_account_id'))){
	    	$bankAccount = Billing_Api_BankAccount::createForContactAndIBAN(
				$_record->getForeignId('contact_id'),
				$_record->__get('iban'),
				$_record->__get('bank_account_name'),
				$_record->getForeignIdBreakNull('bank_account_id')
			);
			$_record->__set('bank_account_id', $bankAccount->getId());
    	}
    	
    	parent::create($_record);
    	
    	if(!$_record->__get('fee_payment_method')){
			$_record->__set('fee_payment_method', Billing_Controller_PaymentMethod::getInstance()->getDefaultPaymentMethod()->getId());
		}
    	
		Membership_Controller_ActionHistory::getInstance()->logCreateAction($_record);
    	
    	if($_record->__get('parent_member_id')){
    		$contact = $_record->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
	    	Membership_Controller_ActionHistory::getInstance()->logAction(
				Membership_Controller_Action::PARENTENTER, 
				$_record->getForeignRecord('parent_member_id', $this),
				array(
					'child_member_id' => $_record->getId(),
					'action_text' => '#'. $_record->__get('member_nr') . ' '. $contact->__get('n_fileas')
				)
			);
    	}
    	
    	try{
    		// check for Donator application
	    	Tinebase_Application::getInstance()->getApplicationByName('Donator');
	    	// create fundmaster (because of integration in membership edit panel)
	    	Donator_Controller_FundMaster::getInstance()->getByContactOrCreate($_record->getForeignId('contact_id'));
	    	
    	}catch(Tinebase_Exception_NotFound $e){
    		// silently fail: Donator is uninstalled, so fundmaster can't be created.
    	}
    	
    	$bankAccount = $_record->getForeignRecordBreakNull('bank_account_id', Billing_Controller_BankAccount::getInstance());
    	if($bankAccount){
	    	
			$usage = $bankAccount->addUsageMembership($_record);
	    	$paymentMethod = $_record->__get('fee_payment_method');
	    	
	    	if($paymentMethod == 'DEBIT' || $paymentMethod == 'DEBIT_GM'){
				Billing_Controller_SepaMandate::getInstance()->generateSepaMandateForBankAccountUsage($usage);
			}
    	}
    	self::customAfterCreate($_record);
    	return $this->getSoMember($_record->getId());
    }
    
    /**
     * (non-PHPdoc)
     * @see Tinebase_Controller_Record_Abstract::update()
     */
    public function update(Tinebase_Record_Interface $_record){
    	$_record->flatten();
    	parent::update($_record);
    	
		
    	self::customAfterUpdate($_record);
    /*	foreach($this->afterUpdateMap as $afterUpdateAction){
    		$controller = $afterUpdateAction['controller'];
    		$dirtyRecord = $afterUpdateAction['record'];
    		$controller->updateRaw($dirtyRecord);
    	}*/
    	return $this->getSoMember($_record->getId());
    }
    
    /**
     * inspect update of one record
     * 
     * @param   Tinebase_Record_Interface $_record      the update record
     * @param   Tinebase_Record_Interface $_oldRecord   the current persistent record
     * @return  void
     * 
     * @todo    check if address changes before setting new geodata
     */
    protected function _inspectUpdate($_record, $_oldRecord)
    {
    	try{
    		$contact = $_record->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
    				
    		if(!$_oldRecord->__get('birth_date')){
	    		if(!$_record->__get('birth_date')){
					if($contact->__get('bday')){
						$mBirthDate = new Zend_Date($contact->__get('bday'));
						$_record->__set('birth_date', $contact->__get('bday'));
					}else{
						$mBirthDate = new Zend_Date($_record->__get('birth_date'));
					}
				}else{
					$mBirthDate = new Zend_Date($_record->__get('birth_date'));
					/*$bday = new Zend_Date($contact->__get('bday'));
					if($mBirthDate->compareTimestamp($bday)!==0){
						$contact->__set('bday', $mBirthDate);
						Addressbook_Controller_Contact::getInstance()->update($contact);
					}*/
				}
				$birthYear = $mBirthDate->get(Zend_Date::YEAR);
				$birthMonth = $mBirthDate->get(Zend_Date::MONTH);
				$birthDay = $mBirthDate->get(Zend_Date::DAY);
				$_record->__set('birth_day',$birthDay);
				$_record->__set('birth_month',$birthMonth);
				$_record->__set('birth_year',$birthYear);
    		}else{
    			if(!$_record->__get('birth_date')){
    				$_record->__set('birth_day',0);
					$_record->__set('birth_month',0);
					$_record->__set('birth_year',0);
    			}else{
    				$mBirthDate = new Zend_Date($_record->__get('birth_date'));
    				$birthYear = $mBirthDate->get(Zend_Date::YEAR);
					$birthMonth = $mBirthDate->get(Zend_Date::MONTH);
					$birthDay = $mBirthDate->get(Zend_Date::DAY);
					$_record->__set('birth_day',$birthDay);
					$_record->__set('birth_month',$birthMonth);
					$_record->__set('birth_year',$birthYear);
    			}
    		}
    		
    		$mBirthDate = new Zend_Date($_record->__get('birth_date'));
    		$cBirthDate = new Zend_Date($contact->__get('bday'));
    		
    		if(!$mBirthDate->equals($cBirthDate)){
    			$contact->__set('bday', $mBirthDate);
    			Addressbook_Controller_Contact::getInstance()->update($contact);
    		}
    		
			
		}catch(Exception $e){
			//echo $e->__toString();
			//
			//throw new Exception('Birthdate could not be retrieved from contact');
		}
   	 // set entry_year
		try{
			if($_record->__get('begin_datetime')){
				$mBeginDate = new Zend_Date($_record->__get('begin_datetime'));
				$beginYear = $mBeginDate->get(Zend_Date::YEAR);
				$_record->__set('entry_year',$beginYear);
			}
			
		}catch(Exception $e){
			//
			throw new Exception('Birthdate could not be retrieved from contact: '.$e->getMessage());
		}
    	
    	if(($_record->__get('iban') != $_oldRecord->__get('iban')) || ($_record->__get('bank_account_id') != $_oldRecord->__get('bank_account_id'))){
	    	$bankAccount = Billing_Api_BankAccount::createForContactAndIBAN(
				$_record->getForeignId('contact_id'),
				$_record->__get('iban'),
				$_record->__get('bank_account_name'),
				$_record->getForeignIdBreakNull('bank_account_id')
			);
			
			$_record->__set('bank_account_id', $bankAccount->getId());
    		$usage = $bankAccount->addUsageMembership($_record);
			
			$paymentMethod = $_record->__get('fee_payment_method');
	    	if($paymentMethod == 'DEBIT' || $paymentMethod == 'DEBIT_GM'){
				Billing_Controller_SepaMandate::getInstance()->generateSepaMandateForBankAccountUsage($usage);
			}
			
			if($_record->__get('sepa_signature_date') && !$_oldRecord->__get('sepa_signature_date')){
				$sepaMandate = $usage->getForeignRecordBreakNull('sepa_mandate_id', Billing_Controller_SepaMandate::getInstance());
				if($sepaMandate){
					$sepaMandate->__set('signature_date', new Zend_Date($_record->__get('sepa_signature_date')));
					$sepaMandate->__set('mandate_state', 'CONFIRMED');
					Billing_Controller_SepaMandate::getInstance()->update($sepaMandate);
				}
			}elseif(!$_record->__get('sepa_signature_date')  && $_oldRecord->__get('sepa_signature_date') ){
				$sepaMandate = $usage->getForeignRecordBreakNull('sepa_mandate_id', Billing_Controller_SepaMandate::getInstance());
				if($sepaMandate){
					$sepaMandate->__set('signature_date', null);
					$sepaMandate->__set('mandate_state', 'GENERATED');
					Billing_Controller_SepaMandate::getInstance()->update($sepaMandate);
				}
			}
			
		}
		
    	//}
		Membership_Controller_ActionHistory::getInstance()->trackDataChanges($_record, $_oldRecord);
    	
    	
    	self::customInspectUpdate($_record);
    }
    
    /**
     * 
     * try to load membership by contact and update member record if found
     * @param Addressbook_Model_Contact $contactRecord
     */
    public function tentativeUpdateFromContact(Addressbook_Model_Contact $contactRecord){
    	$members = $this->getByContactId($contactRecord->getId());
    	foreach($members as $member){
    		$dirty = false;
    		if($member->__get('birth_date')!= $contactRecord->__get('bday')){
    			$member->__set('birth_date', new Zend_Date($contactRecord->__get('bday')));
    			$dirty = true;
    		}
    		if($member->__get('sex')!= $contactRecord->__get('sex')){
    			$member->__set('sex', $contactRecord->getLetterDrawee()->getSexCode());
    			$dirty = true;
    		}
    		if($dirty){
    			// suppress update inspectors, in order to avoid recursive cyclic
    			// update from contact to member
    			$this->updateRaw($member);
    		}
    	}
    }
    
	/**
	 * (non-PHPdoc)
	 * @see release/sopen 1.1/main/app/core/vendor/tine/v/2/base/Tinebase/Controller/Record/Tinebase_Controller_Record_Abstract::_inspectCreate()
	 */
	protected function _inspectCreate(Tinebase_Record_Interface $_record)
	{
		// create fundmaster record if not already exists
		$fm = Donator_Controller_FundMaster::getInstance()->getByContactOrCreate($_record->getForeignId('contact_id'));
		
		try{
	
			$contact = $_record->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
			if(!$_record->__get('birth_date')){
				if($contact->__get('bday')){
					$mBirthDate = new Zend_Date($contact->__get('bday'));
					$_record->__set('birth_date', $contact->__get('bday'));
				}else{
					$mBirthDate = new Zend_Date($_record->__get('birth_date'));
				}
			}else{
				$mBirthDate = new Zend_Date($_record->__get('birth_date'));
				/*$bday = new Zend_Date($contact->__get('bday'));
				if($mBirthDate->compareTimestamp($bday)!==0){
					$contact->__set('bday', $mBirthDate);
					Addressbook_Controller_Contact::getInstance()->update($contact);
				}*/
			}
			$birthYear = $mBirthDate->get(Zend_Date::YEAR);
			$birthMonth = $mBirthDate->get(Zend_Date::MONTH);
			$birthDay = $mBirthDate->get(Zend_Date::DAY);
			$_record->__set('birth_day',$birthDay);
			$_record->__set('birth_month',$birthMonth);
			$_record->__set('birth_year',$birthYear);
			/*if($_record->__get('sex') != $contact->__get('sex')){
				$contact->__set('sex', $_record->__get('sex'));
			}
			$memKind = $_record->getForeignRecord('membership_type', Membership_Controller_MembershipKind::getInstance());
			$contact->__set('container_id', $memKind->__get('addressbook_id'));
			Addressbook_Controller_Contact::getInstance()->update($contact);*/
			$_record->__set('sex', $contact->getLetterDrawee()->getSexCode());
		}catch(Exception $e){
			//
			throw new Exception('Birthdate could not be retrieved from contact '. $e->getTraceAsString());
		}
		// set entry_year
		try{
			if($_record->__get('begin_datetime')){
				$mBeginDate = new Zend_Date($_record->__get('begin_datetime'));
				$beginYear = $mBeginDate->get(Zend_Date::YEAR);
				$_record->__set('entry_year',$beginYear);
			}
			
		}catch(Exception $e){
			//
			throw new Exception('Birthdate could not be retrieved from contact');
		}
		
		self::customInspectCreate($_record);
		
	}
	
	public function onSetAccountBankTransferDetected($objEvent){
	if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::onSetAccountBankTransferDetected($objEvent);
    	}else{
    		echo 'Membership_Custom_SoMember does not exist';
    	}
	}
	
    /**
     * 
     * Call customized inspect create if existent
     * @param Tinebase_Model_SoMember	$_record
     */
    protected static function customInspectCreate(Membership_Model_SoMember $_record){
    	if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::inspectCreate($_record);
    	}else{
    		echo 'Membership_Custom_SoMember does not exist';
    	}
    }
    /**
     * 
     * Call customized after create if existent
     * @param Tinebase_Model_SoMember	$_record
     */
    protected static function customAfterCreate(Membership_Model_SoMember $_record){
    	if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::afterCreate($_record);
    	}
    }
    /**
     * 
     * Call customized inspect update if existent
     * @param Tinebase_Model_SoMember	$_record
     */
    protected static function customInspectUpdate(Membership_Model_SoMember $_record){
        if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::inspectUpdate($_record);
    	}
    }
    /**
     * 
     * Call customized after update if existent
     * @param Tinebase_Model_SoMember	$_record
     */
    protected static function customAfterUpdate(Membership_Model_SoMember $_record){
        if(class_exists('Membership_Custom_SoMember')){
    		Membership_Custom_SoMember::afterUpdate($_record);
    	}
    }
}
?>