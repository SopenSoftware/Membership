<?php
class Membership_Model_CommitteeFuncFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_CommitteeFuncFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
   		'query'          => array('filter' => 'Tinebase_Model_Filter_Query', 'options' => array('fields' => array('id'))),
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
       'association_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
	        'options' => array(
	                'filtergroup'       => 'Membership_Model_AssociationFilter', 
	                'controller'        => 'Membership_Controller_Association'
	         )
        ),
        'committee_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
    		'options' => array(
                'filtergroup'       => 'Membership_Model_CommitteeFilter', 
                'controller'        => 'Membership_Controller_Committee'
            )
        ),
       'committee_function_id' => array('filter' => 'Tinebase_Model_Filter_Id'),
       'begin_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
       'end_datetime' => array('filter' => 'Tinebase_Model_Filter_Date')
       
    );
}
?>