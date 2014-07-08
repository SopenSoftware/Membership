<?php
class Membership_Backend_FeeArticle extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_fee_article';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_FeeArticle';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// don't forget third parameter -> causes nasty filter bugs!
        $recordSet = parent::search($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    protected function appendDependentRecords($record){
        if($record->__get('article_id')){
    		$this->appendForeignRecordToRecord($record, 'article_id', 'article_id', 'id', new Billing_Backend_Article());
        }
        if($record->__get('price_group_id')){
    		$this->appendForeignRecordToRecord($record, 'price_group_id', 'price_group_id', 'id', new Billing_Backend_PriceGroup());
        }
    }
}
?>