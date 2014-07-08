<?php
class Membership_Model_MemberQueryFilter extends Tinebase_Model_Filter_Abstract
{
    /**
     * @var array list of allowed operators
     */
    protected $_operators = array(
        'contains','equals'
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
        	$filterData = array(
            	array('field' => 'member_nr',   'operator' => 'contains', 'value' => $this->_value)
        	);
        	
	    	$filter = new Membership_Model_SoMemberFilter($filterData, 'OR');
	    	
	    	$contactFilter = new Addressbook_Model_ContactFilter(array(
	            array('field' => 'query',   'operator' => 'contains', 'value' => $this->_value),
	        ));
	        $contactIds = Addressbook_Controller_Contact::getInstance()->search($contactFilter, NULL, FALSE, TRUE);
	        
	        $filter->addFilter(new Tinebase_Model_Filter_Id('contact_id', 'in', $contactIds));
	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}