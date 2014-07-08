<?php
class Membership_Model_OpenItemMemberNrFilter extends Tinebase_Model_Filter_Abstract
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

        	// get member
        	// get contact
        	// get debitor
        	
        	
	    	
	    	$mFilter = new Membership_Model_SoMemberFilter(array(
	            array('field' => 'member_nr',   'operator' => $this->_operator, 'value' => $this->_value),
	        ));
	        $members = Membership_Controller_SoMember::getInstance()->search($mFilter);
	        
	        $contactIds = $members->__getFlattened('contact_id');
	        
	        $dFilter = new Billing_Model_DebitorFilter(array(), 'AND');
	    	$dFilter->addFilter(new Tinebase_Model_Filter_Id('contact_id', 'in', $contactIds));
	       	
	    	$debitorIds = Billing_Controller_Debitor::getInstance()->search($dFilter, null, false, true);
	    		    	
	    	$filter = new Billing_Model_OpenItemFilter(array(), 'AND');
	    	$filter->addFilter(new Tinebase_Model_Filter_Id('debitor_id', 'in', $debitorIds));
	       	
	    	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}