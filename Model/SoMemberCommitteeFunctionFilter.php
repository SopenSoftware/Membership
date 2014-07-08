<?php
class Membership_Model_SoMemberCommitteeFunctionFilter extends Tinebase_Model_Filter_Abstract
{
    /**
     * @var array list of allowed operators
     */
    protected $_operators = array(
        'equals'
    );
    
    
    /**
     * appends sql to given select statement
     *
     * @param Zend_Db_Select                $_select
     * @param Tinebase_Backend_Sql_Abstract $_backend
     */
    public function appendFilterSql($_select, $_backend)
    {
        if($this->_value){
        	$filter = new Membership_Model_SoMemberFilter(array(), 'AND');
        	$committeeFilter = new Membership_Model_CommitteeFuncFilter(array(
	            array('field' => 'committee_function_id',   'operator' => 'equals', 'value'=> $this->_value)
	        ));
	        
	        // -> no dependent records!
	        $committeeFuncs = Membership_Controller_CommitteeFunc::getInstance()->search($committeeFilter, NULL, FALSE, FALSE, 'get', FALSE);
	        $memberIds = $committeeFuncs->__get('member_id');
	        
	        $filter->addFilter(new Tinebase_Model_Filter_Id('id', 'in', array_unique($memberIds)));
	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}