<?php
class Membership_Model_SoMemberFilter extends Tinebase_Model_Filter_FilterGroup implements Tinebase_Model_Filter_AclFilter
{
    /**
     * @var string application of this filter group
     */
    protected $_applicationName = 'Membership';
    
    protected $_className = 'Membership_Model_SoMemberFilter';
    
    /**
     * @var array filter model fieldName => definition
     */
    protected $_filterModel = array(
    	'query'       => array('filter' => 'Membership_Model_MemberQueryFilter'),
        'id'          => array('filter' => 'Tinebase_Model_Filter_Id'),
    	'member_nr' => array('filter' => 'Tinebase_Model_Filter_Text'),
    	'member_nr_numeric' => array('filter' => 'Membership_Model_MemberNumberNumericFilter'),
    	'member_ext_nr' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'contact_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Addressbook_Model_ContactFilter', 
                'controller'        => 'Addressbook_Controller_Contact'
            )
        ),
        'parent_member_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Membership_Model_SoMemberFilter', 
                'controller'        => 'Membership_Controller_SoMember'
            )
        ),
        'association_contact_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
            'options' => array(
                'filtergroup'       => 'Addressbook_Model_ContactFilter', 
                'controller'        => 'Addressbook_Controller_Contact'
            )
        ),
//        'association_id' => array('filter' => 'Tinebase_Model_Filter_ForeignId', 
//            'options' => array(
//                'filtergroup'       => 'Membership_Model_AssociationFilter', 
//                'controller'        => 'Membership_Controller_Association'
//            )
//        ),
        'fee_group_id' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'association_id' => array('filter' => 'Tinebase_Model_Filter_Id'),
        'membership_type' => array('filter'=>'Tinebase_Model_Filter_Text'),
        'membership_status' => array('filter'=>'Tinebase_Model_Filter_Text'),
        'sex' => array('filter'=>'Tinebase_Model_Filter_Text'),
        'parent_member_nr' => array('filter' => 'Membership_Model_ParentMemberNrFilter'),
        'assoc_nr' => array('filter' => 'Membership_Model_AssocNrFilter'),
        'club' => array('filter' => 'Membership_Model_ClubQueryFilter'),
        'created_by'     => array('filter' => 'Tinebase_Model_Filter_User'),
        'begin_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'birth_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'birth_year' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'termination_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'exp_membercard_datetime' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'fee_from_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'fee_to_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'admission_fee_payed' => array('filter' => 'Tinebase_Model_Filter_Bool'),
        'age_current_period' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'member_age' => array('filter' => 'Membership_Model_MemberAgeFilter'),
        'person_age' => array('filter' => 'Membership_Model_PersonAgeFilter'),
        'contact_custom_field' => array('filter' => 'Membership_Model_ContactCustomFieldFilter'),
        'fee_payment_method' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'debit_auth_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'committee_id' => array('filter' => 'Membership_Model_SoMemberCommitteeFilter'),
        'committee_function_id' => array('filter' => 'Membership_Model_SoMemberCommitteeFunctionFilter'),
        'termination_reason_id' => array('filter' => 'Tinebase_Model_Filter_Text'),
        'customfield'          => array('filter' => 'Tinebase_Model_Filter_CustomField', 'options' => array('idProperty' => 'membership.id')),
        'print_reception_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'print_confirmation_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'print_discharge_date' => array('filter' => 'Tinebase_Model_Filter_Date'),
        'member_card_year' => array('filter' => 'Tinebase_Model_Filter_Int'),
        'status_due_date' => array('filter' => 'Membership_Model_StatusAtDueDateFilter', 'alias' => 'dta'),
        'is_affiliator'             => array('filter' => 'Tinebase_Model_Filter_Bool', 'alias' => 'co'),
        'is_affiliated'             => array('filter' => 'Tinebase_Model_Filter_Bool', 'alias' => 'co'),
        'affiliate_contact_id' => array('filter' => 'Tinebase_Model_Filter_Int', 'alias' => 'co'),
        'affiliator_provision_date'             => array('filter' => 'Tinebase_Model_Filter_Date', 'alias' => 'co'),
        'affiliator_provision'   => array('filter' => 'Tinebase_Model_Filter_Int', 'alias' => 'co'),
        'count_magazines'             => array('filter' => 'Tinebase_Model_Filter_Int', 'alias' => 'co'),
        'count_additional_magazines'             => array('filter' => 'Tinebase_Model_Filter_Int', 'alias' => 'co')
        
        //'status_due_date' => array('filter' => 'Tinebase_Model_Filter_Text', 'alias' => 'dta')
        
    );
    
	public function __construct(array $_data = array(), $_condition='AND', $_options = array())
    {
    	parent::__construct($_data, $_condition, $_options);
    }
}
?>