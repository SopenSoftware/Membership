<?php
/**
 * Membership csv generation class
 * 
 * @package     Membership
 * @subpackage	Export
 * 
 */
class Membership_Export_Csv extends Tinebase_Export_Csv
{
	const SOURCE_CONTACT = 'contact';
	const SOURCE_MEMBER = 'member';
	private $membershipController = null;
	private $contactController = null;
	private $fields = array(
		'header' => array(
			'Anrede',
			'Title',
			'Vorname',
			'Name',
			'Geburtsdatum',
			'Mitgliedsstatus',
			'Strasse',
			'Postleitzahl',
			'Ort',
			'Land',
			'Eintritt',
			'Austritt',
			'Eintrittsgrund',
			'Austrittsgrund'
		)
	);
    /**
     * export timesheets to csv file
     *
     * @param Membership_Model_ContactFilter $_filter
     * @return string filename
     */
    public function generate($_filter) {

    	$pagination = new Tinebase_Model_Pagination(array(
            'start' => 0,
            'limit' => 0,
            'sort' => 'member_nr',
            'dir' => 'ASC',
        ));
        
        //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($_filter->toArray(), true));
        $this->membershipController = Membership_Controller_SoMember::getInstance();
        $this->contactController = Addressbook_Controller_Contact::getInstance();
        
        $memberIds = $this->membershipController->search($_filter, $pagination, FALSE, TRUE, 'export');

        if (count($memberIds) < 1) {
            throw new Membership_Exception_NotFound('No members found.');
        }
        return $this->doExport($memberIds);
    }
    
 	private function doExport( $memberIds) {
        $tempFilePath = CSopen::instance()->getCustomerPath().'/customize/data/documents/temp/';
        $filename =  $tempFilePath . DIRECTORY_SEPARATOR . md5(uniqid(rand(), true)) . '.csv';
        
      
        $filehandle = fopen($filename, 'w');
        
        self::fputcsv($filehandle, $this->fields['header']);
       
        foreach($memberIds as $id){
        	$member = $this->membershipController->get($id);
        	$contact = $member->getForeignRecord('contact_id', $this->contactController);
        	$drawee = $contact->getLetterDrawee();
        	$address = $contact->getLetterAddress();
 			
        	$sportDiver = 0;
       		$affiliate = 0;

        	if($contact->has('customfields')){
       			if(array_key_exists('vdstSportDiver', $contact['customfields'])){
       				$sportDiver = $contact['customfields']['vdstSportDiver'];
       			}
        		if(array_key_exists('vdstAffiliate', $contact['customfields'])){
       				$affiliate = $contact['customfields']['vdstAffiliate'];
       			}
       		}
       		$resultArray = array(
        		$drawee->getSalutationText(),
        		$drawee->getTitle(),
        		$contact->__get('n_given'),
        		$contact->__get('n_family'),
        		\org\sopen\app\util\format\Date::format($contact->__get('bday')),
        		$member->tellMembershipStatus(),
        		$address->getStreet(),
        		$address->getPostalCode(),
        		$address->getLocation(),
        		$address->getCountryCode('DE'),
        		\org\sopen\app\util\format\Date::format($member->__get('begin_datetime')),
        		\org\sopen\app\util\format\Date::format($member->__get('termination_datetime')),
        		0,0
        	);
        	self::fputcsv($filehandle, $resultArray);
        }

        fclose($filehandle);
        
        return $filename;
    }
}
