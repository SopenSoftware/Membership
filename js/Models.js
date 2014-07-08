Ext.ns('Tine.Membership','Tine.Membership.Model');

Tine.Membership.Model.CommitteeKindArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'is_default'}
];

Tine.Membership.Model.CommitteeKind = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CommitteeKindArray, {
	appName: 'Membership',
	modelName: 'CommitteeKind',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Gremium-Art',
	recordsName: 'Gremium-Arten',
	containerProperty: null
});

Tine.Membership.Model.CommitteeKind.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.CommitteeKind.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.AwardListArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'is_default'},
	{name: 'key'}
];

Tine.Membership.Model.AwardList = Tine.Tinebase.data.Record.create(Tine.Membership.Model.AwardListArray, {
	appName: 'Membership',
	modelName: 'AwardList',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Auszeichnung',
	recordsName: 'Auszeichnungen',
	containerProperty: null
});

Tine.Membership.Model.AwardList.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.AwardList.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.CommitteeLevelArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'is_default'}
];

Tine.Membership.Model.CommitteeLevel = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CommitteeLevelArray, {
	appName: 'Membership',
	modelName: 'CommitteeLevel',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Gremium-Ebene',
	recordsName: 'Gremium-Ebenen',
	containerProperty: null
});

Tine.Membership.Model.CommitteeLevel.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.CommitteeLevel.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};


Tine.Membership.Model.CommitteeFunctionArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'is_default'},
	{name: 'committee_kind_id'}
];

Tine.Membership.Model.CommitteeFunction = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CommitteeFunctionArray, {
	appName: 'Membership',
	modelName: 'CommitteeFunction',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Gremien-Funktion',
	recordsName: 'Gremien-Funktionen',
	containerProperty: null
});

Tine.Membership.Model.CommitteeFunction.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.CommitteeFunction.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.ActionArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'category'}
];

Tine.Membership.Model.Action = Tine.Tinebase.data.Record.create(Tine.Membership.Model.ActionArray, {
	appName: 'Membership',
	modelName: 'Action',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Aktion',
	recordsName: 'Aktionen',
	containerProperty: null
});

Tine.Membership.Model.Action.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.Action.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.EntryReasonArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'key'},
	{name: 'is_default'}
];

Tine.Membership.Model.EntryReason = Tine.Tinebase.data.Record.create(Tine.Membership.Model.EntryReasonArray, {
	appName: 'Membership',
	modelName: 'EntryReason',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Eintrittsgrund',
	recordsName: 'Eintrittsgründe',
	containerProperty: null
});

Tine.Membership.Model.EntryReason.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.EntryReason.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.TerminationReasonArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'key'},
	{name: 'is_default'}
];

Tine.Membership.Model.TerminationReason = Tine.Tinebase.data.Record.create(Tine.Membership.Model.TerminationReasonArray, {
	appName: 'Membership',
	modelName: 'TerminationReason',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Austrittsgrund',
	recordsName: 'Austrittsgründe',
	containerProperty: null
});

Tine.Membership.Model.TerminationReason.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.TerminationReason.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};



Tine.Membership.Model.ActionHistoryArray = 
[
	{name: 'id'},
	{name: 'member_id'},
	{name: 'association_id'},
	{name: 'parent_member_id'},
	{name: 'old_data_id'},
	{name: 'data_id'},
	{name: 'fee_progress_id'},
	{name: 'order_id'},
	{name: 'receipt_id'},
	{name: 'action_id'},
	{name: 'action_text'},
	{name: 'action_data'},
	{name: 'action_category'},
	{name: 'action_type'},
	{name: 'action_state'},
	{name: 'error_info'},
	{name: 'created_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'valid_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'to_process_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'process_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'created_by_user'},
	{name: 'processed_by_user'}
];

Tine.Membership.Model.ActionHistory = Tine.Tinebase.data.Record.create(Tine.Membership.Model.ActionHistoryArray, {
	appName: 'Membership',
	modelName: 'ActionHistory',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Mitgliedsaktion',
	recordsName: 'Mitgliedsaktionen',
	containerProperty: null
});

Tine.Membership.Model.ActionHistory.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.ActionHistory.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: app.i18n._('Aktion'),  field: 'action_id',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:Tine.Membership.getStore('Action')},
        {label: app.i18n._('Status'),  field: 'action_state',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['OPEN', 'offen'],['DONE','erledigt'],['ERROR', 'Fehler']]},
        {label: _('Benutzer (Erfassung)'),   field: 'created_by_user',  valueType: 'user'},
	    {label: _('Benutzer (Ausführung)'),   field: 'processed_by_user',  valueType: 'user'},
	    {label: app.i18n._('Nr. Verein'),  field: 'parent_member_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Nr. Verband'),  field: 'assoc_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {app: app, filtertype: 'foreignrecord', label: 'Mitglied', field: 'member_id', foreignRecordClass: Tine.Membership.Model.SoMember, ownField:'member_id'},
        {app: app, filtertype: 'foreignrecord', label: 'Überg. Mitglied', field: 'parent_member_id', foreignRecordClass: Tine.Membership.Model.SoMember, ownField:'parent_member_id'},
        {label: _('Angelegt am'),         field: 'created_datetime', valueType: 'date'},
        {label: _('Gültig ab'),         field: 'valid_datetime', valueType: 'date'},
        {label: _('Ausgeführt am'),         field: 'process_datetime', valueType: 'date'}
  ];
};


Tine.Membership.Model.MembershipKindArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'parent_kind_id'},
	{name: 'dialog_text'},
	{name: 'dialog_text_assoc'},
	{name: 'dialog_text_member_nr'},
	{name: 'dialog_text_member_ext_nr'},
	{name: 'subject_singular'},
	{name: 'subject_plural'},
	{name: 'is_default'},
	{name: 'uses_fee_progress'},
	{name: 'uses_member_fee_groups'},
	{name: 'identical_contact'},
	{name: 'invoice_template_id'},
	{name: 'has_functionaries'},
	{name: 'has_functions'},
	{name: 'fee_group_is_duty'},
	{name: 'addressbook_id'},
	{name: 'default_tab'},
	{name: 'begin_letter_template_id'},
	{name: 'insurance_letter_template_id'},
	{name: 'termination_letter_template_id'},
	{name: 'membercard_letter_template_id'}
];

Tine.Membership.Model.MembershipKind = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MembershipKindArray, {
	appName: 'Membership',
	modelName: 'MembershipKind',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Mitgliedsart',
	recordsName: 'Mitgliedsarten',
	containerProperty: null
});

Tine.Membership.Model.MembershipKind.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.MembershipKind.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.FeeGroupArray = 
[
	{name: 'id'},
	{name: 'name'},
	{name: 'key'},
	{name: 'membership_kind_id'},
	{name: 'article_id'},
	{name: 'is_default'},
	{name: 'customfields'}
];

Tine.Membership.Model.FeeGroup = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeGroupArray, {
	appName: 'Membership',
	modelName: 'FeeGroup',
	idProperty: 'id',
	titleProperty: 'feegroup_title',
	recordName: 'Beitragsgruppe',
	recordsName: 'Beitragsgruppen',
	containerProperty: null,
	useTitleMethod:true,
	getTitle: function(){
		return this.get('key') + ' ' + this.get('name');
	}
});

Tine.Membership.Model.FeeGroup.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.FeeGroup.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.MembershipFeeGroupArray = 
[
	{name: 'id'},
	{name: 'member_id'},
	{name: 'fee_group_id'},
	{name: 'fee_group_key'},
	{name: 'article_id'},
	{name: 'valid_from_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'valid_to_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'price'},
	{name: 'category'},
	{name: 'summarize'}
];

Tine.Membership.Model.MembershipFeeGroup = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MembershipFeeGroupArray, {
	appName: 'Membership',
	modelName: 'MembershipFeeGroup',
	idProperty: 'id',
	titleProperty: 'price',
	recordName: 'spez. Beitrag',
	recordsName: 'spez. Beiträge',
	containerProperty: null
});

Tine.Membership.Model.MembershipFeeGroup.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.MembershipFeeGroup.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};


Tine.Membership.Model.AssociationArray = 
[
	{name: 'id'},
	{name: 'contact_id'},
	{name: 'association_nr'},
	{name: 'association_name'},
	{name: 'short_name'},
	{name: 'is_default'}
];

Tine.Membership.Model.Association = Tine.Tinebase.data.Record.create(Tine.Membership.Model.AssociationArray, {
   appName: 'Membership',
   modelName: 'Association',
   idProperty: 'id',
   titleProperty: 'association_title',
   recordName: 'Hauptorganisation',
   recordsName: 'Hauptorganisation',
   containerProperty: null,
   useTitleMethod:true,
   getTitle: function(){
		return this.get('association_nr') + ' ' + this.get('association_name');
   },
   getContact: function(){
	   	var assocContact = this.get('contact_id');
	   	var assocContactId = assocContact.id;
	   	assocContact.jpegphoto = null;
	   	return new Tine.Addressbook.Model.Contact(assocContact,assocContactId);
   }
});

Tine.Membership.Model.Association.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.Association.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: app.i18n._('Bezeichung'),  field: 'association_name'},
        {label: app.i18n._('Kurzbezeichnung'),  field: 'short_name'},
        {label: app.i18n._('Nummer'),  field: 'association_nr', operators: ['equals','startswith','endswith','greater','less']}
    ];
};


/**
* sopen membership model
*/
Tine.Membership.Model.SoMemberArray = [
  {name: 'id'},
  {name: 'contact_id'},
  {name: 'parent_member_id'},
  {name: 'association_id'},
  {name: 'fee_group_id'},
  {name: 'account_id'},
  {name: 'member_nr'},
  {name: 'member_nr_numeric'},
  {name: 'member_ext_nr'},
  //{name: 'affiliate_contact_id'},
  {name: 'begin_datetime' },
  {name: 'discharge_datetime'},
  {name: 'termination_datetime'},
  {name: 'exp_membercard_datetime'},
  {name: 'entry_reason_id'},
  {name: 'termination_reason_id'},
  {name: 'member_notes'},
  {name: 'invoice_fee'},
  {name: 'membership_type'},
  {name: 'membership_status'},
  {name: 'society_sopen_user'},
  {name: 'fee_payment_interval'},
  {name: 'fee_payment_method'},
  {name: 'debit_auth_date'},
  {name: 'bank_code'},
  {name: 'bank_name'},
  {name: 'bank_account_nr'},
  {name: 'account_holder'},
  {name: 'is_online_user'},
  {name: 'is_family_leading'},
  {name: 'has_account'},
  {name: 'individual_admission_fee'},
  {name: 'pays_admission_fee'},
  {name: 'admission_fee_payed'},
  {name: 'individual_yearly_fee'},
  {name: 'age_current_period'},
  {name: 'begin_progress_nr'},
  {name: 'additional_fee'},
  {name: 'donation'},
  {name: 'feegroup_prices'},
  {name: 'customfields'},
  {name: 'birth_date'},
  {name: 'birth_year'},
  {name: 'member_age'},
  {name: 'person_age'},
  {name: 'sex'}, 
  {name: 'fee_from_date'},
  {name: 'fee_to_date'},
  {name: 'ext_system_username'},
  {name: 'ext_system_modified'},
  {name: 'print_reception_date'},
  {name: 'print_discharge_date'},
  {name: 'print_confirmation_date'},
  {name: 'member_card_year'},
  {name: 'debitor_id'},
  {name: 'count_open_items'},
  {name: 's_brutto'},
  {name: 'h_brutto'},
  {name: 'saldation'},
  {name: 'last_receipt_id'},
  {name: 'last_receipt_date'},
  {name: 'last_receipt_netto'},
  {name: 'last_receipt_brutto'},
  {name: 'public_comment'},
  {name: 'status_due_date'},
  {name: 'is_affiliator'},
  {name: 'affiliate_contact_id'},
  {name: 'affiliator_provision'},
  {name: 'affiliator_provision_date',       type: 'date', dateFormat: Date.patterns.ISO8601Short},
  {name: 'is_affiliated'},
  {name: 'count_magazines'},
  {name: 'count_additional_magazines'},
  {name: 'sepa_mandate_id'},
  {name: 'bank_account_id'},
  {name: 'bic'},
  {name: 'iban'},
  {name: 'bank_account_number'},
  {name: 'bank_account_bank_code'},
  {name: 'bank_account_name'},
  {name: 'bank_account_bank_name'},
  {name: 'sepa_mandate_ident'},
  {name: 'sepa_signature_date'}
];

/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.SoMember = Tine.Tinebase.data.Record.create(Tine.Membership.Model.SoMemberArray, {
   appName: 'Membership',
   modelName: 'SoMember',
   idProperty: 'id',
   recordName: 'Mitglied',
   recordsName: 'Mitglieder',
   containerProperty: 'contact_id',
   containerName: 'Kontakt',
   containersName: 'Kontakte',
   titleProperty: 'somember_record_title',
   getContact: function(){
   	var contact = this.get('contact_id');
   	var contactId = contact.id; 
   	contact.jpegphoto = null;
   	return new Tine.Addressbook.Model.Contact(contact,contactId);
   },
   getTitle: function(){
	 return this.get(this.titleProperty);  
   },
   isClub: function(){
	   return this.get('membership_type') == 'SOCIETY';
   },
   isSingle: function(){
	   return this.get('membership_type') == 'SINGLE';
   },
   isFamily: function(){
	   return this.get('membership_type') == 'FAMILY';
   },
   isFamilyLeading: function(){
	   return (this.isFamily() & this.get('is_family_leading') == 1);
   },
   isFamilyDependent: function(){
	   return (this.isFamily() & this.get('is_family_leading') == 0);
   },
   isClubMember: function(){
	   return this.get('membership_type') == 'VIASOCIETY';
   },
   isInsurance: function(){
	   return this.get('membership_type') == 'TCINSURANCE';
   },
   getFeeGroupPrices: function(){
	   return this.get('feegroup_prices');   
   },
   relations:[{
   name: 'somember_contact',
	model: Tine.Addressbook.Model.Contact,
	fkey: 'contact_id',
	embedded:true,
	emissions:[
	    {dest: {
	    	name: 'somember_contact_title'}, 
	    	source: function(contact, somember){
	    			if(typeof(contact) === 'object'){
	    				if(somember.get('membership_type') == 'SOCIETY'){
	    					return contact.get('org_name') + ' AP: ' +contact.getTitle();
	    				}else{
	    					return contact.getTitle() + ' ' + contact.get('org_name');
	    				}
		    		}else{
		    			return contact;
		    		}
		    	}
	    	},
	    	{dest: {
	 	    	name: 'somember_contact_orgname'}, 
	 	    	source: function(contact, somember){
 	    			if(typeof(contact) === 'object'){
    					return contact.get('org_name');
 		    		}else{
 		    			return contact;
 		    		}
	 		    }
	 	    },
	    	{dest: {
		    	name: 'membership_named_type'}, 
		    	source: function(contact, somember){
		    		return Tine.Membership.renderer.memshipType(somember.get('membership_type'));
		    	}
		    },
		    {dest: {
	 	    	name: 'somember_record_title'}, 
	 	    	source: function(contact, somember){
	 	    		if(typeof(contact) === 'object'){
    					var title = somember.get('member_nr') + ' ' + contact.getTitle(true);
 		    		}else{
 		    			var title = '';
 		    			if(typeof(somember)==='object'){
 		    				title += somember.get('member_nr');
 		    			}
 		    			title += contact;
 		    		}
	 	    		return title;
	 		    }
	 	    }
		]
	 }]
});

Tine.Membership.Model.SoMember.getDefaultData = function(){
	return {
		fee_payment_interval:'YEAR'
	};
};

Tine.Membership.Model.SoMember.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        //{label: _('Sporttaucher'), field:'contact_custom_field', type:'customfield', cfId:'ctVDSTSportdiver',operators:['equals','contains','greater','less']},
        
        
        {label: app.i18n._('Mitglied-Nr'),  field: 'member_nr', operators: ['equals','startswith','endswith']},
        {label: app.i18n._('Mitglied-Nr (numerisch)'),  field: 'member_nr_numeric', operators: ['equals','greater','less'], defaultOperator:'equals'},
        {label: app.i18n._('Mitglied-Nr-2'),  field: 'member_ext_nr', operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Nr. Verein'),  field: 'parent_member_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Nr. Verband'),  field: 'assoc_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Art Mitgliedschaft'),  field: 'membership_type',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not','in','notin'],
        	injectStore:true, useSimpleSelector:true, store:Tine.Membership.getStore('MembershipKind')},
        {label: app.i18n._('Gremium'),  field: 'committee_id',  valueType: 'combo', valueField:'id', displayField:'name', 
              	store:Tine.Membership.getStore('Committee')},
        {label: app.i18n._('Funktion in Gremium'),  field: 'committee_function_id',  valueType: 'combo', valueField:'id', displayField:'name', 
                  	store:Tine.Membership.getStore('CommitteeFunction')},
            	
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'],
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
        {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
           
        //{app: app, filtertype: 'foreignrecord', label: 'übergeord. Mitglied', field: 'parent_member_id', foreignRecordClass: Tine.Membership.Model.SoMember, ownField:'parent_member_id'},
        //{label: app.i18n._('Verein'),  field: 'club', operators: ['contains']},
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'date'},
        {label: _('Eintritts-Datum'),         field: 'begin_datetime', valueType: 'date'},
        {label: _('Austritts-Datum'),         field: 'termination_datetime', valueType: 'date'},
        {label: _('Exp.Mg.Ausweis-Datum'),         field: 'exp_membercard_datetime', valueType: 'date'},
        {label: _('Bank-EZErm'),         field: 'debit_auth_date', valueType: 'date'},
        
        {label: app.i18n._('Austrittsgrund'),  field: 'termination_reason_id',  valueType: 'combo', valueField:'id', displayField:'name',  operators: ['equals','not'],
           	store:Tine.Membership.getStore('TerminationReason')},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Jahr Mitgliedsausweis'),  field: 'member_card_year', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
        {label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
              	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  },
    	{app: app, filtertype: 'foreignrecord', label: 'Kontakt', field: 'contact_id', foreignRecordClass: Tine.Addressbook.Model.Contact, ownField:'contact_id'}
        
    ];
};

Tine.Membership.Model.SoMember.getFilterModelForContact = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        //{label: _('Sporttaucher'), field:'contact_custom_field', type:'customfield', cfId:'ctVDSTSportdiver',operators:['equals','contains','greater','less']},
        
        
        {label: app.i18n._('Mitglied-Nr'),  field: 'member_nr', operators: ['equals','startswith','endswith']},
        {label: app.i18n._('Mitglied-Nr (numerisch)'),  field: 'member_nr_numeric', operators: ['equals','greater','less'], defaultOperator:'equals'},
        {label: app.i18n._('Mitglied-Nr-2'),  field: 'member_ext_nr', operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Nr. Verein'),  field: 'parent_member_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Nr. Verband'),  field: 'assoc_nr', operators: ['contains'], operators: ['equals','startswith','endswith','greater','less']},
        {label: app.i18n._('Art Mitgliedschaft'),  field: 'membership_type',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not','in','notin'],
        	injectStore:true, useSimpleSelector:true, store:Tine.Membership.getStore('MembershipKind')},
        {label: app.i18n._('Gremium'),  field: 'committee_id',  valueType: 'combo', valueField:'id', displayField:'name', 
              	store:Tine.Membership.getStore('Committee')},
        {label: app.i18n._('Funktion in Gremium'),  field: 'committee_function_id',  valueType: 'combo', valueField:'id', displayField:'name', 
                  	store:Tine.Membership.getStore('CommitteeFunction')},
            	
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'],
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
        {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
           
        //{app: app, filtertype: 'foreignrecord', label: 'übergeord. Mitglied', field: 'parent_member_id', foreignRecordClass: Tine.Membership.Model.SoMember, ownField:'parent_member_id'},
        //{label: app.i18n._('Verein'),  field: 'club', operators: ['contains']},
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'date'},
        {label: _('Eintritts-Datum'),         field: 'begin_datetime', valueType: 'date'},
        {label: _('Austritts-Datum'),         field: 'termination_datetime', valueType: 'date'},
        {label: _('Exp.Mg.Ausweis-Datum'),         field: 'exp_membercard_datetime', valueType: 'date'},
        {label: _('Bank-EZErm'),         field: 'debit_auth_date', valueType: 'date'},
        
        {label: app.i18n._('Austrittsgrund'),  field: 'termination_reason_id',  valueType: 'combo', valueField:'id', displayField:'name',  operators: ['equals','not'],
           	store:Tine.Membership.getStore('TerminationReason')},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Jahr Mitgliedsausweis'),  field: 'member_card_year', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
        {label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
              	store:Tine.Billing.getSimpleStore('PaymentMethod')}
    ];
};

Tine.Membership.Model.SoMember.getReducedFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: app.i18n._('Mitglied-Nr'),  field: 'member_nr', operators: ['equals','startswith','endswith']},
        {label: app.i18n._('Mitglied-Nr (numerisch)'),  field: 'member_nr_numeric', operators: ['equals','greater','less'], defaultOperator:'equals'},
        {label: app.i18n._('Mitglied-Nr-2'),  field: 'member_ext_nr'},
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'], 
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
      
            {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
               	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
               	
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'date'},
        {label: _('Eintritts-Datum'),         field: 'begin_datetime', valueType: 'date'},
        {label: _('Austritts-Datum'),         field: 'termination_datetime', valueType: 'date'},
        {label: _('Exp.Mg.Ausweis-Datum'),         field: 'exp_membercard_datetime', valueType: 'date'},
        
        {label: app.i18n._('Austrittsgrund'),  field: 'termination_reason_id',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:Tine.Membership.getStore('TerminationReason')},
        {label: app.i18n._('Gremium'),  field: 'committee_id',  valueType: 'combo', valueField:'id', displayField:'name', 
          	store:Tine.Membership.getStore('Committee')},
    {label: app.i18n._('Funktion in Gremium'),  field: 'committee_function_id',  valueType: 'combo', valueField:'id', displayField:'name', 
              	store:Tine.Membership.getStore('CommitteeFunction')},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Jahr Mitgliedsausweis'),  field: 'member_card_year', valueType: 'number'},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
       	{label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
          	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  },
       	{app: app, filtertype: 'foreignrecord', label: 'Kontakt', field: 'contact_id', foreignRecordClass: Tine.Addressbook.Model.Contact, ownField:'contact_id'}
        
    ];
};

Tine.Membership.Model.SoMember.getFilterModelForFeeDefinitionIterator = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    
    return [
        {label: app.i18n._('Art Mitgliedschaft'),  field: 'membership_type',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
        	store:Tine.Membership.getStore('MembershipKind')},
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'],  
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'],  
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
        {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
       
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'date'},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
           	{label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
              	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  }   
 
    ];
};

Tine.Membership.Model.SoMember.getFilterModelForFeeDefinition = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
      return [
        {label: app.i18n._('Art Mitgliedschaft'),  field: 'membership_type',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'],  
        	store:Tine.Membership.getStore('MembershipKind')},
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'],  
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
        {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
       
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'date'},
            
           	{label: app.i18n._('Eintritts-Datum'),         field: 'begin_datetime', valueType: 'date'},
        {label: app.i18n._('Austritts-Datum'),         field: 'termination_datetime', valueType: 'date'},
        {label: app.i18n._('Austrittsgrund'),  field: 'termination_reason_id',  valueType: 'combo', valueField:'id', displayField:'name', 
        	store:Tine.Membership.getStore('TerminationReason')},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
           	{label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
              	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  }    
    ];
};

Tine.Membership.Model.SoMember.getFilterModelForDynamicExport = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
      return [
        {label: app.i18n._('Art Mitgliedschaft'),  field: 'membership_type',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
        	store:Tine.Membership.getStore('MembershipKind')},
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'], 
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Status'),  field: 'membership_status',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'],  
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
        {label: app.i18n._('Status-Stichtag'),  field: 'status_due_date',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['ACTIVE', 'aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']]},
       
        {label: _('Geburts-Datum'),         field: 'birth_date', valueType: 'dynamicdate'},
            
        {label: app.i18n._('Eintritts-Datum'),         field: 'begin_datetime', valueType: 'dynamicdate'},
        {label: app.i18n._('Austritts-Datum'),         field: 'termination_datetime', valueType: 'dynamicdate'},
        {label: app.i18n._('Exp.Mg.Ausweis-Datum'),         field: 'exp_membercard_datetime', valueType: 'date'},
        
        {label: app.i18n._('Austrittsgrund'),  field: 'termination_reason_id',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
        	store:Tine.Membership.getStore('TerminationReason')},
        {label: app.i18n._('Geb./Gründ.jahr'),  field: 'birth_year', valueType: 'number'},
        {label: app.i18n._('Alter Mitglied'),  field: 'person_age', valueType: 'number'},
        {label: app.i18n._('Jahr Mitgliedsausweis'),  field: 'member_card_year', valueType: 'number'},
        {label: app.i18n._('Mitgliedsjahre'),  field: 'member_age', valueType: 'number'},
        {label: app.i18n._('Geschlecht'),  field: 'sex',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:[['MALE', 'männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']]},
       	{label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
          	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  }   
    ];
};

Tine.Membership.Model.SoMember.getFilterModelForEconomicView = function(){
	var app = Tine.Tinebase.appMgr.get('Membership');
	
	var baseFields = Tine.Membership.Model.SoMember.getFilterModel();
    baseFields = baseFields.concat(
    [
        {label: app.i18n._('Saldo S'),  field: 's_brutto',       operators: ['equals','greater','less'] },
        {label: app.i18n._('Saldo H'),  field: 'h_brutto',       operators: ['equals','greater','less'] },
        {label: app.i18n._('Saldo Gesamt'),  field: 'saldation',       operators: ['equals','greater','less'] },
       	{label: app.i18n._('Zahlungsmethode'),  field: 'fee_payment_method',  valueType: 'combo', valueField:'id', displayField:'name',
          	store:Tine.Billing.getSimpleStore('PaymentMethod')},
      	{label: app.i18n._('ist Werber'), field: 'is_affiliator',  valueType: 'bool' },
        {label: app.i18n._('ist geworben'), field: 'is_affiliated',  valueType: 'bool' },
        {label: app.i18n._('Werber-Nr'), field: 'affiliate_contact_id', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Werber.Prov.Datum'), field: 'affiliator_provision_date', valueType: 'date' },
        {label: app.i18n._('Werber.Provision Betrag'), field: 'affiliator_provision', valueType:'number', operators: ['greater','less','equals'] },
        {label: app.i18n._('Anz. Zeitungen'), field: 'count_magazines', valueType:'number', operators: ['greater','less','equals']  },
        {label: app.i18n._('Anz. zus. Zeitungen'), field: 'count_additional_magazines', valueType:'number', operators: ['greater','less','equals']  }
    ]);
    return baseFields;
};


// set equivalent models for economic view
Tine.Membership.Model.SoMemberEconomic = Tine.Membership.Model.SoMember;


Tine.Membership.Model.SoMemberFeeProgressArray = [
	{name: 'id'},
	{name: 'member_id'},
	// -> not contained: contact_id -> phantom for level 2 foreignrecord filter
	{name: 'contact_id'},
	{name: 'parent_member_id'},
	{name: 'order_id'},
	{name: 'invoice_receipt_id'},
	{name: 'cancellation_receipt_id'},
	{name: 'fee_definition_id'},
	{name: 'fee_group_id'},
	{name: 'fee_from_datetime'},
	{name: 'fee_to_datetime'},
	{name: 'fee_year'},
	{name: 'progress_nr'},
	{name: 'is_calculation_approved'},
	{name: 'fee_period_notes'},
	{name: 'fee_calc_datetime'},
	{name: 'amount_admission_fee'},
	{name: 'individual_yearly_fee'},
	{name: 'age'},
	{name: 'fee_units'},
	{name: 'is_first'},
	{name: 'deb_summation'},
	{name: 'fee_to_calculate'},
	{name: 'sum_brutto'},
	{name: 'payment_state'},
	{name: 'open_sum'},
	{name: 'payed_sum'},
	{name: 'payment_date'},
	{name: 'monition_stage'},
	{name: 'due_days'},
	{name: 'is_cancelled'},
	{name: 'fg_begin_datetime'},
	{name: 'fg_termination_datetime'},
	{name: 'fg_membership_status'},
	{name: 'fg_member_nr'}
];

Tine.Membership.Model.SoMemberFeeProgress = Tine.Tinebase.data.Record.create(Tine.Membership.Model.SoMemberFeeProgressArray, {
   appName: 'Membership',
   modelName: 'SoMemberFeeProgress',
   idProperty: 'id',
   recordName: 'Beitragsverlauf',
   recordsName: 'Beitragsverläufe',
   relations:[
	   {
			name: 'so_member',
			model: Tine.Membership.Model.SoMember,
			fkey: 'member_id',
			embedded:true,
			emissions:[
			    {dest: {
			    	name: 'member_nr'}, 
			    	source: function(soMember){
			    		if(typeof(soMember) === 'object'){
			    			if(soMember.get === undefined){
			    				return '';
			    			}
			    			return soMember.get('member_nr');
			    		}else{
			    			return '';
			    		}
			    	}
			    }
			]
		}
   ]
});

Tine.Membership.Model.SoMemberFeeProgress.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.SoMemberFeeProgress.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: app.i18n._('Beitragsgruppe'),  field: 'fee_group_id',  valueType: 'combo', valueField:'id', displayField:'name',operators: ['equals','not'],
           	store:Tine.Membership.getStore('FeeGroup')},
        {label: app.i18n._('Zahlungsstatus'),  field: 'payment_state',  valueType: 'combo', valueField:'id', displayField:'name', operators: ['equals','not'], 
           	store:[['NOTDUE', 'noch nicht fällig'],['TOBEPAYED','unbezahlt'],['PARTLYPAYED', 'teilbezahlt'],['PAYED','bezahlt']]},
        {label: _('Freigabe Beitragsrechnung'),   field: 'is_calculation_approved',  valueType: 'bool'},
        {label: app.i18n._('Beitragsjahr'),         field: 'fee_year', valueType: 'number', operators: ['equals','greater','less']},
        {label: app.i18n._('Beitr.Rechnung am'),         field: 'fee_calc_datetime', valueType: 'date'},
        {label: app.i18n._('Beitr.pflicht. ab'),         field: 'fee_from_datetime', valueType: 'date'},
        {label: app.i18n._('Beitr.pflicht. bis'),         field: 'fee_to_datetime'},
        {label: app.i18n._('Zahlsaldo vor Sollst.'),         field: 'deb_summation', valueType: 'number', operators: ['equals','greater','less']},
        {label: app.i18n._('Sollgest. Beitrag'),         field: 'sum_brutto', valueType: 'number', operators: ['equals','greater','less']},
        {label: app.i18n._('Vorschau Summe Beitrag'),         field: 'fee_to_calculate', valueType: 'number', operators: ['equals','greater','less']},
        // -> must be integrated in own backend filter:just alias does not work{label: app.i18n._('Tage fällig'),         field: 'due_days', valueType: 'number', operators: ['equals','greater','less']},
        {label: app.i18n._('Mahnstufe'),         field: 'monition_stage', valueType: 'number', operators: ['equals','greater','less']},
        {label: app.i18n._('Zahldatum'),         field: 'payment_date', valueType: 'date'},
        {app: app, filtertype: 'foreignrecord', label: 'Mitglied', field: 'member_id', foreignRecordClass: Tine.Membership.Model.SoMember, ownField:'member_id'},
        {app: app, filtertype: 'foreignrecord', label: 'Kontakt', field: 'contact_id', foreignRecordClass: Tine.Addressbook.Model.Contact, ownField:'contact_id'},
        {app: app, filtertype: 'foreignrecord', label: 'Beleg', field: 'invoice_receipt_id', foreignRecordClass: Tine.Billing.Model.Receipt, ownField:'invoice_receipt_id'}
    ];
}

/**
* sopen membership model
*/
Tine.Membership.Model.FeeDefinitionArray = [
  {name: 'id'},
  {name: 'order_template_id'},
  {name: 'name'},
  {name: 'description'},
  {name: 'iterator_filters'}
];

/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FeeDefinition = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeDefinitionArray, {
   appName: 'Membership',
   modelName: 'FeeDefinition',
   idProperty: 'id',
   recordName: 'Beitragsordnung',
   recordsName: 'Beitragsordnungen',
   containerProperty: null,
   titleProperty: 'fee_class_name'
});

Tine.Membership.Model.FeeDefinition.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FeeDefinition.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}


/**
* sopen membership model
*/
Tine.Membership.Model.FeeDefFilterArray = [
  {name: 'id'},
  {name: 'fee_definition_id'},
  {name: 'name'},
  {name: 'is_invoice_component'},
  {name: 'type'},
  {name: 'related_membership'},
  {name: 'filters'}
];

/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FeeDefFilter = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeDefFilterArray, {
   appName: 'Membership',
   modelName: 'FeeDefFilter',
   idProperty: 'id',
   recordName: 'Filter abhängige Mitglieder',
   recordsName: 'Filter abhängige Mitglieder',
   containerProperty: null,
   titleProperty: 'name'
});

Tine.Membership.Model.FeeDefFilter.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FeeDefFilter.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}


/**
* sopen membership model
*/
Tine.Membership.Model.FeeVarConfigArray = [
  {name: 'id'},
  {name: 'fee_definition_id'},
  {name: 'feedef_dfilters_id'},
  {name: 'name'},
  {name: 'label'},
  {name: 'type'},
  {name: 'vartype'},
  {name: 'floatvalue'},
  {name: 'intvalue'},
  {name: 'textvalue'},
  {name: 'dataobject'},
  {name: 'compare1'},
  {name: 'compare_value1'},
  {name: 'result_value1'},
  {name: 'compare2'},
  {name: 'compare_value2'},
  {name: 'result_value2'},
  {name: 'compare3'},
  {name: 'compare_value3'},
  {name: 'result_value3'},
  {name: 'compare4'},
  {name: 'compare_value4'},
  {name: 'result_value4'},
  {name: 'compare5'},
  {name: 'compare_value5'},
  {name: 'result_value5'},
  {name: 'compare6'},
  {name: 'compare_value6'},
  {name: 'result_value6'},
  {name: 'compare7'},
  {name: 'compare_value7'},
  {name: 'result_value7'},
  {name: 'transform1'},
  {name: 'transform2'}
];


/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FeeVarConfig = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeVarConfigArray, {
   appName: 'Membership',
   modelName: 'FeeVarConfig',
   idProperty: 'id',
   recordName: 'Variablendefinition',
   recordsName: 'Variablendefinitionen',
   containerProperty: null,
   titleProperty: 'name',
   isFloat: function(){
	   return this.get('type')=='FLOAT';
   },
   isInteger: function(){
	   return this.get('type')=='INTEGER';
   },
   isString: function(){
	   return this.get('type')=='TEXT';
   },
   getFloatValue: function(){
	   return this.get('floatvalue');
   },
   getIntValue: function(){
	   return this.get('intvalue');
   },
   getStringValue: function(){
	   return this.get('stringvalue');
   },
   getValue: function(){
	   switch(this.get('type')){
	   case 'FLOAT':
		   return this.getFloatValue();
	   case 'INTEGER':
		   return this.getIntValue();
	   case 'TEXT':
		   return this.getTextValue();
	   }
   }
});

Tine.Membership.Model.FeeVarConfig.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FeeVarConfig.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}


/**
* sopen membership model
*/
Tine.Membership.Model.FeeVarArray = [
  {name: 'id'},
  {name: 'fee_var_config_id'},
  {name: 'fee_progress_id'},
  {name: 'floatvalue'},
  {name: 'intvalue'},
  {name: 'textvalue'},
  {name: 'value'}
];


/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FeeVar = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeVarArray, {
   appName: 'Membership',
   modelName: 'FeeVar',
   idProperty: 'id',
   recordName: 'Variable',
   recordsName: 'Variablen',
   containerProperty: null,
   titleProperty: 'name'
});

Tine.Membership.Model.FeeVar.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FeeVar.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}

/**
* sopen membership model
*/
Tine.Membership.Model.FeeVarOrderPosArray = [
  {name: 'id'},
  {name: 'order_pos_id'},
  {name: 'use_fee_var_config_id'},
  {name: 'amount_fee_var_config_id'},
  {name: 'price_netto_fee_var_config_id'},
  {name: 'price_brutto_fee_var_config_id'},
  {name: 'name_fee_var_config_id'},
  {name: 'factor_fee_var_config_id'}
];


/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FeeVarOrderPos = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FeeVarOrderPosArray, {
   appName: 'Membership',
   modelName: 'FeeVarOrderPos',
   idProperty: 'id',
   recordName: 'Positionzuord. Variable',
   recordsName: 'Positionzuord. Variable',
   containerProperty: null,
   titleProperty: 'name'
});

Tine.Membership.Model.FeeVarOrderPos.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FeeVarOrderPos.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}

/**
* sopen membership model
*/
Tine.Membership.Model.FilterSetArray = [
  {name: 'id'},
  {name: 'conjunction'},
  {name: 'result_type'},
  {name: 'transform'},
  {name: 'name'},
  {name: 'description'}
];

/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FilterSet = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FilterSetArray, {
   appName: 'Membership',
   modelName: 'FilterSet',
   idProperty: 'id',
   recordName: 'Filtergruppe',
   recordsName: 'Filtergruppen',
   containerProperty: null,
   titleProperty: 'name'
});

Tine.Membership.Model.FilterSet.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FilterSet.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}

/**
* sopen membership model
*/
Tine.Membership.Model.FilterResultArray = [
  {name: 'id'},
  {name: 'filter_set_id'},
  {name: 'sort_order'},
  {name: 'name'},
  {name: 'key'},
  {name: 'type'},
  {name: 'sub_type'},
  {name: 'filters'},
  {name: 'sum_category'},
  {name: 'scalar_formula1'},
  {name: 'scalar_formula2'}
];

/**
* @type {Tine.Tinebase.data.Record}
* Contact record definition
*/
Tine.Membership.Model.FilterResult = Tine.Tinebase.data.Record.create(Tine.Membership.Model.FilterResultArray, {
   appName: 'Membership',
   modelName: 'FilterResult',
   idProperty: 'id',
   recordName: 'Filter-Ergebnis',
   recordsName: 'Filter-Ergenisse',
   containerProperty: null,
   titleProperty: 'name'
});

Tine.Membership.Model.FilterResult.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.FilterResult.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
    ];
}

Tine.Membership.Model.CreateMemberAccountArray = [
	{name: 'id'},
	{name: 'auto_create_pass'},   
	{name: 'user_email_as_login_name'},
	{name: 'user_name'},
	{name: 'contact_id'},
	{name: 'related_member_id'},
	{name: 'member_id'},
	{name: 'member_account_mail'}
   ];
   Tine.Membership.Model.CreateMemberAccount = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CreateMemberAccountArray, {
	   	appName: 'Membership',
		modelName: 'CreateMemberAccount',
		idProperty: 'id',
		recordName: 'MemberAccountCreate',
		recordsName: 'MemberAccountCreate',
		containerProperty: null,
		titleProperty: 'id'
   });

   Tine.Membership.Model.CreateMemberAccount.getDefaultData = function(){
   	return {
   		auto_create_pass:1
   	};
   };
   
Tine.Membership.Model.TDImportJob = Tine.Tinebase.data.Record.create
   ([
     {name: 'files'                  },
     //{name: 'import_definition_id'   },
     //{name: 'model'                  },
     {name: 'import_function'        },
     //{name: 'container_id'           },
     //{name: 'dry_run'                },
     //{name: 'options'                }
 ], {
     appName: 'Membership',
     modelName: 'Import',
     idProperty: 'id',
     titleProperty: 'model',
     // ngettext('Import', 'Imports', n); gettext('Import');
     recordName: 'Import',
     recordsName: 'Imports',
     containerProperty: null
 });

Tine.Membership.Model.MembershipExportArray = 
	[
		{name: 'id'},
		{name: 'output_template_id'},
		{name: 'filter_set_id'},
		{name: 'name'},
		{name: 'calculation_type'},
		{name: 'classify_main_orga'},
		{name: 'classify_society'},
		{name: 'classify_fee_group'},
		{name: 'classify_mem_kind'},
		{name: 'result_source'},
		{name: 'result_type'},
		{name: 'output_type'},
		{name: 'begin_datetime'},
		{name: 'end_datetime'},
		{name: 'filter_main_orga'},
		{name: 'filter_society'},
		{name: 'filter_membership'},
		{name: 'assoc_sortfield1'},
		{name: 'assoc_sortfield1_dir'},
		{name: 'assoc_sortfield2'},
		{name: 'assoc_sortfield2_dir'},
		{name: 'society_sortfield1'},
		{name: 'society_sortfield1_dir'},
		{name: 'society_sortfield2'},
		{name: 'society_sortfield2_dir'},
		{name: 'member_sortfield1'},
		{name: 'member_sortfield1_dir'},
		{name: 'member_sortfield2'},
		{name: 'member_sortfield2_dir'}
	];

	Tine.Membership.Model.MembershipExport = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MembershipExportArray, {
		appName: 'Membership',
		modelName: 'MembershipExport',
		idProperty: 'id',
		titleProperty: 'name',
		recordName: 'Mitgliederexport',
		recordsName: 'Mitgliederexport',
		containerProperty: null
	});

	Tine.Membership.Model.MembershipExport.getDefaultData = function(){
		return {
			output_template_id: null,
			filter_set_id: null,
			
			name:''
		};
	};

	Tine.Membership.Model.MembershipExport.getFilterModel = function() {
		var app = Tine.Tinebase.appMgr.get('Membership');
		return [
		    {label: _('Quick search'),          field: 'id',       operators: ['contains']}
		];
	};
	
Tine.Membership.Model.CommitteeArray = 
[
	{name: 'id'},
	{name: 'committee_kind_id'},
	{name: 'committee_level_id'},
	{name: 'committee_nr'},
	{name: 'name'},
	{name: 'challenge'},
	{name: 'description'},
	{name: 'begin_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'end_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'jur_committee'}
];

Tine.Membership.Model.Committee = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CommitteeArray, {
	appName: 'Membership',
	modelName: 'Committee',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Gremium',
	recordsName: 'Gremien',
	containerProperty: null
});

Tine.Membership.Model.Committee.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.Committee.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};


Tine.Membership.Model.CommitteeFuncArray = 
[
	{name: 'id'},
	{name: 'member_id'},
	{name: 'parent_member_id'},
	{name: 'association_id'},
	{name: 'committee_id'},
	{name: 'committee_function_id'},
	{name: 'description'},
	{name: 'begin_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'end_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'management_mail'},
	{name: 'treasure_mail'}
];

Tine.Membership.Model.CommitteeFunc = Tine.Tinebase.data.Record.create(Tine.Membership.Model.CommitteeFuncArray, {
	appName: 'Membership',
	modelName: 'CommitteeFunc',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Gremium-Funktion',
	recordsName: 'Gremium-Funktionen',
	containerProperty: null
});

Tine.Membership.Model.CommitteeFunc.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.CommitteeFunc.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']},
	    {label: app.i18n._('Funktion'),  field: 'committee_function_id',  valueType: 'combo', valueField:'id', displayField:'name', 
           	store:Tine.Membership.getStore('CommitteeFunction')},
        {label: _('Beginn'),         field: 'begin_datetime', valueType: 'date'},
        {label: _('Ende'),         field: 'end_datetime', valueType: 'date'}
           
        
	];
};

Tine.Membership.Model.MembershipAwardArray = 
[
	{name: 'id'},
	{name: 'member_id'},
	{name: 'award_list_id'},
	{name: 'award_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long}
];

Tine.Membership.Model.MembershipAward = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MembershipAwardArray, {
	appName: 'Membership',
	modelName: 'MembershipAward',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Mitglied Auszeichnung',
	recordsName: 'Mitglied Auszeichnungen',
	containerProperty: null
});

Tine.Membership.Model.MembershipAward.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.MembershipAward.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.JobArray = 
[
	{name: 'id'},
	{name: 'account_id'},
	{name: 'job_nr'},
	{name: 'job_name1'},
	{name: 'job_name2'},
	{name: 'job_category'},
	{name: 'job_type'},
	{name: 'job_data'},
	{name: 'job_state'},
	{name: 'job_result_state'},
	{name: 'on_error'},
	{name: 'process_info'},
	{name: 'error_info'},
	{name: 'ok_count'},
	{name: 'skip_count'},
	{name: 'error_count'},
	{name: 'create_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'schedule_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'start_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'end_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'modified_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
	{name: 'process_percentage'},
	{name: 'task_count'}
];

Tine.Membership.Model.Job = Tine.Tinebase.data.Record.create(Tine.Membership.Model.JobArray, {
	appName: 'Membership',
	modelName: 'Job',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Job',
	recordsName: 'Jobs',
	containerProperty: null
});

Tine.Membership.Model.Job.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.Job.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.MembershipAccountArray = 
[
	{name: 'id'},
	{name: 'account_id'},
	{name: 'account_loginname'},
	{name: 'account_emailadress'},
	{name: 'account_lastpasswordchange'},
	{name: 'account_lastlogin'},
	{name: 'contact_id'},
	{name: 'related_member_id'},
	{name: 'member_id'},
	{name: 'valid_from_datetime', type: 'datetime', dateFormat: Date.patterns.ISO8601Long},
	{name: 'valid_to_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long}
];

Tine.Membership.Model.MembershipAccount = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MembershipAccountArray, {
	appName: 'Membership',
	modelName: 'MembershipAccount',
	idProperty: 'id',
	titleProperty: 'name',
	recordName: 'Mitglieds-Zugang',
	recordsName: 'Mitglieds-Zugänge',
	containerProperty: null
});

Tine.Membership.Model.MembershipAccount.getDefaultData = function(){
	return {};
};

Tine.Membership.Model.MembershipAccount.getFilterModel = function() {
	var app = Tine.Tinebase.appMgr.get('Membership');
	return [
	    {label: _('Quick search'),          field: 'query',       operators: ['contains']}
	];
};

Tine.Membership.Model.OpenItemArray = [
    {name: 'id'}, 	// (pk)
    {name: 'order_id'},
    {name: 'op_nr'},
    {name: 'receipt_id'},
    {name: 'debitor_id'},
    {name: 'payment_method_id'},
    {name: 'receipt_date'}, 
    {name: 'receipt_nr'}, 
    {name: 'type'},
    {name: 'due_date'},
    {name: 'fibu_exp_date'}, 
    {name: 'total_netto'},
    {name: 'total_brutto'},
    {name: 'context'},
    {name: 'banking_exp_date'}, 
    {name: 'state'}
  ];

  /**
  * @type {Tine.Tinebase.data.Record}
  * Contact record definition
  */
  Tine.Membership.Model.OpenItem = Tine.Tinebase.data.Record.create(Tine.Membership.Model.OpenItemArray, {
  	appName: 'Membership',
 	modelName: 'OpenItem',
 	idProperty: 'id',
 	recordName: 'Offener Posten',
 	recordsName: 'Offene Posten',
 	containerProperty: null,
 	titleProperty: 'name'
  });

 Tine.Membership.Model.OpenItem.getDefaultData = function(){
 	return {};
 };
  
 Tine.Membership.Model.OpenItem.getFilterModel = function() {
     var app = Tine.Tinebase.appMgr.get('Membership');
     return [
             {label: app.i18n._('Mitglied-Nr'),  field: 'member_nr', operators: ['equals','startswith','endswith','contains']},
             {label: app.i18n._('Status'),  field: 'state',  valueType: 'combo', valueField:'id', displayField:'name', 
                	store:[['OPEN', 'offen'],['DONE','erledigt/bezahlt']]},
        	 {label: app.i18n._('Typ'),  field: 'type',  valueType: 'combo', valueField:'id', displayField:'name', 
            	store:[['DEBIT', 'Belastung'],['CREDIT','Gutschrift']]},
             {label: _('Belegdatum'),         field: 'receipt_date', valueType: 'date', pastOnly: true},
             {label: _('Fälligkeitsdatum'),         field: 'due_date', valueType: 'date'},
             {label: _('Datum Fibu Exp.'),         field: 'fibu_exp_date', valueType: 'date', pastOnly: true},
             {label: _('Datum Bank Exp.'),         field: 'banking_exp_date', valueType: 'date', pastOnly: true},
             {label: app.i18n._('Kontext'),  field: 'erp_context_id',  valueType: 'combo', valueField:'id', displayField:'name', 
             	store:[['ERP', 'ERP'],['MEMBERSHIP','Mitglieder'],['DONATOR','Spenden']]}
     ];
 };
 
 /**
 * sopen membership model
 */
 Tine.Membership.Model.MessageArray = [
   {name: 'id'},
   {name: 'receiver_group_id'},
   {name: 'receiver_account_id'},
   {name: 'sender_account_id'},
   {name: 'parent_member_id'},
   {name: 'member_id'},
   {name: 'receiver_type'},
   {name: 'send_mail'},
   {name: 'direction'},
   {name: 'subject'},
   {name: 'message'},
   {name: 'ticket'},
   {name: 'created_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long},
   {name: 'expiry_datetime', type: 'date', dateFormat: Date.patterns.ISO8601Long}
 ];


 /**
 * @type {Tine.Tinebase.data.Record}
 * Contact record definition
 */
 Tine.Membership.Model.Message = Tine.Tinebase.data.Record.create(Tine.Membership.Model.MessageArray, {
    appName: 'Membership',
    modelName: 'Message',
    idProperty: 'id',
    recordName: 'Nachricht',
    recordsName: 'Nachrichten',
    containerProperty: null,
    titleProperty: 'name',
    
    getTicket: function(){
    	return this.get('ticket');
    },
    getTicketData: function(){
    	return Ext.util.JSON.decode(this.getTicket());
    },
    hasTicketItem: function(key){
    	var ticketData = this.getTicketData();
    	try{
	    	if(ticketData[key] !== undefined){
	    		return true;
	    	}
    	}catch(e){
    		return false;
    	}
    	return false;
    },
    getTicketItem: function(key){
    	var ticketData = this.getTicketData();
    	if(!this.hasTicketItem(key)){
    		throw new Exception('Membership.Model.Message: Ticket does not contain item ' + key);
    	}
    	return ticketData[key];
    },
    getTicketItemBreakNull: function(key){
    	try{
    		return this.getTicketItem(key);
    	}catch(e){
    		return null;
    	}
    }
 });

 Tine.Membership.Model.Message.getDefaultData = function(){
 	return {
 	};
 };

 Tine.Membership.Model.Message.getFilterModel = function() {
     var app = Tine.Tinebase.appMgr.get('Membership');
     return [
         {label: _('Quick search'),          field: 'query',       operators: ['contains']},
         {label: app.i18n._('Eingang/Ausgang'),  field: 'direction',  valueType: 'combo', valueField:'id', displayField:'name', 
          	store:[['IN', 'Eingang'],['OUT','Ausgang']]},
         {label: _('verfasst am'),         field: 'created_datetime', valueType: 'date'}
     ];
 }