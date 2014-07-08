<?php
class Membership_Backend_SoMember extends Tinebase_Backend_Sql_Abstract
{
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'membership';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Membership_Model_SoMember';
    
    protected $defaultAge = 0;
    
        
    public function __construct ($_dbAdapter = NULL, $_modelName = NULL, $_tableName = NULL, $_tablePrefix = NULL, $_modlogActive = NULL, $_useSubselectForCount = NULL){
    	parent::__construct($_dbAdapter, $_modelName, $_tableName, $_tablePrefix , $_modlogActive, $_useSubselectForCount);
    	
		$this->setDefaultAge();
    }
    
    public function setDefaultAge($age = null){
    	if(is_null($age) || $age == ''){
    		$this->defaultAge = \Tinebase_Config::getInstance()->getConfig('MembersDefaultAge', NULL, TRUE)->value;
			if(!$this->defaultAge){
				$this->defaultAge = 0;
			}
			
    	}else{
    		$this->defaultAge = $age;
    	}
    }
    
    public function getDefaultAge(){
    	return $this->defaultAge;
    }
    

    /**
     * if modlog is active, we add 'is_deleted = 0' to select object in _getSelect()
     *
     * @var boolean
     */
    protected $_modlogActive = false;
    
    public function search(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_onlyIds = FALSE, $withDep = true){
    	// TODO HH: no ids searchable
    	// check if needed anywhere and modify if so
    	$recordSet = parent::search($_filter,$_pagination,$_onlyIds, $withDep, $_property);
    	if( $withDep && ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
			$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
	public function searchProperty(Tinebase_Model_Filter_FilterGroup $_filter = NULL, Tinebase_Model_Pagination $_pagination = NULL, $_property = null){
    	// TODO HH: no ids searchable
    	// check if needed anywhere and modify if so
    	$aResult = parent::search($_filter,$_pagination,false, false, $_property);
    	return $aResult;
    }
    
    /**
     * Append contacts by foreign key (record embedding)
     * 
     * @param Tinebase_Record_Abstract $record
     * @return void
     */
    protected function appendDependentRecords($record){
      	if($record->__get('contact_id')){
    		$this->appendForeignRecordToRecord($record, 'contact_id', 'contact_id', 'id', Addressbook_Backend_Factory::factory(Addressbook_Backend_Factory::SQL));
    	}
       	if($record->__get('parent_member_id')){
       		$id = $record->__get('parent_member_id');
       		try{
       		$societyMember = $this->get($id, false, true);
       		$record->__set('parent_member_id', $societyMember->toArray(true));
       		}catch(Exception $e){
       			// --> TODO
       		}
    		//$this->appendForeignRecordToRecord($record, 'parent_member_id', 'parent_member_id', 'id', new Membership_Backend_SoMember());
    	}
        if($record->__get('association_id')){
    		$this->appendForeignRecordToRecord($record, 'association_id', 'association_id', 'id', new Membership_Backend_Association());
    	}    			
        /*if($record->__get('affiliate_contact_id')){
    		$this->appendForeignRecordToRecord($record, 'affiliate_contact_id', 'affiliate_contact_id', 'id', Addressbook_Backend_Factory::factory(Addressbook_Backend_Factory::SQL));
    	} */
    	if($record->__get('fee_group_id')){
    		$this->appendForeignRecordToRecord($record, 'fee_group_id', 'fee_group_id', 'id', new Membership_Backend_FeeGroup());
    	} 
    	   
        if($record->__get('fee_payment_method')){
    		$this->appendForeignRecordToRecord($record, 'fee_payment_method', 'fee_payment_method', 'id', new Billing_Backend_PaymentMethod());
        }  
        
         if($record->__get('entry_reason_id')){
    		$this->appendForeignRecordToRecord($record, 'entry_reason_id', 'entry_reason_id', 'id', new Membership_Backend_EntryReason());
        } 

		if($record->__get('termination_reason_id')){
    		$this->appendForeignRecordToRecord($record, 'termination_reason_id', 'termination_reason_id', 'id', new Membership_Backend_TerminationReason());
        }  
    	if($record->__get('bank_account_id')){
    		$this->appendForeignRecordToRecord($record, 'bank_account_id', 'bank_account_id', 'id', new Billing_Backend_BankAccount());
    	}
   	 	if($record->__get('sepa_mandate_id')){
    		$this->appendForeignRecordToRecord($record, 'sepa_mandate_id', 'sepa_mandate_id', 'id', new Billing_Backend_SepaMandate());
    	}
    }
    
    /**
     * (non-PHPdoc)
     * @see Tinebase_Backend_Sql_Abstract::get()
     */
    public function get($id, $_getDeleted = FALSE, $getDependent = false){
    	$record = parent::get($id, $_getDeleted);
    	if($getDependent){
    		$this->appendDependentRecords($record);
    	}
    	return $record;
    }
    
	public function getSoMemberByNumber($memberNr){
    	$record = $this->getByProperty($memberNr, 'member_nr');
   		$this->appendDependentRecords($record);
   		return $record;
    }
    /**
     * 
     * Get the maximum member number of a club
     * @param string $societyMemberId
     * @throws Exception
     */
    public function getClubMaxMemberNr($memberId, $memberKind = null){
    	//throw new Exception('TODO');
        $select = $this->_db->select()
	    	->from(SQL_TABLE_PREFIX . 'membership', array('MAX(member_nr)'))
    		->where( "parent_member_id = '$memberId'");
    	if(!is_null($memberKind)){
    		$select->where("membership_type ='$memberKind'");
    	}
    	$stmt = $this->_db->query($select);
        $result = $stmt->fetchAll();
        return $result[0]['MAX(member_nr)'];
    }
    /**
     * 
     * Get the members of a club by its membership id
     * @param string $clubId
     * @throws Exception
     */
	public function getSoMembersByClubId($clubId){
		throw new Exception('TODO');
    	$recordSet = $this->getMultipleByProperty($clubId, 'society_contact_id');
    	if( ($recordSet instanceof Tinebase_Record_RecordSet) && ($recordSet->count()>0)){
    		$it = $recordSet->getIterator();
    		foreach($it as $key => $record){
				$this->appendDependentRecords($record);				
    		}
    	}
    	return $recordSet;
    }
    
    protected function _getSelect($_cols = '*', $_getDeleted = FALSE)
    {        
        
    	
    	$select = $this->_db->select();
        
        if (is_array($_cols) && isset($_cols['count'])) {
            $cols = array(
                'count'                => 'COUNT(*)'
            );
            
        } else {
            $cols = array_merge(
                (array)$_cols, 
                //array('account_id'    	=> 'co.account_id'),
                array('has_account'     => 'IF(co.account_id IS NOT NULL,1,0)')
            );
        }
        
        $baseYear = $this->getBaseYear();
        $dm = $this->getBaseDate()->get(Zend_Date::DAY_OF_YEAR);
        $isLeapYear = (int) Zend_Date::checkLeapYear($baseYear);
        //$strDueDate = $this->getDueDate()->toString('yyyy-MM-dd');
        
        
        //SELECT *, MIN(ABS(DATEDIFF(NOW(),valid_from))) FROM `sopen_membership_data` WHERE valid_from<=NOW() 
//GROUP BY member_id
        
       /* $subselect = $this->_db->select()
			->from($this->_tablePrefix . 'membership_data', array(
				'due_diff' => "MIN(ABS(DATEDIFF("."'".$strDueDate."'".",valid_from)))",
				'status_due_date' => 'membership_status',
				'membership_status' => 'membership_status',
				'member_id' => 'member_id'
			))
			->where(
				"valid_from <= '".$strDueDate."' AND valid_state='DONE' "
			)->group(array($this->_tablePrefix . 'membership_data.member_id'));
        */
          
        $addCols =  array(
        	'member_age'     => "IF((entry_year IS NOT NULL) AND (entry_year>0) ,($baseYear - entry_year),0)",
            'person_age'     => "IF((birth_date IS NOT NULL) AND (birth_year >0) AND (birth_month >0) AND (birth_year<=$baseYear) ,($baseYear - birth_year -($dm < (DATE_FORMAT(birth_date, '%j')-1+$isLeapYear))),".(int)$this->defaultAge.")",
			'member_nr_numeric' => "(member_nr)+0",
        	'is_affiliator' => 'co.is_affiliator',
	        'affiliate_contact_id' => 'co.affiliate_contact_id',
	        'affiliator_provision' => 'co.affiliator_provision',
	        'is_affiliated' => 'co.is_affiliated',
	        'count_magazines' => 'co.count_magazines',
	        'count_additional_magazines' => 'co.count_additional_magazines',
        
        	//'bank_account_id' => 'bact.id',
        	'bic' => 'ba.bic',
        	'iban' => 'bact.iban',
        	'bank_account_number' => 'bact.number',
        	'bank_account_bank_code' => 'ba.code',
        	'bank_account_name' => 'bact.name',
        	'bank_account_bank_name' => 'ba.name',
        	'sepa_mandate_id'	=> 'sepa.id',
        	'sepa_mandate_ident'	=> 'sepa.mandate_ident',
        	'sepa_signature_date'   => 'sepa.signature_date'
        
        	//,
        	//'status_due_date' => 'dta.membership_status'
        );			
        $cols = array_merge(
                $cols, 
                //array('account_id'    	=> 'co.account_id'),
               $addCols
				
                //array('person_age'     => "IF((birth_year IS NOT NULL) AND (birth_year >0) AND (birth_month >0) AND (birth_year<=$baseYear) ,$this->defaultAge,".(int)$this->defaultAge.")")
            );
            
            // (YEAR(CURRENT_DATE) - YEAR(Geburtsdatum)) - (DATE_FORMAT(CURRENT_DATE, '%d%m') < DATE_FORMAT(Geburtsdatum, '%d%m'))
        
        $select->from(array($this->_tableName => $this->_tablePrefix . $this->_tableName), $cols);
                // join with timeaccounts to get combined is_billable / is_cleared
        $select->joinLeft(array('co' => $this->_tablePrefix . 'addressbook'),
                    $this->_db->quoteIdentifier($this->_tableName . '.contact_id') . ' = ' . $this->_db->quoteIdentifier('co.id'),
                    array()); 

		$select->joinLeft(array('bact' => $this->_tablePrefix . 'bill_bank_account'),
			$this->_db->quoteIdentifier( 'bank_account_id') . ' = ' . $this->_db->quoteIdentifier('bact.id'),
			array()
		);
		
		$select->joinLeft(array('bactusage' => $this->_tablePrefix . 'bill_bank_account_usage'),
			$this->_db->quoteIdentifier( 'bactusage.bank_account_id') . ' = ' . $this->_db->quoteIdentifier('bact.id').' AND '.
			$this->_db->quoteIdentifier('bactusage.membership_id') . ' = ' .$this->_db->quoteIdentifier($this->_tableName .'.id') ,
			array()
		);
		
		$select->joinLeft(array('sepa' => $this->_tablePrefix . 'bill_sepa_mandate'),
			$this->_db->quoteIdentifier('bactusage.sepa_mandate_id') . ' = ' . $this->_db->quoteIdentifier('sepa.id'),
			array()
		);
        
        $select->joinLeft(array('ba' => $this->_tablePrefix . 'bill_bank'),
			$this->_db->quoteIdentifier('bact.bank_id') . ' = ' . $this->_db->quoteIdentifier('ba.id'),
			array()
		);      
        
        //SELECT EVENT_ID FROM TABLE WHERE EVENT_START_DATE > NOW() 
        //ORDER BY ABS(DATEDIFF(EVENT_START_DATE, NOW())) ASC LIMIT 3
                    
     /* $select->joinLeft(
      		array('dta' => $subselect),
            $this->_db->quoteIdentifier($this->_tableName . '.id') . ' = ' . $this->_db->quoteIdentifier('dta.member_id'),
        array());       
       */ 
                  
        if (!$_getDeleted && $this->_modlogActive) {
            // don't fetch deleted objects
            $select->where($this->_db->quoteIdentifier($this->_tableName . '.is_deleted') . ' = 0');                        
        }
        
       // echo $select->assemble();
        return $select;
    }
    
	public function getUnbilledMemberIdsForFeeYear($feeYear){
		
		/*
			SELECT id
			FROM sopen_membership
			WHERE id NOT IN (
				SELECT DISTINCT mm.id
				FROM sopen_membership AS mm
				JOIN sopen_membership_action_history AS ah ON ah.member_id = mm.id
				AND ah.action_id = 'BILLPARENTMEMBER'
				AND ah.valid_datetime >='2012-01-01'
				AND ah.valid_datetime <='2012-12-31'
			)
			
			
			SELECT DISTINCT member_id FROM sopen_membership_action_history WHERE
				action_id = 'BILLPARENTMEMBER'
				AND valid_datetime >='2012-01-01'
				AND valid_datetime <='2012-12-31'
		*/
		
		

		$subselect = $this->_db->select()
			->distinct()
			->from($this->_tablePrefix . 'membership_action_history', array('member_id'))
			->where(
				"action_id = 'BILLPARENTMEMBER' AND valid_datetime >= '$feeYear-01-01' AND valid_datetime <= '$feeYear-12-31'"
			);
			
		/*$select = $this->_db->select()
			->from(SQL_TABLE_PREFIX . 'membership', array('id'))
			->where("id NOT IN ?", $subselect);
			*/
			
		$result = $this->_db->fetchAll($subselect);
		$retVal = array();
		foreach($result as $arr){
			$retVal[] = $arr['member_id'];
		}
		return $retVal;
		
		/*
		
		$filter = new Membership_Model_ActionHistoryFilter(array(
			array(
				'field' => 'action_id',
				'operator' => 'in',
				'value' => array(array(
					'field' => 'id',
					'operator' => 'equals',
					'value' => Membership_Controller_Action::BILLPARENTMEMBER
				))
			)
		), 'AND');
		
		$dateFilter = new Tinebase_Model_Filter_Date(
			'valid_datetime',
			'within',
			'yearVar'
		);
		
		$dateFilter->setDueDate(new Zend_Date($feeYear.'-01-01'));
		
		$filter->addFilter( $dateFilter );
		
		$actionHistoryItems = $this->search($filter, null, false);
		return $actionHistoryItems->__get('member_id');
		*/
	}
}
?>