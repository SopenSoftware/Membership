<?php
class Membership_Model_ActionHistoryFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_ActionHistoryFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
        'member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
        	'options' => array(
                'filtergroup'       => 'Membership_Model_SoMemberFilter', 
                'controller'        => 'Membership_Controller_SoMember'
            )
        ),
        'parent_member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
        	'options' => array(
                'filtergroup'       => 'Membership_Model_SoMemberFilter', 
                'controller'        => 'Membership_Controller_SoMember'
            )
        ),
        'job_id' =>  array('filter' => 'Tinebase_Model_Filter_Id'),
        
//        	=> array('filter' => 'Tinebase_Model_Filter_ForeignId', 
//        	'options' => array(
//                'filtergroup'       => 'Membership_Model_Job', 
//                'controller'        => 'Membership_Controller_Job'
//            )
//        ),
        'action_id' =>  array('filter' => 'Tinebase_Model_Filter_Id'),
        'receipt_id' =>  array('filter' => 'Tinebase_Model_Filter_Id'),
        'association_id' => array('filter' => 'Tinebase_Model_Filter_Id'),
        'parent_member_nr' => array('filter' => 'Membership_Model_ParentMemberNrFilter'),
        'assoc_nr' => array('filter' => 'Membership_Model_AssocNrFilter'),
        'action_state' => array('filter'=>'Tinebase_Model_Filter_Text'),
        'created_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'valid_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'process_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'created_by_user'     => array('filter' => 'Tinebase_Model_Filter_User'),
        'processed_by_user'     => array('filter' => 'Tinebase_Model_Filter_User'),
        'valid_state' => array('filter'=>'Tinebase_Model_Filter_Text')
    );
}
?>