<?php
class Membership_Controller_FilterResult extends Tinebase_Controller_Record_Abstract
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
        $this->_backend = new Membership_Backend_FilterResult();
        $this->_modelName = 'Membership_Model_FilterResult';
        $this->_currentAccount = Tinebase_Core::getUser();
        $this->_purgeRecords = FALSE;
        $this->_doContainerACLChecks = FALSE;
        $this->_config = isset(Tinebase_Core::getConfig()->membership) ? Tinebase_Core::getConfig()->brevetation : new Zend_Config(array());
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
    
    public function getEmptyFilterResult(){
     	$emptyFilterResult = new Membership_Model_FilterResult(null,true);
     	return $emptyFilterResult;
    }
    
    public function calculateForMemberAndFeeDefinition($membership, $feeDefinition, $feeProgress, $parentMembership, $calculationDate){
    	try{
	    	$mController = Membership_Controller_SoMember::getInstance();
	    	$feeVarConfigController = Membership_Controller_FeeVarConfig::getInstance();
	    	$feeVarController = Membership_Controller_FeeVar::getInstance();
	    	
	    	$feeDefFilters = $this->_backend->getMultipleByProperty($feeDefinition->getId(), 'fee_definition_id');
	
	    	$filterResults = array();
	    	$invoiceRelevantMemberFilters = array();
	    	
	    	foreach($feeDefFilters as $feeDefFilter){
	    		$filters = Zend_Json::decode($feeDefFilter->__get('filters'));
	    		// add id filter of membership
	    		$type = $feeDefFilter->__get('related_membership');
	    		$field = 'parent_member_id';
	    		switch($type){
	    			case 'OWN':
	    				$id = $membership->getId();
	    				break;
	    			case 'PARENT':
	    				$id = $membership->getForeignId('parent_member_id');
	    				break;
	    		}
	    		$foreignIdField = 'id';
	    		$filters[] = array(
	    			'field' => $field,
	    			'operator' => 'AND',
	    			'value' => array(array(
	    				'field' => 'id',
	    				'operator' => 'equals',
	    				'value' => $id)
	    			)
	    		);
	    		$filters[] = array(
	    			'field' => 'fee_from_date',
	    			'operator' => 'beforeOrAt',
	    			'value' => $calculationDate->toString('yyyy-mm-dd')
	    		);
	    		$filters[] = array(
	    			'field' => 'fee_to_date',
	    			'operator' => 'afterAtOrNull',
	    			'value' => $calculationDate->toString('yyyy-mm-dd')
	    		);
	    		$filters[] = array(
	    			'field' => 'termination_datetime',
	    			'operator' => 'afterAtOrNull',
	    			'value' => $calculationDate->toString('yyyy-mm-dd')
	    		);
	    		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
				
	    		// collect invoice relevant member filters
	    		// -> can get used for logging the actionhistory (member gets respected for parent member invoice)
	    		if($feeDefFilter->__get('is_invoice_component')){
					$invoiceRelevantMemberFilters[] = $filters;
				}
	    		// count membership matching filters
				$memCount =  $mController->searchCount(
					$filters,
					new Tinebase_Model_Pagination(array('sort' => 'id', 'dir' => 'ASC'))
				);

				$filterResults[$feeDefFilter->getId()] = $memCount;
	    	}
		
	    	$feeVarConfigs = $feeVarConfigController->getByFeeDefinitionId($feeDefinition->getId());
	    	foreach($feeVarConfigs as $feeVarConfig){
	    		$filtersId = $feeVarConfig->__get('feedef_dfilters_id');
	    		$value = null;
	    		if($filtersId && array_key_exists($filtersId, $filterResults)){
	    			$value = $filterResults[$filtersId];
	    		}
	    		try{
    				$feeVarRecSet = $feeVarController->getByVarConfigId($feeVarConfig->getId(), $feeProgress->getId());
    				$feeVar = $feeVarRecSet->getFirstRecord();
    				$update = true;
    			}catch(Exception $e){
    				$feeVar = $feeVarController->getEmptyFeeVar();
    				$update = false;
    			}
    			
    			$feeVar->__set('fee_progress_id', $feeProgress->getId());
    			$feeVar->__set('fee_var_config_id', $feeVarConfig->getId());
    			$feeVar->setValue($feeVarConfig, $value, $membership, $feeProgress, $parentMembership);
    			
    			if($update){
    				$feeVarController->update($feeVar);
    			}else{
    				$feeVarController->create($feeVar);
    				$update = true;
    			}
	    	}
	    	return $invoiceRelevantMemberFilters;
    	}catch(Exception $e){
    		throw $e;
    	}
    	
    }
}
?>