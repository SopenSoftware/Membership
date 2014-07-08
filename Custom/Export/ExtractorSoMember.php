<?php
class Membership_Custom_Export_ExtractorSoMember{
	const INDEX_MODE_NUMERIC = 0;
	const INDEX_MODE_CONTACT_ID = 1;
	const INDEX_MODE_MEMBER_ID = 2;
	
	private $active = 0;
	private $passive = 0;
	private $discharged = 0;
	private $terminated = 0;
	
	private $totalMainAssocFee = 0;
	private $totalClubFee = 0;
	private $totalAdditionalFee = 0;
	private $totalDonation = 0;
	
	private $ageTotal = 0;
	private $countTotal = 0;
	
	private $data = array();
	private $indexMode = null;
	
	public $summarize = array();
	
	public function __construct(){
		$this->setIndexMode(self::INDEX_MODE_NUMERIC);
	}
	
	public function setIndexMode($indexMode){
		$this->indexMode = $indexMode;
	}
	
	public function setCountTotal($countTotal){
		$this->countTotal = $countTotal;
	}
	
	public function addTotalMainAssocFee($count){
		$this->totalMainAssocFee += $count;
	}
	
	public function addTotalClubFee($count){
		$this->totalClubFee += $count;
	}
	
	public function addTotalAdditionalFee($count){
		$this->totalAdditionalFee += $count;
	}
	
	public function addTotalDonation($count){
		$this->totalDonation += $count;
	}
	
	public function addAgeTotal($count){
		$this->ageTotal += $count;
	}
	
	public function getAverageAge(){
		return $this->ageTotal/max($this->countTotal,1);
	}
	
	public function addActive($count){
		$this->active += $count;
	}
	
	public function addPassive($count){
		$this->passive += $count;
	}
	
	public function addDischarged($count){
		$this->discharged += $count;
	}
	
	public function addTerminated($count){
		$this->terminated += $count;
	}
	
	public function addItem($item, $contactId = null, $memberId = null){
		if($this->indexMode == self::INDEX_MODE_NUMERIC){
			$this->data[] = $item;
		}else{
			if($this->indexMode == self::INDEX_MODE_CONTACT_ID){
				$this->data[$contactId] = $item;
			}else{
				$this->data[$memberId] = $item;
			}
		}
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function getCountDataAsArray(){
		return array(
			'age_total' => $this->ageTotal,
			'members_total' => $this->countTotal
		);
	}
	
	public function addMemberData($member, array $aAdditionalData = null){
		// TODO: take care! if multiple memberships are associated with one contact
		// find will only deliver the first record!
		//$membership = $memberships->find('contact_id', $contact->getId());
		$contact = $member->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
		 
		$pmNr = null;
		$parentName = null;
		$parentMember = null;
		$parentData = array(
			'PARENT_BRIEFANREDE' => '',
			'PARENT_NAME' => '',
			'PARENT_VORNAME' => '',
			'PARENT_EMAIL' => '',
			'PARENT_ADRESSE1' => array(
				'STRASSE' => ''
			),
			'PARENT_ANSCHRIFT' => array(
				'BRIEF' => '',
				'RECHNUNG' => ''		
			),
			'PARENT_ANREDE' => array(
				'DIREKT' => '',
				'ANSCHRIFT' => ''
		));
		if($member->__get('parent_member_id')){
			$parentMember = $member->getForeignRecordBreakNull('parent_member_id', Membership_Controller_SoMember::getInstance());
			if($parentMember){
				$pmNr = $parentMember->__get('member_nr');
				$parentContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
				$parentName = $parentContact->__get('org_name').' '.$parentContact->__get('company2');
				$parentData = array(
					'PARENT_BRIEFANREDE' => $parentContact->__get('letter_salutation'),
					'PARENT_NAME' => $parentContact->__get('n_family'),
					'PARENT_VORNAME' => $parentContact->__get('n_given'),
					'PARENT_EMAIL' => $parentContact->__get('email'),
					'PARENT_ADRESSE1' => array(
						'STRASSE' => $parentContact->__get('adr_one_street')
					),
					'PARENT_ANSCHRIFT' => array(
						'BRIEF' => $parentContact->getLetterDrawee()->toText(),
						'RECHNUNG' => $parentContact->getInvoiceDrawee()->toText()		
					),
					'PARENT_ANREDE' => array(
						'DIREKT' => $parentContact->getLetterDrawee()->getSalutationText(true),
						'ANSCHRIFT' => $parentContact->getLetterDrawee()->getSalutationText()
				));
			}
			
			
		}
		$feeSums = $member->__get('feegroup_prices');
		$mainAssocFee = 0;
		$clubFee = 0;
		$additionalFee = 0;
		$donation = 0;
		if($member->__get('additional_fee')){
			$additionalFee = $member->__get('additional_fee');
		}
		if($member->__get('donation')){
			$donation = $member->__get('donation');
		}
		if(is_array($feeSums) && array_key_exists('sums', $feeSums)){
			if(array_key_exists('XI', $feeSums['sums'])){
				$mainAssocFee = $feeSums['sums']['XI'];
			}
			if(array_key_exists('YI', $feeSums['sums'])){
				$clubFee = $feeSums['sums']['YI'];
			}
		}
		
		$this->addTotalAdditionalFee($mainAssocFee);
		$this->addTotalClubFee($clubFee);
		$this->addTotalAdditionalFee($additionalFee);
		$this->addTotalDonation($donation);
		$this->addAgeTotal((int) $member->__get('person_age'));
			
		
		$awards = array();
		$recordsAwards = $member->getAwards();
		foreach($recordsAwards as $award){
			$awardList = $award->getForeignRecord('award_list_id', Membership_Controller_AwardList::getInstance());
			$awards[$awardList->__get('key')] =  \org\sopen\app\util\format\Date::format($award->__get('award_datetime'));
		}
				
		$payment = $feeGroupKey = $paymentKey = null;
		if($paymentMethod = $member->getForeignRecordBreakNull('fee_payment_method', Billing_Controller_PaymentMethod::getInstance())){
			$payment = $paymentMethod->__get('name');
			$paymentKey = $paymentMethod->__get('id');
		}
		if($feeGroup = $member->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance())){
			$feeGroupKey = $feeGroup->__get('key');
		}
	
		$birthDay = null;
		if($contact->__get('bday')){
			$birthDay = \org\sopen\app\util\format\Date::format($contact->__get('bday'));
		}
		
		
		$partnerName = '';
		$tpartnerName = '';
		try{
			if($contact->__get('partner_lastname')){
				$partnerName = $contact->__get('partner_lastname').', '.$contact->__get('partner_forename');
				$tpartnerName = $contact->getLetterDrawee()->getQualifiedPartnerName();
			}
		}catch(Exception $e){
			//
		}
		
		$memberNames = $contact->getLetterDrawee()->getQualifiedName();
		if($tpartnerName){
			$memberNames .= ' und ' . $tpartnerName;
		}
		/*if($contact->__getBreakNull('partner_lastname')){
			$partner = $contact->__getBreakNull('partner_lastname').','.$contact->__getBreakNull('partner_forename');
		}*/
		$data = array(
        	'ADRNR' => $contact->getId(),
        	'PARENTNR' => $pmNr,
			'PARENTNAME' => $parentName,
        	'NR' => $member->__get('member_nr'),
			'NAME' => $contact->__get('n_fileas'),
			'TNAME' =>  $contact->getLetterDrawee()->getQualifiedName(),
        	'LASTNAME' => $contact->__get('n_family'),
        	'FORENAME' => $contact->__get('n_given'),
			'PARTNERNAME' => $partnerName,
			'TPARTNERNAME' => $tpartnerName,
			'MEMBER_NAMES' => $memberNames,
        	'SALUTATION' => $contact->getLetterDrawee()->getSalutationText(true),
        	'TITLE' => $contact->getLetterDrawee()->getTitle(),
        	'LETTERSALUTATION' => $contact->__get('letter_salutation'),
			'STREET' => $contact->__get('adr_one_street'),
        	'POSTAL' => $contact->__get('adr_one_postalcode'),
			'LOCATION' => $contact->__get('adr_one_locality'),
			'PHONE1' => $contact->__get('tel_work'),
			'EMAIL1' => $contact->__get('email'),
			'WWW1' => $contact->__get('url'),
			'AGE' => $member->__get('person_age'),
        	'MEMYEARS' => $member->__get('member_age'),
        	'ORGA1' => $contact->__get('org_name'),
			'ORGA2' => $contact->__get('company2'),
        	'STATE' => $member->tellMembershipStatus(),
        	'SEX' => $contact->getSexLong(),
			'TODAY' =>  \org\sopen\app\util\format\Date::format(new Zend_Date()),
			'BIRTH' =>  $birthDay,
			'BEGIN' =>  \org\sopen\app\util\format\Date::format($member->__get('begin_datetime')),
			'END' =>  ($member->__get('termination_datetime'))?\org\sopen\app\util\format\Date::format($member->__get('termination_datetime')):'',
			//'MAGAZINES' => 1,//$contact->getSex(), -> pushed to inspectPrintMember for SAV!
			'KIND' => $member->tellMemberKind(),
			'MITGLIEDSART' => $member->tellMemberKind(),
			'PAYMENT' => $payment,
			'PAYMENTKEY' => $paymentKey,
			'FEEGROUP' => $feeGroupKey,
			'ADDFEE_VAL' => $additionalFee,
			'MAINFEE' => \org\sopen\app\util\format\Currency::formatCurrency($mainAssocFee),
			'CLUBFEE' => \org\sopen\app\util\format\Currency::formatCurrency($clubFee),
			'ADDFEE' => \org\sopen\app\util\format\Currency::formatCurrency($additionalFee),
			'DONATION' => \org\sopen\app\util\format\Currency::formatCurrency($donation),
			'TOTAL' => \org\sopen\app\util\format\Currency::formatCurrency($mainAssocFee + $clubFee + $additionalFee + $donation),
			'COMMENT' => ''
			
		);
		
		$data = array_merge($data, $parentData);
		
		if(is_array($aAdditionalData) && !is_null($aAdditionalData)){
			$data = array_merge($data, $aAdditionalData);
		}
		
		foreach($awards as $key => $date){
			$data['A_'.$key] = $date;
		}
			
		Membership_Custom_SoMember::inspectPrintMember(
        		array(
        			'contact' => $contact,
        			'parentMember' => $parentMember,
        			'member' => $member
        		), 
        		$data,
        		$this->summarize
        	);			
		$this->addItem($data, $contact->getId(), $member->getId());
	}
	
	public function getSummarize(){
		return $this->summarize;
	}
	
	
	
}