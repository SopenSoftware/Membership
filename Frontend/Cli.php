 <?php
/**
 * Tine 2.0
 * @package     Membership
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Hans-Jürgen Hartl <hhartl@sopen.de>
 * @copyright   Copyright (c) 2010 sopen GmbH (http://www.sopen.de)
 * @version     $Id: Cli.php  $
 * 
 */

/**
 * cli server for membership
 *
 * This class handles cli requests for the membership
 *
 * @package     Membership
 */
class Membership_Frontend_Cli extends Tinebase_Frontend_Cli_Abstract
{
    /**
     * the internal name of the application
     *
     * @var string
     */
    protected $_applicationName = 'Membership';
    
    /**
     * import config filename
     *
     * @var string
     */
    protected $_configFilename = 'importconfig.inc.php';

    /**
     * help array with function names and param descriptions
     */

    /**
     * import memberships
     *
     * @param Zend_Console_Getopt $_opts
     */
    public function import($_opts)
    {
		set_time_limit(0);
    	parent::_import($_opts, Membership_Controller_SoMember::getInstance());        
     }
    
    public function importFeeProgress($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_SoMemberFeeProgress::getInstance());  
    }
    
    public function importExternalFeeHistory($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_SoMember::getInstance());  
    }
 
    public function importExternalFeeInvoices($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_SoMember::getInstance());  
    }
    
    public function importFeeGroups($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_FeeGroup::getInstance());  
    }
    
    public function importMembershipFeeGroups($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_MembershipFeeGroup::getInstance());  
    }
    
    
  	public function importCommittees($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_Committee::getInstance());  
    }
    
	public function importCommitteeFunctions($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_CommitteeFunction::getInstance());  
    }
    
   	public function importCommitteeFuncs($_opts){
    	set_time_limit(0);              
    	parent::_import($_opts, Membership_Controller_CommitteeFunc::getInstance());  
    }
  
    public function repairMemberships($_opts){
    	set_time_limit(0);
    	Membership_Controller_SoMember::getInstance()->repairMemberships();
    }
}
