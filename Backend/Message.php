<?php
class Membership_Backend_Message extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership_message';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_Message';

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function __construct ($_dbAdapter = NULL, $_modelName = NULL, $_tableName = NULL, $_tablePrefix = NULL, $_modlogActive = NULL, $_useSubselectForCount = NULL)
    {
   		parent::__construct ($_dbAdapter, $_modelName, $_tableName, $_tablePrefix, $_modlogActive, $_useSubselectForCount);
    }
    
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $_filter
	 * @param unknown_type $_pagination
	 * @param unknown_type $_cols
	 */
    protected function _publicSearch(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE)    
    {
        if ($_pagination === NULL) {
            $_pagination = new Tinebase_Model_Pagination();
        }
        
        // build query
        $selectCols = ($_onlyIds) ? $this->_tableName . '.id' : '*';
        $select = $this->_getSelect($selectCols);
   //print_r($_filter);
        $this->_addFilter($select, $_filter);
        $_pagination->appendPaginationSql($select);
        //if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . $select->__toString());
//echo $select->assemble();
        // get records
        $stmt = $this->_db->query($select);
       
        $rows = (array)$stmt->fetchAll(Zend_Db::FETCH_ASSOC);
        
        if ($_onlyIds) {
            $result = array();
            foreach ($rows as $row) {
                $result[] = $row[$this->_getRecordIdentifier()];
            }
        } else {
            $result = $this->_rawDataToRecordSet($rows);
        }
        
        return $result;
    }
    
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
    
	public function publicSearch(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE){
    	// don't forget third parameter -> causes nasty filter bugs!
        $recordSet = $this->_publicSearch($_filter,$_pagination,$_onlyIds);
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    protected function appendDependentRecords($record){
		Tinebase_User::getInstance()->resolveUsers($record, 'sender_account_id');
		Tinebase_User::getInstance()->resolveUsers($record, 'receiver_account_id');
       	if($record->__get('receiver_group_id')){
       		
       		$backend = Tinebase_Group::factory(Tinebase_Group::SQL);
       		$group = $backend->getGroupById($record->__get('receiver_group_id'));
       		$record->__set('receiver_group_id', $group->toArray());
    		
    	} 
    }
    
    public function get($id, $_getDeleted = FALSE){
    	$record = parent::get($id, $_getDeleted);
    	$this->appendDependentRecords($record);
    	return $record;
    }   
    
	public function publicSearchCount(Tinebase_Model_Filter_FilterGroup $_filter)
    {        
        $select = $this->_getSelect(array('count' => 'COUNT(*)'));
        $this->_addFilter($select, $_filter);
        
        // fetch complete row here
        $result = $this->_db->fetchRow($select);
        return $result['count'];        
    } 
    
    protected function _getSelect($_cols = '*', $_getDeleted = FALSE)
    {    
    	
    	$accountId = Tinebase_Core::getUser()->getId();
    	
    	$select = $this->_db->select();    
        $cols = (array)$_cols;
         
        if(!array_key_exists('count', $_cols)){
        	$cols['read_datetime'] =  'message_read.read_datetime';
        }

        $select->from(array($this->_tableName => $this->_tablePrefix . $this->_tableName), $cols);
     
    	
    	$select->joinLeft(array('message_read' => $this->_tablePrefix . 'membership_message_read'),
        	$this->_db->quoteIdentifier($this->_tableName . '.id') . ' = ' . $this->_db->quoteIdentifier('message_read.message_id'). ' AND ' .
        	$this->_db->quoteIdentifier('message_read.account_id') . ' = ' . $this->_db->quote($accountId),
        	array()); 
        	
        return $select;
    }
}
?>