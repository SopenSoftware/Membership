<?php
class Membership_Model_AwardListFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_AwardListFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
   		'query'          => array('filter' => 'Tinebase_Model_Filter_Query', 'options' => array('fields' => array('name'))),#
    	'key' => array('filter' => 'Tinebase_Model_Filter_Text'),
   		'name' => array('filter' => 'Tinebase_Model_Filter_Text')
    );
}
?>