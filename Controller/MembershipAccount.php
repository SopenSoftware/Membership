<?php
class Membership_Controller_MembershipAccount extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_MembershipAccount();
		$this->_modelName = 'Membership_Model_MembershipAccount';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
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

	public function getEmptyMembershipAccount(){
		$emptyObj = new Membership_Model_MembershipAccount(null,true);
		return $emptyObj;
	}
	
    /**
     * get attender eventAccounts
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderMembershipAccount
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllMembershipAccounts($_sort = 'related_member_id', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
    /**
     * get membership kinds as simple array (key=>value)
     * e.g. for simple array store on client side
     *
     */
    public function getMembershipAccountsAsSimpleArray(){
    	$rows = $this->getAllMembershipAccounts();
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
     * 
     * Enter description here ...
     * @param unknown_type $relatedMemberId
     */
    public function getByRelatedMemberId($relatedMemberId){
    	return $this->_backend->getMultipleByProperty($relatedMemberId, 'related_member_id');
    }
    
    public function getByAccountId($accountId){
    	return $this->_backend->getMultipleByProperty($accountId, 'account_id');
    }
    
    public function getMembershipDependencies(){
    	$rows = $this->getAllMembershipAccounts();
    	$aResults = array();
    	foreach($rows as $row){
    	
	    	$children = array();
	    	$hasChildren = false;
	    	$parents = array();
	    	$hasParents = false;
	    	$childResult = $this->getMembershipAccountsByParentId($row->getId());
	    	if($childResult->count()>0){
	    		$hasChildren = true;
	    		foreach($childResult as $child){
	    			$children[] = $child->getId();
	    		}
	    	}
	    	if($row->__get('parent_kind_id')){
	    		$hasParents = true;
	    		$parents = array($row->__get('parent_kind_id'));
	    	}
	    	
	    	$aResults[$row->getId()] = array(
		    	'id' => $row->getId(),
		    	'hasChildren' => $hasChildren,
		    	'hasParents' => $hasParents,
		    	'parents' => $parents,
		    	'children' => $children
	    	);
	    }
    	return $aResults;
    }
}
?>