<?php
class Membership_Controller_Committee extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_Committee();
		$this->_modelName = 'Membership_Model_Committee';
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

	public function getEmptyCommittee(){
		$emptyOrder = new Membership_Model_Committee(null,true);
		return $emptyOrder;
	}
	
	/**
	 * 
	 * Get committee kind by name
	 * @param unknown_type $name
	 */
	public function getByName($name){
		return $this->_backend->getByProperty($name, 'name');
	}
	
	/**
	 * 
	 * Get associated committee (1:1) to membership (although its a 1:n-relation,
	 * we should assume only one committee per membership
	 * @param string $clubMemberId
	 * @throws Exception if record not found
	 */
	public function getClubCommittee($committeeName){
		return $this->_backend->getMultipleByProperty( $committeeName, 'name' );
	}
	
    /**
     * get attender Committees
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderCommittee
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllCommittees($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
	/**
     * get committees as simple array (key=>value)
     * e.g. for simple array store on client side
     *
     */
    public function getCommitteesAsSimpleArray(){
    	$rows = $this->getAllCommittees();
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
	
    /**
     * inspect update of one record
     * 
     * @param   Tinebase_Record_Interface $_record      the update record
     * @param   Tinebase_Record_Interface $_oldRecord   the current persistent record
     * @return  void
     * 
     * @todo    check if address changes before setting new geodata
     */
    protected function _inspectUpdate($_record, $_oldRecord)
    {
    }
	/**
	 * (non-PHPdoc)
	 * @see release/sopen 1.1/main/app/core/vendor/tine/v/2/base/Tinebase/Controller/Record/Tinebase_Controller_Record_Abstract::_inspectCreate()
	 */
	protected function _inspectCreate(Tinebase_Record_Interface $_record)
	{
		if(!$_record->__get('committee_nr')){
			$_record->__set('committee_nr', Tinebase_NumberBase_Controller::getInstance()->getNextNumber('membership_committee_nr'));
		}
	}
}
?>