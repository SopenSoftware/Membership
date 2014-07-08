<?php
class Membership_Controller_SoMemberFeeProgress extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_SoMemberFeeProgress();
		$this->_modelName = 'Membership_Model_SoMemberFeeProgress';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->somembers : new Zend_Config(array());
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

	public function getEmptySoMemberFeeProgress(){
		$emptyOrder = new Membership_Model_SoMemberFeeProgress(null,true);
		return $emptyOrder;
	}
	
	public function getForMemberByYear($memberId, $year){
		return 
			$this->_backend->getByPropertySet(
				array(
					'member_id' => $memberId,
					'fee_year' => $year
					),
				false, 	// no deleted
				false 	// not a single result: deliver RecordSet
		);
	}
	
	public function getMemberIdByFeeProgressId($feeProgressId){
		return $this->_backend->getFieldByProperty('member_id', $feeProgressId, 'id');
	}
	
	/**
	 * 
	 * Check whether feeProgress is the first one to be created
	 */
	public function isFirstToCreate(){
		try{
			$recordSetfeeProgress = $this->_backend->getMultipleByProperty($memberId, 'member_id');
			if(count($recordSetfeeProgress) == 0){
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return true;
		}
	}
	
	public function decidePayOpenItem(Billing_Model_Payment $payment, Billing_Model_OpenItem $openItem){
		$receiptId = $openItem->getForeignIdBreakNull('receipt_id');
		if($receiptId){
			try{
				$feeProgress = $this->_backend->getByProperty($receiptId, 'invoice_receipt_id');
				
				$convertState = array(
					'OPEN' => 'TOBEPAYED',
					'PARTLYOPEN' => 'PARTLYPAYED',
					'DONE' => 'PAYED'
				);
				
				$openItemState = $openItem->__get('state');
				
				if(array_key_exists($openItemState, $convertState)){
					$feeProgress->__set('payment_state', $convertState[$openItemState]);
					$this->update($feeProgress);
				}
				
			}catch(Exception $e){
				// fail silently: means no fee progress for given invoice
			}
		}
	}
	
	/*protected function _inspectCreate(Tinebase_Record_Interface $_record)
	{
		$feeProgressExtId = $_record->__get('fee_progress_ext_id');
		if(is_array($feeProgressExtId)){
			$feeProgressExt = new Membership_Model_SoMemberFeeProgressExt($feeProgressExtId);
			if($feeProgressExt->__get('id')==0){
				$feeProgressExt = Membership_Controller_SoMemberFeeProgressExt::getInstance()->create($feeProgressExt);
			}
			$feeProgressExtId = $feeProgressExt->__get('id');
		}else{
			$feeProgressExtId = null;
		}
		$_record->__set('fee_progress_ext_id',$feeProgressExtId);
	}
	
	protected function _inspectUpdate(Tinebase_Record_Interface $_record)
	{
		$feeProgressExtId = $_record->__get('fee_progress_ext_id');
		if(is_array($feeProgressExtId)){
			$feeProgressExt = new Membership_Model_SoMemberFeeProgressExt($feeProgressExtId);
			if($feeProgressExt->__get('id')==0){
				$feeProgressExt = Membership_Controller_SoMemberFeeProgressExt::getInstance()->create($feeProgressExt);
			}else{
				$feeProgressExt = Membership_Controller_SoMemberFeeProgressExt::getInstance()->update($feeProgressExt);
			}
			$feeProgressExtId = $feeProgressExt->__get('id');
		}else{
			$feeProgressExtId = null;
		}
		$_record->__set('fee_progress_ext_id',$feeProgressExtId);		
	}*/
}
?>