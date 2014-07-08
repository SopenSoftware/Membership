<?php 
class Membership_Model_OpenItemFilter extends Tinebase_Model_Filter_FilterGroup
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Billing';
    
    protected $_className = 'Billing_Model_OpenItemFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
     	'payment_method_id'		  => array('filter' => 'Tinebase_Model_Filter_Text'),
        'order_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId',
            'options' => array(
                'filtergroup'       => 'Billing_Model_OrderFilter', 
                'controller'        => 'Billing_Controller_Order'
            )
        ),
        'debitor_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId',
            'options' => array(
                'filtergroup'       => 'Billing_Model_DebitorFilter', 
                'controller'        => 'Billing_Controller_Debitor'
            )
        ),
        'receipt_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'fibu_exp_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'banking_exp_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'state' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'type' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'query'                => array(
            'filter' => 'Tinebase_Model_Filter_Query', 
            'options' => array('fields' => array('order_id'))
        ),
        'erp_context_id'		  => array('filter' => 'Tinebase_Model_Filter_Text'),
        'member_nr' => array('filter' => 'Membership_Model_OpenItemMemberNrFilter')
    );
}
?>