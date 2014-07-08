<?php
class Membership_Controller_FeeVar extends Tinebase_Controller_Record_Abstract
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
        $this->_backend = new Membership_Backend_FeeVar();
        $this->_modelName = 'Membership_Model_FeeVar';
        $this->_currentAccount = Tinebase_Core::getUser();
        $this->_purgeRecords = FALSE;
        $this->_doContainerACLChecks = FALSE;
        $this->_config = isset(Tinebase_Core::getConfig()->brevetation) ? Tinebase_Core::getConfig()->brevetation : new Zend_Config(array());
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
    
    public function getEmptyFeeVar(){
     	$emptyFeeVar = new Membership_Model_FeeVar(null,true);
     	return $emptyFeeVar;
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id
     * @param unknown_type $feeProgressId
     */
    public function getByVarConfigId($feeVarConfigId, $feeProgressId ){
    	return $this->_backend->getByPropertySet(
    		array(
    			'fee_var_config_id' => $feeVarConfigId,
    			'fee_progress_id' => $feeProgressId
    		),
    		false, // no deleted
    		false // get multiple results
    	);
    }
    
    
    
    
}
?>