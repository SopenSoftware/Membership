<?php
class Membership_Controller_ActionHistory extends Tinebase_Controller_Record_Abstract
{
	const MEM_CHANGE_REQUEST_FEE_GROUP 		= 'FeeGroup';
	const MEM_CHANGE_REQUEST_PARENT_MEMBER 	= 'ParentMember';
	const MEM_CHANGE_REQUEST_STATE 			= 'State';
	const MEM_CHANGE_REQUEST_TERMINATION 	= 'Termination';
	
	
	/**
	 * config of courses
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;
	
	protected $bunchCreate = false;
	
	protected $collection = null;
	
	protected $ommitTracks = false;

	/**
	 * the constructor
	 *
	 * don't use the constructor. use the singleton
	 */
	private function __construct() {
		$this->_applicationName = 'Membership';
		$this->_backend = new Membership_Backend_ActionHistory();
		$this->_modelName = 'Membership_Model_ActionHistory';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->somembers : new Zend_Config(array());
		
		$this->initCollection();
	}

	private static $_instance = NULL;

	/**
	 * the singleton pattern
	 *
	 * @return SoMembership_Controller_SoEvent
	 */
	public static function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	public function setOmmitTracks(){
		$this->ommitTracks = true;
		return $this;
	}
	
	public function unsetOmmitTracks(){
		$this->ommitTracks = false;
		return $this;
	}

	public function hasOpenForMemberAndAction($memberId, $action, $validDate = null){
		$props = array(
			'member_id' => $memberId,
			'action_id' => $action
		);
		try{
			return count($this->_backend->getByPropertySet($props)>0);
			
		}catch(Exception $e){
			return false;
		}
	}
	
	public function initCollection(){
		$this->collection = new Tinebase_Record_RecordSet('Membership_Model_ActionHistory');
	}
	
	public function getEmptyActionHistory(){
		$emptyOrder = new Membership_Model_ActionHistory(null,true);
		return $emptyOrder;
	}
	/**
	 * 
	 * Get action history entries for given member id
	 * @param string (mandatory) 	$memberId
	 * @param string (optional) 	$parentMemberId
	 */
	public function getByMemberId($memberId, $parentMemberId = null){
		$aProp = array(
			'member_id' => $memberId
		);
		if(!is_null($parentMemberId)){
			$aProp['parent_member_id'] = $parentMemberId;
		}
		return $this->_backend->getByPropertySet($aProp, false, false);
	}
	
	public function setBunchCreate($bunchCreate){
		$this->bunchCreate = $bunchCreate;
	}
	
	public function isBunchCreate(){
		return $this->bunchCreate;
	}
	
	public function add(Membership_Model_ActionHistory $record){
		$this->collection[] = $record;
		if(count($this->collection)>100){
			$this->createRecords();
		}
	}
	
	public function cleanup(){
		$this->initCollection();
	}
	
	public function createRecords(){
		if(count($this->collection)==0){
			return null;
		}
		$result = $this->_backend->createPrepared($this->collection);
		$this->cleanup();
		return $result;
	}
	
	public function getByJobAndActionId($jobId, $actionId, $singleResult=true){
		return $this->_backend->getByPropertyset(
			array(
				'job_id' => $jobId,
				'action_id' => $actionId
			),
			false,
			$singleResult // strategy first
		);
	}
	
	public function getDiffForResultSet($resultSetActionHistory){
		$diffs = array();
		foreach($resultSetActionHistory as $actionHistory){
			$memDataOld = $actionHistory->getOldData();
			$memDataNew = $actionHistory->getNewData();
			if(!$memDataNew || !$memDataOld){
				continue;
			}
			
			if($this->getDiff($memDataOld,$memDataNew, $diff)){
				$member = $memDataNew->getMember();
				$memberContact = $memDataNew->getContact();
				$action = $actionHistory->getAction();
				$actionName = $action->__get('name');
				$diffs[] = array(
					'headers' => array(
						'member_nr' => $member->__get('member_nr'),
						'forename' => $memberContact->__get('n_given'),
						'lastname' => $memberContact->__get('n_family'),
						'member_name' => $member->__get('member_nr').' '.$memberContact->__get('n_fileas'),
						'created_datetime' => \org\sopen\app\util\format\Date::format($actionHistory->__get('created_datetime'),'dd.MM.YY H:m:s'),
						'action' => $actionName
					),
					'rows' => $diff
				);
			}
		}
		return $diffs;
		
	}
	
	public function getDiff($oldMembershipData, $newMembershipData, &$diff){
		$oldData = $oldMembershipData->getDescriptiveData();
		$newData = $newMembershipData->getDescriptiveData();
		$diff = array();
		$hasDiff = false;
		foreach($newData as $fieldName => $item){
			if($item['value']){
				if($item['value'] != $oldData[$fieldName]['value']){
					$hasDiff = true;
					$diff[$fieldName] = array(
						'label' => $item['label'],
						'old_value' => $oldData[$fieldName]['value'],
						'new_value' => $item['value']
					);
				}
			}
		}
		return $hasDiff;
	}
	
	public function logErrorAction($actionId, Membership_Model_SoMember $member, array $data = array()){
		if($this->ommitTracks){
			return;
		}
		$data['action_state'] = 'ERROR';
		return $this->logAction($actionId, $member, $data);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $actionId
	 * @param Membership_Model_SoMember $member
	 * @param array $data
	 */
	public function logAction($actionId, Membership_Model_SoMember $member, array $data = null){
		if($this->ommitTracks){
			return;
		}
		$action = Membership_Controller_Action::getInstance()->get($actionId);
		$actionHistory = $this->getEmptyActionHistory();
		$actionHistory->setFromArray(
			array(
				'member_id' => $member->getId(),
				'association_id' => $member->getForeignId('association_id'),
				'parent_member_id' => $member->getForeignId('parent_member_id'),
				'child_member_id' => null,
				'action_id' => $actionId,
				'action_category' => $action->__get('category'),
				'action_type' => 'AUTO',
				'action_state' => 'DONE',
				'created_datetime' => new Zend_Date(),
				'valid_datetime' => Membership_Controller_SoMember::getInstance()->getDueDate(),
				'to_process_datetime' => new Zend_Date(),
				'process_datetime' => new Zend_Date(),
				'created_by_user' => Tinebase_Core::get(Tinebase_Core::USER)->getId(),
				'processed_by_user' => Tinebase_Core::get(Tinebase_Core::USER)->getId()
			)
		);
		
		if(!is_null($data)){
			$actionHistory->setFromArray($data);
		}
		
		if(Membership_Api_JobManager::getInstance()->hasJob()){
			Membership_Api_JobManager::getInstance()->completeTask($actionHistory);
		}		
		if($this->isBunchCreate()){
			$this->add($actionHistory);
		}else{
			$actionHistory = $this->create($actionHistory);
		}
		return $actionHistory;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $actionId
	 * @param array $memberIds
	 */
	public function logActionForMemberIds($actionId, array $memberIds, array $data = null){
		if($this->ommitTracks){
			return;
		}
		$action = Membership_Controller_Action::getInstance()->get($actionId);
		$actionCategory = $action->__get('category');
		
		foreach($memberIds as $memberId){
//if($actionCategory !== 'BILLING'){
				$member = Membership_Controller_SoMember::getInstance()->get($memberId);
//			}else{
//				$member = Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
//			}
			$this->logAction($actionId, $member, $data);
		}
	}
	
	
/**
	 * 
	 * Enter description here ...
	 * @param Membership_Model_SoMember $member
	 */
	public function logCreateAction(Membership_Model_SoMember $member){
		if($this->ommitTracks){
			return;
		}
		// begin datetime as second param: membership data is valid since begin datetime of membership
		$membershipDataId = Membership_Controller_MembershipData::getInstance()->saveForMember($member, $member->__get('begin_datetime'));
		$this->logAction(Membership_Controller_Action::CREATE, $member, array(
			'old_data_id' => $membershipDataId,
			'data_id' => $membershipDataId
		));
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Membership_Model_SoMember $member
	 */
	public function trackContactDataChanges(Membership_Model_SoMember $member, array $trackFields, $contactData, Addressbook_Model_Contact $previousContactRecord, Addressbook_Model_Contact $newContactRecord){
		if($this->ommitTracks){
			return;
		}
		//$oldMemberRecord = Membership_Controller_SoMember::getInstance()->get($member->getId());
		$actions = array();
		
		if($this->detectChange($trackFields,$previousContactRecord, $newContactRecord)){
			$actions[] = array(
				'action'=>  Membership_Controller_Action::CONTACTDATACHANGE,
				'text' => ''
			);
		}
		
		if(count($actions)>0){
			// data change detected
			$oldMembershipData = Membership_Controller_MembershipData::getInstance()->getLastForMember($member->getId());
			$oldMembershipDataId = null;
			if($oldMembershipData){
				$oldMembershipDataId = $oldMembershipData->getId();
			}
			
			$membershipDataId = Membership_Controller_MembershipData::getInstance()->saveForMember($member, null, $contactData, $oldMembershipDataId);
			
			foreach($actions as $action){
				$this->logAction($action['action'], $member, array(
					'old_data_id' => $oldMembershipDataId,
					'data_id' => $membershipDataId,
					'action_text' => $action['text']
				));
			}
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param Membership_Model_SoMember $member
	 */
	public function trackDataChanges(Membership_Model_SoMember $member, Membership_Model_SoMember $oldMemberRecord){
		if($this->ommitTracks){
			return;
		}
		//$oldMemberRecord = Membership_Controller_SoMember::getInstance()->get($member->getId());
		$actions = array();
		if($this->detectChange(array(
			'parent_member_id'
		), $oldMemberRecord, $member)){
			try{
				$oldParentMember = $oldMemberRecord->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
				$oldPContactOrgName = 'EMPTY';
				if($oldParentMember){
					$id = $oldParentMember->getId();
					$this->logAction(
						Membership_Controller_Action::PARENTLEAVE, 
						$oldParentMember,
						array(
							'child_member_id' => $member->getId()
						)
					);
					$oldPContact = $oldParentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
					$oldPContactOrgName = $oldPContact->__get('org_name');
				}
				
				$newParentMember = $member->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
				if($newParentMember){
					$newPContact = $newParentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
					
					$actions[] = array(
						'action'=> Membership_Controller_Action::PARENTCHANGE,
						'text' => $oldPContactOrgName.' -> '. $newPContact->__get('org_name')
					);
					
					$this->logAction(
						Membership_Controller_Action::PARENTENTER, 
						$newParentMember,
						array(
							'child_member_id' => $member->getId()
						)
					);
				}
			}catch(Tinebase_Exception_Record_ForeignRecordNotFound $e){
				// don't log: in the fact, that the membership didn't pocess a parent member previously
			}
		}
		
		if($this->detectChange(array(
			'fee_group_id'
		), $oldMemberRecord, $member)){
			$oldFeeGroup = $oldMemberRecord->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
			$newFeeGroup = $member->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
			if($oldFeeGroup || $newFeeGroup){
				if(!$oldFeeGroup){
					$oldName = '';
				}else{
					$oldName = $oldFeeGroup->__get('name');
				}
				$actions[] = array(
					'action'=> Membership_Controller_Action::FEEGROUPCHANGE,
					'text' => $oldName. ' -> '.$newFeeGroup->__get('name')
				);
			}
		}
		
		if($this->detectChange(array(
			'membership_type'
		), $oldMemberRecord, $member)){
			$actions[] = array(
				'action'=>  Membership_Controller_Action::MEMKINDCHANGE,
				'text' => ''
			);
		}
		
		if($this->detectChange(array(
			'membership_status'
		), $oldMemberRecord, $member)){
			$actions[] = array(
				'action'=> Membership_Controller_Action::MEMSTATECHANGE,
				'text' => $oldMemberRecord->__get('membership_status').' -> '.$member->__get('membership_status')
			);
		}
		
		if($this->detectChange(array(
			'fee_payment_interval'
		), $oldMemberRecord, $member)){
			$actions[] = array(
				'action'=>  Membership_Controller_Action::PAYMENTINTERVALCHANGE,
				'text' => $oldMemberRecord->__get('fee_payment_interval').' -> '.$member->__get('fee_payment_interval')
			);
		}
		
		if($this->detectChange(array(
			'fee_payment_method'
		), $oldMemberRecord, $member)){
			$actions[] = array(
				'action'=>  Membership_Controller_Action::PAYMENTMETHODCHANGE,
				'text' => $oldMemberRecord->__get('fee_payment_method').' -> '.$member->__get('fee_payment_method')
			);
		}
		
		if($this->detectChange(array(
			'bank_account_id'
		), $oldMemberRecord, $member)){
			$oldBank = $oldMemberRecord->getForeignRecordBreakNull('bank_account_id', Billing_Controller_BankAccount::getInstance());
			$oldBankText = '--';
			if(!is_null($oldBank)){
				$oldBankText = $oldBank->toText();
			}
			$newBank = $member->getForeignRecordBreakNull('bank_account_id', Billing_Controller_BankAccount::getInstance());
			$newBankText = '--';
			if(!is_null($newBank)){
				$newBankText = $newBank->toText();
			}
			$actions[] = array(
				'action'=>  Membership_Controller_Action::BANKACCOUNTCHANGE,
				'text' => 
					$oldBankText.
					' -> '.
					$newBankText
			);
		}
		
	/*if($this->detectChange(array(
			'birth_date',
			'member_nr',
			'member_ext_nr',
			'account_holder'
		), $oldMemberRecord, $member)){
			$actions[] = array(
				'action'=>  Membership_Controller_Action::COMMONDATA,
				'text' => 
					'BLZ:'.$oldMemberRecord->__get('bank_code').
					' Bank:'.$oldMemberRecord->__get('bank_name').
					' Kto:'.$oldMemberRecord->__get('bank_account_nr').
					' Inhaber:'.$oldMemberRecord->__get('account_holder').
					' -> '.
					'BLZ:'.$member->__get('bank_code').
					' Bank:'.$member->__get('bank_name').
					' Kto:'.$member->__get('bank_account_nr').
					' Inhaber:'.$member->__get('account_holder')
			);
		}*/
		
		if(count($actions)>0){
			// data change detected
			$oldMembershipData = Membership_Controller_MembershipData::getInstance()->getLastForMember($member->getId());
			$oldMembershipDataId = null;
			if($oldMembershipData){
				$oldMembershipDataId = $oldMembershipData->getId();
			}
			
			$membershipDataId = Membership_Controller_MembershipData::getInstance()->saveForMember($member, null, null, $oldMembershipDataId);
			
			foreach($actions as $action){
				$this->logAction($action['action'], $member, array(
					'old_data_id' => $oldMembershipDataId,
					'data_id' => $membershipDataId,
					'action_text' => $action['text']
				));
			}
		}
	}
	/**
	 * 
	 * Enter description here ...
	 * @param array $fields
	 * @param unknown_type $oldRecord
	 * @param unknown_type $newRecord
	 */
	protected function detectChange(array $fields, $oldRecord, $newRecord){
		$or = clone $oldRecord;
		$nr = clone $newRecord;
		$or->__set('id', $oldRecord->getId());
		$nr->__set('id', $newRecord->getId());
		$or->flatten();
		$nr->flatten();
		foreach($fields as $field){
			if($or->__get($field) !== $nr->__get($field)){
				return true;
			}
		}
		return false;
	}	
}
?>