<?php
class Membership_Controller_MembershipData extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_MembershipData();
		$this->_modelName = 'Membership_Model_MembershipData';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->somembers : new Zend_Config(array());
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
	/**
	 *
	 * Enter description here ...
	 */
	public function getEmptyMembershipData(){
		$emptyOrder = new Membership_Model_MembershipData(null,true);
		return $emptyOrder;
	}
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $memberId
	 */
	public function getLastForMember($memberId, $withPhantoms = false){
		$maxId = $this->_backend->getMaxIdForMember($memberId, $withPhantoms);
		if($maxId){
			return $this->get($maxId);
		}
		return null;
	}
	/**
	 *
	 * Enter description here ...
	 * @param Membership_Model_SoMember $member
	 * @return int	Id of newly generated record
	 */
	public function saveForMember(Membership_Model_SoMember $member, $validFrom = null, array $contactData = null, $oldDataId = null){
		$record = $this->getEmptyMembershipData();
		// set foreign ids
		$record->__set('member_id', $member->getId());
		$record->__set('parent_member_id', $member->getForeignId('parent_member_id'));
		$record->__set('association_id', $member->getForeignId('association_id'));
		$record->__set('fee_group_id', $member->getForeignId('fee_group_id'));

		// set valid datetime: assume this gets executed for future actions?->
		// then the validdatetime must be taken from the valid_datetime of the action_history
		// TODO: check this
		// for now take current timestamp
		if(!$validFrom){
			$validFrom = new Zend_Date();
		}
		$record->__set('valid_from', $validFrom);



		$aData = array(
			'membership_type',
			'membership_status',
			'fee_payment_interval',
			'fee_payment_method',
			'bank_account_id',	
		//'bank_code',
			//'bank_name',
			//'bank_account_nr',
			//'account_holder',
			'individual_yearly_fee',
			'donation',
			'additional_fee'
			);
			foreach($aData as $field){
				$record->__set($field, $member->__get($field));
			}

			if(!is_null($contactData) && is_array($contactData)){
				$record->setContactData($contactData);
			}

			if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) {
				$mem = $member->toArray();
				$dta = $record->toArray();
				$message = ' membership-data historized: ' . print_r($mem, true) . ' -> in history: ' . print_r($dta, true);
				Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . $message);
			}
			if($oldDataId){
				$record->__set('previous_data_id', $oldDataId);
				$previousData = $this->get($oldDataId);
				$oldContactData = $previousData->getContactData();
				if(!is_array($oldContactData)){
					$oldContactData = array();
				}
				foreach($contactData as $key => $value){
					if(!array_key_exists($key,$oldContactData)){
						$oldContactData[$key] = $value;
					}
				}
				$previousData->setContactData($oldContactData);
				$this->update($previousData);
			}
			$record = $this->create($record);
			return $record->getId();
	}
}
?>