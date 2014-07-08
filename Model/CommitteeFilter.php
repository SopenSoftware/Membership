<?php
class Membership_Model_CommitteeFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_CommitteeFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
     	'id' => array('filter' => 'Tinebase_Model_Filter_Id'),
   		'query'          => array('filter' => 'Tinebase_Model_Filter_Query', 'options' => array('fields' => array('committee_nr', 'name'))),
    	'committee_nr' => array('filter' => 'Tinebase_Model_Filter_Int'),
    	'name' => array('filter' => 'Tinebase_Model_Filter_Text')
    	// member_id dropped, as not needed anymore
    	
    );
}
?>