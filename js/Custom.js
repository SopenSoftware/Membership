Ext.ns('Tine.Membership.Custom');

Tine.Membership.Custom.getSoMemberRecordPicker = function(id, config){
	if(!id){
		id = 'soMemberEditorField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.SoMember,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    // default membership type: SOCIETY
	    membershipType: null,
	    appendFilters: [],
	    itemSelector: 'div.search-item',
	    minChars: 2,
	    onBeforeQuery: function(qevent){
	    	this.store.baseParams.filter = [
	    	    {field: 'query', operator: 'contains', value: qevent.query },
	            {field: 'membership_type', operator: 'equals', value: this.membershipType }
	        ];
	    	this.store.baseParams.filter = this.store.baseParams.filter.concat(this.appendFilters);
	    	this.store.baseParams.sort = 'member_nr';
	    	this.store.baseParams.dir = 'ASC';
	    },
	    setAppendFilters: function(appendFilters){
	    	this.appendFilters = appendFilters;
	    },
	    setMembershipType: function(membershipType){
	    	this.membershipType = membershipType;
	    },
	    onBeforeLoad: function(store, options) {
	        options.params.paging = {
                start: options.params.start,
                limit: options.params.limit
            };
	        options.params.sort = 'member_nr';
	        options.params.dir = 'ASC';
	        options.params.paging.sort = 'member_nr';
		    options.params.paging.dir = 'ASC';
	    },   
	    tpl:new Ext.XTemplate(
	            '<tpl for="."><div class="search-item">',
                '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 11px;" width="100%">',
                    '<tr  style="font-size: 11px;border-bottom:1px solid #000000;">',
                        '<td width="30%"><b>{[this.encode(values.member_nr)]}</b><br/>{[this.encode(values.membership_named_type)]}</td>',
                        '<td width="70%">{[this.encode(values.somember_contact_title)]}<br/>',
                            '{[this.encode(values.somember_contact_orgname)]}</td>',
                    '</tr>',
                '</table>',
            '</div></tpl>',
            {
                encode: function(value) {
                     if (value) {
                        return Ext.util.Format.htmlEncode(value);
                    } else {
                        return '';
                    }
                }
            }
        )
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getFeeDefFilterRecordPicker = function(id, config){
	if(!id){
		id = 'feeDefFilterRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.FeeDefFilter,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Abfrageergebnis',
	    feeDefinitionRecord: null,
	    setFeeDefinition: function(feeDefinitionRecord){
	    	this.feeDefinitionRecord = feeDefinitionRecord;
	    	// provide fluent interface
	    	return this;
	    },
	   // itemSelector: 'div.search-item',
	    onBeforeQuery: function(qevent){
	    	this.store.baseParams.filter = [
				{	
					field: 'fee_definition_id',
					operator:'AND',
					value:[{
						field:'id',
						operator:'equals',
						value: this.feeDefinitionRecord.get('id') }]
				}
			];
	    	this.store.baseParams.sort = 'name';
	    	this.store.baseParams.dir = 'ASC';
	    },
	    onBeforeLoad: function(store, options) {
	        options.params.paging = {
                start: options.params.start,
                limit: options.params.limit
            };
	        options.params.sort = 'name';
	        options.params.dir = 'ASC';
	        options.params.paging.sort = 'name';
		    options.params.paging.dir = 'ASC';
	    }	    
//	    tpl:new Ext.XTemplate(
//	            '<tpl for="."><div class="search-item">',
//                '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 11px;" width="100%">',
//                    '<tr  style="font-size: 11px;border-bottom:1px solid #000000;">',
//                        '<td width="30%"><b>{[this.encode(values.member_nr)]}</b><br/>{[this.encode(values.membership_named_type)]}</td>',
//                        '<td width="70%">{[this.encode(values.somember_contact_title)]}<br/>',
//                            '{[this.encode(values.somember_contact_orgname)]}</td>',
//                    '</tr>',
//                '</table>',
//            '</div></tpl>',
//            {
//                encode: function(value) {
//                     if (value) {
//                        return Ext.util.Format.htmlEncode(value);
//                    } else {
//                        return '';
//                    }
//                }
//            }
//        )
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};


Tine.Membership.Custom.getFeeVarConfigRecordPicker = function(id, config){
	if(!id){
		id = 'feeVarConfigRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.FeeVarConfig,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Ergebniswert',
		feeDefinitionRecord: null,
	    setFeeDefinition: function(feeDefinitionRecord){
	    	this.feeDefinitionRecord = feeDefinitionRecord;
	    	// provide fluent interface
	    	return this;
	    },
	   // itemSelector: 'div.search-item',
	    onBeforeQuery: function(qevent){
	    	this.store.baseParams.filter = [
				{	
					field: 'fee_definition_id',
					operator:'AND',
					value:[{
						field:'id',
						operator:'equals',
						value: this.feeDefinitionRecord.get('id') }]
				}
			];
	    	this.store.baseParams.sort = 'name';
	    	this.store.baseParams.dir = 'ASC';
	    },
	    onBeforeLoad: function(store, options) {
	        options.params.paging = {
	            start: options.params.start,
	            limit: options.params.limit
	        };
	        options.params.sort = 'name';
	        options.params.dir = 'ASC';
	        options.params.paging.sort = 'name';
		    options.params.paging.dir = 'ASC';
	    }    
//	    tpl:new Ext.XTemplate(
//	            '<tpl for="."><div class="search-item">',
//                '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 11px;" width="100%">',
//                    '<tr  style="font-size: 11px;border-bottom:1px solid #000000;">',
//                        '<td width="30%"><b>{[this.encode(values.member_nr)]}</b><br/>{[this.encode(values.membership_named_type)]}</td>',
//                        '<td width="70%">{[this.encode(values.somember_contact_title)]}<br/>',
//                            '{[this.encode(values.somember_contact_orgname)]}</td>',
//                    '</tr>',
//                '</table>',
//            '</div></tpl>',
//            {
//                encode: function(value) {
//                     if (value) {
//                        return Ext.util.Format.htmlEncode(value);
//                    } else {
//                        return '';
//                    }
//                }
//            }
//        )
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getFeeGroupRecordPicker = function(id, config){
	if(!id){
		id = 'feeGroupRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.FeeGroup,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Ergebniswert',
	    membershipKindId: null,
	    minChars: 2,
	    setMembershipKind: function(membershipKindId){
	    	this.membershipKindId = membershipKindId;
	    	// provide fluent interface
	    	return this;
	    },
	   // itemSelector: 'div.search-item',
	    onBeforeQuery: function(qevent){
	    	if(!this.membershipKindId){
	    		return true;
	    	}
	    	this.store.baseParams.filter = [
	    	    {field: 'query', operator: 'contains', value: qevent.query },
				{	
					field: 'membership_kind_id',
					operator:'AND',
					value:[{
						field:'id',
						operator:'equals',
						value: this.membershipKindId }]
				}
			];
	    	this.store.baseParams.sort = 'name';
	    	this.store.baseParams.dir = 'ASC';
	    },
	    onBeforeLoad: function(store, options) {
	        options.params.paging = {
	            start: options.params.start,
	            limit: options.params.limit
	        };
	        options.params.sort = 'name';
	        options.params.dir = 'ASC';
	        options.params.paging.sort = 'name';
		    options.params.paging.dir = 'ASC';
	    }    

	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getMembershipKindRecordPicker = function(id, config){
	if(!id){
		id = 'membershipKindRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.MembershipKind,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Mitgliedsart'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getMembershipExportRecordPicker = function(id, config){
	if(!id){
		id = 'membershipExportRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.MembershipExport,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Exportvorlage'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getFilterSetRecordPicker = function(id, config){
	if(!id){
		id = 'filterSetRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.FilterSet,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Filter-Set'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getAssociationRecordPicker = function(id, config){
	if(!id){
		id = 'organizerEditorField';
	}
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.Association,
		fieldLabel: 'Hauptorganisation',
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    itemSelector: 'div.search-item',
	    hasDefault: true,
	    //injectStore: true,
	    //store: Tine.Membership.getStore('Association'),
	    tpl:new Ext.XTemplate(
	            '<tpl for="."><div class="search-item">',
	                '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 12px;" width="100%">',
	                    '<tr  style="font-size: 12px;border-bottom:1px solid #000000;">',
	                        '<td width="30%"><b>{[this.encode(values.association_nr)]}</b><br/>{[this.encode(values.short_name)]}</td>',
	                        '<td width="70%">{[this.encode(values.association_name)]}<br/></td>',
	                    '</tr>',
	                '</table>',
	            '</div></tpl>',
	            {
	                encode: function(value) {
	                     if (value) {
	                        return Ext.util.Format.htmlEncode(value);
	                    } else {
	                        return '';
	                    }
	                }
	            }
	        )
	},config));
};

Tine.Membership.Custom.getCommitteeRecordPicker = function(id, config){
	if(!id){
		id = 'organizerEditorField';
	}
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.Committee,
		fieldLabel: 'Gremium',
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    itemSelector: 'div.search-item',
	    tpl:new Ext.XTemplate(
	            '<tpl for="."><div class="search-item">',
	                '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 12px;" width="100%">',
	                    '<tr  style="font-size: 12px;border-bottom:1px solid #000000;">',
	                        '<td width="30%">{[this.encode(values.name)]}</td>',
	                        '<td width="70%">{[this.encode(values.challenge)]}<br/></td>',
	                    '</tr>',
	                '</table>',
	            '</div></tpl>',
	            {
	                encode: function(value) {
	                     if (value) {
	                        return Ext.util.Format.htmlEncode(value);
	                    } else {
	                        return '';
	                    }
	                }
	            }
	        )
	},config));
};

Tine.Membership.Custom.getCommitteeKindRecordPicker = function(id, config){
	if(!id){
		id = 'committeeKindRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.CommitteeKind,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Gremium-Art'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getCommitteeFunctionRecordPicker = function(id, config){
	if(!id){
		id = 'committeeFunctionRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.CommitteeFunction,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Gremium-Art'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getCommitteeLevelRecordPicker = function(id, config){
	if(!id){
		id = 'committeeLevelRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.CommitteeLevel,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Gremium-Art'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};

Tine.Membership.Custom.getAwardListRecordPicker = function(id, config){
	if(!id){
		id = 'awardListRecordPickerField';
	}
	var obj = Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.AwardList,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    fieldLabel: 'Auszeichnung'
	}, config);
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(obj);
};


Tine.Membership.Custom.getEntryReasonRecordPicker = function(id, config){
	if(!id){
		id = 'entryReasonEditorField';
	}
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.EntryReason,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    hasDefault:true
	}, config));
};

Tine.Membership.Custom.getTerminationReasonRecordPicker = function(id, config){
	if(!id){
		id = 'entryReasonEditorField';
	}
	return new Tine.Tinebase.widgets.form.RecordPickerComboBox(Ext.apply({
		id:id,
		disabledClass: 'x-item-disabled-view',
		recordClass: Tine.Membership.Model.TerminationReason,
	    allowBlank:false,
	    autoExpand: true,
	    triggerAction: 'all',
	    selectOnFocus: true,
	    hasDefault:true
	}, config));
};

Tine.Membership.Custom.getRecordPicker = function(modelName, id, config){
	switch(modelName){
	case 'SoMember':
		return Tine.Membership.Custom.getSoMemberRecordPicker(id, config);
	case 'FeeDefFilter':
		return Tine.Membership.Custom.getFeeDefFilterRecordPicker(id, config);
	case 'FeeVarConfig':
		return Tine.Membership.Custom.getFeeVarConfigRecordPicker(id, config);
	case 'FeeGroup':
		return Tine.Membership.Custom.getFeeGroupRecordPicker(id, config);
	case 'MembershipKind':
		return Tine.Membership.Custom.getMembershipKindRecordPicker(id, config);
	case 'MembershipExport':
		return Tine.Membership.Custom.getMembershipExportRecordPicker(id, config);
	case 'Association':
		return Tine.Membership.Custom.getAssociationRecordPicker(id, config);
	case 'Committee':
		return Tine.Membership.Custom.getCommitteeRecordPicker(id, config);
	case 'CommitteeKind':
		return Tine.Membership.Custom.getCommitteeKindRecordPicker(id, config);
	case 'CommitteeLevel':
		return Tine.Membership.Custom.getCommitteeLevelRecordPicker(id, config);
	case 'CommitteeFunction':
		return Tine.Membership.Custom.getCommitteeFunctionRecordPicker(id, config);
	case 'AwardList':
		return Tine.Membership.Custom.getAwardListRecordPicker(id, config);
	case 'EntryReason':
		return Tine.Membership.Custom.getEntryReasonRecordPicker(id, config);
	case 'TerminationReason':
		return Tine.Membership.Custom.getTerminationReasonRecordPicker(id, config);
	case 'FilterSet':
		return Tine.Membership.Custom.getFilterSetRecordPicker(id, config);
	}
};