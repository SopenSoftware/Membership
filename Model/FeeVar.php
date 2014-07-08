<?php

/**
 * class to hold FeeVar data
 *
 * @package     Membership
 */
class Membership_Model_FeeVar extends Tinebase_Record_Abstract
{
    /**
     * key in $_validators/$_properties array for the filed which
     * represents the identifier
     *
     * @var string
     */
    protected $_identifier = 'id';
    
    /**
     * application the record belongs to
     *
     * @var string
     */
    protected $_application = 'Membership';
    
    /**
     * list of zend validator
     *
     * this validators get used when validating user generated content with Zend_Input_Filter
     *
     * @var array
     *
     */
    protected $_validators = array(
        'id'                    => array(Zend_Filter_Input::ALLOW_EMPTY => true, Zend_Filter_Input::DEFAULT_VALUE => NULL),
     	'fee_var_config_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'fee_progress_id'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'floatvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'intvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'textvalue'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'transform'				=> 	array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'value'				=> 	array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
    /**
     * 
     * Set value
     * @param Membership_Model_FeeVarConfig $varConfig
     * @param mixed $value
     * @param Tinebase_Record_Interface $dataObject
     */
    public function setValue(Membership_Model_FeeVarConfig $varConfig, $value, Tinebase_Record_Interface $membership = null, Tinebase_Record_Interface $feeProgress = null, Tinebase_Record_Interface $parentMembership = null){
    	$type = $varConfig->__get('vartype');
    	switch($type){
    		case 'INTEGER':
    			$this->__set('intvalue', $this->transformValue($varConfig, (int)$value, $membership, $feeProgress, $parentMembership));
    			return true;
    		case 'STRING':
    			$this->__set('textvalue', $this->transformValue($varConfig, (string)$value, $membership, $feeProgress, $parentMembership));
    			return true;
    		case 'FLOAT':
    			$this->__set('floatvalue', $this->transformValue($varConfig, (float)$value, $membership, $feeProgress, $parentMembership));
    			return true;
    		default:
    			throw new Exception('Unsupported vartype');
    	}
    }
    
    public function getName(){
    	$varConfig = $this->getForeignRecord('fee_var_config_id', Membership_Controller_FeeVarConfig::getInstance());
    	return $varConfig->__get('name');
    }
    
    public function equals(Membership_Model_FeeVar $feeVar){
    	$varConfig = $this->getForeignRecord('fee_var_config_id', Membership_Controller_FeeVarConfig::getInstance());

    	if($varConfig->getId()!=$feeVar->getForeignId('fee_var_config_id')){
    		return false;
    	}
    	
    	if($this->getForeignId('fee_progress_id') != $feeVar->getForeignId('fee_progress_id')){
    		return false;
    	}
    	
    	return ($this->getValue($varConfig) == $feeVar->getValue($varConfig));
    }
    
    public function getValue($varConfig){
        $type = $varConfig->__get('vartype');
    	switch($type){
    		case 'INTEGER':
    			return (int)$this->__get('intvalue');
    		case 'STRING':
    			return (string)$this->__get('textvalue');
    		case 'FLOAT':
    			return (float)$this->__get('floatvalue');
    		default:
    			throw new Exception('Unsupported vartype');
    	}
    }
    
    /**
     * 
     * Transform value according to value transform definition
     * @param mixed $value
     * @param Tinebase_Record_Interface $dataObject
     */
    private function transformValue(Membership_Model_FeeVarConfig $varConfig, $value, $membership = null, $feeProgress = null, $parentMembership = null){
    	$type = $varConfig->__get('type');
    	switch($type){
    		case 'FIX':
    			$value = null;
    			break;
    		case 'VARIABLE':
    			// take given value parameter
    			break;
    		case 'DATAOBJECT':
    			$checkMem = array(
    			'MB_ADMFEE_PAYED' => 'admission_fee_payed',
    			'MB_PAYS_ADMFEE' => 'pays_admission_fee',
    			'MB_INDIV_FEE' => 'individual_yearly_fee',
    			'MB_ADDITIONAL_FEE' => 'additional_fee',
    			'MB_DONATION' => 'donation'
    			);
    			
    			$checkFee = array(
    			'FPROG_FIRST' =>'is_first',
    			'FPROG_FEE_UNITS' => 'fee_units'
    			);
 			
    			if(!$membership instanceof Membership_Model_SoMember){
    				throw new Membership_Exception('Given dataobject is not of class: Membership_Model_SoMember');
    			}
    			$val = $varConfig->__get('dataobject');
    			
    			if(array_key_exists($val, $checkMem)){
    				$value = $membership->__get($checkMem[$val]);
    			}elseif(array_key_exists($val, $checkFee)){
    				$value = $feeProgress->__get($checkFee[$val]);
//    			}elseif($val == 'MB_SPEC_FEE_ARTICLE'){
//    				$price = $parentMembership->getArticlePrice();
				}elseif($val == 'FG_SUM_I'){
    				$value = $membership->getFeeGroupPriceSumByCategory('I');
    			}else{
    				throw new Membership_Exception('Unsupported attribute of dataobject');
    			}
    			break;
    	}
    	
    	return $this->doComparison($varConfig, $value);
    	//$value = $this->doTransform($value);
    }
    
    private function doComparison($varConfig, $value){
    	for($i = 1; $i<7; $i++){
    		$compareField = $varConfig->__get('compare'.$i);
    		if(!$compareField){
    			return $value;
    		}
    		$compareValue = $varConfig->__get('compare_value'.$i);
	    	$type = $varConfig->__get('vartype');
	    	switch($type){
	    		case 'INTEGER':
	    			$compareValue = (int) $compareValue;
	    			break;
	    		case 'STRING':
	    			$compareValue = (string) $compareValue;
	    			break;
	    		case 'FLOAT':
	    			$compareValue = (string) $compareValue;
	    			break;
	    			
	    	}
    		$resultValue = $varConfig->__get('result_value'.$i);
    		
    		if($this->compare($compareField, $compareValue, $resultValue, &$value)){
    			return $value;
    		}
    	}
    	throw new Membership_Exception('Value does not match any condition');
    }
    
    private function compare($compareField, $compareValue, $resultValue, &$value){
    	switch($compareField){
    		case 'EQUALS':
    			if($compareValue == $value){
    				$value = $resultValue;
    				return true;
    			}
    			return false;
    		case 'GREATER':
    			if($compareValue > $value){
    				$value = $resultValue;
    				return true;
    			}
    			return false;
    		case 'GREATEROREQUALS':
    			if($compareValue >= $value){
    				$value = $resultValue;
    				return true;
    			}
    			return false;
    		case 'LESS':
    			if( $value < $compareValue){
    				$value = $resultValue;
    				return true;
    			}
    			return false;
    		case 'LESSOREQUALS':
    			if($value <= $compareValue){
    				$value = $resultValue;
    				return true;
    			}
    			return false;
    	}
    }
}