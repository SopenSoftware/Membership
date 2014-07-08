<?php
/**
 * main controller for Membership
 *
 * @package     Membership
 * @subpackage  Controller
 */
class Membership_Controller extends Tinebase_Controller_Abstract implements Tinebase_Event_Interface, Tinebase_Container_Interface
{
    /**
     * holds the instance of the singleton
     *
     * @var Filemamager_Controller
     */
    private static $_instance = NULL;

    /**
     * constructor (get current user)
     */
    private function __construct() {
        $this->_currentAccount = Tinebase_Core::getUser();
    }
    
    /**
     * don't clone. Use the singleton.
     *
     */
    private function __clone() 
    {        
    }
    
    /**
     * the singleton pattern
     *
     * @return Addressbook_Controller
     */
    public static function getInstance() 
    {
        if (self::$_instance === NULL) {
            self::$_instance = new Membership_Controller;
        }
        
        return self::$_instance;
    }

    /**
     * event handler function
     * 
     * all events get routed through this function
     *
     * @param Tinebase_Event_Abstract $_eventObject the eventObject
     * 
     * @todo    write test
     */
    public function handleEvents(Tinebase_Event_Abstract $_eventObject)
    {
    	try{
	    	if($_eventObject instanceof Billing_Events_OpenItemPayed){
	    		try{
	    			//Membership_Controller_SoMemberFeeProgress::getInstance()->decidePayOpenItem($_eventObject->payment, $_eventObject->openItem);
	    		}catch(Exception $e){
	    			throw $e;
	    		}
	    	}
	    	
	    	if($_eventObject instanceof Billing_Events_OpenItemUnpayed){
	    		try{
	    			//Membership_Controller_SoMemberFeeProgress::getInstance()->decidePayOpenItem($_eventObject->payment, $_eventObject->openItem);
	    		}catch(Exception $e){
					throw $e;
	    		}
	    	}
	    	
	    	if($_eventObject instanceof Billing_Events_SetAccountsBankTransferDetected){
	    		try{
	    			Membership_Controller_SoMember::getInstance()->onSetAccountBankTransferDetected($_eventObject);
	    		}catch(Exception $e){
					throw $e;
	    		}
	    	}
    	}catch(Exception $e){
			Tinebase_Core::getLogger()->warn($e->__toString());
			echo $e->__toString();
		}
    	
    }
        
    /**
     * creates the initial folder for new accounts
     *
     * @param mixed[int|Tinebase_Model_User] $_account   the accountd object
     * @return Tinebase_Record_RecordSet of subtype Tinebase_Model_Container
     */
    public function createPersonalFolder($_account)
    {
    }
    
    /**
     * delete all personal user folders and the contacts associated with these folders
     *
     * @param Tinebase_Model_User $_account the accountd object
     * @todo implement and write test
     */
    public function deletePersonalFolder($_account)
    {
    }
}
