<?php
class Membership_Model_ParentMemberNrFilter extends Tinebase_Model_Filter_Abstract
{
    /**
     * @var array list of allowed operators
     */
    protected $_operators = array(
        'contains','equals', 'greater', 'less', 'startswith', 'endswith'
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
//        	$filterData = array(
//            	array('field' => 'member_nr',   'operator' => 'contains', 'value' => $this->_value)
//        	);
        	
	    	$filter = new Membership_Model_SoMemberFilter(array(), 'AND');
	    	
	    	$pmFilter = new Membership_Model_SoMemberFilter(array(
	            array('field' => 'member_nr',   'operator' => $this->_operator, 'value' => $this->_value),
	        ));
	        $parentMemberIds = Membership_Controller_SoMember::getInstance()->search($pmFilter, NULL, FALSE, TRUE);
	        
	        $filter->addFilter(new Tinebase_Model_Filter_Id('parent_member_id', 'in', $parentMemberIds));
	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}