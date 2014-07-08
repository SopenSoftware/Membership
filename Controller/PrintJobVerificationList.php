<?php 
use org\sopen\app\api\filesystem\storage\StorageException;
/**
 * 
 * Class for printing memberships (certificates, cards)
 * 
 * @author hhartl
 *
 */
class Membership_Controller_PrintJobVerificationList extends Tinebase_Controller_Abstract{
	
	/**
	 * config of courses
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;
	private $pdfServer = null;
	private $printJobStorage = null;
	private $map = array();
	private $count = 0;
	
	/**
	 * the constructor
	 *
	 * don't use the constructor. use the singleton
	 */
	private function __construct() {
		$this->_applicationName = 'Membership';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_membershipController = Membership_Controller_SoMember::getInstance();
		$this->_doContainerACLChecks = FALSE;
	}

	private static $_instance = NULL;
	private $jobId = null;
	
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
	
	public function getPrintJobStorage(){
		return $this->printJobStorage;
	}
	public function setJobId($jobId){
		$this->jobId = $jobId;	
	}
	
	public function printVerificationList($processArray, $assocIds, $parentMemberIds){
		$this->processArray = $processArray;
		
		$pagination = new Tinebase_Model_Pagination(array(
			'sort' => 'association_nr'
		));
		$filters = array();
		$filters[] = array(
    		'field' => 'id',
    		'operator' => 'in',
    		'value' => $assocIds
	    );
	    
		$filter = new Membership_Model_AssociationFilter($filters, 'AND');
		$this->assocIds = Membership_Controller_Association::getInstance()->search(
			$filter,
			$pagination,
			false,
			true
		);
		
		$pagination = new Tinebase_Model_Pagination(array(
			'sort' => 'member_nr'
		));
		$filters = array();
		$filters[] = array(
    		'field' => 'id',
    		'operator' => 'in',
    		'value' => $parentMemberIds
	    );
	    
		$filter = new Membership_Model_SoMemberFilter($filters, 'AND');
		$this->parentMemberIds = Membership_Controller_SoMember::getInstance()->search(
			$filter,
			$pagination,
			false,
			true
		);

		$this->runTransaction();
	}
	
	private function createVerificationList(){
		$this->templateId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::TEMPLATE_VERIFICATION);
		foreach($this->assocIds as $assocId){
			
			$association = Membership_Controller_Association::getInstance()->get($assocId);
			
			foreach($this->parentMemberIds as $parentMemberId){
				
				$parentMember = Membership_Controller_SoMember::getInstance()->get($parentMemberId);
				$this->createDoc($association, $parentMember);
				
			}
		}

		$this->finalizeDocs();
	}
	
	private function createDoc($association, $parentMember){
		$assocId = $association->getId();
		$parentMemberId = $parentMember->getId();
		
		$memberIds = $this->processArray[$assocId][$parentMemberId];
//		$pagination = new Tinebase_Model_Pagination(array(
//			'sort' => 'member_nr'
//		));
//		$filters = array();
//		$filters[] = array(
//    		'field' => 'id',
//    		'operator' => 'in',
//    		'value' => $memberIds
//	    );
//	    
//		$filter = new Membership_Model_SoMemberFilter($filters, 'AND');
//		$memberIds = Membership_Controller_SoMember::getInstance()->search(
//			$filter,
//			$pagination,
//			false,
//			true
//		);
		$aMem = array();
		$count = 0;
		$totalMainAssocFee =0;
		$totalClubFee =0;
		$totalAdditionalFee =0;
		$totalDonation =0;
		$membersTotal = count($memberIds);
		if(Tinebase_Core::isLogLevel(Zend_Log::DEBUG)){
			Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Creating verification list for count: '.$membersTotal);
		}
		foreach($memberIds as $memberId){
			$member = Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
			$contact = $member->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
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
			$totalMainAssocFee += $mainAssocFee;
			$totalClubFee += $clubFee;
			$totalAdditionalFee += $additionalFee;
			$totalDonation += $donation;
			
			$feeGroup = $member->getForeignRecordBreakNull('fee_group_id', Membership_Controller_FeeGroup::getInstance());
			$feeGroupKey = null;
			if($feeGroup){
				$feeGroupKey = $feeGroup->__get('key');
			}
			$aMem[$contact->__get('n_fileas')] = array(
				'NR' => $member->__get('member_nr'),
				'NAME' => $contact->__get('n_fileas'),
				'STREET' => $contact->__get('adr_one_street'),
				'LOCATION' => $contact->__get('adr_one_postalcode'). ' ' .$contact->__get('adr_one_locality'),
				'BIRTH' =>  \org\sopen\app\util\format\Date::format($contact->__get('bday')),
				'PAYMENT' => $member->getForeignRecord('fee_payment_method', Billing_Controller_PaymentMethod::getInstance())->__get('name'),
				'FEEGROUP' => $feeGroupKey,
				'MAINFEE' => \org\sopen\app\util\format\Currency::formatCurrency($mainAssocFee),
				'CLUBFEE' => \org\sopen\app\util\format\Currency::formatCurrency($clubFee),
				'ADDFEE' => \org\sopen\app\util\format\Currency::formatCurrency($additionalFee),
				'DONATION' => \org\sopen\app\util\format\Currency::formatCurrency($donation),
				'TOTAL' => \org\sopen\app\util\format\Currency::formatCurrency($mainAssocFee + $clubFee + $additionalFee + $donation),
				'AGE' => $member->__get('person_age'),
        		'MEMYEARS' => $member->__get('member_age'),
				'BEGIN' =>  \org\sopen\app\util\format\Date::format($member->__get('begin_datetime')),
				'END' =>  ($member->__get('termination_datetime'))?\org\sopen\app\util\format\Date::format($member->__get('termination_datetime')):''
			);
			
			// log each member being part of the list
//			if(Tinebase_Core::isLogLevel(Zend_Log::DEBUG)){
//				Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($aMem[$count], true));
//			}
            
			$count++;         
		}
		
		ksort($aMem);

		$parentContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());

		$totalFee = $totalMainAssocFee + $totalClubFee + $totalAdditionalFee + $totalDonation;
		
		if($this->jobId){
			try{
				// action history of parent billing process
				$actionHistory = Membership_Controller_ActionHistory::getInstance()->getByJobAndActionId(
					$this->jobId, 
					Membership_Controller_Action::BILLMEMBER	
				);
				$receiptId = $actionHistory->getForeignId('receipt_id');
				if($receiptId){
					$receipt = Billing_Controller_Receipt::getInstance()->get($receiptId);
					$invoiceNr = $receipt->__get('invoice_nr');
					$invoiceDate =  \org\sopen\app\util\format\Date::format($receipt->__get('invoice_date'));
					$invoiceTotalNetto = \org\sopen\app\util\format\Currency::formatCurrency($receipt->__get('total_netto'));
					$invoiceTotalBrutto = \org\sopen\app\util\format\Currency::formatCurrency($receipt->__get('total_brutto'));
				}
			}catch(Exception $e){
				$invoiceNr = $invoiceDate = $invoiceTotalNetto = $invoiceTotalBrutto = null;
			}
		}
		
		$aData = array(
			'ASSOC_NR' => $association->__get('association_nr'),
			'CLUBNAME' => $parentContact->__get('org_name'),
			'DATE' => strftime('%d.%m.%Y %H:%M:%S'),
			'INVOICE_NR' => $invoiceNr,
			'INVOICE_DATE' => $invoiceDate,
			'TOTAL_NETTO' => $invoiceTotalNetto,
			'TOTAL_BRUTTO' => $invoiceTotalBrutto, 
			'POS_TABLE' => $aMem,
			'members_total' => $membersTotal,
			'main_total' => \org\sopen\app\util\format\Currency::formatCurrency($totalMainAssocFee),
			'cl_fee_total' => \org\sopen\app\util\format\Currency::formatCurrency($totalClubFee),
			'add_fee_total' => \org\sopen\app\util\format\Currency::formatCurrency($totalAdditionalFee),
			'donation_total' => \org\sopen\app\util\format\Currency::formatCurrency($totalDonation),
			'total_fee' => \org\sopen\app\util\format\Currency::formatCurrency($totalFee)
		);
		$tempInFile = $this->tempFilePath . md5(serialize($parentMember).microtime()) . '_in.odt';
		$tempOutFile = $this->tempFilePath . md5(serialize($parentMember).microtime()) . '_out.odt';
		if(Tinebase_Core::isLogLevel(Zend_Log::DEBUG)){
			Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . 
				print_r($parentContact->toArray(), true). ' ' .
				"tempInFile: $tempInFile ".
				"tempOutFile: $tempOutFile ");
		}
		$this->templateController->renderTemplateToFile($this->templateId, $aData, $tempInFile, $tempOutFile, array());
		
		// move file into storage: cleans up tempfile at once
		$this->printJobStorage->moveIn( $tempOutFile,"//in/$assocId/$parentMemberId/odt/vlist");
			
	
	}
	
	private function finalizeDocs(){
		$inputFiles = array();
		foreach($this->processArray as $assocId => $aParents){
			foreach($aParents as $parentMemberId => $aMembers){
				if($this->printJobStorage->fileExists("//in/$assocId/$parentMemberId/odt/vlist")){
					$inFile = $this->printJobStorage->resolvePath( "//in/$assocId/$parentMemberId/odt/vlist" );
					$bufferAssocId = $assocId;
					$outFile = $this->printJobStorage->getCreateIfNotExist( "//convert/$assocId/$parentMemberId/pdf/vlist" );
					$inputFiles[] = $outFile;
					$this->pdfServer->convertDocumentToPdf($inFile, $outFile);
				}
			}
		}
		
		$outputFile = $this->printJobStorage->getCreateIfNotExist( "//out/result/merge/pdf/final" );
		if(count($inputFiles)>1){
			$this->pdfServer->mergePdfFiles($inputFiles, $outputFile);
		}else{
			//$inputFile = current($inputFileSPaths);
			$this->printJobStorage->copy("//convert/$bufferAssocId/$parentMemberId/pdf/vlist", "//out/result/merge/pdf/final" );
		}
	}
	
	private function outputResult(){
		header('Content-Type: application/pdf');
		// get content from storage and close it (temporary storage gets deleted by this operation)
		$content = $this->printJobStorage->getFileContent("//out/result/merge/pdf/final");
		//$this->printJobStorage->close();
		echo $content;
	}
	
	private function outputNone(){
		//$this->printJobStorage->close();
		echo 'Keine Dokumente fällig zum Druck!';
	}
	
	private function runTransaction(){
		try{
			$config = \Tinebase_Config::getInstance()->getConfig('pdfserver', NULL, TRUE)->value;
			$storageConf = \Tinebase_Config::getInstance()->getConfig('printjobs', NULL, TRUE)->value;
			
    		$this->tempFilePath = CSopen::instance()->getCustomerPath().'/customize/data/documents/temp/';
			$this->templateController = DocManager_Controller_Template::getInstance();
			$db = Tinebase_Core::getDb();
			$tm = Tinebase_TransactionManager::getInstance();
			
			$this->pdfServer = org\sopen\app\api\pdf\server\PdfServer::getInstance($config)->
				setDocumentsTempPath(CSopen::instance()->getDocumentsTempPath());
			$this->printJobStorage =  org\sopen\app\api\filesystem\storage\TempFileProcessStorage::createNew(
				'printjobs', 
				$storageConf['storagepath']
			);

			$this->printJobStorage->addProcessLines(array('in','convert','out'));
			
			$tId = $tm->startTransaction($db);
			
			$this->createVerificationList();
			
			// create the multipage output from single page input files
			if($this->count>0){
				$this->createResult();
			}
			// make db changes final
			$tm->commitTransaction($tId);
			
			// output the result
//			if($this->count>0){
//				$this->outputResult();
//			}else{
//				$this->outputNone();
//			}
		}catch(Exception $e){
			echo $e->__toString();
			$tm->rollback($tId);
		}
	}
}
?>