<?php
class Membership_Backend_FilterSet extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_filter_set';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_FilterSet';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// don't forget third parameter -> causes nasty filter bugs!
    	return parent::search($_filter,$_pagination,$_onlyIds);
    }
}
?>