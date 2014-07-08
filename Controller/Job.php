<?php
class Membership_Controller_Job extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_Job();
		$this->_modelName = 'Membership_Model_Job';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->somembers : new Zend_Config(array());
	}

	private static $_instance = NULL;

	/**
	 * the singleton pattern
	 *
	 * @return SoMembership_Controller_SoEvent
	 */
	public static function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getEmptyJob(){
		$emptyOrder = new Membership_Model_Job(null,true);
		return $emptyOrder;
	}
	
	public function createRuntimeJob( $category, $name1, $name2, $data ){
		$job = Membership_Model_Job::createRuntimeJob( $this->_currentAccount->getId(), $category, $name1, $name2, $data );
		return $this->create($job);
	}
	
	public function createSchedulerJob( $category, $name1, $name2, $data ){
		$job = Membership_Model_Job::createSchedulerJob(  $this->_currentAccount->getId(),$category, $name1, $name2, $data );
		return $this->create($job);
	}
	
    protected function _inspectCreate(Tinebase_Record_Interface $_record)
    {
    	$_record->__set('job_nr', Tinebase_NumberBase_Controller::getInstance()->getNextNumber('membership_job_nr'));
    }
    
    protected function _inspectUpdate(Tinebase_Record_Interface $_record)
    {
    	$_record->__set('modified_datetime', new Zend_Date());
    }
	/**
	 * 
	 * Purge all entries from printjobs which are not in use by a job
	 * @todo additionaly build a function which allows to delete printjobstorage entries of
	 * selected jobs, which are not needed anymore. The user has to decide.
	 */
    public function purgePrintJobStorage(){
    	
    	set_time_limit(0);
    	// get all printjob storage ids which are in use
    	// TODO: make a function which allows cleanup of single Jobs as well
    	
    	$printjobFilters = array(
    		'field' => 'job_category',
    		'operator' => 'equals',
    		'value' => Membership_Model_Job::CATEGORY_PRINT
    	);
    	
    	$printJobFilter = new Membership_Model_JobFilter($printJobFilters, 'AND');
    	
    	$printJobIds = $this->search($printJobFilter, null, false, true);
    	
    	$printJobStorageIds = array();
    	
    	// grab printjobs
    	foreach($printJobIds as $printJobId){
    		$printJob = $this->get($printJobId);
    		
    		if($printJob->hasDataItem('printJobStorageId')){
    			$printJobStorageId = $printJob->getDataItem('printJobStorageId');
    			$printJobStorageIds[] = $printJobStorageId;
    		}
    	}
    	
    	\SPDirectory::cleanup();
    	
    	// grab exports -> located in tempfile directory (very bad hack)
    	$manualExportFilters = array(
    		'field' => 'job_category',
    		'operator' => 'equals',
    		'value' => Membership_Model_Job::MANUALEXPORT
    	);
    	
    	$manualExportFiles = array();
    	$manualExportFilter = new Membership_Model_JobFilter($manualExportFilters, 'AND');
    	
    	$manualExportIds = $this->search($manualExportFilter, null, false, true);
    	
    	// grab printjobs
    	foreach($manualExportIds as $manualExportId){
    		$manualExport = $this->get($manualExportId);
    		
    		if($manualExport->hasDataItem('exportFileName')){
    			$manualExportFile = $manualExport->getDataItem('exportFileName');
    			$manualExportFiles[] = SPDirectory::replaceBackslashes($manualExportFile);
    		}
    	}
    	
    }
	
    /**
     * get attender Jobs
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderJob
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllJobs($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
    public function getJobsAsSimpleArray(){
    	$rows = $this->getAllJobs();
    	if($rows){
    		$aResult = array();
	    	$rows->translate();
	    	
	    	foreach($rows as $row){
	    		$aResult[] = array(
	    			$row->getId(),
	    			$row->__get('name')
	    		);
	    	}
	    	return $aResult;
    	}
    	// return empty arra
    	return array();
    }
}
?>