<?php
class Membership_Controller_FeeDefinition extends Tinebase_Controller_Record_Abstract
{
    /**
     * config of courses
     *
     * @var Zend_Config
     */
    protected $_config = NULL;
    
    /**
     * the constructor
     *
     * don't use the constructor. use the singleton
     */
    private function __construct() {
        $this->_applicationName = 'Membership';
        $this->_backend = new Membership_Backend_FeeDefinition();
        $this->_modelName = 'Membership_Model_FeeDefinition';
        $this->_currentAccount = Tinebase_Core::getUser();
        $this->_purgeRecords = FALSE;
        $this->_doContainerACLChecks = FALSE;
        $this->_config = isset(Tinebase_Core::getConfig()->brevetation) ? Tinebase_Core::getConfig()->brevetation : new Zend_Config(array());
    }
    
    private static $_instance = NULL;
    
    /**
     * the singleton pattern
     *
     * @return SoEventManager_Controller_SoEvent
     */
    public static function getInstance()
    {
        if (self::$_instance === NULL) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    /**
     * 
     * Get empty record
     * @return Membership_Model_FeeDefinition
     */
    public function getEmptyFeeDefinition(){
     	$emptyFeeDefinition = new Membership_Model_FeeDefinition(null,true);
     	return $emptyFeeDefinition;
    }    
    
    /**
     * 
     * Checks, to which fee defintion a given membership belongs to
     * @param string $membershipId
     * @return matching fee definition if found
     * 
     * @throws Membership_Exception_NoFeeDefinition
     */
    public function matchToMembership($membershipId){
    	// fetch all fee definitions to check
    	// fee definition should not be a mass entity!
    	$feeDefs = $this->getAll();
    	if($feeDefs->count()==0){
    		throw new Membership_Exception_NoFeeDefinition('No fee definition found in database');
    	}
    	$mController = Membership_Controller_SoMember::getInstance();
    	
    	foreach($feeDefs as $feeDef){
    		// fetch filter definition
    		$filters = Zend_Json::decode($feeDef->__get('iterator_filters'));
    		// add id filter of membership
    		$filters[] = array(
    			'field' => 'id',
    			'operator' => 'equals',
    			'value' => $membershipId
    		);
    		$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
    		
    		// search memberships: result will be one record in maximum
			$memberships =  $mController->search(
				$filters,
				new Tinebase_Model_Pagination(array('sort' => 'id', 'dir' => 'ASC'))
			);
			
			if($memberships->count()==1){
				return $feeDef;
			}
    	}
    	throw new Membership_Exception_NoMatchingFeeDefinition('No matching fee definition for membership with id: '.$membershipId);
    }
    
    /**
     * 
     * Calculate filter def results based on parent membership
     * @param Membership_Model_SoMember $membership
     */
    public function calculateFilterDefs($membership){
    	// load filter defs
    	
    }
}
?>