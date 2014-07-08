<?php 
class Membership_Model_MessageFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_MessageFilter';
    
    protected $_modelName = 'Membership_Model_Message';
    
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
    	'query'                => array(
            'filter' => 'Tinebase_Model_Filter_Query', 
            'options' => array('fields' => array('subject','message'))
        ),
        'receiver_group_id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
        'receiver_account_id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
        'parent_member_id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
		'expiry_datetime'          => array('filter' => 'Tinebase_Model_Filter_Date'),
        'created_datetime'          => array('filter' => 'Tinebase_Model_Filter_Date'),
		'read_datetime'          => array('filter' => 'Tinebase_Model_Filter_Date', 'alias' => 'message_read'),
        'direction'          => array('filter' => 'Tinebase_Model_Filter_Text')
    );
}
?>