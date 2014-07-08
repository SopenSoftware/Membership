<?php 
class Membership_Model_MessageReadFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_MembershipRead';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
    	'message_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Membership_Model_MessageFilter', 
                'controller'        => 'Membership_Controller_Message'
            )
        )
    );
}
?>