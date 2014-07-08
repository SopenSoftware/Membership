<?php
/**
 *
 * Export processor
 * @author hhartl
 *
 */
class Membership_Custom_Export_Processor{
	/**
	 *
	 * The export definition
	 * @var Membership_Model_MembershipExport
	 */
	private $exportDefinition = null;
	private $membershipFilter = null;
	private $societyFilter = null;
	private $associationFilter = null;
	private $actionHistoryFilter = null;

	private $processArray = array();

	private function __construct(){}

	/**
	 *
	 * Self factory
	 * @param Membership_Model_MembershipExport $def
	 */
	public static function create(Membership_Model_MembershipExport $def){
		$obj = new self();
		$obj->setMembershipExportDefinition($def);
		return $obj;
	}

	public static function createWithOptions(Membership_Model_MembershipExport $def, $exportDefOptions, $actionHistoryFilter){
		$exportDefOptions = Zend_Json::decode($exportDefOptions);

		$obj = new self();
		$obj->setMembershipExportDefinition($def);
		foreach($exportDefOptions as $fieldName => $value){
			/*if(in_array($fieldName,array('begin_datetime','end_datetime'))){
				$value = new Zend_Date($value);
				}*/
			if($value == ''){
				$value = null;
			}
			$obj->getMembershipExportDefinition()->__set($fieldName, $value);
		}

		$obj->setActionHistoryFilterFromJson($actionHistoryFilter);

		return $obj;
	}

	public function getMembershipExportDefinition(){
		return $this->exportDefinition;
	}

	public function getMembershipExportDefinitionName(){
		return $this->getMembershipExportDefinition()->__get('name');
	}

	public function getBeginDateFormatted(){
		return \org\sopen\app\util\format\Date::format($this->getMembershipExportDefinition()->__get('begin_datetime'));
	}

	public function getEndDateFormatted(){
		return \org\sopen\app\util\format\Date::format($this->getMembershipExportDefinition()->__get('end_datetime'));
	}

	public function export(){
		$baseDate = new Zend_Date(strftime('%Y').'-01-01');
		$dueDate = new Zend_Date($this->getMembershipExportDefinition()->__get('begin_datetime'));
		$beginDate = new Zend_Date($this->getMembershipExportDefinition()->__get('begin_datetime'));
		$endDate = new Zend_Date($this->getMembershipExportDefinition()->__get('end_datetime'));

		Membership_Controller_SoMember::getInstance()->setBaseDate($baseDate);
		Membership_Controller_SoMember::getInstance()->setDueDate($dueDate);
		Membership_Controller_SoMember::getInstance()->setBeginDate($beginDate);
		Membership_Controller_SoMember::getInstance()->setEndDate($endDate);

		if($this->isCalculationTypeFeeGroupOverview()){
			$this->exportFeeGroupOverview();
			Membership_Controller_PrintJobFeeGroupCalculation::getInstance()->printFeeGroupCalculation($this->processArray, $this);
		}elseif($this->isCalculationTypeFeeOverview()){
			$this->exportFeeOverview();
			Membership_Controller_PrintJobFeeGroupCalculation::getInstance()->printFeeGroupCalculation($this->processArray, $this);
		}elseif($this->isCalculation()){
			if($this->hasFilterSetMembers()){
				$this->exportFilterSetCalculation();
				if($this->getFilterSetMembers()->isTransformPercentage()){
					Membership_Controller_PrintJobDistributionPercentage::getInstance()->printResult($this->processArray, $this);
				}else{
					Membership_Controller_PrintJobCalculation::getInstance()->printResult($this->processArray, $this);
				}
			}
		}else{
			if($this->isResultSourceFlow()){
				$this->exportActionHistory();
			}else{
				$this->exportData();
				Membership_Controller_PrintJobMemberData::getInstance()->printResult($this->processArray, $this);
			}
		}
	}

	public function exportData(){

		if($this->hasFilterSetMembers()){

			if($this->classifyMainOrga() && !$this->classifySociety()){

				// nothing to do


			}elseif($this->classifySociety() && !$this->classifyMainOrga()){
				$societyFilter = $this->getFilterSociety();
				$memberController = Membership_Controller_SoMember::getInstance();
				$societyFilter = new Membership_Model_SoMemberFilter($societyFilter);

				$aIdSociety = $memberController->search(
				$societyFilter,
				new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')),
				false,
				true
				);
				try{
					$objFilterSet = $this->getFilterSetMembers();

					foreach($aIdSociety as $socId){
						$memDta = array();
						$parentMemberFilter = new Membership_Model_SoMemberFilter(array(array(
							'field' => 'parent_member_id',
							'operator' => 'AND', 	
							'value' => array(array(
								'field' => 'id',
								'operator' => 'equals',
								'value' => $socId
						))
						)));

						$society = Membership_Controller_SoMember::getInstance()->get($socId);
						$assoc = $society->getForeignRecord('association_id', Membership_Controller_Association::getInstance());
						$aFilter =  $this->getFilterMembers();

						$assocNr = $assoc->__get('association_nr');
						$assocName = $assoc->__get('association_name');
						$clubNr = $society->__get('member_nr');
						$clubMember = $society->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
						$clubName = $clubMember ->__get('org_name').' '.$clubMember->__get('company2');

						if($this->hasFilterSet()){
							$members = $objFilterSet->getResult($parentMemberFilter->getFilter('parent_member_id'));
						}else{
							$aMemberFilter = $this->getFilterMembers();
							$aMemberFilter[] = array(
								'field' => 'parent_member_id',
								'operator' => 'AND', 	
								'value' => array(array(
									'field' => 'id',
									'operator' => 'equals',
									'value' => $socId
							))
							);
							$memberFilter = new Membership_Model_SoMemberFilter(
							$aMemberFilter,
								 'AND'
								 );
								 $members = Membership_Controller_SoMember::getInstance()->search($memberFilter);
						}
						$extractor = new Membership_Custom_Export_ExtractorSoMember();
						$extractor->setCountTotal(count($members));

						foreach($members as $member){
							$memberId = $member->getId();
							$fullMember = Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
							$extractor->addMemberData($fullMember);
						}

						$memDta = $extractor->getData();
						$countData = $extractor->getCountDataAsArray();
						if(count($memDta)>0){
							$data = array(
								'members_total' => $countData['members_total']
							);
							Membership_Custom_SoMember::addAdditionalDataPrintMember(
							$extractor->getSummarize(),
							$data
							);
							$data['assoc_nr'] = $assocNr;
							$data['assoc_name'] = $assocName;
							$data['club_nr'] = $clubNr;
							$data['club_name'] = $clubName;

							$data['POS_TABLE'] = $memDta;
							$this->processArray[] = $data;

							/*$this->processArray[] = array(
								'POS_TABLE' => $memDta
								);*/
						}
					}

				}catch(Exception $e){
					echo $e->__toString();
					exit;
				}
			}else{


				// no classification: (no main orga, no society)

				$aMemberFilter = $this->getFilterMembers();
				$memberFilter = new Membership_Model_SoMemberFilter(
				$aMemberFilter,
					'AND'
					);

					if($this->hasFilterSet()){
						$objFilterSet = $this->getFilterSetMembers();
						$members = $objFilterSet->getResult($memberFilter, $this->getMemberPagination());
					}else{
							
						$members = Membership_Controller_SoMember::getInstance()->search($memberFilter, $this->getMemberPagination());
					}

					$extractor = new Membership_Custom_Export_ExtractorSoMember();
					$extractor->setCountTotal(count($members));

					foreach($members as $member){
						$memberId = $member->getId();
						$fullMember = Membership_Controller_SoMember::getInstance()->getSoMember($memberId);
						$extractor->addMemberData($fullMember);
					}

					$memDta = $extractor->getData();
					$countData = $extractor->getCountDataAsArray();
					if(count($memDta)>0){
						$data = array(
								'members_total' => $countData['members_total']
						);
						Membership_Custom_SoMember::addAdditionalDataPrintMember(
						$extractor->getSummarize(),
						$data
						);
						$data['assoc_nr'] = $assocNr;
						$data['assoc_name'] = $assocName;
						$data['club_nr'] = $clubNr;
						$data['club_name'] = $clubName;

						$data['POS_TABLE'] = $memDta;
						$this->processArray[] = $data;
					}
			}
		}
	}

	public function exportFilterSetCalculation(){
		if($this->classifyMainOrga() && !$this->classifySociety()){
			$assocFilter = $this->getFilterMainOrga();
			$assocController = Membership_Controller_Association::getInstance();
			$assocFilter = new Membership_Model_AssociationFilter($assocFilter);

			$aIdAssoc = $assocController->search(
			$assocFilter,
			new Tinebase_Model_Pagination(array( 'sort' => 'association_nr', 'dir' => 'ASC')),
			false,
			true
			);
			$dataArray = array(
				'values' => array()
			);
			$aTotal = array();
					
			foreach($aIdAssoc as $assocId){
				$assocMemberFilter = new Membership_Model_SoMemberFilter(array(array(
					'field' => 'association_id',
					'operator' => 'equals',
					'value' => $assocId
				)));
				$assoc = $assocController->get($assocId);

				$additionalRowData = array(
						'assoc_nr' => $assoc->__get('association_nr'),
						'assoc_name' => $assoc->__get('association_name'),
						'club_nr' => '',
						'club_name' => '',
						'exp_name' => $this->getMembershipExportDefinitionName(),
						'begin' => $this->getBeginDateFormatted(),
						'end' => $this->getEndDateFormatted()
				);

				$objFilterSet = $this->getFilterSetMembers();
				$values = $objFilterSet->getResult($assocMemberFilter->getFilter('association_id'), new Tinebase_Model_Pagination(array( 'sort' => 'association_nr', 'dir' => 'ASC')),$additionalRowData, $aTotal );
				$aTotal = $values['total'];
				$dataArray['values'][] = $values['data'];
			}
			
			$this->processArray[] = array(
				'data'=>$dataArray,
				'total' => $aTotal,
				'additional_data' => $additionalRowData
			);
			
			//print_r($this->processArray);
			//exit;

		}elseif($this->classifySociety() && !$this->classifyMainOrga()){
			$societyFilter = $this->getFilterSociety();
			$memberController = Membership_Controller_SoMember::getInstance();
			$societyFilter = new Membership_Model_SoMemberFilter($societyFilter);

			$aIdSociety = $memberController->search(
			$societyFilter,
			new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')),
			false,
			true
			);
			try{
				$objFilterSet = $this->getFilterSetMembers();
				foreach($aIdSociety as $socId){
					$parentMemberFilter = new Membership_Model_SoMemberFilter(array(array(
						'field' => 'parent_member_id',
						'operator' => 'AND', 	
						'value' => array(array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $socId
					))
					)));

					$society = Membership_Controller_SoMember::getInstance()->get($socId);
					$assoc = $society->getForeignRecord('association_id', Membership_Controller_Association::getInstance());

					$additionalRowData = array(
							'assoc_nr' => $assoc->__get('association_nr'),
							'assoc_name' => $assoc->__get('association_name'),
							'club_nr' => $society->__get('member_nr'),
							'club_name' => $society->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance())->__get('n_fileas'),
							'exp_name' => $this->getMembershipExportDefinitionName(),
							'begin' => $this->getBeginDateFormatted(),
							'end' => $this->getEndDateFormatted()
					);
						
					$values = $objFilterSet->getResult($parentMemberFilter->getFilter('parent_member_id'), new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')), $additionalRowData);
					$aTotal = $values['total'];
					$dataArray['values'][] = $values['data'];
						
					
					$this->processArray[] = array(
						'data'=>$dataArray,
						'total' => $aTotal,
						'additional_data' => $additionalRowData
					);


				}
			}catch(Exception $e){
				echo $e->__toString();
				exit;
			}
		}elseif($this->classifySociety() && $this->classifyMainOrga()){
			
			$dataArray = array();
			$assocFilter = $this->getFilterMainOrga();
			
			$assocController = Membership_Controller_Association::getInstance();
			$assocFilter = new Membership_Model_AssociationFilter($assocFilter);

			$aIdAssoc = $assocController->search(
				$assocFilter,
				new Tinebase_Model_Pagination(array( 'sort' => 'association_nr', 'dir' => 'ASC')),
				false,
				true
			);
			
			foreach($aIdAssoc as $assocId){
				$aTotal = array();
				$assocMemberFilter = new Membership_Model_SoMemberFilter(array(array(
					'field' => 'association_id',
					'operator' => 'equals',
					'value' => $assocId
				)));
				$assoc = $assocController->get($assocId);

				$additionalRowData = array(
						'assoc_nr' => $assoc->__get('association_nr'),
						'assoc_name' => $assoc->__get('association_name'),
						'club_nr' => '',
						'club_name' => '',
						'exp_name' => $this->getMembershipExportDefinitionName(),
						'begin' => $this->getBeginDateFormatted(),
						'end' => $this->getEndDateFormatted()
				);

				$societyFilter = $this->getFilterSociety();
				$memberController = Membership_Controller_SoMember::getInstance();
				$societyFilter = new Membership_Model_SoMemberFilter($societyFilter, 'AND');
				
				$societyFilter->addFilter($societyFilter->createFilter('association_id','equals',$assocId));
					
				//$a = $societyFilter->toArray();
				//print_r($a);
					
				$aIdSociety = $memberController->search(
					$societyFilter,
					new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')),
					false,
					true
				);
				
				//print_r($aIdSociety);
				$dataArray = array(
					'values' => array()
				);
				$total = array();
				
				try{
					$objFilterSet = $this->getFilterSetMembers();
					foreach($aIdSociety as $socId){
						$parentMemberFilter = new Membership_Model_SoMemberFilter(array(array(
						'field' => 'parent_member_id',
						'operator' => 'AND', 	
						'value' => array(array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $socId
						))
						)));

						$society = Membership_Controller_SoMember::getInstance()->get($socId);
						$assoc = $society->getForeignRecord('association_id', Membership_Controller_Association::getInstance());

						$additionalRowData = array(
							'assoc_nr' => $assoc->__get('association_nr'),
							'assoc_name' => $assoc->__get('association_name'),
							'club_nr' => $society->__get('member_nr'),
							'club_name' => $society->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance())->getLetterDrawee()->getCompanyTotal(),
							'exp_name' => $this->getMembershipExportDefinitionName(),
							'begin' => $this->getBeginDateFormatted(),
							'end' => $this->getEndDateFormatted()
						);
						
						$values = $objFilterSet->getResult($parentMemberFilter->getFilter('parent_member_id'), new Tinebase_Model_Pagination(array( 'sort' => 'member_nr', 'dir' => 'ASC')), $additionalRowData, $aTotal);
						$aTotal = $values['total'];
						$dataArray['values'][] = $values['data'];
						

					}
				}catch(Exception $e){
					
					echo $e->__toString();
					exit;
				}

				$this->processArray[] = array(
					'data'=>$dataArray,
					'total' => $aTotal,
					'additional_data' => $additionalRowData
				); 

				/////

					


				//////
			}
			
	//		print_r($this->processArray);
//exit;

		}else{
			$objFilterSet = $this->getFilterSetMembers();
			if(!$this->classifyFeeGroup()){
				$values = $objFilterSet->getResult($this->getFilterMembersAsFilterGroup());
				$this->processArray[] = array(
					'values' => $values['data'],
					'total' => $values['total'],
					'additional_data' => array(
						'assoc_nr' => '',
						'assoc_name' => '',
						'club_nr' => '',
						'club_name' => '',
						'exp_name' => $this->getMembershipExportDefinitionName(),
						'begin' => $this->getBeginDateFormatted(),
						'end' => $this->getEndDateFormatted()
				)
				);

			}else{
				$feeGroups = $this->getFeeGroups();
				foreach($feeGroups as $feeGroup){
					$memberFilters = $this->getFilterMembersAsFilterGroup();
					$memberFilters->addFilter($memberFilters->createFilter('fee_group_id','in',array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $feeGroup->getId()
					)));
						
						
					$values = $objFilterSet->getResult($memberFilters);
					$this->processArray[] = array(
						'values' => $values['data'],
						'total' => $values['total'],
						'additional_data' => array(
							'exp_name' => $this->getMembershipExportDefinitionName(),
							'assoc_nr' => '',
							'assoc_name' => '',
							'club_nr' => '',
							'club_name' => '',
							'fee_group' => $feeGroup->__get('name'),
							'fee_group_key' => $feeGroup->__get('key'),
							'begin' => $this->getBeginDateFormatted(),
							'end' => $this->getEndDateFormatted()
					)
					);
				}
			}
		}
	}

	public function exportActionHistory(){

		if($this->classifyMainOrga() && !$this->classifySociety()){

		}elseif($this->classifyMainOrga() && !$this->classifySociety()){

		}else{
			$aObjActionHistory = Membership_Controller_ActionHistory::getInstance()->search(
			$this->getActionHistoryFilter(),
			new Tinebase_Model_Pagination(
			array( 'sort' => 'created_datetime', 'dir' => 'ASC')
			),
			false	// no relations
			);

			$this->processArray = Membership_Controller_ActionHistory::getInstance()->getDiffForResultSet($aObjActionHistory);
			Membership_Controller_PrintJobActionHistory::getInstance()->printResult($this->processArray, $this);
		}
	}

	public function exportFeeGroupOverview(){
		$this->memberController = Membership_Controller_SoMember::getInstance();
		$this->fgController = Membership_Controller_FeeGroup::getInstance();
		$this->memFgController = Membership_Controller_MembershipFeeGroup::getInstance();
		$this->feeGroups = $this->fgController->getAllFeeGroups('key');

		$totalCount = 0;
		$feeTotal = 0;

		// BG-Übersicht je Ortsgruppe
		if($this->classifySociety() && !$this->classifyMainOrga()){
			$result = array();
			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_society'));
			$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
			$aObjSoc = $this->memberController->search($filters, new Tinebase_Model_Pagination(
			array( 'sort' => 'member_nr', 'dir' => 'ASC')
			));

			foreach($aObjSoc as $parentMember){
				$parentContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
				$result[] = $this->getFeeGroupValues(
				array(array(
						'field' => 'parent_member_id',
						'operator' => 'AND', 	
						'value' => array(array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $parentMember->getId()
				))
				)), array(
						'soc_nr' => $parentMember->__get('member_nr'),
						'soc_org_name' => $parentContact->__get('org_name').' '.$parentContact->__get('company2')
				));
			}
			// BG-Übersicht je Gau
		}elseif($this->classifyMainOrga() && !$this->classifySociety()){
			$assocController = Membership_Controller_Association::getInstance();

			$result = array();

			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_main_orga'));
			$filters = new Membership_Model_AssociationFilter($filters, 'AND');
			$aObjAssoc = $assocController->search($filters, new Tinebase_Model_Pagination(
			array( 'sort' => 'association_nr', 'dir' => 'ASC')
			));

			foreach($aObjAssoc as $assoc){
				$result[] = $this->getFeeGroupValues(
				array(array(
						'field' => 'association_id',
						'operator' => 'in', 	
						'value' => array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $assoc->getId()
				)
				)), array(
						'assoc_nr' => $assoc->__get('association_nr'),
						'assoc_name' => $assoc->__get('association_name')
				));
			}

			// BG-Übersicht global (Hauptverein)
		}elseif(!$this->classifyMainOrga() && !$this->classifySociety()){
			$result = array(
			0 => $this->getFeeGroupValues(array(), array())
			);
		}
		$this->processArray = $result;
	}

	public function getProcessArray(){
		return $this->processArray;
	}

	private function getFeeGroupValues(array $memberFilters, array $additionalValues){
		$aResult = array(
			'values' => array()
		);
		foreach($this->feeGroups as $feeGroup){
			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_membership'));
			$filters[] = array(
					'field' => 'fee_group_id',
					'operator' => 'in',
					'value' => array(
						'field' => 'id',
						'operator' => 'equals',
						'value' => $feeGroup->getId()
			)
			);
			$filters = array_merge($filters, $memberFilters);
			$filters = new Membership_Model_SoMemberFilter($filters, 'AND');

			$count = $this->memberController->searchCount($filters);
			$fee = $this->memFgController->getFeeGroupFeeByCategory($feeGroup->getId(), 'I');
			$feeTotal = $count * $fee;
			$totalCount += $count;
			$totalFee += $feeTotal;

			$values = array(
					'FG_KEY' => $feeGroup->__get('key'),
					'FG_NAME' => $feeGroup->__get('name'),
					'count' => $count,
					'fee' => \org\sopen\app\util\format\Currency::formatCurrency($fee),
					'totalfee' => \org\sopen\app\util\format\Currency::formatCurrency($feeTotal)
			);
			$values = array_merge($values, $additionalValues);
			$aResult['values'][] = $values;
		}
		$aResult['sums'] = array(
				'members_total' => $totalCount,
				'fee_total' => \org\sopen\app\util\format\Currency::formatCurrency($totalFee)
		);
		return $aResult;
	}

	public function exportFeeOverview(){
		$this->memberController = Membership_Controller_SoMember::getInstance();
		$this->fgController = Membership_Controller_FeeGroup::getInstance();
		$this->memFgController = Membership_Controller_MembershipFeeGroup::getInstance();
		$this->feeGroups = $this->fgController->getAllFeeGroups('key');

		$totalCount = 0;
		$feeTotal = 0;
		$totalFeeTotal = 0;
		$totalMembersTotal = 0;
		$aResult = array();
		$aResultTemplate = array(
			'values' => array(),
			'sums' => array()
		);
			
		// BG-Übersicht je Ortsgruppe
		if($this->classifySociety() && !$this->classifyMainOrga()){
			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_society'));
			$filters = new Membership_Model_SoMemberFilter($filters, 'AND');
			$aObjSoc = $this->memberController->search($filters, new Tinebase_Model_Pagination(
			array( 'sort' => 'member_nr', 'dir' => 'ASC')
			));
			foreach($aObjSoc as $parentMember){
				$parentContact = $parentMember->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());
				$rowResult =  $aResultTemplate;
				$rowResult['values'] = $this->getFeeValues(
				array(array(
						'field' => 'parent_member_id',
						'operator' => 'AND', 	
						'value' => array(array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $parentMember->getId()
				))
				)), array(
						'soc_nr' => $parentMember->__get('member_nr'),
						'soc_org_name' => $parentContact->__get('org_name').' '.$parentContact->__get('company2')
				));
				$aResult[] = $rowResult;
			}
			// Beitrag-Übersicht je Gau
		}elseif($this->classifyMainOrga() && !$this->classifySociety()){
			$assocController = Membership_Controller_Association::getInstance();

			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_main_orga'));
			$filters = new Membership_Model_AssociationFilter($filters, 'AND');
			$aObjAssoc = $assocController->search($filters, new Tinebase_Model_Pagination(
			array( 'sort' => 'association_nr', 'dir' => 'ASC')
			));
			$rowResult = array(
				'values' => array(),
				'sums' => array()
			);
			foreach($aObjAssoc as $assoc){
				$aCalc = $this->getFeeValues(
				array(array(
						'field' => 'association_id',
						'operator' => 'in', 	
						'value' => array(
							'field' => 'id',
							'operator' => 'equals',
							'value' => $assoc->getId()
				)
				)), array(
						'assoc_nr' => $assoc->__get('association_nr'),
						'assoc_name' => $assoc->__get('association_name')
				));
				$totalFeeTotal += $aCalc['totalfee_float'];
				$totalMembersTotal += $aCalc['count'];
				$rowResult['values'][] = $aCalc;
			}

			$rowResult['sums'] = array(
				'fee_total' => \org\sopen\app\util\format\Currency::formatCurrency((float)$totalFeeTotal),
				'members_total' => $totalMembersTotal
			);
			$aResult[] = $rowResult;
			// Beitrag-Übersicht Ortsgruppen je Gau
		}elseif($this->classifyMainOrga() && $this->classifySociety()){
			$assocController = Membership_Controller_Association::getInstance();

			$result = array();

			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_main_orga'));
			$filters = new Membership_Model_AssociationFilter($filters, 'AND');
			$aObjAssoc = $assocController->search($filters, new Tinebase_Model_Pagination(
			array( 'sort' => 'association_nr', 'dir' => 'ASC')
			));

			foreach($aObjAssoc as $assoc){
				$totalCount = 0;
				$feeTotal = 0;
				$totalFeeTotal = 0;
				$totalMembersTotal = 0;
				$filters = Zend_Json::decode($this->exportDefinition->__get('filter_society'));
				$filters[] = array(
					'field' => 'association_id',
					'operator' => 'in', 	
					'value' => array(
						'field' => 'id',
						'operator' => 'equals',
						'value' => $assoc->getId()
				)
				);
				$filters = new Membership_Model_SoMemberFilter($filters, 'AND');

				$aObjSoc = $this->memberController->search($filters, new Tinebase_Model_Pagination(
				array( 'sort' => 'member_nr', 'dir' => 'ASC')
				));
				$rowResult =  $rowResult = array(
					'values' => array(),
					'sums' => array()
				);
				foreach($aObjSoc as $soc){
					$contact = $soc->getForeignRecord('contact_id', Addressbook_Controller_Contact::getInstance());

					$aCalc = $this->getFeeValues(
					array(array(
							'field' => 'association_id',
							'operator' => 'in', 	
							'value' => array(
								'field' => 'id',
								'operator' => 'equals',
								'value' => $assoc->getId()
					)
					),array(
							'field' => 'parent_member_id',
							'operator' => 'AND', 	
							'value' => array(array(
								'field' => 'id',
								'operator' => 'equals',
								'value' => $soc->getId()
					))
					)),
					array(
							'assoc_nr' => $assoc->__get('association_nr'),
							'assoc_name' => $assoc->__get('association_name'),
							'soc_nr' => $soc->__get('member_nr'),
							'soc_name' => $contact->__get('org_name').' '.$contact->__get('company2')
					));
					$totalFeeTotal += $aCalc['totalfee_float'];
					$totalMembersTotal += $aCalc['count'];
					$rowResult['values'][] = $aCalc;

				}
				$rowResult['sums'] = array(
						'fee_total' => \org\sopen\app\util\format\Currency::formatCurrency((float)$totalFeeTotal),
						'members_total' => $totalMembersTotal
				);
				$aResult[] = $rowResult;
			}
			// BG-Übersicht global (Hauptverein)
		}elseif(!$this->classifyMainOrga() && !$this->classifySociety()){
			$rowResult =  $aResultTemplate;
			$rowResult['values'] = $this->getFeeValues(array(), array());
			$aResult[]  = $rowResult;
		}
		$this->processArray = $aResult;
	}

	private function getFeeValues(array $memberFilters, array $additionalValues){
		$aResult = array(
			'values' => array()
		);
		foreach($this->feeGroups as $feeGroup){
			$filters = Zend_Json::decode($this->exportDefinition->__get('filter_membership'));
			$filters[] = array(
				'field' => 'fee_group_id',
				'operator' => 'in',
				'value' => array(
					'field' => 'id',
					'operator' => 'equals',
					'value' => $feeGroup->getId()
			)
			);
			$filters = array_merge($filters, $memberFilters);
			$filters = new Membership_Model_SoMemberFilter($filters, 'AND');

			$count = $this->memberController->searchCount($filters);
			$fee = $this->memFgController->getFeeGroupFeeByCategory($feeGroup->getId(), 'I');
			$feeTotal = $count * $fee;
			$totalCount += $count;
			$totalFee += $feeTotal;
		}
		$values = array(
			'count' => $totalCount,
			'totalfee' => \org\sopen\app\util\format\Currency::formatCurrency($totalFee),
			'totalfee_float' => $totalFee
		);
		$values = array_merge($values, $additionalValues);
		return $values;
	}

	/**
	 *
	 * Set the export definition
	 * @param Membership_Model_MembershipExport $def
	 */
	protected function setMembershipExportDefinition(Membership_Model_MembershipExport $def){
		$this->exportDefinition = $def;
	}

	public function getExportDefinition(){
		return $this->exportDefinition;
	}

	public function isCalculation(){
		return $this->isResultTypeCount();
	}

	public function isCalculationTypeUnspecified(){
		return $this->exportDefinition->__get('calculation_type') == 'UNSPECIFIED';
	}

	public function isCalculationTypeFeeGroupOverview(){
		return $this->exportDefinition->__get('calculation_type') == 'FEEGROUPOVERVIEW';
	}

	public function isCalculationTypeFeeOverview(){
		return $this->exportDefinition->__get('calculation_type') == 'FEEOVERVIEW';
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function isResultSourceAsset(){
		return $this->exportDefinition->__get('result_source') == 'ASSET';
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function isResultSourceFlow(){
		return $this->exportDefinition->__get('result_source') == 'FLOW';
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function isResultTypeCount(){
		return $this->exportDefinition->__get('result_type') == 'COUNT';
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function isResultTypeData(){
		return $this->exportDefinition->__get('result_type') == 'DATA';
	}
	public function hasFilterMainOrga(){
		return $this->exportDefinition->__get('filter_main_orga');
	}
	public function hasFilterSociety(){
		return $this->exportDefinition->__get('filter_society');
	}
	public function hasFilterMembers(){
		return $this->exportDefinition->__get('filter_members');
	}
	public function hasFilterSetMembers(){
		try{
			$this->getFilterSetMembers();
			return true;
		}catch(Exception $e){
			// catch record not found exception
			return false;
		}
	}

	public function getFilterSetMembers(){
		return $this->exportDefinition->getForeignRecord('filter_set_id', Membership_Controller_FilterSet::getInstance());
	}

	public function getFilterMainOrga(){
		return Zend_Json::decode($this->exportDefinition->__get('filter_main_orga'));
	}
	public function getFilterSociety(){
		return Zend_Json::decode($this->exportDefinition->__get('filter_society'));
	}
	public function getFilterMembers(){
		return Zend_Json::decode($this->exportDefinition->__get('filter_membership'));
	}
	public function getFilterMembersAsFilterGroup(){
		return new Membership_Model_SoMemberFilter($this->getFilterMembers(),'AND');
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function classifyMainOrga(){
		return $this->exportDefinition->__get('classify_main_orga');

		if(count($this->getFilterMainOrga())==0){
			return false;
		}
		return true;
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function classifySociety(){

		return $this->exportDefinition->__get('classify_society');

		if(count($this->getFilterSociety())==0){
			return false;
		}
		return true;
	}
	/**
	 *
	 * Enter description here ...
	 */
	public function classifyFeeGroup(){
		return $this->exportDefinition->__get('classify_fee_group');
	}

	public function setActionHistoryFilterFromJson($ahFilterJson){
		$filter = Zend_Json::decode($ahFilterJson);
		$this->setActionHistoryFilter(new Membership_Model_ActionHistoryFilter($filter,'AND'));
	}

	public function setActionHistoryFilter(Membership_Model_ActionHistoryFilter $ahFilter){
		$this->actionHistoryFilter = $ahFilter;
	}

	public function getActionHistoryFilter(){
		return $this->actionHistoryFilter;
	}

	public function hasFilterSet(){
		$filterSet = $this->exportDefinition->getForeignRecordBreakNull('filter_set_id', Membership_Controller_FilterSet::getInstance());
		return !is_null($filterSet);
	}

	public function getMemberSortArray(){
		$sort = array(
			'sort' => 'member_nr',
			'dir' => 'ASC'
			);
			if($this->exportDefinition->__get('member_sortfield1')!== 'UNDEFINED'){
				$sort = array(
				'sort' => array($this->exportDefinition->__get('member_sortfield1')),
				'dir' => 'ASC'
				);
			}

			if($this->exportDefinition->__get('member_sortfield2')!== 'UNDEFINED'){
				if(!is_array($sort['sort'])){
					$sort = array('sort'=>array(), 'dir' => 'ASC');
				}
				$sort['sort'] = $this->exportDefinition->__get('member_sortfield2');
			}

			return $sort;
	}

	public function getMemberPagination(){
		return new Tinebase_Model_Pagination(
		$this->getMemberSortArray()
		);
	}

	private function getFeeGroups(){
		if(!$this->feeGroups){
			$this->fgController = Membership_Controller_FeeGroup::getInstance();
			$this->feeGroups = $this->fgController->getAllFeeGroups('key');
		}
		return $this->feeGroups;
	}
}