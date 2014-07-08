<?php 
use org\sopen\app\api\filesystem\storage\StorageException;
/**
 * 
 * Class for printing memberships (certificates, cards)
 * 
 * @author hhartl
 *
 */
class Membership_Controller_Print extends Tinebase_Controller_Abstract{
	const TYPE_CLUBMEMBERSLIST 	= 'clubmemberslist';
	
	const PROCESS_CLUBMEMBERSLIST = 'clubmemberslist';
	
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
	private $isPreview = false;
	private $filters = array();

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
	
	public function setPreview($preview){
		$this->isPreview = $preview;
	}
	
	public function setFilters($filters){
		$this->filters = Zend_Json::decode($filters);
	}
	
	public function printMemberList($filters, $preview = false){
		$this->setPreview($preview);
		$this->setFilters($filters);
		$this->runTransaction(self::PROCESS_CLUBMEMBERSLIST);
	}
	
	private function createClubMembersList(){
		//$this->printDateAttribute = null;
		$this->storageFileName = 'memberlist';
		$this->type = self::TYPE_CLUBMEMBERSLIST;
		$this->customDataFunction = 'getClubMemberListData';
		
		$filters = new Membership_Model_SoMemberFilter($this->filters, 'AND');

		$count = $this->_membershipController->searchCount($filters);
		if($count<=1000){
			$pagination = array('sort' => 'member_nr', 'dir' => 'ASC');
			$memberships =  $this->_membershipController->search($filters,new Tinebase_Model_Pagination($pagination));
		}else{
			echo "Die aktuelle Selektion umfasst $count Einträge und kann nicht in einem einzelnen Dokument ausgegeben werden.";
			exit;
		}
		$this->count = 1;
		$this->createMembershipsDoc($memberships);
		$this->finalizeDocs();
	}
	
	
	private function getData($aData, $replateTextBlocks){
		switch($this->type){
			case self::TYPE_CLUBMEMBERSLIST:
				return Membership_Custom_Template::getClubMembersListData(
					$aData,
					$replaceTextBlocks
				);
		}
		
	}
	
	private function createMembershipsDoc(Tinebase_Record_RecordSet $memberships, $templateId=null){
		//throw new Exception('TODO');
		//$fullRecordMembership = $this->_membershipController->get($membership->getId());
		if($memberships->count()==0){
			$this->count = 0;
			return;
		}
		if(!Membership_Custom_Template::isToPrint($memberships, $this->type, $this->isPreview, &$templateId)){
			--$this->count;
			return;
		}
		if(!$templateId){
			throw new Exception('Template could not be retrieved for membership.');
		}
		
		// fetch first membership in order to retrieve club, association etc.
		$firstMemship = $memberships->offsetGet(0);
		
		$clubContactId = 1;
		$orgName = '';
		if($firstMemship->__get('parent_member_id')){
			$clubMembership = $firstMemship->getForeignRecord('parent_member_id', Membership_Controller_SoMember::getInstance());
			$fullMembership = Membership_Controller_SoMember::getInstance()->get($clubMembership->getId());
			$clubContact = $fullMembership->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
			$clubContactId = $clubContact->getId(); 
			$orgName = $clubContact->__get('org_name');
		}
		//$clubContact = $firstMemship->__get('society_contact_id');
		$assoc = $firstMemship->getForeignRecord('association_id', Membership_Controller_Association::getInstance());
		
		
		$this->clubContactId = $clubContactId;
		$this->map[] = $clubContactId;
		$membershipsData = array();
		
		$countActive = 0;
		$countPassive = 0;
		$countSportsDiver = 0;
		$countTotal = 0;
		$avAge = 0;
		$avAgeCount = 0;
		$avAgeCalc = 0;
		
		foreach($memberships as $membership){
			$countTotal++;
			$mId = $membership->getId();
			$fullMembership = Membership_Controller_SoMember::getInstance()->getSoMember($mId);
			$contact = $fullMembership->__get('contact_id');
			
			$bday = $contact->__get('bday');
			$age = null;
			if($bday){
				$today = new Zend_Date();
				$today->sub($bday,Zend_Date::YEAR);
				$age = $today->get(Zend_Date::YEAR);
				if($age>0){
					$avAgeCount++;
					$avAgeCalc += $age;
				}
			}
			$sportdiver = 'nein';
			if($contact->has('customfields')){
				$cm = $contact->__get('customfields');
				if(array_key_exists('vdstSportDiver',$cm)){
					$sportdiver = $cm['vdstSportDiver'];

					if($sportdiver=='ja'){
						$countSportsDiver++;
					}
				}
				if(!$sportdiver){
					$sportdiver = 'nein';
				}
			}
			
			$state = $fullMembership->__get('membership_status');
			
			if($state == 'ACTIVE'){
				$state = 'aktiv';
				$countActive++;
			}else{
				$state = 'passiv';
				$countPassive++;
			}
			$drawee = $contact->getLetterDrawee();
			$drawee->setLineBreak(' ,');
			$begin = \org\sopen\app\util\format\Date::format($fullMembership->__get('begin_datetime'));
			$endDate = $fullMembership->__get('termination_datetime');
			if($endData){
				$end = \org\sopen\app\util\format\Date::format($endDate);
			}else{
				$end = '';
			}
			$salutation = $drawee->getSalutationText();
			
			$sex = '';
			if(strpos($salutation, 'Herr')){
				$sex = 'm';
			}else if(strpos($salutation, 'Frau')){
				$sex = 'w';
			}
			
			$aMemberships[] = array(
				'member_nr' => $fullMembership->__get('member_nr'),
				'salutation' => $salutation,
				'title' => $drawee->getTitle(),
				'forename' => $contact->__get('n_given'),
				'lastname' => $contact->__get('n_family'),
				'letter_salutation' => $drawee->toText(),
				'sportdiver' => $sportdiver,
				'age' =>  $age,
				'sex' =>  $sex,
				'begin' => $begin,
				'end' => $end,
				'state' => $state,
				'feecalcdate' => ''
			);
			
		}
		if($avAgeCount>0){
			$avAge = floor($avAgeCalc/$avAgeCount);
		}
		$aData = array(
			'CLUB' => array(
				'ORG_NAME' => $orgName
			),
			'DATE' => strftime('%d.%m.%Y'),
			'LIST_TABLE' => $aMemberships,
			'count_active' => $countActive,
			'count_passive' => $countPassive,
			'count_total' => $countTotal,
			'count_sportsdiver' => $countSportsDiver,
			'average_age' => $avAge
		);

		$tempInFile = $this->tempFilePath . md5(serialize($membership).microtime()) . '_in.odt';
		$tempOutFile = $this->tempFilePath . md5(serialize($membership).microtime()) . '_out.odt';

		$this->templateController->renderTemplateToFile($templateId, $aData, $tempInFile, $tempOutFile, array());
		
		// move file into storage: cleans up tempfile at once
		$this->printJobStorage->moveIn( $tempOutFile,"//in/$this->clubContactId/odt/$this->storageFileName");
//		if($this->printDateAttribute && !$this->isPreview){
//			$membership->__set($this->printDateAttribute, strftime('%Y-%m-%d'));
//			$membership->flatten();
//			Membership_Controller_SoMember::getInstance()->update($membership);
//		}
	}
	
	private function finalizeDocs(){
		foreach($this->map as $contactId){
			if($this->printJobStorage->fileExists("//in/$this->clubContactId/odt/$this->storageFileName")){
				$inFile = $this->printJobStorage->resolvePath( "//in/$this->clubContactId/odt/$this->storageFileName" );
				$outFile = $this->printJobStorage->getCreateIfNotExist( "//convert/$this->clubContactId/pdf/$this->storageFileName" );
				$this->pdfServer->convertDocumentToPdf($inFile, $outFile);
			}
		}
	}
	
	private function createResult(){
		$this->printJobStorage->copy("//convert/$this->clubContactId/pdf/$this->storageFileName", "//out/result/$this->storageFileName/pdf/final" );
	}
	
	private function outputResult(){
		header('Content-Type: application/pdf');
		// get content from storage and close it (temporary storage gets deleted by this operation)
		$content = $this->printJobStorage->getFileContent("//out/result/$this->storageFileName/pdf/final");
		$this->printJobStorage->close();
		echo $content;
	}
	
	private function outputNone(){
		$this->printJobStorage->close();
		echo 'Keine Dokumente fällig zum Druck!';
	}
	
	private function runTransaction($process){
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
			
			switch($process){
				
				case self::PROCESS_CLUBMEMBERSLIST:
					
					$this->createClubMembersList();
					break;
			}
			
			// create the multipage output from single page input files
			if($this->count>0){
				$this->createResult();
			}
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