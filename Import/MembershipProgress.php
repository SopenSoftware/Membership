<?php
/**
 * Tine 2.0
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Csv.php 12800 2010-02-12 16:08:17Z p.schuele@metaways.de $
 * 
 *

/**
 * csv import class for the addressbook
 * 
 * @package     Addressbook
 * @subpackage  Import
 * 
 */
class Membership_Import_Csv extends Tinebase_Import_Csv_Abstract
{    
    /**
     * the constructor
     *
     * @param Tinebase_Model_ImportExportDefinition $_definition
     * @param mixed $_controller
     * @param array $_options additional options
     */
    public function __construct(Tinebase_Model_ImportExportDefinition $_definition, $_controller = NULL, $_options = array())
    {
        parent::__construct($_definition, $_controller, $_options);
    }

    
    /**
     * add some more values (container id)
     *
     * @return array
     */
    protected function _addData($recordData)
    {
    	$result = array();

    	
		return $result;
    }
    
    /*protected function _importRecord($_recordData, &$_result)
    {
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($_recordData, true));
        
        $record = new $this->_modelName($_recordData, TRUE);
        
        if ($record->isValid()) {
            if (! $this->_options['dryrun']) {
                
                // check for duplicate
                if (isset($this->_options['duplicates']) && $this->_options['duplicates']) {
                    // search for record in container and print log message
                    $existingRecords = $this->_controller->search($this->_getDuplicateSearchFilter($record), NULL, FALSE, TRUE);
                    if (count($existingRecords) > 0) {
                        Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' Duplicate found: ' . $existingRecords[0]);
                        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
                        $_result['duplicatecount']++;
                        return;
                    }
                }
                
                // create/add shared tags
                if (isset($_recordData['tags'])) {
                    $record->tags = $this->_addSharedTags($_recordData['tags']);
                }

                $record = call_user_func(array($this->_controller, $this->_createMethod), $record);
            } else {
                $_result['results']->addRecord($record);
            }
            
            $_result['totalcount']++;
            
        } else {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
            throw new Tinebase_Exception_Record_Validation('Imported record is invalid.');
        }
    }*/
	protected function _importRecord($_recordData, &$_result)
    {
        $record = new $this->_modelName($_recordData, TRUE);
        
        if ($record->isValid()) {
            if (! $this->_options['dryrun']) {
                
                // check for duplicate
                if (isset($this->_options['duplicates']) && $this->_options['duplicates']) {
                    // search for record in container and print log message
                    $existingRecords = $this->_controller->search($this->_getDuplicateSearchFilter($record), NULL, FALSE, TRUE);
                    if (count($existingRecords) > 0) {
                        Tinebase_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' Duplicate found: ' . $existingRecords[0]);
                        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
                        $_result['duplicatecount']++;
                        return;
                    }
                }
                
                // create/add shared tags
                if (isset($_recordData['tags'])) {
                    $record->tags = $this->_addSharedTags($_recordData['tags']);
                }

                $record = call_user_func(array($this->_controller, $this->_createMethod), $record);
            } else {
                $_result['results']->addRecord($record);
            }
            
            $_result['totalcount']++;
            
        } else {
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' ' . print_r($record->toArray(), true));
            throw new Tinebase_Exception_Record_Validation('Imported record is invalid.');
        }
    }
    
    /**
     * do conversions
     * -> sanitize account_id
     *
     * @param array $_data
     * @return array
     */
    protected function _doConversions($_data)
    {
        $result = parent::_doConversions($_data);
        
        return $result;
    }    
}