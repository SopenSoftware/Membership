<?php
class Membership_Model_ContactCustomFieldFilter extends Tinebase_Model_Filter_Abstract
{
    /**
     * @var array list of allowed operators
     */
    protected $_operators = array(
        'contains','equals','greater','less','startswith','endswith'
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
        	 $db = Tinebase_Core::getDb();
        	 foreach($this->_value as $value){
	        	 $correlationName = Tinebase_Record_Abstract::generateUID() . $value['cfId'] . 'cf';
	
	        	 $_select->joinLeft(
	            /* what */    array($correlationName => SQL_TABLE_PREFIX . 'customfield'), 
	            /* on   */    $db->quoteIdentifier("{$correlationName}.record_id")      . " = co.id AND " 
	                        . $db->quoteIdentifier("{$correlationName}.customfield_id") . " = " . $db->quote($value['cfId']),
	            /* select */  array());
	            $_select->where($db->quoteInto($db->quoteIdentifier("{$correlationName}.value") . $this->_opSqlMap[$this->_operator]['sqlop'], $value['value']));
        	 }
        	
   //     	echo $_select->assemble();
//        	$filter = new Membership_Model_SoMemberFilter(array(), 'AND');
//	    	
//	    	$contactFilter = new Addressbook_Model_ContactFilter(array(
//	            array('field' => 'customfield',   'operator' => 'equals', 'value' => $this->_value),
//	        ));
//	        $contactIds = Addressbook_Controller_Contact::getInstance()->search($contactFilter, NULL, FALSE, TRUE);
//	        
//	        $filter->addFilter(new Tinebase_Model_Filter_Id('contact_id', 'in', $contactIds));
//	       	Tinebase_Backend_Sql_Filter_FilterGroup::appendFilters($_select, $filter, $_backend);
    	}
    }
}