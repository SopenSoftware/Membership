<?php 
class Membership_Model_FeeVarConfigFilter extends Tinebase_Model_Filter_FilterGroup// implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_FeeVarConfigFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
       	'fee_definition_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Membership_Model_FeeDefinitionFilter',
                'controller'        => 'Membership_Controller_FeeDefinition'
            )
        )
    );
}
?>