<?php 
class Membership_Model_VoteFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_VoteFilter';
    
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
        'transfer_member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Membership_Model_SoMemberFilter',
                'controller'        => 'Membership_Controller_SoMember'
            )
        ),
        'association_id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
        'on_site'          => array('filter' => 'Tinebase_Model_Filter_Bool'),
        'member_nr'          => array('filter' => 'Tinebase_Model_Filter_Text', 'alias' => 'mem')
        
    );
}
