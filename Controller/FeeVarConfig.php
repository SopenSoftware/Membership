<?php
class Membership_Controller_FeeVarConfig extends Tinebase_Controller_Record_Abstract
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
        $this->_backend = new Membership_Backend_FeeVarConfig();
        $this->_modelName = 'Membership_Model_FeeVarConfig';
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
    
    public function getEmptyFeeVarConfig(){
     	$emptyFeeVarConfig = new Membership_Model_FeeVarConfig(null,true);
     	return $emptyFeeVarConfig;
    }
    
    public function getByDefFilterId($id){
    	return $this->_backend->getByDefFilterId($id);
    }
    
    public function getByFeeDefinitionId($id){
    	return $this->_backend->getMultipleByProperty($id, 'fee_definition_id');
    }
    
    /**
     * 
     * Get the calculated var value for a given config and fee progress
     * @param Membership_Model_FeeVarConfig $feeVarConfig
     * @param unknown_type $feeProgressId
     */
    public function getVarValue(Membership_Model_FeeVarConfig $feeVarConfig, $feeProgressId){
    	// get var by fee progress id
    	$var = Membership_Controller_FeeVar::getInstance()->getByVarConfigId(
    		$feeVarConfig->getId(),
    		$feeProgressId
    	)
    	->getFirstRecord();
    	
    	$value = $var->getValue($feeVarConfig);
    	return $value;
    }
}
?>