<?php
class Membership_Controller_CommitteeKind extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_CommitteeKind();
		$this->_modelName = 'Membership_Model_CommitteeKind';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somember) ? Tinebase_Core::getConfig()->somember : new Zend_Config(array());
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

	public function getEmptyCommitteeKind(){
		$emptyObj = new Membership_Model_CommitteeKind(null,true);
		return $emptyObj;
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
     * get attender roles
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_CommitteeKind
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllCommitteeKinds($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
}
?>