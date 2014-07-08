<?php
class Membership_Model_FeeGroupNoMemberFilter extends Tinebase_Model_Filter_Abstract
{
    /**
     * @var array list of allowed operators
     */
    protected $_operators = array(
        'isnull','notnull'
    );
    
    
    /**
     * appends sql to given select statement
     *
     * @param Zend_Db_Select                $_select
     * @param Tinebase_Backend_Sql_Abstract $_backend
     */
    public function appendFilterSql($_select, $_backend)
    {
        //if($this->_value){
      	
	    	$filter = new Membership_Model_SoMemberFilter(array(), 'AND');
	    	$filter->addFilter(new Tinebase_Model_Filter_Text('member_id', 'isnull', null));
	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	//}
        
        
        
    }
}