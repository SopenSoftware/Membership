<?php
class Membership_Controller_Association extends Tinebase_Controller_Record_Abstract
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
		$this->_backend = new Membership_Backend_Association();
		$this->_modelName = 'Membership_Model_Association';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->somembers) ? Tinebase_Core::getConfig()->somembers : new Zend_Config(array());
	}

	private static $_instance = NULL;

	/**
	 * the singleton pattern
	 *
	 * @return SoMembership_Controller_SoEvent
	 */
	public static function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getEmptyAssociation(){
		$emptyOrder = new Membership_Model_Association(null,true);
		return $emptyOrder;
	}
	
    /**
     * get attender Associations
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderAssociation
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllAssociations($_sort = 'association_name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
	
    /**
     * inspect update of one record
     * 
     * @param   Tinebase_Record_Interface $_record      the update record
     * @param   Tinebase_Record_Interface $_oldRecord   the current persistent record
     * @return  void
     * 
     * @todo    check if address changes before setting new geodata
     */
    protected function _inspectUpdate($_record, $_oldRecord)
    {
    }
	/**
	 * (non-PHPdoc)
	 * @see release/sopen 1.1/main/app/core/vendor/tine/v/2/base/Tinebase/Controller/Record/Tinebase_Controller_Record_Abstract::_inspectCreate()
	 */
	protected function _inspectCreate(Tinebase_Record_Interface $_record)
	{
		if(!$_record->__get('association_nr')){
			$_record->__set('association_nr', Tinebase_NumberBase_Controller::getInstance()->getNextNumber('membership_association_nr'));
		}
	}
	
	public function getAssociationByShortName($shortName){
		return $this->_backend->getByProperty($shortName, 'short_name');
	}
	
	public function getByNumber($number){
		return $this->_backend->getByProperty($number, 'association_nr');
	}
	
	public function getByName($name){
		return $this->_backend->getByProperty($name, 'association_name');
	}
	
	public function getByNameOrCreate($name, $number){
		try{
			return $this->getByName($name);
		}catch(Exception $e){
			$adrCon = Addressbook_Controller_Contact::getInstance();
			$containerId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::ADDRESSBOOK_ASSOCIATIONS);
    		
			$contact = new Addressbook_Model_Contact(null,true);
			$contact->__set('org_name', $name);
			$contact->__set('n_fileas', $name);
			$contact->__set('n_fn', $name);
			$contact->__set('company2', $number);
			
			$contact->__set('container_id', $containerId);
    		$contact = $adrCon->create($contact);
			
			$assoc = new Membership_Model_Association();
			$assoc->__set('contact_id', $contact->getId());
			$assoc->__set('association_nr', (string)$number);
			$assoc->__set('association_name', $name);
			$result = $this->create($assoc);
		}
		return $result;
	}
	public function getByNumberOrCreate($number, $name){
		try{
			$result = $this->_backend->getByProperty($number, 'association_nr');
		}catch(Exception $e){
			$adrCon = Addressbook_Controller_Contact::getInstance();
			$containerId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::ADDRESSBOOK_ASSOCIATIONS);
    		
			$contact = new Addressbook_Model_Contact(null,true);
			$contact->__set('org_name', $name);
			$contact->__set('n_fileas', $name);
			$contact->__set('n_fn', $name);
			$contact->__set('company2', $number);
			
			$contact->__set('container_id', $containerId);
    		$contact = $adrCon->create($contact);
			
			$assoc = new Membership_Model_Association();
			$assoc->__set('contact_id', $contact->getId());
			$assoc->__set('association_nr', $number);
			$assoc->__set('association_name', $name);
			$result = $this->create($assoc);
		}
		return $result;
	}
}
?>