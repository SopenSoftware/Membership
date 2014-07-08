<?php 
class Membership_Model_FeeVarOrderPosFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_FeeVarOrderPosFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
        'order_pos_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Billing_Model_OrderTemplatePositionFilter',
                'controller'        => 'Billing_Controller_OrderTemplate'
            )
        )
    );
}
?>