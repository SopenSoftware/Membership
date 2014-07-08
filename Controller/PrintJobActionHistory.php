<?php 
use org\sopen\app\api\filesystem\storage\StorageException;
/**
 * 
 * Class for printing memberships (certificates, cards)
 * 
 * @author hhartl
 *
 */
class Membership_Controller_PrintJobActionHistory extends Tinebase_Controller_Abstract{
	
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
	
	public function printResult($processArray, $exportProcessor){
		$this->processArray = $processArray;
		$this->exportProcessor = $exportProcessor;
		$this->runTransaction();
	}
	
	private function createFeeGroupCalculation(){
		$this->templateId = $this->exportProcessor->getMembershipExportDefinition()->__get('output_template_id');
		//foreach($this->processArray as $item){
			$this->createDoc($this->processArray);
		//}

		$this->finalizeDocs();
	}
	
	private function getItemHash($item){
		return md5(serialize($item));
	}
	
	private function createDoc($data){
		$aData = array(
			'DATE' => strftime('%d.%m.%Y %H:%M:%S'),
			'REPEATABLE_DYN_TABLE' => $data
		);
		
		$tempInFile = $this->tempFilePath . md5(serialize($item).microtime()) . '_in.odt';
		$tempOutFile = $this->tempFilePath . md5(serialize($item).microtime()) . '_out.odt';
		$this->templateController->renderTemplateToFile($this->templateId, $aData, $tempInFile, $tempOutFile, array());
		$itemHash = $this->getItemHash($item);
		$this->map[] = $itemHash;
		
		$this->count++;
		// move file into storage: cleans up tempfile at once
		$this->printJobStorage->moveIn( $tempOutFile,"//in/$itemHash/a/odt/vlist");
	}
	
	private function finalizeDocs(){
		$inputFiles = array();
		foreach($this->map as $itemHash){
			if($this->printJobStorage->fileExists("//in/$itemHash/a/odt/vlist")){
				$inFile = $this->printJobStorage->resolvePath( "//in/$itemHash/a/odt/vlist" );
				$bufferItemHash = $itemHash;
				$outFile = $this->printJobStorage->getCreateIfNotExist( "//convert/$itemHash/a/pdf/vlist" );
				$inputFiles[] = $outFile;
				$this->pdfServer->convertDocumentToPdf($inFile, $outFile);
			}
		}
		
		$outputFile = $this->printJobStorage->getCreateIfNotExist( "//out/result/merge/pdf/final" );
		if(count($inputFiles)>1){
			//print_r($inputFiles);
			$this->pdfServer->mergePdfFiles($inputFiles, $outputFile);
		}else{
			//$inputFile = current($inputFileSPaths);
			$this->printJobStorage->copy("//convert/$bufferItemHash/a/pdf/vlist", "//out/result/merge/pdf/final" );
		}
	}
	
	private function outputResult(){
		$printFileName = $this->exportProcessor->getExportDefinition()->__get('name').'-'.strftime('%d.%m.%Y %H:%M:%S').'.pdf';
		header("Pragma: public");
        header("Cache-Control: max-age=0");
        header('Content-Disposition: attachment; filename='.$printFileName);
        header("Content-Description: Pdf Datei");  
      	header('Content-Type: application/pdf');
		echo $this->printJobStorage->getFileContent("//out/result/merge/pdf/final");

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
			
			$this->createFeeGroupCalculation();
			
			// make db changes final
			$tm->commitTransaction($tId);
			
			// output the result
			if($this->count>0){
				$this->outputResult();
			}else{
				$this->outputNone();
			}
		}catch(Exception $e){
			echo $e->__toString();
			$tm->rollback($tId);
		}
	}
}
?>