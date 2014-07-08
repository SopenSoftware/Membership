Ext.ns('Tine.Membership');

Tine.Membership.MembershipGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	title: ' ',
	layout:'border',
	evalGrants:false,
	memberForm:null,
	formId: null,
    grouping: true,
    withFilterToolbar: true,
    invisibleFilters: null,
    previousFilterKey: null,
    groupField: '',
    groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Mitgliedschaften" : "Mitgliedschaft"]})',
	gridConfig: {
        loadMask: true
    },
    recordClass: Tine.Membership.Model.SoMember,
	initComponent : function() {
		this.addEvents(
				'showmember',
				'addmember',
				'editmember'
		);
		this.recordProxy = Tine.Membership.membershipBackend;
		
		this.app = Tine.Tinebase.appMgr.get('Membership');
		this.gridConfig.cm = this.getColumnModel();
        this.gridConfig.sm =  new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
        		rowselect: {
        			scope: this,
        			fn:	function(sm, row, rec) {
                   			this.actions_editMember.enable();
                   			this.showMember();
                		}
            	}
        	}
        });
        if(this.withFilterToolbar){
        	this.initFilterToolbar();
        	this.plugins = this.plugins || [];
        	this.plugins.push(this.filterToolbar);
        }
		this.invisibleFilters = new Ext.util.MixedCollection();
		Tine.Membership.MembershipGridPanel.superclass.initComponent.call(this);
		// add buttons to paging toolbar
	   this.addToolbarButtons();
	},
    initFilterToolbar: function() {
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.SoMember.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: [
                new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()
            ]
        });
    },    
	initActions: function(){
        this.actions_newMember = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Neue Mitgliedschaft'),
            tooltip: this.app.i18n._('Neue Mitgliedschaft'),
            disabled: true,
            iconCls: 'actionAdd',
            handler: this.addMember,
            scale:'medium',
            iconAlign:'top',
            scope: this
        });
        this.actions_editMember = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Mitgliedschaft bearbeiten'),
            tooltip: this.app.i18n._('Mitgliedschaft bearbeiten'),
            disabled: true,
            handler: this.editMember,
            iconCls: 'actionEdit',
            scale:'medium',
            iconAlign:'top',
            scope: this
        });
        this.actions_deleteMember = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Mitgliedschaft löschen'),
            handler: this.deleteMember,
            tooltip: this.app.i18n._('Mitgliedschaft löschen'),
            iconCls: 'actionRemove',
            disabled: true,
            scale:'medium',
            iconAlign:'top',
            scope: this
        });
        this.action_useGrouping = new Ext.Action({
        	requiredGrant:'readGrant',
            text: this.app.i18n._('Gruppierung'),
            iconCls: 'action_useGrouping',
            handler: this.useGrouping,
            enableToggle:true,
            scale:'medium',
            iconAlign:'top',
            scope:this
        });
        Tine.Membership.MembershipGridPanel.superclass.initActions.call(this);
	},
	
    addToolbarButtons: function() {
		this.pagingToolbar.addSpacer();
        this.pagingToolbar.addButton(
            Ext.apply(new Ext.Button(this.actions_useGrouping), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
        this.pagingToolbar.addButton(
            Ext.apply(new Ext.Button(this.actions_newMember), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
        this.pagingToolbar.addButton(
            Ext.apply(new Ext.Button(this.actions_editMember), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
        this.pagingToolbar.addButton(            
            Ext.apply(new Ext.Button(this.actions_deleteMember), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
	},
	getActionToolbarItems: function(){
        return [
				this.actions_newMember,
				this.actions_editMember,
				this.actions_deleteMember,
                this.action_useGrouping
            ];
	},
	getColumnModel: function() {
		var columns = [
		   { id: 'contact_id', header: this.app.i18n._('Kontakt'), dataIndex: 'contact_id',renderer:Tine.Membership.renderer.contactRenderer  },
		   { id: 'member_nr', header: this.app.i18n._('Mitglieds-Nr.'), dataIndex: 'member_nr' },		               
           { id: 'society_contact_id', header: this.app.i18n._('Verein'), dataIndex: 'society_contact_id',renderer:Tine.Membership.renderer.contactRenderer },
           { id: 'association_id', header: this.app.i18n._('Verband'), dataIndex: 'association_id',renderer:Tine.Membership.renderer.associationRenderer },
           { id: 'affiliate_contact_id', header: this.app.i18n._('geworben durch'), dataIndex: 'affiliate_contact_id',renderer:Tine.Membership.renderer.contactRenderer,hidden:true },
           { id: 'begin_datetime', header: this.app.i18n._('Eintrittsdatum'), dataIndex: 'begin_datetime', renderer: Tine.Tinebase.common.dateRenderer },
           { id: 'discharge_datetime', header: this.app.i18n._('Kündigungsdatum'), dataIndex: 'discharge_datetime', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { id: 'termination_datetime', header: this.app.i18n._('Austrittsdatum'), dataIndex: 'termination_datetime', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { id: 'entry_reason_id', header: this.app.i18n._('Eintrittsgrund'), dataIndex: 'entry_reason_id',hidden:true },
           { id: 'termination_reason_id', header: this.app.i18n._('Austrittsgrund'), dataIndex: 'termination_reason_id',hidden:true },
           { id: 'exp_membercard_datetime', header: this.app.i18n._('Export Mitgl.-Ausweis'), dataIndex: 'exp_membercard_datetime', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { id: 'member_notes', header: this.app.i18n._('Bemerkungen'), dataIndex: 'member_notes',hidden:true },
           { id: 'invoice_fee', header: this.app.i18n._('Rechung Beitrag'), dataIndex: 'invoice_fee',hidden:true },
           { id: 'membership_type', header: this.app.i18n._('Art Mitgliedschaft'), dataIndex: 'membership_type', renderer: Tine.Membership.renderer.memshipType },
           { id: 'membership_status', header: this.app.i18n._('Status'), dataIndex: 'membership_status', renderer: Tine.Membership.renderer.memshipStatus },
           { id: 'society_sopen_user', header: this.app.i18n._('Verein n. sopen'), dataIndex: 'society_sopen_user',hidden:true },
           { id: 'fee_payment_interval', header: this.app.i18n._('Zahlungsintervall'), dataIndex: 'fee_payment_interval', renderer: Tine.Membership.renderer.feePaymentInterval,hidden:true },
           { id: 'fee_payment_method', header: this.app.i18n._('Zahlungsmethode'), dataIndex: 'fee_payment_method', renderer: Tine.Membership.renderer.feePaymentMethod,hidden:true },
           { id: 'bank_code', header: this.app.i18n._('BLZ'), dataIndex: 'bank_code',hidden:true },
           { id: 'bank_name', header: this.app.i18n._('Bank'), dataIndex: 'bank_name',hidden:true },
           { id: 'bank_account_nr', header: this.app.i18n._('Kto.nummer'), dataIndex: 'bank_account_nr',hidden:true },
           { id: 'account_holder', header: this.app.i18n._('Kto.inhaber'), dataIndex: 'account_holder',hidden:true },
           { id: 'is_online_user', header: this.app.i18n._('online-User'), dataIndex: 'is_online_user',hidden:true }  
		];
        return new Ext.grid.ColumnModel({ 
            defaults: {
                sortable: true
            },
            columns: columns
        });
	},
	
	getDetailsPanel: function() {
		// not used here
		/*
		return new Ext.Panel(
			{
				html:'<p>membership details</p>'
			}	
		);*/
	},
	loadStore: function(contactRecord){
		this.contactId = contactRecord.get('id');
		this.setTitle('Mitgliedschaften von "' + contactRecord.getTitle()+'"');
		this.getStore().load();
	},
	loadStoreAll: function(){
		this.store.load();
	},
    onStoreBeforeload: function(store, options) {
		Tine.Membership.MembershipGridPanel.superclass.onStoreBeforeload.call(this,store,options); 
		if(this.contactId!==undefined){
			// TODO HH: use proper filter model
			options.params.filter = options.params.filter.concat([{field:'contact_id',operator:'AND',value:[{field:'id',operator:'equals',value:this.contactId}]}]);
		}
		this.invisibleFilters.each(
			function(filter){
				this.filter = this.filter.concat([filter]);
//				//console.log('add filter:');
//				//console.log(filter);
			},options.params
		);
//		//console.log(this);
    },
    onStoreLoad: function(store, records, options) {
    	Tine.Membership.MembershipGridPanel.superclass.onStoreLoad.call(this,store,records,options);
    },
	onEditInNewWindow: function(button, event) {
		// TODO HH: better approach needed
		// this code is copied from parent class
        var record; 
        if (button.actionType == 'edit') {
            this.editMemberContact();
        }else {
        	this.addMemberContact();
        }
	},
	addMemberContact: function(){
        var record = new Tine.Addressbook.Model.Contact(Tine.Addressbook.Model.Contact.getDefaultData(), 0);
	    var popupWindow = Tine.Addressbook.ContactEditDialog.openWindow({
	        record: record,
	        listeners: {
	            scope: this.memberForm,
	            'update': function(record) {
	                this.load(true, true, true);
	            }
	        }
	    });
	},
	editMemberContact: function(){
		this.action_addInNewWindow.disable();
		this.action_editInNewWindow.disable();
		this.action_deleteRecord.disable();
        var selectedRows = this.grid.getSelectionModel().getSelections();
        var record = selectedRows[0];
        record = new Tine.Addressbook.Model.Contact(record.data.contact_id,record.data.contact_id.id);
        var popupWindow = Tine.Addressbook.ContactEditDialog.openWindow({
	        record: record,
	        listeners: {
	            scope: this.memberForm,
	            'update': function(record) {
	                this.load(true, true, true);
	            }
	        }
	    });
	},
	addMember: function(){
		this.action_editInNewWindow.disable();
		this.action_deleteRecord.disable();
		var record = new this.recordClass(this.recordClass.getDefaultData(), 0);
		this.fireEvent('addmember', record);
	},
	showMember: function(){
		this.action_editInNewWindow.enable();
		this.action_deleteRecord.enable();
        var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		this.fireEvent('showmember', record);
	},
	editMember: function(){
		this.action_editInNewWindow.disable();
		this.action_deleteRecord.disable();
		var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
        this.fireEvent('editmember', record);
		
	},
	deleteMember: function(){
		//alert('delete');
	},
	useGrouping: function(button,event){
		if(button.pressed){
			this.getStore().groupBy('contact_id');
		}else{
			this.getStore().clearGrouping();
		}
	},
	addFilter: function(key,filter){
		this.invisibleFilters.add(key,filter);
		this.previousFilterKey = key;
	},
	removeFilter: function(key){
		var filter = this.invisibleFilters.getKey(key);
		this.filterToolbar.deleteAllFilters();
		this.invisibleFilters.removeKey(key);
	},
	removePreviousFilter: function(){
		if(this.previousFilterKey){
			this.removeFilter(this.previousFilterKey);
		}
	}
	
});
Ext.reg('membershipgrid', Tine.Membership.MembershipGridPanel);

