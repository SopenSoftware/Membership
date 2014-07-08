<?php 

class Membership_Import_TD{
	
	public static function import($fileName){
		$result = array(
			'failcount' => 0,
			'totalcount' => 0,
			'duplicatecount' => 0,
			'status' => 'success'
		);
		
		try{
			$td = Membership_Import_Td_File::createAndOpen($fileName);
			
			$aClub = $td->getClub();
			
			try{
				$clubMember = self::saveClubData($aClub);
				
				if(!($clubMember instanceof Membership_Model_SoMember)){
					Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' ' . 'TD-Import - Fehler: Vereinsdaten konnten nicht aktualisiert werden. ' . print_r($aClub,true));
					throw new Exception('Fehler: Verein wurde nicht gefunden und konnte auch nicht neu angelegt werden. ');
				}
				
			}catch(Exception $e){
				Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' ' . 'TD-Import - Fehler: Vereinsdaten konnten nicht aktualisiert werden.' . print_r($e->__toString(),true));
				throw $e;
			}
			
			if($td->hasMembers()){
				
				while($td->hasNextMember()){
					$aMember = $td->getNextMember();
					try{
						self::saveMemberData($aMember, $clubMember);
						$result['totalcount'] ++;
					}catch(Exception $e){
						$result['failcount'] ++;
						Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . " TD-Import - Fehler: Folgendes Mitglied konnte nicht angelegt oder aktualisiert werden: ". print_r($aMember,true) . print_r($e->__toString(),true));
					}
				}
				
			}else{
				Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . " TD-Import - Hinweis: Keine Mitglieder in der Importdatei $fileName gefunden");
			}
			
			$td->close();
		}catch(Exception $e){
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' ' . 'TD-Import - Es trat ein Fehler auf. Nicht ausgeführt.' . print_r($e->__toString(),true));
			$result['status'] = 'error';
		}
		
		return $result;
	}
	
	private static function saveClubData($data){
		
		// TODO: do not create/update club records (contacts and memberships)
		// TODO: deactivate!!!
		
		$number = $data['club_nr'];
		$mCon = Membership_Controller_SoMember::getInstance();
		$cCon = Addressbook_Controller_Contact::getInstance();
		
		// get club membership by club_nr = member_nr of club
		try{
			$member = $mCon->getSoMemberByMemberNr($number);
		}catch(Exception $e){
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' ' . 'TD-Import - Hinweis: Verein nicht gefunden - Neuanlage' . print_r($e->__toString(),true));
			$member = null;
		}
		
		$update = true;
		// if exists: modify
		if(!$member){
			$update = false;
			$member = $mCon->getEmptySoMember();
			$contact = new Addressbook_Model_Contact(null,true);
			
			// set member data
		
			$member->__set('begin_datetime',  new Zend_Date($data['begin_date']));
			if($data['end_date']){
				$member->__set('termination_datetime',  new Zend_Date($data['end_date']));
			}
			$member->__set('membership_type', 'SOCIETY');
			
	//		if($data['membership_status'] == 0){
	//			$status = 'PASSIVE';
	//		}else{
				$status = 'ACTIVE';
	//		}
			
			$member->__set('membership_status', $status);
		}else{
			$contact = $member->getForeignRecord('contact_id', $cCon);
		}
		$containerId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::ADDRESSBOOK_CLUBS);
		$names = explode(' ',trim($data['contact_name']));
		$nFamily = $nGiven = '';
		if(is_array($names)){
			if(array_key_exists('0', $names)){
				$nGiven = $names[0];
			}
			if(array_key_exists('1', $names)){
				$nFamily = $names[1];
			}
		}
	
		$email2 = $data['email'];
		$email1 = '';
		if(strpos($email2, '/')){
			list($email2, $email1) = explode('/',$email2);
			$email1 = trim($email1);
			$email2 = trim($email2);
		}
		
		$foundationDate = null;
		if($data['foundation_date']){
			$foundationDate = new Zend_Date($data['foundation_date']);
		}
		$contact->setFromArray(
			array(
				'container_id' => $containerId,
				'org_name' => $data['org_name'],
				'company2' => $data['org_name2'],
				'n_given' => $nGiven,
				'n_family' => $nFamily,
				'adr_one_street' => $data['street'],
				'adr_one_postalcode' => $data['postalcode'],
				'adr_one_locality' => $data['location'],
				'email' => $email2,
				'email_home' => $email1,
				'url' => $data['www'],
				'tel_fax' => $data['fax'],
				'tel_work' => $data['phone1'],
				'tel_home' => $data['phone2'],
				//'bday' => $foundationDate,
				'customfields' => array(
					'vdstSportDiver' => ($data['sportdiver']==1?1:0),
					'vdstAffiliate' => ($data['affiliate']==1?1:0)
				)
			)
		);
		
		if($update){
			$cCon->update($contact);
			$mCon->update($member);
		}else{
			$cCon->create($contact);
			$member->__set('member_nr', $number);
			$member->__set('contact_id', $contact->getId());
			//$assocContact = Brevetation_Controller_Brevetation::getInstance()->getAssociationContactByShortName('VDST');
			$assoc = Membership_Controller_Association::getInstance()->getAssociationByShortName('VDST');
			
			$member->__set('association_id', $assoc->getId());
			$member = $mCon->create($member);
		}
		
		return $member;
	}
	
	private static function saveMemberData($data, $clubMembership){
		$number = $data['member_nr'];
		$mCon = Membership_Controller_SoMember::getInstance();
		$cCon = Addressbook_Controller_Contact::getInstance();
		// get club membership by club_nr = member_nr of club
		try{
			$member = $mCon->getSoMemberByMemberNr($number);
			// skip, if termination_datetime is set or status = TERMINATED
			if($member->__get('termination_datetime') || $member->__get('membership_status')=='TERMINATED'){
				return;
			}
		}catch(Exception $e){
			$member = null;
		}
		
		$update = true;
		// if exists: modify
		if(!$member){
			$update = false;
			$member = $mCon->getEmptySoMember();
			$contact = new Addressbook_Model_Contact(null,true);
		}else{
			$contact = $member->getForeignRecord('contact_id', $cCon);
		}

		try{
			$bday = new Zend_Date($data['bday']);
		}catch(Exception $e){
			$bday = null;
		}
		$sexMap = array(
			1 => 'MALE',
			2 => 'FEMALE'
		);
		
		$containerId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::ADDRESSBOOK_CLUBMEMBERS);
		
		$email2 = $data['email'];
		$email1 = '';
		if(strpos($email2, '/')){
			list($email2, $email1) = explode('/',$email2);
			$email1 = trim($email1);
			$email2 = trim($email2);
		}
		
		$contact->setFromArray(
			array(
				'container_id' => $containerId,
				'n_family' => $data['n_family'],
				'n_given' => $data['n_given'],
				//'org_name' => $data['org_name'],
				'adr_one_street' => $data['street'],
				'adr_one_postalcode' => $data['postalcode'],
				'adr_one_locality' => $data['location'],
				'email' => $email2,
				'email_home' => $email1,
				'url' => $data['url'],
				'sex' => $sexMap[$data['sex']],
				'salutation_id' => $data['sex'],
				'tel_fax' => $data['fax'],
				'tel_work' => $data['phone1'],
				'tel_home' => $data['phone2'],
				'tel_cell' => $data['mobile'],
				'bday' => $bday,
				'customfields' => array(
					'vdstSportDiver' => ($data['sportdiver']==1?1:0),
					'vdstAffiliate' => ($data['affiliate']==1?1:0)
				)
			)
		);
		
		// todo: check this
		if(!$data['begin_date']){
			return;
		}
		
		// set member data
		$member->__set('begin_datetime', new Zend_Date($data['begin_date']));
		if($data['end_date']){
			$member->__set('termination_datetime',  new Zend_Date($data['end_date']));
		}
		$member->__set('membership_type', 'VIASOCIETY');
		
		if($data['membership_status'] == 0){
			$status = 'PASSIVE';
		}else{
			$status = 'ACTIVE';
		}
		
		$member->__set('membership_status', $status);
		
		
		if($update){
			$cCon->update($contact);
			$mCon->update($member);
		}else{
			$cCon->create($contact);
			$member->__set('member_nr', $number);
			$member->__set('parent_member_id', $clubMembership->getId());
			$member->__set('association_id', $clubMembership->__get('association_id'));
			$member->__set('contact_id', $contact->getId());
			$mCon->create($member);
		}
	}
}
?>