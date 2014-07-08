<?php 
/**
 * 
 * Class for exporting memberships
 * 
 * @author hhartl
 *
 */
class Membership_Controller_Export extends Tinebase_Controller_Abstract{
	const TYPE_CLUBMEMBERSLIST 			= 'clubmemberslist';
	
	const PROCESS_CLUBMEMBERSLIST = 'clubmemberslist';
	
	/**
	 * config of courses
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;
	private $filters = null;
	private $aFilters = array();
	private $forFeeProgress = false;

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
	
	public function setFilters($filters){
		if(!is_array($filters)){
			$filters = Zend_Json::decode($filters);
		}
		$this->filters = new Membership_Model_SoMemberFilter($filters,'AND');
		$this->aFilters = $filters;
	}
	
	public function exportAsCsv($filters, $exportClassName = 'Membership_Export_Csv'){
		try{
			$this->setFilters($filters);
			$export = new $exportClassName();
			$outFile = $export->generate($this->filters);
			$contentType = $export->getDownloadContentType();
			$filename = 'Members.csv';
	        header("Pragma: public");
	        header("Cache-Control: max-age=0");
	        header('Content-Disposition: attachment; filename=' . $filename);
	        header("Content-Description: csv File");  
	        header("Content-type: $contentType");
	        readfile($outFile);
	        unlink($outFile);
		}catch(Exception $e){
			echo $e->__toString();
		}
	}
	
	public function runJobExportAsCsv($job){
		//$filters, $exportClassName = 'Membership_Export_Csv'
		try{
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: delegate to Membership_Controller_Export(job: '.$job.')');
			
			set_time_limit(0);
			$data = $job->getData();
			$filters = $data['filters'];
			
			$exportClassName = $data['exportClassName'];
			$forFeeProgress = $data['forFeeProgress'];
			
			$this->setFilters($filters);
			$this->forFeeProgress = Zend_Json::decode($forFeeProgress);

			
			$export = new $exportClassName();
			if(method_exists($export, 'setForFeeProgress')){
				$export->setForFeeProgress($this->forFeeProgress);
			}
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: created Membership_Controller_Export(export: '.print_r($export, true).')');
			
			$outFile = $export->generate($this->aFilters);
			
			$contentType = $export->getDownloadContentType();
			
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: export finished Membership_Controller_Export(outfile: '.$outFile.')');
			if($export->hasErrors()){
				Membership_Api_JobManager::getInstance()->jobAddData('exportErrorFileName',$export->getErrorFileName());
			}
			Membership_Api_JobManager::getInstance()->jobAddData('exportFileName',$outFile);
			Membership_Api_JobManager::getInstance()->jobAddData('exportFileContentType',$contentType);
			Membership_Api_JobManager::getInstance()->jobAddData('downloadFileName',$job->__get('job_name1').'-'.strftime('%d%m%Y-%H%S%M').'.csv');
			
			Membership_Api_JobManager::getInstance()->finish();
		}catch(Exception $e){
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: export error('.$e->__toString().')');
			if(Membership_Api_JobManager::getInstance()->hasJob()){
				Membership_Api_JobManager::getInstance()->finishError(
					$e->getMessage().' '.
					$e->getFile(). ' '.
					$e->getLine(). ' '.
					$e->getTraceAsString()
				);
			}
			throw $e;
		}
	}
	
	public function downloadJobExportFile($jobId){
		$job = Membership_Api_JobManager::getInstance()->loadJob($jobId);
		$data = $job->getData();
		$exportFileName = $data['exportFileName'];
		$downloadFileName = $data['downloadFileName'];
		$contentType = $data['exportFileContentType'];
		
		header("Pragma: public");
        header("Cache-Control: max-age=0");
        header('Content-Disposition: attachment; filename=' . $downloadFileName);
        header("Content-Description: csv File");  
        header("Content-type: $contentType");
        readfile($exportFileName);
	}
	
	public function downloadJobErrorFile($jobId){
		$job = Membership_Api_JobManager::getInstance()->loadJob($jobId);
		$data = $job->getData();
		$errorFileName = $data['exportErrorFileName'];
		$downloadFileName = 'ERROR-'.$data['downloadFileName'];
		$contentType = $data['exportFileContentType'];
		
		header("Pragma: public");
        header("Cache-Control: max-age=0");
        header('Content-Disposition: attachment; filename=' . $downloadFileName);
        header("Content-Description: csv File");  
        header("Content-type: $contentType");
        readfile($errorFileName);
	}
}
?>