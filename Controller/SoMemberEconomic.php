<?php
/**
 * 
 * Enter description here ...
 * @author hhartl
 *
 */
class Membership_Controller_SoMemberEconomic extends Membership_Controller_SoMember
{
	
	private function __construct() {
		$this->_applicationName = 'Membership';
		$this->_backend = new Membership_Backend_SoMemberEconomic();
		$this->_modelName = 'Membership_Model_SoMemberEconomic';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->soorders : new Zend_Config(array());
		$this->_resolveCustomFields = TRUE;
		// set due/begin/end date: default to current date
		$this->setDueDate(new Zend_Date());
		$this->setBeginDate(new Zend_Date());
		$this->setEndDate(new Zend_Date());
		
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
}
?>