<?php
/**
 *
 * Controller OpenItem
 *
 * @copyright sopenGmbH Herzogenrath, www.sopen.de (2011)
 * @author hhartl <hhartl@sopen.de>
 *
 */
class Membership_Controller_OpenItem extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_OpenItem();
		$this->_modelName = 'Membership_Model_OpenItem';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->billing) ? Tinebase_Core::getConfig()->billing : new Zend_Config(array());
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

	/**
	 * Get empty record
	 * @return Billing_Model_OpenItem
	 */
	public function getEmptyOpenItem(){
		$emptyBrevet = new Billing_Model_OpenItem(null,true);
		return $emptyOpenItem;
	}
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $receiptId
	 */
	public function getByReceiptId($receiptId){
		return $this->_backend->getByProperty($receiptId, 'receipt_id');
	}

	/**
	 * (non-PHPdoc)
	 * @see Tinebase_Controller_Record_Abstract::_inspectCreate()
	 */
	protected function _inspectCreate(Tinebase_Record_Interface $_record)
	{
		$order = $_record->getForeignRecord('order_id', Billing_Controller_Order::getInstance());
		
		$_record->__set('erp_context_id', $order->__get('erp_context_id'));
		
		$_record->__set('op_nr', Tinebase_NumberBase_Controller::getInstance()->getNextNumber('op_nr'));
	}

	public function exportFibu($filter){
		 
	}

	public function directDebit($paymentTypeKeys, $filters){
		$db = Tinebase_Core::getDb();
		$tm = Tinebase_TransactionManager::getInstance();
		try{
			require_once 'Payment/DTA.php';
				
			if(!is_array($filters)){
				$filters = Zend_Json::decode($filters);
			}
				
			if(!is_array($paymentTypeKeys)){
				$paymentTypeKeys = Zend_Json::decode($paymentTypeKeys);
			}
			$filters[] = array(
	    			'field' => 'banking_exp_date',
	    			'operator' => 'isnull',
	    			'value' => ''
			);
			
			$rawFilters = $filters;
				
			$paymentTypeCount = count($paymentTypeKeys);
				
			$filters[] = array(
	    			'field' => 'payment_method_id',
	    			'operator' => 'equals',
	    			'value' => $paymentTypeKeys[1]
			);

			$filter1 = new Billing_Model_OpenItemFilter($filters, 'AND');
				
			$filter = new Tinebase_Model_Filter_FilterGroup(array(), 'OR');
			$filter->addFilterGroup($filter1);
				
				
			if($paymentTypeCount>1){
				unset($paymentTypeKeys[1]);
				foreach($paymentTypeKeys as $paymentTypeKey){
					$newFilters = $rawFilters;
					$newFilters[] = array(
		    			'field' => 'payment_method_id',
		    			'operator' => 'equals',
		    			'value' => $paymentTypeKey
					);
					$pFilterGroup = new Billing_Model_OpenItemFilter($newFilters, 'AND');
					$filter->addFilterGroup($pFilterGroup);
				}
			}
			
			// start transaction
			$tId = $tm->startTransaction($db);
			
			// count membership matching filters
			$openItems =  $this->search(
			$filter,
			new Tinebase_Model_Pagination(array('sort' => 'due_date', 'dir' => 'ASC'))
			);

			$tempFilePath = CSopen::instance()->getCustomerPath().'/customize/data/documents/temp/';
	   
			$mandators = \Tinebase_Config::getInstance()->getConfig('mandators', NULL, TRUE)->value;
			$mandator = $mandators[1]['bankdata'];
			$hash = md5(serialize($mandator).microtime());
			$dtaFile = new DTA(DTA_DEBIT);
			$dtaFile->setAccountFileSender(
			array(
			        "name"           => $mandator['account_holder'],
			        "bank_code"      => $mandator['bank_code'],
			        "account_number" => $mandator['account_number'],
			)
			);


			// 		create DTA file
			foreach($openItems as $openItem){

				// value
				$val = (float) $openItem->__get('total_brutto');

				if($val>0){
					$debitor = $openItem->getForeignRecordBreakNull('debitor_id', Billing_Controller_Debitor::getInstance());
					$contact = $debitor->getForeignRecordBreakNull('contact_id', Addressbook_Controller_Contact::getInstance());
					$receipt = $openItem->getForeignRecordBreakNull('receipt_id', Billing_Controller_Receipt::getInstance());
					$dtaFile->addExchange(
					array(
					        "name"          	=> $contact->__get('bank_account_name'),
				        	"bank_code"      	=> $contact->__get('bank_code'),
				        	"account_number" 	=> $contact->__get('bank_account_number')
					),
					(string)$val,                 // Amount of money.
					array(                  // Description of the transaction ("Verwendungszweck").
					        "Einzug Re.nr. ".$receipt->__get('invoice_nr'),
					        "NRW-Stiftung"
					        )
					        );
				}

				$openItem->__set('banking_exp_date', new Zend_Date());
				$this->update($openItem);
			}

			$dtaFile->saveFile($tempFilePath.'DTAUS0'.$hash);
			$meta = $dtaFile->getMetaData();
				
			$date	= strftime("%d.%m.%y", $meta["date"]);
			$execDate	=strftime("%d.%m.%y", $meta["exec_date"]);
			$count	=$meta["count"];
			$sumEUR	= $meta["sum_amounts"];
			$sumKto	=$meta["sum_accounts"];
			$sumBankCodes	= $meta["sum_bankcodes"];

			$sender	=$mandator['account_holder'];
			$senderBank	= $mandator['bank'];
			$senderBankCode	= $mandator['bank_code'];
			$senderAccount	=$mandator['account_number'];
				
			$handoutContent = "Datenträger-Begleitzettel
	Erstellungsdatum: $date 
	Ausführungsdatum: $execDate
	Anzahl der Lastschriften: $count
	Summe der Beträge in EUR: $sumEUR
	Kontrollsumme Kontonummern: $sumKto
	Kontrollsumme Bankleitzahlen: $sumBankCodes
	Auftraggeber: $sender
	Beauftragtes Bankinstitut: $senderBank
	Bankleitzahl: $senderBankCode
	Kontonummer: $senderAccount";

			$zip = new ZipArchive();
			$filename = "$tempFilePath/DTAUS0-$ogNr.zip";
				
			if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
				exit("cannot open <$filename>\n");
			}
				
			$zip->addFromString("begleitzettel.txt", $handoutContent);
			$zip->addFile($tempFilePath.'DTAUS0'.$hash, 'DTAUS0');
			$zip->close();

			header("Content-type: application/zip;\n");
			header("Content-Transfer-Encoding: binary");
			$len = filesize($filename);
			header("Content-Length: $len;\n");
			$outname="DTAUS0-$ogNr.zip";
			header("Content-Disposition: attachment; filename=\"$outname\";\n\n");
				
			readfile($filename);
				
			unlink($filename);
			
			$tm->commitTransaction($tId);
		}catch(Exception $e){
			$tm->rollback($tId);
		}
	}
}
?>