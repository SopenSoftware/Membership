<?php
class Membership_Custom_Template{
	/**
	 * 
	 * Check whether current brevetation has to be printed
	 * @param 	Membership_Model_Membership $brevetation
	 * @param 	string 	$templateId	Reference: retrieve templateId
	 * @return	bool	true|false	has to be printed | not to be printed
	 */
	public static function isToPrint($obj, $type, $isPreview = false, &$templateId){
		switch($type){
			case Membership_Controller_Print::TYPE_CLUBMEMBERSLIST:
				// no control field to be queried
				// -> return always true, but retrieve required template
				$templateId = Tinebase_Core::getPreference('Membership')->getValue(Membership_Preference::TEMPLATE_CLUBMEMBERSLIST);
				return true;
		}
	}
	
	public static function replaceTextBlocks($data, &$textBlocks){
		if(array_key_exists('PAYMENTKEY', $data)){
			if($data['PAYMENTKEY'] == 'DEBIT'){
				$textBlocks['ANSCHREIBEN_UEB'] = '';
			}elseif($data['PAYMENTKEY'] == 'BANKTRANSFER'){
				$textBlocks['ANSCHREIBEN_LS'] = '';
			}
		}
	}
	
	public static function getClubMembersListData(array $dataObjects, &$textBlocks){
		
		// Datenobject Spende
		$brevetation = $dataObjects['brevetation'];
		
		// Datenobject Spender-Kontakt
		$contact = $dataObjects['contact'];
		
		$brevet = $dataObjects['brevet'];
		
		// Textblöcke umordnen, assoziativ nach Namen
		$textBlocksVar = array();
		foreach($textBlocks as $textBlock){
			$textBlocksVar[$textBlock['name']] = $textBlock['data'];
		}
		// Datum formatiert von ISO nach dd.mm.yyyy
		$DATUM = \org\sopen\app\util\format\Date::format($brevetation->__get('exam_date'));
		
		return array(
			'NAME' => $contact->getLetterDrawee()->getQualifiedName(),
			'DATE' => $DATUM,
			'BREVET' => str_replace('\n', chr(13).chr(10),$brevet->__get('label_text'))
		);
	}
}

?>