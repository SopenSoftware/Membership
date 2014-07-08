<?php
class Membership_Controller_FeeVarOrderPos extends Tinebase_Controller_Record_Abstract
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
        $this->_backend = new Membership_Backend_FeeVarOrderPos();
        $this->_modelName = 'Membership_Model_FeeVarOrderPos';
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
    
    public function getEmptyFeeVarOrderPos(){
     	$emptyFeeVarOrderPos = new Membership_Model_FeeVarOrderPos(null,true);
     	return $emptyFeeVarOrderPos;
    }
    
    public function getByOrderPosId($orderPosId){
    	return $this->_backend->getByProperty($orderPosId, 'order_pos_id');
    }
    
    /**
     * 
     * Get the calculated var value for a given config and fee progress
     * @param Membership_Model_FeeVarConfig $feeVarConfig
     * @param unknown_type $feeProgressId
     */
    public function getValues(Membership_Model_FeeVarOrderPos $feeVarOrderPos, $feeProgressId){
    	// get var by fee progress id
    	$a = array(
	    	'use_fee_var_config_id' => null,
	    	'amount_fee_var_config_id' => null,
	    	'price_netto_fee_var_config_id' => null,
	    	'price_brutto_fee_var_config_id' => null,
	    	'name_fee_var_config_id' => null,
	    	'factor_fee_var_config_id' => null
    	);
    	$b = array(
	    	'use_fee_var_config_id' => null,
	    	'amount_fee_var_config_id' => null,
	    	'price_netto_fee_var_config_id' => null,
	    	'price_brutto_fee_var_config_id' => null,
	    	'name_fee_var_config_id' => null,
	    	'factor_fee_var_config_id' => null
    	);
    	$varConfigController = Membership_Controller_FeeVarConfig::getInstance();

    	foreach($a as $key => $value){
    		try{
    			$varConfigId = $feeVarOrderPos->getForeignId($key);
    			$varConfig = $varConfigController->get($varConfigId);
   				$val = $varConfigController->getVarValue($varConfig, $feeProgressId);
				$b[$key] = $val;
    		}catch(Exception $e){
    			// keep it null if no foreign record, and therefore no value
    			$b[$key] = null;
    		}
    	}
    	return $b;
    }
}
?>