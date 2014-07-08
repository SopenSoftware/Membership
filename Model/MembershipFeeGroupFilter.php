<?php 
class Membership_Model_MembershipFeeGroupFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_MembershipFeeGroupFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
    	'member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
        	'options' => array(
                'filtergroup'       => 'Membership_Model_SoMemberFilter', 
                'controller'        => 'Membership_Controller_SoMember'
            )
        ),
        'fee_group_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
        	'options' => array(
                'filtergroup'       => 'Membership_Model_FeeGroupFilter', 
                'controller'        => 'Membership_Controller_FeeGroup'
            )
        ),
        'no_member' =>  array('filter' => 'Membership_Model_FeeGroupNoMemberFilter'),
        'valid_from_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'), 
        'valid_to_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'category' => array('filter' => 'Tinebase_Model_Filter_Text')
        
    );
}
?>