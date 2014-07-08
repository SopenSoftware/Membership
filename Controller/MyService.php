<?php
class Membership_Controller_MyService extends Tinebase_Controller_Record_Abstract
{
	const MEMBERS_CONTACT_CONTAINER = 20;
	const CLUB_MEMBERSHIP_TYPE = 'VIASOCIETY';
	
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

	private function getContactIdFromAccount(){
		return $this->_currentAccount->__get('contact_id');
	}

	private function getMyNumberFromAccount(){
		return $this->_currentAccount->__toString();
	}

	public function saveMyContactData($masterData){
		try{
			//$contactId = $this->_currentAccount->__get('contact_id');
			$contactId = $this->getContactIdFromAccount();
			/*if(!array_key_exists('club_contact_id',$masterData) || ($masterData['club_contact_id'] != $contactId)){
			 throw new Exception('ERROR: no right');
			 }*/
			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);

			$this->extractContactFromArray($contact, $masterData);

			Addressbook_Controller_Contact::getInstance()->update($contact);
			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);

			$memberNr = $this->getMyNumberFromAccount();
			$membership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);

			$this->extractMembershipFromArray($membership, $masterData);
			Membership_Controller_SoMember::getInstance()->update($membership);			
			
			$membership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);
			
			return array(
	   			'success' => true,
	   			'data' => $this->contactToArray($contact,$membership)
			);
		}catch(Exception $e){
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
	}

	private function contactToArray($contact, $membership){
		//$club = $contact->__get('society_contact_id');
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


	   			'bank_account_number' => $membership->__get('bank_account_nr'),
	   			'bank_code' => $membership->__get('bank_code'),
	   			'bank_account_name' => $membership->__get('account_holder'),
	   			'bank_name' => $membership->__get('bank_name'),
	   			'tel_work' => $contact->__get('tel_work'),
	   			'tel_cell' => $contact->__get('tel_cell'),
	   			'tel_fax' => $contact->__get('tel_fax'),
	   			'email' => $contact->__get('email'),
	   			'url' => $contact->__get('url')

		);
	}

	public function getMyContactData(){
		try{
			$contactId = $this->getContactIdFromAccount();
			$memberNr = $this->getMyNumberFromAccount();
			$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
			$membership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);

			return array(
	   			'success' => true,
	   			'data' => $this->contactToArray($contact,$membership)
			);
		}catch(Exception $e){
			return array(
   				'success' => false,
	   			'data' => array()
			);
		}
	}

	private function extractMembershipFromRecord($membership){
		$contact = $membership->__get('contact_id');
		
		return array(
		   	'member_nr' => $membership->__get('member_nr'),
			'membership_status' => $membership->__get('membership_status'),
			'begin_datetime' => $membership->__get('begin_datetime'),
//			'begin_datetime' => ($membership->__get('begin_datetime')?$membership->__get('begin_datetime')->get('Y-M-d'):''),
//   			'termination_datetime' => ($membership->__get('termination_datetime')?$membership->__get('termination_datetime')->get('Y-M-d'):''),
			'termination_datetime' => $membership->__get('termination_datetime'),
			'bday' => ($contact->__get('bday')?$contact->__get('bday')->get('YYYY-MM-dd'):''),
   			//'club_name' => $contact->__get('org_name'),
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
   			'tel_cell' => $contact->__get('tel_cell'),
   			'tel_fax' => $contact->__get('tel_fax'),
   			'email' => $contact->__get('email'),
   			'url' => $contact->__get('url')
		);
	}
	
	private function extractMembershipFromArray($membership, $memberData){
		$membership->__set('membership_status',$memberData['membership_status']);
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
		
		if($memberData['bank_account_number']){
			$membership->__set('bank_account_nr',$memberData['bank_account_number']);
		}
		if($memberData['bank_account_name']){
			$membership->__set('account_holder',$memberData['bank_account_name']);
		}
	}
	
	// --> TODO
	public function getFamilyMembers($idList = array()){
		try{
			$contactId = $this->getContactIdFromAccount();
			$memberships = Membership_Controller_SoMember::getInstance()->getSoMembersByMyId($contactId);

			$memberData = array();
			$idMap = null;
			if(!empty($idList)){
				$idMap = array_flip($idList);
			}

			foreach($memberships as $membership){
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
	   			'results' => array()
			);
		}
	}
	
	public function addFamilyMember($memberData){
		throw new Exception('TODO');
		$clubContactId = $this->getContactIdFromAccount();
		$clubContact = Addressbook_Controller_Contact::getInstance()->get($clubContactId);
		
		$clubMemberNr = $this->getMyNumberFromAccount();
		$clubMembership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($clubMemberNr);
		
		$memberNr = Membership_Controller_SoMember::getInstance()->getMyMaxMemberNr($clubContactId);
		$memberNr++;
		
		$contact = new Addressbook_Model_Contact(array(	
				'n_given'=>$memberData['n_given'],
				'n_family'=>$memberData['n_family']
		));
		$this->extractContactFromArray($contact, $memberData);
		$contact->__set('container_id',self::MEMBERS_CONTACT_CONTAINER);
		$contact = Addressbook_Controller_Contact::getInstance()->create($contact);
		
		$membership = new Membership_Model_SoMember();
		$this->extractMembershipFromArray($membership, $memberData);
		$membership->__set('member_nr',$memberNr);
		$membership->__set('contact_id', $contact->getId());
		$membership->__set('society_contact_id', $clubContactId);
		$membership->__set('association_contact_id', $clubMembership->__get('association_contact_id')->getId());
		$membership->__set('membership_type',self::CLUB_MEMBERSHIP_TYPE);
		$membership = Membership_Controller_SoMember::getInstance()->create($membership);
		return Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);
	}
	
	public function updateFamilyMember($memberNr, $memberData){
		throw new Exception('TODO');
		$clubContactId = $this->getContactIdFromAccount();
		$clubContact = Addressbook_Controller_Contact::getInstance()->get($clubContactId);
		$clubMemberNr = $this->getMyNumberFromAccount();
		$clubMembership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($clubMemberNr);
		
		
		$membership = Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);
		$memberContactId = $membership->__get('contact_id')->getId();
		
		$memberContact = Addressbook_Controller_Contact::getInstance()->get($memberContactId);
		$contactId = $memberContact->getId();
		$this->extractContactFromArray($memberContact, $memberData);
		
		$this->extractMembershipFromArray($membership, $memberData);
		
		Addressbook_Controller_Contact::getInstance()->update($memberContact);
		
		$membership->__set('contact_id', $contactId);
		$membership->__set('society_contact_id', $clubContactId);
		$membership->__set('association_contact_id', $clubMembership->__get('association_contact_id')->getId());
		
		Membership_Controller_SoMember::getInstance()->update($membership);
		
		// update delivers full membership including foreign records embedded
		return Membership_Controller_SoMember::getInstance()->getSoMemberByMemberNr($memberNr);
	}
	
	
	public function saveFamilyMemberData($memberData){
		try{
			SPIncludeManager::requireClass(CSopen::instance()->getLibPath()."Sopen/util/array/ArrayHelper.class.php",'ArrayHelper');
    		$memberNr = ArrayHelper::getKeyPathBreak('member_nr',$memberData);
    		
			if($memberNr != 0){
				$membership = $this->updateMyMember($memberNr,$memberData);
			}else{
				$membership = $this->addMyMember($memberData);
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
	   			'data' => array()
			);
		}
	}
}

?>