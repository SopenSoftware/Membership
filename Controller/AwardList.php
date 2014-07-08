<?php
class Membership_Controller_AwardList extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_AwardList();
		$this->_modelName = 'Membership_Model_AwardList';
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

	public function getEmptyAwardList(){
		$emptyOrder = new Membership_Model_AwardList(null,true);
		return $emptyOrder;
	}
	
	/**
	 * 
	 * Get item by key
	 * @param string $key
	 */
	public function getByKey($key){
		return $this->_backend->getByProperty($key, 'key');
	}
	
/**
	 * 
	 * Get item by name
	 * @param string $name
	 */
	public function getByName($name){
		return $this->_backend->getByProperty($name, 'name');
	}
	
    /**
     * get attender AwardLists
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderAwardList
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllAwardLists($_sort = 'name', $_dir = 'ASC')
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