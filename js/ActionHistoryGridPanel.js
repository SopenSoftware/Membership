Ext.ns('Tine.Membership');

Tine.Membership.getActionHistoryGridConfig = function(app){
	return {
	    recordClass: Tine.Membership.Model.ActionHistory,
		recordProxy: Tine.Membership.Model.actionHistoryBackend,
		columns: [
		   { header: app.i18n._('Mitglied'), dataIndex: 'member_id',renderer:Tine.Membership.renderer.membershipRenderer },
		   { header: app.i18n._('Verein'), dataIndex: 'parent_member_id',renderer:Tine.Membership.renderer.membershipRenderer },
		   { header: app.i18n._('Verband'), dataIndex: 'association_id',renderer:Tine.Membership.renderer.associationRenderer },
		   { header: app.i18n._('Aktion'), dataIndex: 'action_id',renderer: Tine.Membership.renderer.actionRenderer},
		   { header: app.i18n._('Text'), dataIndex: 'action_text'},
		   { header: app.i18n._('Kategorie'), dataIndex: 'action_category',renderer: Tine.Membership.renderer.actionCategoryRenderer},
		   { header: app.i18n._('Typ'), dataIndex: 'action_type',renderer: Tine.Membership.renderer.actionTypeRenderer},
		   { header: app.i18n._('Status'), dataIndex: 'action_state',renderer: Tine.Membership.renderer.actionStateRenderer},
		   { header: app.i18n._('Fehlerinfo'), dataIndex: 'error_info'},
		   { header: app.i18n._('Auftrags-Nr'), dataIndex: 'order_id', sortable:true, renderer: Tine.Billing.renderer.orderRenderer },	
           { header: app.i18n._('Rechnung'), dataIndex: 'receipt_id', sortable:true, renderer: Tine.Billing.renderer.receiptRenderer },	
           { header: app.i18n._('Angelegt am'), dataIndex: 'created_datetime', renderer: Tine.Tinebase.common.dateTimeRenderer, sortable:true },
           { header: app.i18n._('Gültig ab'), dataIndex: 'valid_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true,hidden:true },
           { header: app.i18n._('Auszuführen am'), dataIndex: 'to_process_datetime', renderer: Tine.Tinebase.common.dateTimeRenderer, sortable:true,hidden:true },
           { header: app.i18n._('Ausgeführt am'), dataIndex: 'process_datetime', renderer: Tine.Tinebase.common.dateTimeRenderer },
           { 
      	 		id: 'created_by_user',      header: 'angelegt von',             width: 220, dataIndex: 'created_by_user',            
      	 		renderer: Tine.Tinebase.common.usernameRenderer 
			}
        ]
	};
};

Tine.Membership.ActionHistoryGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-action-history-gridpanel',
	stateId: 'tine-membership-action-history-gridpanel-state',
    recordClass: Tine.Membership.Model.ActionHistory,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'created_datetime', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'action_text'
    },
    crud:{
    	_add:false,
    	_edit:false,
    	_delete:false,
    	_copy:false
    },
    useImplicitForeignRecordFilter: false,
    soMemberRecord: null,
    jobRecord: null,
    initComponent: function() {
        this.recordProxy = Tine.Membership.actionHistoryBackend;
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.ActionHistoryGridPanel.superclass.initComponent.call(this);
    },
    initActions: function(){
    	 this.actions_openReceipt = new Ext.Action({
             actionType: 'edit',
             handler: this.openReceipt,
             text: 'Öffne Beleg',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openOrder = new Ext.Action({
             actionType: 'edit',
             handler: this.openOrder,
             text: 'Öffne Auftrag',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openMember = new Ext.Action({
             actionType: 'edit',
             handler: this.openMember,
             text: 'Öffne Mitglied',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openMemberContact = new Ext.Action({
             actionType: 'edit',
             handler: this.openMemberContact,
             text: 'Öffne Kontakt Mitglied',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openParentMember = new Ext.Action({
             actionType: 'edit',
             handler: this.openParentMember,
             text: 'Öffne übergeord. Mitglied (Verein)',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openParentMemberContact = new Ext.Action({
             actionType: 'edit',
             handler: this.openParentMemberContact,
             text: 'Öffne Kontakt übergeord. Mitglied (Verein)',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openChildMember = new Ext.Action({
             actionType: 'edit',
             handler: this.openChildMember,
             text: 'Öffne untergeord. Mitglied',
             iconCls: 'actionEdit',
             scope: this
         });
    	 this.actions_openChildMemberContact = new Ext.Action({
             actionType: 'edit',
             handler: this.openChildMemberContact,
             text: 'Öffne Kontakt untergeord. Mitglied',
             iconCls: 'actionEdit',
             scope: this
         });
    	this.actions_printActionHistorys= new Ext.Action({
            text: 'Druck nach Filter',
			disabled: false,
            handler: this.printActionHistorys,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
    	this.supr().initActions.call(this);
    },
    printActionHistorys: function(){
    	var win = Tine.Membership.PrintActionHistoryDialog.openWindow({
			mainGrid: this,
			//outputType: 'CARD',
			preview: false,
			title: 'Mitgliederänderungen drucken'
		});
		return;
    },
    getActionToolbarItems: function() {
    	return [
            Ext.apply(new Ext.Button(this.actions_printActionHistorys), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                iconCls: 'action_exportAsPdf'
            })
        ];
    },
    
    initFilterToolbar: function() {
    	var plugins = [];
    	if(!this.useImplicitForeignRecordFilter){
    		plugins = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
    	}
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.ActionHistory.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: plugins
        });
    },  
//    initActions: function(){
//        this.actions_createFeeInvoice = new Ext.Action({
//            requiredGrant: 'readGrant',
//            text: 'Beitragsrechnung erzeugen',
//            disabled: true,
//            //actionType: 'edit',
//            handler: this.createFeeInvoice,
//            iconCls: 'action_edit',
//            scope: this
//        });
//        this.actions_openReceipt = new Ext.Action({
//            actionType: 'edit',
//            handler: this.openReceipt,
//            text: 'Öffne Beitragsrechnung',
//            iconCls: 'actionEdit',
//            scope: this
//        });
//        this.actions_progressiveCreateFeeInvoice = new Ext.Action({
//            requiredGrant: 'readGrant',
//            text: 'Beitragsnachberechnung erzeugen',
//            disabled: false,
//            //actionType: 'edit',
//            handler: this.progressiveCreateFeeInvoice,
//            iconCls: 'action_edit',
//            scope: this
//        });
//        this.supr().initActions.call(this);
//    },
    getContextMenuItems: function(){
    	return [
    	        this.actions_openMemberContact,
    	        this.actions_openMember,
    	        this.actions_openParentMemberContact,
    	        this.actions_openParentMember,
    	        this.actions_openChildMemberContact,
    	        this.actions_openChildMember,
    	        this.actions_openOrder,
    	        this.actions_openReceipt
    	];
    },
	getColumns: function() {
    	return Tine.Membership.getActionHistoryGridConfig(this.app).columns;
	},
	openReceipt: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('receipt_id')){
			var receipt = record.getForeignRecord(Tine.Billing.Model.Receipt, 'receipt_id');
			//consider type of receipt:
			// 1: invoice
			// 2: credit
			var receiptClass = Tine.Billing.InvoiceEditDialog;
			if(receipt.get('type')== 'CREDIT'){
				receiptClass = Tine.Billing.CreditEditDialog
			}
			var win = receiptClass.openWindow({
	    		record: receipt
			});
		}
	},
	openOrder: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('order_id')){
			var win = Tine.Billing.OrderEditDialog.openWindow({
	    		record: record.getForeignRecord(Tine.Billing.Model.Order, 'order_id')
			});
		}
	},
	openMember: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('member_id')){
			var win = Tine.Membership.SoMemberEditDialog.openWindow({
	    		record: record.getForeignRecord(Tine.Membership.Model.SoMember, 'member_id')
			});
		}
	},
	openMemberContact: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('member_id')){
			var member = record.getForeignRecord(Tine.Membership.Model.SoMember, 'member_id');
			var contact = member.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
			var win = Tine.Addressbook.ContactEditDialog.openWindow({
	    		record: contact
			});
		}
	},
	openParentMember: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('parent_member_id')){
			var win = Tine.Membership.SoMemberEditDialog.openWindow({
	    		record: record.getForeignRecord(Tine.Membership.Model.SoMember, 'parent_member_id')
			});
		}
	},
	openParentMemberContact: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('parent_member_id')){
			var member = record.getForeignRecord(Tine.Membership.Model.SoMember, 'parent_member_id');
			var contact = member.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
			var win = Tine.Addressbook.ContactEditDialog.openWindow({
	    		record: contact
			});
		}
	},
	openChildMember: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('child_member_id')){
			var win = Tine.Membership.SoMemberEditDialog.openWindow({
	    		record: record.getForeignRecord(Tine.Membership.Model.SoMember, 'child_member_id')
			});
		}
	},
	openChildMemberContact: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('child_member_id')){
			var member = record.getForeignRecord(Tine.Membership.Model.SoMember, 'child_member_id');
			var contact = member.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
			var win = Tine.Addressbook.ContactEditDialog.openWindow({
	    		record: contact
			});
		}
	},
	loadSoMember: function( soMemberRecord ){
    	this.soMemberRecord = soMemberRecord;
    	this.store.reload();
    },
    loadJob: function(jobRecord){
    	this.jobRecord = jobRecord;
    	this.store.reload();
    },
    createForeignIdFilter: function( filterOptions){
    	if(!filterOptions.record){// || filterOptions.record.id == 0){
    		return false;
    	}
    	var recordId = filterOptions.record.get('id');
    	if(recordId == 0 || recordId == undefined){
    		recordId = -1;
    	}
    	//alert(recordId);
    	var filter = {	
			field: filterOptions.field,
			operator:'AND',
			value:[{
				field:'id',
				operator:'equals',
				value: recordId }]
		};
    	return filter;
    },
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.ActionHistoryGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	if(!this.useImplicitForeignRecordFilter){
    		return true;
    	}
    	
    	if(!this.soMemberRecord && !this.jobRecord){
    		return true;
    	}
    	
    	var filterOptions = {};
    	var filter;
    	if(this.soMemberRecord){
    		filterOptions.record = this.soMemberRecord;
        	filterOptions.field = 'member_id';
        	filter = this.createForeignIdFilter(filterOptions);
    	}else if(this.jobRecord){
    		filter = {
        		field: 'job_id',
        		operator: 'equals',
        		value: this.jobRecord.get('id')
        	};
    	}
    	
    	
		options.params.filter.push(filter);
    }
});
Ext.reg('memberactionhistory', Tine.Membership.ActionHistoryGridPanel);