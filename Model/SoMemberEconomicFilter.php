<?php
class Membership_Model_SoMemberEconomicFilter extends Membership_Model_SoMemberFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_SoMemberEconomicFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_extFilterModel = array(
    	's_brutto' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'h_brutto' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'saldation' => array('filter' => 'Tinebase_Model_Filter_Int')
    );
    
	public function __construct(array $_data = array(), $_condition='', $_options = array())
    {
    	/*$len = count($this->_filterModel);
    	for($i = 0; $i<$len; $i++){
    		$this->_filterModel[$i]['alias'] = ' ';
    	}*/
		$this->_filterModel = array_merge($this->_filterModel, $this->_extFilterModel);
    	parent::__construct($_data, $_condition, $_options);
    }
    
 	protected function _getQuotedFieldName($_backend) {
        return $_backend->getAdapter()->quoteIdentifier(
            $this->_field
        );
    }
    
	/*public function appendFilterSql($select, $_backend)
    {
    	$db = Tinebase_Core::getDb();
    	
    	$select->joinLeft(array('debitor' => $_backend->getTablePrefix() . 'bill_debitor'),
                    $db->quoteIdentifier($_backend->getTableName() . '.contact_id') . ' = ' . $db->quoteIdentifier('debitor.contact_id'),
                    array()); 
        $select->joinLeft(array('debitor_account' => $_backend->getTablePrefix() . 'bill_debitor_account'),
          $db->quoteIdentifier('debitor_account.debitor_id') . ' = ' . $db->quoteIdentifier('debitor.id'),
        array());        

        $select->joinLeft(array('open_item' => $_backend->getTablePrefix() . 'bill_open_item'),
          '(('.$db->quoteIdentifier('open_item.debitor_id') . ' = ' . $db->quoteIdentifier('debitor.id') .') AND ' .
          '('.$db->quoteIdentifier('open_item.state') . ' = ' . "'OPEN'" .') AND ' .
          '('.$db->quoteIdentifier('open_item.erp_context_id') . ' = ' . "'MEMBERSHIP'" .'))',
          
        array());   
        
        $select->columns(array(
         	'debitor_id' 			=> 'debitor.id',
        	's_brutto'              => 'ABS(SUM(debitor_account.s_brutto))',
        	'h_brutto'              => 'ABS(SUM(debitor_account.h_brutto))',
        	'saldation'              => 'ABS(SUM(debitor_account.h_brutto))-ABS(SUM(debitor_account.s_brutto))',
        	'last_receipt_id'		=> null,
        	'last_receipt_date' => 'MAX(open_item.receipt_date)',
			'last_receipt_netto' => 0,
        	'last_receipt_brutto' => 0,
           	'count_open_items'       => 'COUNT(open_item.id)'
        ));
        
        $select->group(array('debitor.id'));
        
        
    }*/
}
?>