<?php


/**
 * This class handles all Http requests for the Membership application
 *
 * @package     Membership
 * @subpackage  Frontend
 */
class Membership_Frontend_Http extends Tinebase_Frontend_Http_Abstract
{
    protected $_applicationName = 'Membership';
    
    /**
     * Returns all JS files which must be included for this app
     *
     * @return array Array of filenames
     */
    public function getJsFilesToInclude()
    {
        return array(
        	'Membership/js/Config.js',
        	'Membership/js/Helpers.js',
        	'Membership/js/Models.js',
            'Membership/js/Backend.js',
        	'Membership/js/Custom.js',
        	'Membership/js/MainScreen.js',
            'Membership/js/AddressbookPlugin.js',
            'Membership/js/SoMemberEditRecord.js',
        	'Membership/js/SoMemberWidget.js',
        	'Membership/js/SoMemberFeeProgressEditRecord.js',
        	'Membership/js/FeeProgressExtEditRecord.js',
            'Membership/js/SoMemberGridPanel.js',
        	'Membership/js/SoMemberFeeProgressGridPanel.js',
        	'Membership/js/FeeDefinitionEditDialog.js',
        	'Membership/js/FeeDefinitionGridPanel.js',
            'Membership/js/FeeDefFilterEditDialog.js',
        	'Membership/js/FeeDefFilterGridPanel.js',
            'Membership/js/FeeVarConfigEditDialog.js',
        	'Membership/js/FeeVarConfigGridPanel.js',
        	'Membership/js/FeeVarGridPanel.js',
            'Membership/js/MembershipKindEditDialog.js',
        	'Membership/js/MembershipKindGridPanel.js',
            'Membership/js/FeeGroupEditDialog.js',
        	'Membership/js/FeeGroupGridPanel.js',
        	'Membership/js/AssociationEditDialog.js',
        	'Membership/js/AssociationGridPanel.js',
        	'Membership/js/MembershipFeeGroupEditDialog.js',
        	'Membership/js/MembershipFeeGroupGridPanel.js',
        	'Membership/js/FeeVarConfigOrderPosPropertyGridPanel.js',
        	'Membership/js/ContactSelect.js',
        	'Membership/js/Renderer.js',
        	'Membership/js/PrintMembershipDialog.js',
        	'Membership/js/PrintActionHistoryDialog.js',
        	'Membership/js/ExpMembershipDialog.js',
        	'Membership/js/CreateMemberAccountEditDialog.js',
        	'Membership/js/TDImportDialog.js',
        	'Membership/js/ActionHistoryGridPanel.js',
        	'Membership/js/CommitteeKindEditDialog.js',
        	'Membership/js/CommitteeKindGridPanel.js',
        	'Membership/js/CommitteeLevelEditDialog.js',
        	'Membership/js/CommitteeLevelGridPanel.js',
        	'Membership/js/CommitteeEditDialog.js',
        	'Membership/js/CommitteeGridPanel.js',
        	'Membership/js/CommitteeFuncEditDialog.js',
        	'Membership/js/CommitteeFuncGridPanel.js',
	        'Membership/js/CommitteeFunctionEditDialog.js',
        	'Membership/js/CommitteeFunctionGridPanel.js',
        	'Membership/js/AwardListEditDialog.js',
        	'Membership/js/AwardListGridPanel.js',
        	'Membership/js/MembershipAwardEditDialog.js',
        	'Membership/js/MembershipAwardGridPanel.js',
        	'Membership/js/JobGridPanel.js',
        	'Membership/js/JobEditDialog.js',
        	'Membership/js/FilterSetEditDialog.js',
        	'Membership/js/FilterSetGridPanel.js',
        	'Membership/js/FilterResultEditDialog.js',
        	'Membership/js/FilterResultGridPanel.js',
        	'Membership/js/MembershipAccountEditDialog.js',
        	'Membership/js/MembershipAccountGridPanel.js',
        	'Membership/js/EntryReasonEditDialog.js',
        	'Membership/js/EntryReasonGridPanel.js',
        	'Membership/js/TerminationReasonEditDialog.js',
        	'Membership/js/TerminationReasonGridPanel.js',
        	'Membership/js/ChangeMemberDataDialog.js',
        	//'Membership/js/OpenItemGridPanel.js',
        	'Membership/js/MessageEditDialog.js',
        	'Membership/js/MessageGridPanel.js',
        	'Membership/js/MessageBroker.js'//,
        	//'Membership/js/VDSTMgvDialog.js'
        );
    }
    
    public function getCssFilesToInclude()
    {
        return array(
            'Membership/css/Membership.css'
        );
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $membershipExport
     */
    public function runPredefinedExport($membershipExport){
    	Membership_Controller_SoMember::runPredefinedExport($membershipExport);
    }
    
    public function runPredefinedExportActionHistory($membershipExportId, $exportDefOptions, $actionHistoryFilter){
    	Membership_Controller_SoMember::runPredefinedExportActionHistory($membershipExportId, $exportDefOptions, $actionHistoryFilter);
    }
    
 	public function printMemberList(){
    	error_reporting(E_ALL);
    	ini_set('display_errors','on');
    	Membership_Controller_SoMember::getInstance()->printMembersList($_REQUEST['filters']);
    }
    
    public function printLabels(){
    	Membership_Controller_SoMember::getInstance()->printLabels($_REQUEST['filters']);
    }
    
    public function publicPrintLabels(){
    	Membership_Controller_ClubService::getInstance()->printLabels($_REQUEST['filters']);
    }
    
    public function publicPrintMembersList(){
    	Membership_Controller_ClubService::getInstance()->printMembersList($_REQUEST['filters']);
    }
    
 	public function exportMembersAsCsv(){
    	Membership_Controller_Export::getInstance()->exportAsCsv($_REQUEST['filters']);
    }
    
 	public function publicExportMembersAsCsv(){
    	Membership_Controller_ClubService::getInstance()->exportAsCsv($_REQUEST['filters'],'Membership_Export_Csv');
    }

 	public function exportMembersAsCustomCsv(){
    	Membership_Controller_Export::getInstance()->exportAsCsv($_REQUEST['filters'],$_REQUEST['exportClassName']);
    }
    

    
 	public function publicExportAsCustomCsv(){
    	Membership_Controller_ClubService::getInstance()->exportAsCsv($_REQUEST['filters'],$_REQUEST['exportClassName']);
    }
    
 	public function getPrintJobResult($printJobId){
    	Membership_Controller_SoMember::getInstance()->getPrintJobResult($printJobId);
    }
    
	public function downloadJobExportFile($customExportCsvJobId){
    	Membership_Controller_Export::getInstance()->downloadJobExportFile($customExportCsvJobId);
    }
    
	public function downloadJobErrorFile($customExportCsvJobId){
    	Membership_Controller_Export::getInstance()->downloadJobErrorFile($customExportCsvJobId);
    }
    
	/**
	 * 
	 * Export DTA file for members of parent member (Club, Local group etc.)
	 * @param string $parentMemberId
	 */
	public function exportDTACurrent($parentMemberId){
		return Membership_Controller_SoMember::getInstance()->exportDTACurrent($parentMemberId);
	}
    
    public function publicGetMembersListAsPDF($params, $day, $active, $passive){

    	$memNums = explode(';',$params);
		
        require_once(CSopen::instance()->getConfigPath()."/pdf/SPBasePDF.php");
		ini_set('display_errors','off');
		$pdf=SPBasePDF::getEmptyPageA4LandscapePDF();
		
		$master = Membership_Controller_ClubService::getInstance()->getClubContactData();
		
		$clubMemberNr = $master['data']['club_contact_id'];
		$clubName = $master['data']['club_name']. ' #'.$clubMemberNr;
		
		$memberships = Membership_Controller_ClubService::getInstance()->getClubMembers($memNums);
		$memberData = $memberships['results'];
		$memResort = array();
		foreach($memberData as $member){
			$memResort[$member['member_nr']] = $member;
		}
		ksort($memResort);

		
		SPBasePDF::renderClubMemberData($pdf,$clubName, $day,($active == 'true'), ($passive == 'true'), $memResort);		
    }
    
    public function printDueMemberLetters($letterType, $reprintDate, $filters,$additionalFilter, $data){
    	Membership_Controller_SoMember::getInstance()->printDueMemberLetters($letterType, $reprintDate, $filters,$additionalFilter, $data);
    }
    
    /**
     * 
     * Print begin letter for member (template like associated in membership kind)
     * @param array $memberIds
     */
    public function printBeginLetter($memberIds, $data){
    	Membership_Controller_SoMember::getInstance()->printBeginLetter(Zend_Json::decode($memberIds), $data);
    }
    /**
     * 
     * Print insurance confirmation letter for member (template like associated in membership kind)
     * @param array $memberIds
     */
	public function printInsuranceLetter($memberIds, $data){
    	Membership_Controller_SoMember::getInstance()->printInsuranceLetter(Zend_Json::decode($memberIds), $data);
    }
    /**
     * 
     * Print discharge/termination letter for member (template like associated in membership kind)
     * @param array $memberIds
     */
	public function printTerminationLetter($memberIds, $data){
    	Membership_Controller_SoMember::getInstance()->printTerminationLetter(Zend_Json::decode($memberIds), $data);
    }
    
 /**
     * 
     * Print membercard letter for member (template like associated in membership kind)
     * @param array $memberIds
     */
	public function printMemberCardLetter($memberIds, $data){
    	Membership_Controller_SoMember::getInstance()->printMemberCardLetter(Zend_Json::decode($memberIds), $data);
    }
}
