<?php 
/**
 * 
 * Holds custom contact functions
 * @author hhartl
 *
 */
class Membership_Custom_SoMember extends Membership_Custom_AbstractSoMember{
	/**
	 * 
	 * Inspect controller create
	 * @param Tinebase_Model_SoMember $record
	 */
	public static function inspectCreate(Membership_Model_SoMember $record){
		
	}
	
	/**
	 * 
	 * Called immediately after a new contact is created and has an ID (pk) already
	 * @param Tinebase_Model_SoMember $record
	 */
	public static function afterCreate(Membership_Model_SoMember $record){
		// do nothing
	}
	
	/**
	 * 
	 * Inspect controller update
	 * @param Tinebase_Model_SoMember $record
	 */
	public static function inspectUpdate(Membership_Model_SoMember $record){
		// standard behaviour
		// membership is not terminated at this moment, but requested at future data change, defined as task
		// -> open ActionHistory is a future task to be processed by job scheduler
		if($record->__get('termination_datetime')){
			$feeToDate = new Zend_Date($record->__get('termination_datetime'));
			$feeToDate->setMonth(12);
			$feeToDate->setDay(31);
			$record->__set('fee_to_date', $feeToDate);
			
			Membership_Controller_SoMember::getInstance()->requestMemberDataChange($record->getId(), array(), $feeToDate, 'Termination');
			
		}
	}
	
	/**
	 * 
	 * Called immediately after a contact is updated
	 * @param Tinebase_Model_SoMember $record
	 */
	public static function afterUpdate(Membership_Model_SoMember $record){
		// do nothing
	}
	
	public static function inspectPrintMember(array $objects, array &$memDta, array &$summarize){
		// do nothing
	}
	
	public static function addAdditionalDataPrintMember(&$summarize, &$data){
		// do nothing
	}
	
	public static function isReceiptToPrint($receipt, $member){
		return true;
	}
	
	public static function regularDonationMemberTerminationAlert($memberships){
		$foundTerminated = false;				
		foreach($memberships as $membership){
    		if($membership->__get('membership_status') == 'TERMINATED'){
    			$foundTerminated = true;
    		}
    	}
    	return $foundTerminated;
	}
	
	public static function onSetAccountBankTransferDetected($objEvent){

		$contactId = $objEvent->getDebitor()->getForeignId('contact_id');
		
		$memships = Membership_Controller_SoMember::getInstance()->getByContactId($contactId);
		
		if($memships){
			foreach($memships as $member){
				if(strpos($member->getForeignId('fee_payment_method'),'DEBIT')){
					$bankAccount = $objEvent->getBankAccount();
					$mBankAccount = Billing_Api_BankAccount::getFromSoMember($member);
					if($mBankAccount->equals($bankAccount)){
						$member->__set('fee_payment_method', 'BANKTRANSFER');
						Membership_Controller_SoMember::getInstance()->update($member);
					}
			
					
				}
			}
		}
		
	}
}
?>