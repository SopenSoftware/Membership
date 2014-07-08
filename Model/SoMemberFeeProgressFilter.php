<?php
class Membership_Model_SoMemberFeeProgressFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_SoMemberFeeProgressFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
        'member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
	        'options' => array(
	                'filtergroup'       => 'Membership_Model_SoMemberFilter', 
	                'controller'        => 'Membership_Controller_SoMember'
	            )
        ),
        'invoice_receipt_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
	        'options' => array(
	                'filtergroup'       => 'Billing_Model_ReceiptFilter', 
	                'controller'        => 'Billing_Controller_Receipt'
	            )
        ),
        'contact_id' => array('filter' => 'Membership_Model_FeeProgressContactFilter',
	        'options' => array(
	                'filtergroup_p1'       => 'Addressbook_Model_ContactFilter', 
	        		'filtergroup'       => 'Membership_Model_SoMemberFilter', 
	        	    'controller_p1'     => 'Addressbook_Controller_Contact',
	        		'controller'		=> 'Membership_Controller_SoMember'
	            )
        ),
        'query'                => array(
            'filter' => 'Tinebase_Model_Filter_Query', 
            'options' => array('fields' => array('fee_period_notes'))
        ),
        'fee_group_id' => array('filter' => 'Tinebase_Model_Filter_Id'),
        'payment_state' => array('filter' => 'Tinebase_Model_Filter_Text', 'alias' => 'receipt'),
        'fee_year' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'deb_summation' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'sum_brutto' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'fee_to_calculate' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'fee_calc_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'fee_from_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'fee_to_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'payment_date' => array('filter' => 'Tinebase_Model_Filter_Date', 'alias' => 'pay'),
        'monition_stage' => array('filter' => 'Tinebase_Model_Filter_Int', 'alias' => 'op'),
        'is_calculation_approved' => array('filter' => 'Tinebase_Model_Filter_Bool'),
        
    );
}
?>