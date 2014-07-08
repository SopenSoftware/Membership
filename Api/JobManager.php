<?php
class Membership_Api_JobManager{
	protected $jobController = null;
	protected $applicationName = 'Membership';
	protected $modelName = null;
	protected $currentAccount = null;
	protected $jobs = array();
	protected $job = null;
	
	protected $updateInterval = 20; // 100/20 = 5 every 5% step will trigger a job update
	
	private function __construct() {
		$this->jobController = Membership_Controller_Job::getInstance();
		$this->actionHistoryController = Membership_Controller_ActionHistory::getInstance();
		
		$this->modelName = 'Membership_Model_Job';
		$this->currentAccount = Tinebase_Core::getUser();
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
	
	private function isNonCountAction(Membership_Model_ActionHistory $actionHistory){
		return in_array($actionHistory->getAction()->getId(), array(
			Membership_Controller_Action::BILLPARENTMEMBER
		));
	}
	
	public function hasJob(){
		return !is_null($this->job);
	}
	
	public function getJobId(){
		if($this->job){
			return $this->job->getId();
		}else{
			return null;	
		}
	}
	
	public function getJob(){
		return $this->job;
	}
	
	public function requestRuntimeJob($category, $name1=null, $name2=null, $data = null){
		return $this->createRuntimeJob($category, $name1, $name2, $data);
	}
	
	public function requestSchedulerJob($category, $name1=null, $name2=null, $data = null){
		return $this->createSchedulerJob($category, $name1, $name2, $data);
	}	
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $config
	 */
	public function createRuntimeJob($category, $name1=null, $name2=null, $data = null){
		$this->job = $this->jobController->createRuntimeJob($category, $name1, $name2, $data);
		return $this->job;	
	}
	
	public function loadJob($jobId){
		$this->job = $this->jobController->get($jobId);
		return $this->job;
	}
	
	public function runJob($jobId, $name1=null, $name2=null, $data = null){
		try{
			$this->job = $this->jobController->get($jobId);
			if(!$this->job->__get('job_name1')){
				$this->job->__set('job_name1', $name1);
			}
			if(!$this->job->__get('job_name2')){
				$this->job->__set('job_name2', $name2);
			}
			$this->startJob();
			$data = $this->job->getData();
			$category = $this->job->__get('job_category');
			
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: run job (data: '.print_r($this->job, true).')');
			
			switch($category){
				case 'FEEPROGRESS':
				case 'FEEINVOICE':
				case 'FEEINVOICECURRENT':
					$class = $data['class'];
					$method = $data['method'];
					$filters = $data['filters'];
					$feeYear = $data['feeYear'];
					$action = $data['action'];
					$dueDate = new Zend_Date($data['dueDate']);
					
					$class::getInstance()->{$method}($filters, $feeYear, $action, $dueDate);
					return $this->job;
					
					break;
					
				case 'PRINT':
					Membership_Controller_SoMember::getInstance()->printJob($this->job);
					return $this->job;
					
					break;
					
				case 'DUETASKS':
					Membership_Controller_SoMember::getInstance()->execDueTasks($this->job);
					return $this->job;
					
					break;
			
				case 'MANUALEXPORT':
					$filename = CSopen::instance()->getCustomerPath().'/conf/logs/mcimport.log';
					Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' JobManager: run manual export (logfile: '.$filename.')');
					
					Membership_Controller_Export::getInstance()->runJobExportAsCsv($this->job);
					return $this->job;
			}
			Tinebase_Core::getLogger()->notice(__METHOD__ . '::' . __LINE__ . ' JobManager: no job category found');
			throw new Exception('No Job Category found');
		}catch(Exception $e){
			$this->finishError(
				$e->getMessage().' '.
				$e->getFile(). ' '.
				$e->getLine(). ' '.
				$e->getTraceAsString()
			);
			return $this->job;
		}
	}
	
	public function createSchedulerJob($category, $name1=null, $name2=null, $data = null){
		$this->job = $this->jobController->createSchedulerJob($category, $name1, $name2, $data);
		return $this->job;
	}
	
	public function startJob(){
		$this->job->start();
		$this->job = $this->jobController->update($this->job);
		return $this->job;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $taskCount
	 */
	public function setTaskCount($taskCount){
		$this->job->__set('task_count', $taskCount);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $tasksPart
	 */
	public function setUpdateInterval($tasksPart){
		$this->updateInterval = $tasksPart;
	}
	
	public function getUpdateInterval(){
		return $this->updateInterval;
	}
	
	public function getTaskCount(){
		return $this->job->__get('task_count');
	}
	
	public function getDoneTaskCount(){
		return $this->job->__get('tasks_done');
	}
	
	public function notifyTaskDone( $tasks = 1 ){
		if(!$this->hasJob()){
			return;
		}
		$this->job->__set('tasks_done', $this->job->__get('tasks_done') + $tasks );
		$this->updatePercentage();
		$this->checkTriggerUpdate();
	}
	
	public function notifyTaskDoneOk( $tasks = 1 ){
		if(!$this->hasJob()){
			return;
		}
		$this->job->__set('tasks_done', $this->job->__get('tasks_done') + $tasks );
		$this->countOk();
		$this->updatePercentage();
		$this->checkTriggerUpdate();
	}
	
	public function notifyTaskDoneError( $tasks = 1 ){
		if(!$this->hasJob()){
			return;
		}
		$this->job->__set('tasks_done', $this->job->__get('tasks_done') + $tasks );
		$this->countError();
		$this->updatePercentage();
		$this->checkTriggerUpdate();
	}
	
	public function notifyTaskSkip( $tasks = 1 ){
		if(!$this->hasJob()){
			return;
		}
		$this->job->__set('tasks_done', $this->job->__get('tasks_done') + $tasks );
		$this->countSkip();
		$this->updatePercentage();
		$this->checkTriggerUpdate();
	}
	
	public function getTaskCalculationPart(){
		return floor($this->getTaskCount()/$this->getUpdateInterval());
	}
	
	public function mustUpdate(){
		if($this->getTaskCalculationPart() == 0){
			return false;
		}
		return $this->getTaskCount % $this->getTaskCalculationPart() == 0;
	}
	
	public function checkTriggerUpdate(){
		if($this->mustUpdate()){
			$this->job = $this->jobController->update($this->job);
		}
	}
	
	public function updatePercentage(){
		$this->job->__set('process_percentage', min(floor(($this->getDoneTaskCount()/$this->getTaskCount())*100),100));
	}
		
	public function completeTask( Membership_Model_ActionHistory $actionHistory){
		$actionHistory->__set('job_id', $this->job->getId());
		if($actionHistory->__get('action_state') == 'ERROR'){
			$this->job->setResultError();
			if(!$this->isNonCountAction($actionHistory)){
				$this->countError();
			}
		}else{
			$this->job->setResultOk();
			if(!$this->isNonCountAction($actionHistory)){
				$this->countOk();
			}
		}
		$this->notifyTaskDone();
		$this->jobController->update($this->job);
	}
	
	public function countOk($count=1){
		$this->job->__set('ok_count', $this->job->__get('ok_count')+$count);
	}
	
	public function countError($count=1){
		$this->job->__set('error_count', $this->job->__get('error_count')+$count);
	}
	
	public function countSkip($count=1){
		$this->job->__set('skip_count', $this->job->__get('skip_count')+$count);
	}
	
	public function finish(){
		$this->job->finish();
		if($this->job->__get('error_count')==0){
			$this->job->setResultOk();
		}else{
			$this->job->setResultError();
		}
		$this->job->__set('process_percentage',100);
		$this->job = $this->jobController->update($this->job);
		//$this->job = null;
	}
	
	public function finishError($errorInfo){
		$this->job->finish();
		$this->job->setResultError();
		$this->job->__set('error_info', $errorInfo);
		$this->job = $this->jobController->update($this->job);
		//$this->job = null;
	}
	public function cancel(){
		
	}
	
	public function updateAddJob($name1 = '', $name2 = ''){
		$this->job->__set('job_name1', $this->job->__get('job_name1') . $name1);
		$this->job->__set('job_name2', $this->job->__get('job_name2') . $name2);
		$this->job = $this->jobController->update($this->job);
	}
	
	public function updateJobFromArray(array $jobData){
		$this->job->setFromArray($jobData);
		$this->job = $this->jobController->update($this->job);
	}
	
	public function jobAddData($key, $value){
		$data = $this->job->getData();
		$data[$key] = $value;
		$this->job->setData($data);
		$this->job = $this->jobController->update($this->job);
	}
	
	public function update(){
		$this->job = $this->jobController->update($this->job);
	}
}