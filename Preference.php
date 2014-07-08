<?php
/**
 * Tine 2.0
 *
 * @package     DocManager
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Preference.php 14258 2010-05-07 14:46:00Z g.ciyiltepe@metaways.de $
 */


/**
 * backend for DocManager preferences
 *
 * @package     DocManager
 */
class Membership_Preference extends Tinebase_Preference_Abstract
{
	/**************************** application preferences/settings *****************/

	/**
	 * default DocManager all newly created contacts are placed in
	 */
	const TEMPLATE_CLUBMEMBERSLIST = 'templateClubMembersList';
	const MEMBER_ONLINE_GROUP = 'memberOnlineGroup';
	
	
	const ADDRESSBOOK_CLUBS = 'addressbookClubs';
	const ADDRESSBOOK_CLUBMEMBERS = 'addressbookClubMembers';
	const ADDRESSBOOK_ASSOCIATIONS = 'addressbookAssociations';
	
	const TEMPLATE_INVOICE = 'templateInvoice';
	const TEMPLATE_INVOICE_RECALC = 'templateInvoiceRecalc';
	const TEMPLATE_VERIFICATION = 'templateVerification';
	const MEMBER_ONLINE_MAIL_RECEIVER = 'memberOnlineMailReceiver';
	
	/**
	 * @var string application
	 */
	protected $_application = 'Membership';

	/**************************** public functions *********************************/

	/**
	 * get all possible application prefs
	 *
	 * @return  array   all application prefs
	 */
	public function getAllApplicationPreferences()
	{
		$allPrefs = array(
		self::TEMPLATE_CLUBMEMBERSLIST,
		self::MEMBER_ONLINE_GROUP,
		self::ADDRESSBOOK_CLUBS,
		self::ADDRESSBOOK_CLUBMEMBERS,
		self::TEMPLATE_INVOICE,
		self::TEMPLATE_INVOICE_RECALC,
		self::ADDRESSBOOK_ASSOCIATIONS,
		self::TEMPLATE_VERIFICATION,
		self::MEMBER_ONLINE_MAIL_RECEIVER
		);

		return $allPrefs;
	}

	/**
	 * get translated right descriptions
	 *
	 * @return  array with translated descriptions for this applications preferences
	 */
	public function getTranslatedPreferences()
	{
		//$translate = Tinebase_Translation::getTranslation($this->_application);

		$prefDescriptions = array(
		self::TEMPLATE_CLUBMEMBERSLIST  => array(
                'label'         => 'Vorlage für Mitgliederliste Verein',
                'description'   => '',
		),
		self::MEMBER_ONLINE_GROUP  => array(
                'label'         => 'Benutzergruppe Mitglieder Online',
                'description'   => '',
		),		
		self::MEMBER_ONLINE_MAIL_RECEIVER  => array(
                'label'         => 'Emailadressen Nachrichten Mitglieder Online',
                'description'   => '',
		),
		self::ADDRESSBOOK_CLUBS  => array(
                'label'         => 'Adressbuch Vereine',
                'description'   => '',
		),
		self::ADDRESSBOOK_CLUBMEMBERS  => array(
                'label'         => 'Adressbuch Vereinsmitglieder',
                'description'   => '',
		),
		self::ADDRESSBOOK_ASSOCIATIONS  => array(
                'label'         => 'Adressbuch Verbände',
                'description'   => '',
		),
		self::TEMPLATE_INVOICE  => array(
                'label'         => 'Dokumentvorlage Beitragsrechnung',
                'description'   => '',
		),
		self::TEMPLATE_INVOICE_RECALC  => array(
                'label'         => 'Dokumentvorlage Nachberechnung Beitrag',
                'description'   => '',
		),		
		self::TEMPLATE_VERIFICATION  => array(
                'label'         => 'Dokumentvorlage Nachweisliste',
                'description'   => '',
		)
		);

		return $prefDescriptions;
	}

	/**
	 * get preference defaults if no default is found in the database
	 *
	 * @param string $_preferenceName
	 * @return Tinebase_Model_Preference
	 */
	public function getPreferenceDefaults($_preferenceName, $_accountId=NULL, $_accountType=Tinebase_Acl_Rights::ACCOUNT_TYPE_USER)
	{
		$preference = $this->_getDefaultBasePreference($_preferenceName);
		switch($_preferenceName) {
			case self::TEMPLATE_CLUBMEMBERSLIST:
				break;
			case self::MEMBER_ONLINE_GROUP:
				break;
			case self::ADDRESSBOOK_CLUBS:
				$accountId          = $_accountId ? $_accountId : Tinebase_Core::getUser()->getId();
                $addressbooks       = Tinebase_Container::getInstance()->getPersonalContainer($accountId, 'Addressbook', $accountId, 0, true);
                $preference->value  = $addressbooks->getFirstRecord()->getId();
                
                break;
			case self::ADDRESSBOOK_CLUBMEMBERS:
				$accountId          = $_accountId ? $_accountId : Tinebase_Core::getUser()->getId();
                $addressbooks       = Tinebase_Container::getInstance()->getPersonalContainer($accountId, 'Addressbook', $accountId, 0, true);
                $preference->value  = $addressbooks->getFirstRecord()->getId();
                
                break;
			case self::ADDRESSBOOK_ASSOCIATIONS:
				$accountId          = $_accountId ? $_accountId : Tinebase_Core::getUser()->getId();
                $addressbooks       = Tinebase_Container::getInstance()->getPersonalContainer($accountId, 'Addressbook', $accountId, 0, true);
                $preference->value  = $addressbooks->getFirstRecord()->getId();
                
                break;
			case self::TEMPLATE_INVOICE:
				break;
			case self::TEMPLATE_INVOICE_RECALC:
				break;	
			case self::TEMPLATE_VERIFICATION:
				break;
			case self::MEMBER_ONLINE_MAIL_RECEIVER:
				break;
				
			default:
				throw new Tinebase_Exception_NotFound('Default preference with name ' . $_preferenceName . ' not found.');
		}

		return $preference;
	}

	/**
	 * get special options
	 *
	 * @param string $_value
	 * @return array
	 */
	protected function _getSpecialOptions($_value)
	{
		$result = array();
		switch($_value) {
			case self::TEMPLATE_CLUBMEMBERSLIST:
				$templates = DocManager_Controller_Template::getInstance()->getAll();
				foreach ($templates as $template) {
					$result[] = array($template->getId(), $template->__get('name'));
				}
				break;
			case self::MEMBER_ONLINE_GROUP:
				try{
				$groups = Tinebase_Group::getInstance()->getGroups();
				}catch(Exception $e){
					echo $e->__toString();
				}
				foreach ($groups as $group) {
					$result[] = array($group->getId(), $group->__get('name'));
				}
				break;	
			case self::ADDRESSBOOK_CLUBS:
                // get all user accounts
                $addressbooks = Tinebase_Container::getInstance()->getPersonalContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Core::getUser(), Tinebase_Model_Grants::GRANT_ADD);
                $addressbooks->merge(Tinebase_Container::getInstance()->getSharedContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Model_Grants::GRANT_ADD));
                
                foreach ($addressbooks as $addressbook) {
                    $result[] = array($addressbook->getId(), $addressbook->name);
                }
                break;
           case self::ADDRESSBOOK_CLUBMEMBERS:
                // get all user accounts
                $addressbooks = Tinebase_Container::getInstance()->getPersonalContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Core::getUser(), Tinebase_Model_Grants::GRANT_ADD);
                $addressbooks->merge(Tinebase_Container::getInstance()->getSharedContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Model_Grants::GRANT_ADD));
                
                foreach ($addressbooks as $addressbook) {
                    $result[] = array($addressbook->getId(), $addressbook->name);
                }
                break;
           case self::ADDRESSBOOK_ASSOCIATIONS:
                // get all user accounts
                $addressbooks = Tinebase_Container::getInstance()->getPersonalContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Core::getUser(), Tinebase_Model_Grants::GRANT_ADD);
                $addressbooks->merge(Tinebase_Container::getInstance()->getSharedContainer(Tinebase_Core::getUser(), 'Addressbook', Tinebase_Model_Grants::GRANT_ADD));
                
                foreach ($addressbooks as $addressbook) {
                    $result[] = array($addressbook->getId(), $addressbook->name);
                }
                break;
			case self::TEMPLATE_INVOICE:
				$templates = DocManager_Controller_Template::getInstance()->getAll();
				foreach ($templates as $template) {
					$result[] = array($template->getId(), $template->__get('name'));
				}
				break;
			case self::TEMPLATE_INVOICE_RECALC:
				$templates = DocManager_Controller_Template::getInstance()->getAll();
				foreach ($templates as $template) {
					$result[] = array($template->getId(), $template->__get('name'));
				}
				break;
			case self::TEMPLATE_VERIFICATION:
				$templates = DocManager_Controller_Template::getInstance()->getAll();
				foreach ($templates as $template) {
					$result[] = array($template->getId(), $template->__get('name'));
				}
				break;
			case self::MEMBER_ONLINE_MAIL_RECEIVER:
				$result = '';
				break;
			default:
				$result = parent::_getSpecialOptions($_value);
		}

		return $result;
	}
}
