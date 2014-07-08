<?php
class Membership_Model_AssocNrFilter extends Tinebase_Model_Filter_Abstract
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
	    	$filter = new Membership_Model_SoMemberFilter(array(), 'AND');
	    	
	    	$assocFilter = new Membership_Model_AssociationFilter(array(
	            array('field' => 'association_nr',   'operator' => 'contains', 'value' => $this->_value),
	        ));
	        $assocIds = Membership_Controller_Association::getInstance()->search($assocFilter, NULL, FALSE, TRUE);
	        
	        $filter->addFilter(new Tinebase_Model_Filter_Id('association_id', 'in', $assocIds));
	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}