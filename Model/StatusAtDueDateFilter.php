<?php
class Membership_Model_StatusAtDueDateFilter extends Tinebase_Model_Filter_Text
{
	
	/**
     * Due date for queries (for some queries, like for age (calculated) values
     * we need an input parameter Date as basis. This can be the actual date (default)
     * or any other set by the controller from outside
     */
    private $dueDate = null;
    private $defaultAge = 0;
    //protected $alias = 'dta';
    
    public function __construct($_field, $_operator, $_value, array $_options = array()){
    	//$this->setAlias('dta');
    	parent::__construct($_field, $_operator, $_value, $_options);
    	$this->setDueDate();
    }
    
   
/**
     * 
     * Set due date
     */
    public function setDueDate( $date = null){
    	if(!$date instanceof Zend_Date){
    		$date = new Zend_Date($date);
    	}
    	$this->dueDate = $date;
    }
    
    public function getDueDate(){
    	return $this->dueDate;
    }
    
    public function getDueYear(){
    	return $this->dueDate->get(Zend_Date::YEAR);
    }
    
	public function getDueMonth(){
    	return $this->dueDate->get(Zend_Date::MONTH);
    }
    
	public function getDueDay(){
    	return $this->dueDate->get(Zend_Date::DAY);
    }
    
 	public function appendFilterSql($_select, $_backend)
    {
    	// transfer backends due date
    	$this->setDueDate($_backend->getDueDate());
    	/*
    	 * 
  	SELECT `membership`.*, IF(co.account_id IS NOT NULL,1,0) AS `has_account`, IF((entry_year IS NOT NULL) AND (entry_year>0) ,(2013 - entry_year),0) AS `member_age`, IF((birth_date IS NOT NULL) AND (birth_year >0) AND (birth_month >0) AND (birth_year<=2013) ,(2013 - birth_year -(77 < (DATE_FORMAT(birth_date, '%j')-1+0))),18) AS `person_age`, (member_nr)+0 AS `member_nr_numeric`, `dta`.`membership_status` AS `status_due_date`, `dta`.`max_valid_from` AS `mval_from`, `dta`.`valid_from` AS `val_from` FROM `sopen_membership` AS `membership`
 	LEFT JOIN `sopen_addressbook` AS `co` ON `membership`.`contact_id` = `co`.`id`
 	LEFT JOIN (
 
	 	SELECT 
	 		id, 
	 		valid_state, 
	 		MAX(valid_from) AS `max_valid_from`, 
	 		MAX(id) AS `max_id`, 
	 		`valid_from`, 
	 		`membership_status` AS `status_due_date`, 
	 		`membership_status`, 
	 		`member_id` 
	 	FROM 
	 		`sopen_membership_data` as dta_inner 
	 	WHERE (
	 		valid_from <= '2012-06-01' 
	 		AND valid_state='DONE'
	 		AND id= (
	 			SELECT 
	 				max(id) 
	 			FROM 
	 				sopen_membership_data as dta_inner2 
	 			WHERE 
	 				member_id = dta_inner.member_id 
	 				AND valid_from <= '2012-06-01' 
	 				AND valid_state='DONE'
	 		)
		) 
		GROUP BY member_id
	) AS `dta` ON `membership`.`id` = `dta`.`member_id` 
 
 --> filter where append
 	WHERE ((`membership`.`parent_member_id` IN ('35946c2a16f20c85f721e2ae7009555a89109cfd'))) AND ((`membership`.`membership_type` LIKE 'VIASOCIETY')) 
  
    	 
    	 */
    	
    	$fromParts = $_select->getPart(Zend_Db_Select::FROM);
    	if(!array_key_exists('dta', $fromParts)){
	    	
	    	$_select = $_select->columns(array('status_due_date' => 'dta.membership_status'));
	    	
	    	$strDueDate = $this->getDueDate()->toString('yyyy-MM-dd');
	    	
	    	$dtaInner2 = $_backend->getAdapter()->select()
				->from(array('dta_inner2' => $_backend->getTablePrefix() . 'membership_data'), array(
					"MAX(id)"
				))
				->where(
					"member_id=dta_inner.member_id AND ".
					"valid_from <= '".$strDueDate." ' AND ".
					"valid_state='DONE'"
				);
	    	
	    	$dtaInner = $_backend->getAdapter()->select()
				->from(array('dta_inner' => $_backend->getTablePrefix() . 'membership_data'), array(
					'id' => 'id',
					'valid_state' => 'valid_state',
					'max_valid_from' => "MAX(valid_from)",
					'valid_from' => 'valid_from',
					'status_due_date' => 'membership_status',
					'membership_status' => 'membership_status',
					'member_id' => 'member_id'
				))
				->where(
					"valid_from <= '".$strDueDate."' AND valid_state='DONE' AND ".
				
					$_backend->getAdapter()->quoteIdentifier( 'id').'=('. 
					$dtaInner2->assemble().')'
				)
				->group(array('member_id'));
	    	
	    	$_select->join(
	      		array('dta' => $dtaInner),
	            $_backend->getAdapter()->quoteIdentifier($_backend->getTableName() . '.id') . ' = ' . $_backend->getAdapter()->quoteIdentifier('dta.member_id') .' AND '.
	            $_backend->getAdapter()->quoteIdentifier('dta.valid_from') . ' = ' . $_backend->getAdapter()->quoteIdentifier('dta.max_valid_from'),
	        array());     
    	}
        // quote field identifier, set action and replace wildcards
        $field = $this->_getQuotedFieldName($_backend);
        $action = $this->_opSqlMap[$this->_operator];
        $value = $this->_replaceWildcards($this->_value);

        // check if group by is operator and return if this is the case
        if ($this->_operator == 'group') {
            $_select->group($this->_field);
        }

        if (in_array($this->_operator, array('in', 'notin')) && ! is_array($value)) {
            $value = explode(' ', $value);
        }
            
        if (is_array($value) && empty($value)) {
             $_select->where('1=' . (substr($this->_operator, 0, 3) == 'not' ? '1/* empty query */' : '0/* impossible query */'));
             return;
        }
        
	   	/*if (in_array($this->_operator, array('greater', 'less')) && ! is_array($value)) {
            $field = " CAST($field AS INTEGER) as $field ";
        }*/
        
        $where = Tinebase_Core::getDb()->quoteInto($field . $action['sqlop'], $value);
        
        if ($this->_operator == 'not' || $this->_operator == 'notin') {
            $where = "( $where OR $field IS NULL)";
        }
         
        // finally append query to select object
        $_select->where($where);
        
        //parent::appendFilterSql($_select, $_backend);
      // var_dump($where);
     // echo $_select->assemble();
        
    }
}