<?php
class Membership_Model_AssociationFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_AssociationFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
   		'query'          => array('filter' => 'Tinebase_Model_Filter_Query', 'options' => array('fields' => array('association_nr', 'association_name'))),#
    	'association_nr' => array('filter' => 'Tinebase_Model_Filter_Text'),
    	'association_name' => array('filter' => 'Tinebase_Model_Filter_Text')
    );
}
?>