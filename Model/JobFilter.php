<?php
class Membership_Model_JobFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_JobFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
    	'job_id' =>  array('filter' => 'Tinebase_Model_Filter_Id'),
    	'job_category' =>  array('filter' => 'Tinebase_Model_Filter_Text')
    );
}
?>