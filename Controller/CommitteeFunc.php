<?php
class Membership_Controller_CommitteeFunc extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_CommitteeFunc();
		$this->_modelName = 'Membership_Model_CommitteeFunc';
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

    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Record_Interface $_pagination = NULL, $_getRelations = FALSE, $_onlyIds = FALSE, $_action = 'get', $getDependent = TRUE)
    {
    	$result = $this->_backend->search($_filter, $_pagination, $_onlyIds, $getDependent);
        return $result;    
    }
	
	public function getEmptyCommitteeFunc(){
		$emptyOrder = new Membership_Model_CommitteeFunc(null,true);
		return $emptyOrder;
	}
	
    /**
     * get attender CommitteeFuncs
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderCommitteeFunc
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllCommitteeFuncs($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
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
	}
}
?>