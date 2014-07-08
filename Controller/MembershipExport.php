<?php
class Membership_Controller_MembershipExport extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_MembershipExport();
		$this->_modelName = 'Membership_Model_MembershipExport';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somember) ? Tinebase_Core::getConfig()->somember : new Zend_Config(array());
	}

	private static $_instance = NULL;

	/**
	 * the singleton pattern
	 *
	 * @return SoMembershipManager_Controller_SoMembership
	 */
	public static function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	public function getByName($name){
		return $this->_backend->getByProperty($name,'name');
	}

	public function getEmptyMembershipExport(){
		$emptyObj = new Membership_Model_MembershipExport(null,true);
		return $emptyObj;
	}
	
    /**
     * get attender eventKinds
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderMembershipExport
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllMembershipExports($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
    /**
     * get membership kinds as simple array (key=>value)
     * e.g. for simple array store on client side
     *
     */
    public function getMembershipExportsAsSimpleArray(){
    	$rows = $this->getAllMembershipExports();
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