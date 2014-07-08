<?php
/**
 *
 * Holds custom SoMember functions
 * @author hhartl
 *
 */
abstract class Membership_Custom_AbstractSoMember{
	const CALCULATION_MODE_STANDARD = 1;
	const CALCULATION_MODE_RECALCULATION = 2;

	protected static $contactTrackFields = array(
		'n_given',
		'n_family',
		'org_name',
		'org_unit',
		'company2',
		'adr_one_countryname',
        'adr_one_locality',
        'adr_one_postalcode',
        'adr_one_street',
        'adr_two_countryname',
        'adr_two_locality',
        'adr_two_postalcode',
        'adr_two_street',
        'adr3_countryname',
        'adr3_location',
        'adr3_postal_code',
        'adr3_street'
	);
	
	public static function getContactTrackFields(){
		return static::$contactTrackFields;
	}
	
	public static function getDescriptiveContactTrackFields(){
		//$translate = Tinebase_Translation::getTranslation('Addressbook');
		return array(
			'n_given' => 'Vorname',
			'n_family' => 'Nachname',
			'org_name' => 'Firma',
			'org_unit'	=> 'Abteilung',
			'company2'	=> 'Firma2',
			'letter_salutation'	=> 'Briefanrede',
			'adr_one_countryname' => 'Land Adr.1',
	        'adr_one_locality' => 'Ort Adr.1',
	        'adr_one_postalcode' => 'PLZ Adr.1',
	        'adr_one_street' => 'Strasse Adr.1',
	        'adr_two_countryname' => 'Land Adr.2',
	        'adr_two_locality' => 'Ort Adr.2',
	        'adr_two_postalcode' => 'PLZ Adr.2',
	        'adr_two_street' => 'Strasse Adr.2',
	        'adr3_countryname' => 'Land Adr.3',
	        'adr3_location' => 'Ort Adr.3',
	        'adr3_postal_code' => 'PLZ Adr.3',
	        'adr3_street' => 'Strasse Adr.3'
		);
	}

	/**
	 *
	 * Inspect controller create
	 * @param Tinebase_Model_SoMember $record
	 */
	abstract public static function inspectCreate(Membership_Model_SoMember $record);
	/**
	 *
	 * Called after controller created new SoMember
	 * @param Tinebase_Model_SoMember $record
	 */
	abstract public static function afterCreate(Membership_Model_SoMember $record);

	/**
	 *
	 * Inspect controller update
	 * @param Tinebase_Model_SoMember $record
	 */
	abstract public static function inspectUpdate(Membership_Model_SoMember $record);

	/**
	 *
	 * Called after controller created new SoMember
	 * @param Tinebase_Model_SoMember $record
	 */
	abstract public static function afterUpdate(Membership_Model_SoMember $record);

	public static function inspectUpdateContact(Addressbook_Model_Contact $previousContactRecord, Addressbook_Model_Contact $newContactRecord){
		
		// try to load memberships for contact
		$memships = Membership_Controller_SoMember::getInstance()->getByContactId($previousContactRecord->getId());
		$contactData = static::getContactDataForMembershipActionHistory($previousContactRecord,$newContactRecord);
		foreach($memships as $membership){
			Membership_Controller_ActionHistory::getInstance()->trackContactDataChanges(
				$membership,
				static::$contactTrackFields,
				$contactData['new'],
				$previousContactRecord,
				$newContactRecord
			);
		}
	}
	
	/**
	 *
	 * Grab data for membership action history and return as array
	 * @param Addressbook_Model_Contact $previousContactRecord
	 * @param Addressbook_Model_Contact $newContactRecord
	 * @return Array contact data for action history
	 */
	public static function getContactDataForMembershipActionHistory(Addressbook_Model_Contact $previousContactRecord, Addressbook_Model_Contact $newContactRecord){
		$oldValues = array();
		$newValues = array();
		$aRes = array();
		foreach(static::$contactTrackFields as $fieldName){
			$prev = $previousContactRecord->__get($fieldName);
			$new = $newContactRecord->__get($fieldName);
			
			if($prev != $new ){
				$oldValues[$fieldName]	= $prev;
				$newValues[$fieldName]  = $new;	
			}
		}
		
		return array(
			'previous' => $oldValues,
			'new' => $newValues
		);
	}

	/**
	 *
	 * Grab customfields data for membership action history and return as array
	 * 
	 * @param Addressbook_Model_Contact $contactRecord
	 * @return Array empty array here, as customfields are optional and based on customfield config
	 */
	public static function getContactCustomDataForMembershipActionHistory(Addressbook_Model_Contact $contactRecord){
		// if needed, implement in derived class (optional, therefore no abstract method)
		return array();
	}
	
	public static function inspectPrintMember(array $objects, array &$memDta, array &$summarize){
		// do nothing
	}
	
	public static function addAdditionalDataPrintMember(&$summarize, &$data){
		// do nothing
	}
	
	public static function canPrintMemberCard($membership){
		return true;
	}

}
?>