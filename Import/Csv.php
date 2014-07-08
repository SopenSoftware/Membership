<?php
/**
 * Tine 2.0
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Csv.php 12800 2010-02-12 16:08:17Z p.schuele@metaways.de $
 * 
 *

/**
 * csv import class for the addressbook
 * 
 * @package     Addressbook
 * @subpackage  Import
 * 
 */
class Membership_Import_Csv extends Tinebase_Import_Csv_Abstract
{    
    /**
     * the constructor
     *
     * @param Tinebase_Model_ImportExportDefinition $_definition
     * @param mixed $_controller
     * @param array $_options additional options
     */
    public function __construct(Tinebase_Model_ImportExportDefinition $_definition, $_controller = NULL, $_options = array())
    {
        parent::__construct($_definition, $_controller, $_options);
    }

    
    /**
     * add some more values (container id)
     *
     * @return array
     */
    protected function _addData($recordData)
    {
    	$aMapInterval = array(
    		1 => 'YEAR',
    		2 => 'QUARTER',
    		3 => 'MONTH'
    	);
    	$aMapMethod = array(
    		1 => 'DEBIT',
    		2 => 'BANKTRANSFER'
    	);
    	$aMapMembershipType = array(
    		'single' => 'SINGLE',
    		'viasociety' => 'VIASOCIETY',
    		'society' => 'SOCIETY',
    		'family' => 'FAMILY'
    	);
    	
    	$aMapStatus = array(
    		1 => 'PASSIVE',
    		0 => 'ACTIVE'
    	);
    	
    	$result = array();
    	if(!array_key_exists('member_nr',$recordData) || !is_numeric($recordData['member_nr'])){
    		$result['member_nr'] = NULL;
    	}
    	if(array_key_exists('affiliate_contact_id',$recordData) || $recordData['affiliate_contact_id']==0){
    		$result['affiliate_contact_id'] = NULL;
    	}
        if(array_key_exists('family_contact_id',$recordData) || $recordData['family_contact_id']==0){
    		$result['family_contact_id'] = NULL;
    	}
        if(array_key_exists('family_contact_id',$recordData) || $recordData['family_contact_id']==0){
    		$result['mc_procure_contact_id'] = NULL;
    	}
    	$result['fee_payment_interval'] = $aMapInterval[$recordData['fee_payment_interval']];
    	$result['fee_payment_method'] = $aMapMethod[$recordData['fee_payment_method']];
    	
    	if(is_null($result['fee_payment_method'])){
    		$result['fee_payment_method'] = 'NOVALUE';
    	}
    	
        if(is_null($result['fee_payment_interval'])){
    		$result['fee_payment_interval'] = 'NOVALUE';
    	}
    	
        if($recordData['membership_type']=='viasociety'){
    		$result['fee_payment_method'] = 'NOVALUE';
    		$result['fee_payment_interval'] = 'NOVALUE';
    	}
// import delivers society contact id
// must be mapped to society_member_id    	
throw new Exception('TODO');    	
    	if($recordData['membership_type']=='single'){
    		$result['society_contact_id'] = 2303130;
    		$result['association_contact_id'] = 2326879;
    	}
    	
    	$membershipStatus = $recordData['membership_status'];
    	$recordData['membership_status'] = $aMapStatus[$membershipStatus];
    	$result['membership_status'] = $aMapStatus[$membershipStatus];
    	
    	
    	$result['membership_type'] = 'NOVALUE';
    	if(array_key_exists($recordData['membership_type'],$aMapMembershipType)){
    		$result['membership_type'] = $aMapMembershipType[$recordData['membership_type']];
    	}
    	
    	if($recordData['begin_datetime']){
    		$result['begin_datetime'] = $this->convertDate($recordData['begin_datetime']);
    	}
    	
    	if($recordData['bank_code']==0){
    		$result['bank_code'] = null;
    		$result['bank_account_nr'] = null;
    	}
    	
    	if($recordData['exp_membercard_datetime']){
    		$result['exp_membercard_datetime'] = $this->convertDate($recordData['exp_membercard_datetime']);
    	}else{
    		$result['exp_membercard_datetime'] = null;
    	}
    	
        if($recordData['discharge_datetime']){
    		$result['discharge_datetime'] = $this->convertDate($recordData['discharge_datetime']);
    	}else{
    		$result['discharge_datetime'] = null;
    	}
    	
        if($recordData['termination_datetime']){
    		$result['termination_datetime'] = $this->convertDate($recordData['termination_datetime']);
    	}else{
    		$result['termination_datetime'] = null;
    	}
    	
    	
// -> TODO: crawl $MEMO2 !!!!    	
    	/*if($recordData['member_notes']=='TODO'){
    		$result['member_notes'] = '';
    	}*/
    	
    	if($recordData['family_contact_id'] == '0'){
    		$result['family_contact_id'] = null;
    	}else{
    		$result['family_contact_id'] = $recordData['family_contact_id'];
    	}
		return $result;
    }
    
    private function convertDate($date){
    	return $date;
    	/*if(!is_null($date)){
    		$parts = explode('.',$date);
    		$dt = mktime(0,0,0,$parts[0],$parts[1],$parts[2]);
    		$date = strftime('%Y-%m-%d',$dt);	
    	}
    	return $date;*/
    }
    
    public function afterImportRecord($importedRecord, $recordData){
    	$feeProgressExtRecord = null;
    	
    	if($recordData['membership_type'] == 'SOCIETY'){
    		$feeProgressExtRecord = $this->createFeeProgressExt($importedRecord, $recordData);
    	}
		$feeProgressRecord = null;
    	if(in_array($recordData['membership_type'],array('SOCIETY','FAMILY','SINGLE'))){
    		$feeProgressRecord = $this->createFeeProgress($importedRecord, $recordData, $feeProgressExtRecord);
    	}
    	
    	// take bank data into contact
    	//Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' bank data to contact: ' . print_r($recordData, true));
    	if(($recordData['bank_account_nr'] != '') && ($recordData['bank_code'] != '')){
    		$contactId = $recordData['contact_id'];
    		//Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' contactId: ' .$contactId);
    		$contact = Addressbook_Controller_Contact::getInstance()->get($contactId);
    		$contact->__set('bank_account_number', $recordData['bank_account_nr']);
    		$contact->__set('bank_code', $recordData['bank_code']);
    		$contact->__set('bank_account_name', $recordData['account_holder']);
    		$contact->__set('bank_name', $recordData['bank_name']);
			Addressbook_Controller_Contact::getInstance()->update($contact); 	
			//Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($contact, true));	
    	}

    }
    private function calcFeeFromDateTime($beginDateTime){
    	$result = '2010-01-01';
    	return $result;
    }
    
    private function calcAge($birthDate){
    	$age = 18;
    	return $age;
    }
    
    public function createFeeProgress($importedRecord, $recordData, $feeProgressExtRecord){
    	$feeProgressExtId = null;
    	if(!is_null($feeProgressExtRecord)){
    		$feeProgressExtId = $feeProgressExtRecord->__get('id');
    	}
    	$feeCategory = 'ADULT';
    	if($recordData['membership_type']=='SOCIETY'){
    		$feeCategory = 'SOCIETY';
    	}
    	if($recordData['membership_type']=='FAMILY'){
    		$feeCategory = 'FAMILY';
    		if($importedRecord->__get('is_family_leading') !== true){
    			$feeCategory = 'NOVALUE';
    		}
    	}
    	$feeFromDateTime = $this->calcFeeFromDateTime($importedRecord->__get('begin_datetime'));
    	$feeToDateTime = '2010-12-31';
    	if($feeCategory == 'ADULT'){
    		$contact = Addressbook_Controller_Contact::getInstance()->get($importedRecord->__get('contact_id'));
    		$birth = $contact->__get('bday');
    		$age = $this->calcAge($birth);
    		if($age>0 && $age<15){
    			$feeCategory = 'CHILD';
    		}elseif($age >14 && $age < 18){
    			$feeCategory = 'JUVENILE';
    		}
    	}
    	
    	$calcApproved = trim($recordData['is_calculation_approved']);
    	if($calcApproved == '2010'){
    		$calcApproved = true;
    	}else{
    		$calcApproved = false;
    	}
    	$aFeeProgress = array(
    		'member_id' => $importedRecord->__get('id'),
    		'fee_progress_ext_id' => $feeProgressExtId,
	    	'member_nr' => $importedRecord->__get('member_nr'),
	    	'fee_category' => $feeCategory,
	    	'fee_from_datetime' => $feeFromDateTime,
	    	'fee_to_datetime' => $feeToDateTime,
	    	'fee_year' => '2010',
	    	'is_calculation_approved' =>$calcApproved,
	    	'fee_period_notes' => '',
	    	'fee_calc_datetime' => null
    	);
    	$feeProgress = new Membership_Model_SoMemberFeeProgress($aFeeProgress);
    	$feeProgress = Membership_Controller_SoMemberFeeProgress::getInstance()->create($feeProgress);
    	return $feeProgress;
    }
    
    public function createFeeProgressExt($importedRecord, $recordData, $result){
    	$aFeeProgressExt = array(
    		'mc_procure_contact_id'=> $recordData['mc_procure_contact_id'],
    		'fee_ext_year'=> '2010',
			'members_total'=> $recordData['members_total'],
			'active_members_total'=> $recordData['active_members_total'],
			'passive_members_total'=> 0,
			'juvenile_members_total'=> 0,
			'adult_members_total'=> 0,
			'acclamative_members_total'=> $recordData['acclamative_members_total'],
			'main_convention_votes'=> $recordData['main_convention_votes'],
			'mc_procure_votes'=>$recordData['mc_procure_votes'] ,
			'mc_votes_acc_procure'=> $recordData['mc_votes_acc_procure'],
			'mc_attendance' => $mcAttendance
    	);
    	$feeProgressExt = new Membership_Model_SoMemberFeeProgressExt($aFeeProgressExt);
    	$feeProgressExt = Membership_Controller_SoMemberFeeProgressExt::getInstance()->create($feeProgressExt);
    	return $feeProgressExt;
    }
 
  protected function _importRecord($_recordData, &$_result)
    {
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($_recordData, true));
        
        $record = new $this->_modelName($_recordData, TRUE);
        
        if ($record->isValid()) {
            if (! $this->_options['dryrun']) {
                
                // check for duplicate
                if (isset($this->_options['duplicates']) && $this->_options['duplicates']) {
                    // search for record in container and print log message
                    $existingRecords = $this->_controller->search($this->_getDuplicateSearchFilter($record), NULL, FALSE, TRUE);
                    if (count($existingRecords) > 0) {
                        Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' Duplicate found: ' . $existingRecords[0]);
                        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
                        $_result['duplicatecount']++;
                        return;
                    }
                }
                
                // create/add shared tags
                if (isset($_recordData['tags'])) {
                    $record->tags = $this->_addSharedTags($_recordData['tags']);
                }

                $record = call_user_func(array($this->_controller, $this->_createMethod), $record);
                //call_user_func(array($this,'afterImportRecord'),$record,$recordData);
                $this->afterImportRecord($record, $_recordData);
            } else {
                $_result['results']->addRecord($record);
            }
            
            $_result['totalcount']++;
            
        } else {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
            throw new Tinebase_Exception_Record_Validation('Imported record is invalid.');
        }
    }
    /**
     * do conversions
     * -> sanitize account_id
     *
     * @param array $_data
     * @return array
     */
    protected function _doConversions($_data)
    {
        $result = parent::_doConversions($_data);
        
        return $result;
    }    
}