<?php
class Membership_Controller_Vote extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_Vote();
		$this->_modelName = 'Membership_Model_Vote';
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

	public function getEmptyVote(){
		$emptyOrder = new Membership_Model_Vote(null,true);
		return $emptyOrder;
	}

	/**
	 * get attender Votes
	 *
	 * @param string $_sort
	 * @param string $_dir
	 * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderVote
	 *
	 * @todo    use getAll from generic controller
	 */
	public function getAllVotes($_sort = 'name', $_dir = 'ASC')
	{
		$result = $this->_backend->getAll($_sort, $_dir);
		return $result;
	}

	public function getByMemberIdBreakNull($memberId){
		try{
			return $this->_backend->getByProperty($memberId, 'member_id');
		}catch(Exception $e){
			return null;
		}
	}

	public function getVotesAsSimpleArray(){
		$rows = $this->getAllVotes();
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

	public function buildMemberVotes(){
		set_time_limit(0);
		ignore_user_abort(true);
		
		try{
			$mCon = Membership_Controller_SoMember::getInstance();
			 
			$baseDate = new Zend_Date(strftime('%Y').'-01-01');
			$dueDate = new Zend_Date(strftime('%Y').'-31-01');
			$mCon->setBaseDate($baseDate);
			$mCon->setBeginDate($dueDate);
			$mCon->setDueDate($dueDate);
			 
			$voteFilter = array();
			$voteFilter[] = array(
        	'field' => 'membership_type',
        	'operator' => 'equals',
        	'value' => 'VIASOCIETY'	
        	);
        	$voteFilter[] = array(
        	'field' => 'status_due_date',
        	'operator' => 'equals',
        	'value' => 'ACTIVE'	
        	);
        	$voteFilter[] = array(
        	'field' => 'person_age',
        	'operator' => 'greater',
        	'value' => 13	
        	);
        	 
        	$societyFilter = array();
        	$societyFilter[] = array(
        	'field' => 'membership_type',
        	'operator' => 'equals',
        	'value' => 'SOCIETY'	
        	);
        	$societyFilter[] = array(
        	'field' => 'membership_status',
        	'operator' => 'equals',
        	'value' => 'ACTIVE'	
        	);
        	$societyFilter[] = array(
        	'field' => 'member_nr_numeric',
        	'operator' => 'greater',
        	'value' => 84340	
        	);
        	$societyFilter = new Membership_Model_SoMemberFilter($societyFilter);

        	$aIdSociety = $mCon->search(
        	$societyFilter,
        	new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')),
        	false,
        	true
        	);
        	$feeDefinitions = Membership_Controller_FeeDefinition::getInstance()->getAll();
        	$foundFeeDef = null;
        	foreach($feeDefinitions as $feeDef){
        		if($feeDef->__get('name') == 'Jahresbeitrag Verein'){
        			$foundFeeDef = $feeDef;
        			break;
        		}
        	}

        	if(!$foundFeeDef){
        		throw new Exception('no fee def found');
        	}

        	$feeVarCon = Membership_Controller_FeeVarConfig::getInstance();
        	$feeProgressCon = Membership_Controller_SoMemberFeeProgress::getInstance();
        	$varConfigs = $feeVarCon->getByFeeDefinitionId($foundFeeDef->getId());
        	$activeYoungstersConfig = null;
        	$activeAdultsConfig = null;
        	foreach($varConfigs as $varConfig){
        		if($varConfig->__get('name')=='aktive Erwachsene'){
        			$activeAdultsConfig = $varConfig;
        		}
        		if($varConfig->__get('name')=='aktive Jugendliche'){
        			$activeYoungstersConfig = $varConfig;
        		}
        	}

        	if(!$activeAdultsConfig || !$activeYoungstersConfig){
        		throw new Exception('no var config found');
        	}

        	//Jahresbeitrag Verein
        	$count = 0;
        	foreach($aIdSociety as $clubId){
        		
        		$voteClubFilter = $voteFilter;
        		$voteClubFilter[] = array(
				'field' => 'parent_member_id',
				'operator' => 'AND', 	
				'value' => array(array(
					'field' => 'id',
					'operator' => 'equals',
					'value' => $clubId
        		))
        		);
        		// orig votes by filter (status due date)
        		$voteClubFilter = new Membership_Model_SoMemberFilter($voteClubFilter);
        		$origVotes = $mCon->searchCount($voteClubFilter);
        		$activeMembers = $origVotes;
        		$origVotes = ceil($origVotes/10);
        			
        		// get order votes
        		$feeProgress = $feeProgressCon->getForMemberByYear($clubId, strftime('%Y'));
        		$activeAdults = 0;
        		$activeYoungsters = 0;
        		try{
        			$activeAdults = $feeVarCon->getVarValue($activeAdultsConfig, $feeProgress->getId());
        			$activeYoungsters = $feeVarCon->getVarValue($activeYoungstersConfig, $feeProgress->getId());
        		}catch(Exception $e){
        			
        		}
        		$orderVotes = $activeAdults + $activeYoungsters;

        		$club = $mCon->getSoMember($clubId);
        		$create = false;
        		if(! ($vote = $this->getByMemberIdBreakNull($clubId)) ){
        			$vote = $this->getEmptyVote();
        			$create = true;
        		}
        		$vote->__set('member_id', $clubId);
        		$vote->__set('on_site', false);
        		$vote->__set('association_id', $club->getForeignId('association_id'));
        			
        		$vote->__set('original_votes', $origVotes);
        		$vote->__set('active_members', $activeMembers);
        		$vote->__set('order_votes', $orderVotes);
        		
        		$vote->__set('vote_permission', 'NOREACTION');
        			
        		
        		if($create){
        			$this->create($vote);
        		}else{
        			$this->update($vote);
        		}
        		
        		$count++;
        			
        	}
        	return array(
    			'state' => 'success',
    			'count' => $count
        	);
		}catch(Exception $e){
			return array(
    			'state' => 'failure',
    			'errorInfo' => $e->__toString()
			);
		}
		 
	}
}
?>