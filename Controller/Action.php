<?php
class Membership_Controller_Action extends Tinebase_Controller_Record_Abstract
{
	const CREATE = 'CREATE';
	const UPDATE = 'UPDATE';
	const DELETE = 'DELETE';
	
	const IMPORTDATA = 'IMPORTDATA';
	
	const DISCHARGE = 'DISCHARGE';
	const TERMINATION = 'TERMINATION';
	const PARENTCHANGE = 'PARENTCHANGE';
	const ASSOCIATIONCHANGE = 'ASSOCIATIONCHANGE';
	const FEEGROUPCHANGE = 'FEEGROUPCHANGE';
	const MEMKINDCHANGE = 'MEMKINDCHANGE';
	const MEMSTATECHANGE = 'MEMSTATECHANGE';
	const PAYMENTMETHODCHANGE = 'PAYMENTMETHODCHANGE';
	const PAYMENTINTERVALCHANGE = 'PAYMENTINTERVALCHANGE';
	const BANKACCOUNTCHANGE = 'BANKACCOUNTCHANGE';
	const PRINTMEMCARD = 'PRINTMEMCARD';
	const EXPORTMEMCARD = 'EXPORTMEMCARD';
	const CREATEFEEPROGRESS = 'CREATEFEEPROGRESS';
	const UPDATEFEEPROGRESS = 'UPDATEFEEPROGRESS';
	const BILLMEMBER = 'BILLMEMBER';
	const BILLMEMBERREVERT = 'BILLMEMBERREVERT';
	const BILLMEMBERRECALCULATION = 'BILLMEMBERRECALCULATION';
	const BILLPARENTMEMBER = 'BILLPARENTMEMBER';
	const PARENTENTER = 'PARENTENTER';
	const PARENTLEAVE = 'PARENTLEAVE';
	const DTAEXPPARENT = 'DTAEXPPARENT';
	const DTAEXPCHILD = 'DTAEXPCHILD';
	
	const CONTACTDATACHANGE = 'CONTACTDATACHANGE';
	const CONTACTCUSTOMCHANGE = 'CONTACTCUSTOMCHANGE';
	
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
		$this->_backend = new Membership_Backend_Action();
		$this->_modelName = 'Membership_Model_Action';
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

	public function getEmptyAction(){
		$emptyOrder = new Membership_Model_Action(null,true);
		return $emptyOrder;
	}
	
    /**
     * get attender Actions
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderAction
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllActions($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
    
    public function getActionsAsSimpleArray(){
    	$rows = $this->getAllActions();
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