<?php

/**
 * class to hold FilterSet data
 *
 * @package     Membership
 */
class Membership_Model_FilterSet extends Tinebase_Record_Abstract
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
     	'conjunction'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'result_type'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'transform' => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'name'                  => array(Zend_Filter_Input::ALLOW_EMPTY => true),
    	'description'       => array(Zend_Filter_Input::ALLOW_EMPTY => true)
    );
    protected $_dateFields = array(
    // modlog
    );
    
    public function getFilterResultRecords(){
    	$filter = new Membership_Model_FilterResultFilter(array(array(
    		'field' => 'filter_set_id',
    		'operator' => 'AND',
    		'value' => array(array(
    			'field' => 'id',
    			'operator' => 'equals',
    			'value' => $this->getId()
    		))
    	)));
    	$paging = new Tinebase_Model_Pagination(array('sort'=>  'sort_order', 'dir' => 'ASC'));
    	
    	return Membership_Controller_FilterResult::getInstance()->search($filter, $paging);
    	
    }
    
    public function getResult($additionalFilter = null, Tinebase_Model_Pagination $paging = null, $additionalRowData = array(), $aTotal = array()){
    	if($this->isData()){
    		if(is_null($paging)){
    			$paging = new Tinebase_Model_Pagination(array());
    		}
    		return Membership_Controller_SoMember::getInstance()->search(
    			$this->getFilterGroup($additionalFilter), 
    			$paging
    		);
    	}else{
    		if(count($aTotal)==0){
    			$aTotal = array(
	    			'sscat1' => 0,
	    			'sscat1_f1' => 0,		
	    			'sscat2' => 0,
	    			'sscat2_f1' => 0,
	    			'sscat3' => 0,
	    			'sscat3_f1' => 0,
	    			'sscat4' => 0,
	    			'sscat4_f1' => 0,
	    			'sscat5' => 0,
	    			'sscat5_f1' => 0
    			);
    		}
    		$total = 0;
    		$partTotal = 0;
    		$result = array(
    			'data' => array(
					'scat1' => 0,
	    			'scat1_f1' => 0,		
	    			'scat2' => 0,
	    			'scat2_f1' => 0,
	    			'scat3' => 0,
	    			'scat3_f1' => 0,
	    			'scat4' => 0,
	    			'scat4_f1' => 0,
	    			'scat5' => 0,
	    			'scat5_f1' => 0,	
	    			),
    			'type' => (!$this->isTransformPercentage())?'DISTRIBUTION':'PERCENTAGE',
    			'formula1' => ''
    		);
    		
    		$aFilters = $this->getFiltersAsArray($additionalFilter);
    		foreach($aFilters as $key => $filter){
    			
    			$count = Membership_Controller_SoMember::getInstance()->searchCount(
	    			$filter['filter']
	    		);
	    		
	    		$objFilter = $filter['objFilter'];
	    		
	    		if($this->isTransformPercentage()){
	    			
		    		if($objFilter->__get('sub_type') == 'TOTAL'){
		    			$result['total'] = array(
		    				'name' => $objFilter->__get('name'),
			    			'value' => $count
		    			);
		    			$total = $count;
		    		}else{
		    			$result['data'][] = array(
			    			'name' => $objFilter->__get('name'),
			    			'value' => $count
			    		);
		    		}
		    		
	    		}else{
	    			$key = $objFilter->__get('key');
	    			$category = $objFilter->__get('sum_category');
	    			
	    			$result['data'][$key] = $count;
	    			
		    		if($objFilter->__get('scalar_formula1')){
		    			$formula = $objFilter->__get('scalar_formula1');
		    			$result['formula1'] = $formula;
		    			if ( preg_match("/[^(0-9,x,y,z,+,\-,\*,\/,\^,., )]/", $formula) ) { 
		    				$formula = str_replace('x',$count,$formula);
		    				eval("\$cRes = $formula;");
		    				$result['data'][$key.'_f1'] = $cRes;
		    				if(!array_key_exists('s'.$key.'_f1', $result['total'])){
				    			$aTotal['s'.$key.'_f1'] = 0;
				    		}
				    		$aTotal['s'.$key.'_f1'] += $cRes;
		    			}
		    		}
		    		
		    		if(!array_key_exists('s'.$key, $aTotal)){
		    			$aTotal['s'.$key] = 0;
		    		}
		    		$aTotal['s'.$key] += $count;
		    		$result['data']['scat'.$category] += $count;
		    		$aTotal['scat'.$category] += $count;
	    		}
    		}
    	
    		if($this->isTransformPercentage()){
	    		if($total>0){
	    			$it = new ArrayIterator(&$result['data']);
	    			foreach($it as &$data){
	    				$val = (($data['value']/$total)*100);
	    				$partTotal += $val;
	    				$data['part_raw'] = $val;
	    				$data['part'] = number_format($val,3,',','.');
	    			}
	    		}
	    		
	    		$aTotal['part_raw'] = $partTotal;
	    		$aTotal['part'] = number_format($partTotal,3,',','.');
    		}else{
    			$data = $result['data'];
    			
    			$result['data'] = array_merge($data, $additionalRowData);
    		
    		}
    		

    		if($result['formula1']){
    			$aCat = array(1,2,3,4,5);
    			foreach($aCat as $cat){
    				
    				$val = $result['data']['scat'.$cat];
    				if(!array_key_exists('sscat'.$cat, $aTotal)){
		    			$aTotal['sscat'.$cat] = $val;
		    		}else{
		    			$aTotal['sscat'.$cat] += $val;
		    		}
    				
    				$formula = $result['formula1'];
    				$value = $result['data']['scat'.$cat];
    				if ( preg_match("/[^(0-9,x,y,z,+,\-,\*,\/,\^,., )]/", $formula) ) { 
	    				$formula = str_replace('x',$value,$formula);
	    				eval("\$cRes = $formula;");
			    		$result['data']['scat'.$cat.'_f1']= $cRes;
	    				if(!array_key_exists('scat'.$cat.'_f'.$cat, $aTotal)){
			    			$aTotal['scat'.$cat.'_f'.$cat] = $cRes;
			    		}else{
			    			$aTotal['scat'.$cat.'_f'.$cat] += $cRes;
			    		}
	    			}
    			}
    		}
    		
    		$result['total'] = $aTotal;
    		
    		return $result;
    	}
    }
    
    /**
     * 
     * 
     * Enter description here ...
     */
    public function getFilterGroup($additionalFilter=null){
    	$filterGroup = new Tinebase_Model_Filter_FilterGroup( array(), $this->__get('conjunction'));
    	$aFilterRecords = $this->getFilterResultRecords();
    	foreach($aFilterRecords as $filter){
    		$queryFilter = $filter->getFilter();
    		if(!is_null($additionalFilter)){
    			if($additionalFilter instanceof Tinebase_Model_Filter_Abstract){
    				$queryFilter->addFilter($additionalFilter);	
    			}elseif($additionalFilter instanceof Tinebase_Model_Filter_FilterGroup){
    				foreach($additionalFilter as $addFilter){
    					$queryFilter->addFilter($addFilter);	
    				}
    			}
    			
    		}
    		if($queryFilter instanceof Tinebase_Model_Filter_Abstract){
    			$filterGroup->addFilter($queryFilter);
    		}elseif($queryFilter instanceof Tinebase_Model_Filter_FilterGroup){
    			$filterGroup->addFilterGroup($queryFilter);
    		}
    	}
    	return $filterGroup;
    }
    
    public function getFiltersAsArray($additionalFilter){
    	$aResult = array();
    	
    	$aFilterRecords = $this->getFilterResultRecords();
    	foreach($aFilterRecords as $filter){
    		$queryFilter = $filter->getFilter();
    		if($additionalFilter instanceof Tinebase_Model_Filter_Abstract){
    				$queryFilter->addFilter($additionalFilter);	
    			}elseif($additionalFilter instanceof Tinebase_Model_Filter_FilterGroup){
    				foreach($additionalFilter as $addFilter){
    					$queryFilter->addFilter($addFilter);	
    				}
    			}
    		$aResult[$filter->__get('key')] = array(
    			'filter' => $queryFilter,
    			'objFilter' => $filter
    		);
    	}
    	return $aResult;
    }
    
    public function isData(){
    	return ($this->isResultDataObject() || $this->isResultDataObjectCollection());
    }
    
	public function isCalculation(){
    	return ($this->isResultScalar() || $this->isResultScalarSet());
    }
    
    public function isResultScalar(){
    	return ($this->__get('result_type') == 'SCALAR');
    }
    
	public function isResultScalarSet(){
    	return ($this->__get('result_type') == 'SCALARSET');
    }
    
	public function isResultDataObject(){
    	return ($this->__get('result_type') == 'DATAOBJECT');
    }
    
	public function isResultDataObjectCollection(){
    	return ($this->__get('result_type') == 'DATAOBJECTCOLLECTION');
    }
    
    public function isTransformPercentage(){
    	return ($this->__get('transform') == 'PERCENTAGE');
    }
}