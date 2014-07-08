<?php
class Membership_Controller_FeeGroup extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_FeeGroup();
		$this->_modelName = 'Membership_Model_FeeGroup';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_resolveCustomFields = true;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->eventmanager) ? Tinebase_Core::getConfig()->eventmanager : new Zend_Config(array());
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

	public function getEmptyFeeGroup(){
		$emptyObj = new Membership_Model_FeeGroup(null,true);
		return $emptyObj;
	}
	
	public function getByKey($key){
		return $this->_backend->getByProperty($key, 'key');
	}
	
    public function getAllFeeGroups($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $articleId
     */
    public function getByArticleId($articleId){
    	return $this->_backend->getByProperty($articleId, 'article_id');
    }
    
    /**
     * get membership kinds as simple array (key=>value)
     * e.g. for simple array store on client side
     *
     */
    public function getFeeGroupsAsSimpleArray(){
    	$rows = $this->getAllFeeGroups();
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