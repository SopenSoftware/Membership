Ext.ns('Tine.Membership');

Tine.Membership.getSoMemberFeeProgressGridConfig = function(app){
	return {
	    recordClass: Tine.Membership.Model.SoMemberFeeProgress,
	    recordProxy: Tine.Membership.Model.soMemberFeeProgressBackend,
		columns:[
		   {id:'progress_nr',header: "Nr in Beitragsperiode", width: 120, dataIndex: 'progress_nr'},
           {header: "Mitglied-Nr", width: 120, dataIndex: 'fg_member_nr', sortable:true},
           { header: app.i18n._('Mitglied'), dataIndex: 'member_id',renderer:Tine.Membership.renderer.membershipRenderer },
           {header: "Eintritt", dataIndex: 'fg_begin_datetime', sortable: true,hidden:true, renderer: Tine.Tinebase.common.dateRenderer},
           {header: "Austritt", dataIndex: 'fg_termination_datetime', sortable: true,hidden:true, renderer: Tine.Tinebase.common.dateRenderer},
           {header: "Status", dataIndex: 'fg_membership_status', sortable: true,hidden:true, renderer: Tine.Membership.renderer.memshipStatus},
           
           { header: app.i18n._('Verein'), dataIndex: 'parent_member_id',renderer:Tine.Membership.renderer.membershipRenderer },
		   //{ id: 'order_id', header: app.i18n._('Auftrags-Nr'), dataIndex: 'order_id', sortable:true, renderer: Tine.Billing.renderer.orderRenderer },	
           { header: app.i18n._('Beleg'), dataIndex: 'invoice_receipt_id', sortable:true, renderer: Tine.Billing.renderer.receiptRenderer },	
           { header: app.i18n._('Beitragsgruppe'), dataIndex: 'fee_group_id', renderer: Tine.Membership.renderer.feeGroupRenderer },
           { header: app.i18n._('Beitragsdefinition'), dataIndex: 'fee_definition_id',renderer:Tine.Membership.renderer.feeDefinitionRenderer },
		   {id:'fee_from_datetime',header: "von", width: 115, dataIndex: 'fee_from_datetime', sortable: true, renderer: Tine.Tinebase.common.dateRenderer},
            {id:'fee_to_datetime',header: "bis", dataIndex: 'fee_to_datetime', sortable: true, renderer: Tine.Tinebase.common.dateRenderer},
            {id:'fee_year',header: "Beitr.jahr", dataIndex: 'fee_year', sortable: true},
            {id:'is_calculation_approved',header: "geprüft", dataIndex: 'is_calculation_approved', sortable: true,hidden:true},
            {id:'fee_period_notes',header: "Bemerkungen", dataIndex: 'fee_period_notes', sortable: true,hidden:true},
            {id:'fee_calc_datetime',header: "Berechnungsdatum", dataIndex: 'fee_calc_datetime', sortable: true,hidden:true, renderer: Tine.Tinebase.common.dateRenderer},
            {header: "storniert", dataIndex: 'is_cancelled', sortable: true,hidden:true},
            { header: app.i18n._('Storno-Beleg'), dataIndex: 'cancellation_receipt_id', sortable:true, renderer: Tine.Billing.renderer.receiptRenderer },	
            { 
    			header: 'Zahlsaldo vor Sollst.', dataIndex: 'deb_summation', sortable:true,
    			renderer: Sopen.Renderer.MonetaryNumFieldRenderer 
    	 	},{ 
	    	 	header: 'Vorschau Beitrag', dataIndex: 'fee_to_calculate', sortable:true,
				renderer: Sopen.Renderer.MonetaryNumFieldRenderer 
    	
    	 	},{ 
	    	 	header: 'sollgest. Beitrag', dataIndex: 'sum_brutto', sortable:true,
				renderer: Sopen.Renderer.MonetaryNumFieldRenderer 
    	 	},{ 
	    	 	header: 'off. Beitrag', dataIndex: 'open_sum', sortable:true,
				renderer: Sopen.Renderer.MonetaryNumFieldRenderer 
    	 	},{ 
	    	 	header: 'bez. Beitrag', dataIndex: 'payed_sum', sortable:true,
				renderer: Sopen.Renderer.MonetaryNumFieldRenderer 
    	 	},
    	 	{ header: app.i18n._('Zahlungsstatus'), dataIndex: 'payment_state', sortable:false, renderer:Tine.Billing.getPaymentStateIcon  },
    	 	{ header: "Zahlungsdatum", width: 115, dataIndex: 'payment_date', sortable: true, renderer: Tine.Tinebase.common.dateRenderer},
    	 	{ header: "Tage fällig", width: 115, dataIndex: 'due_days'},
    	 	{ header: "Mahnstufe", width: 115, dataIndex: 'monition_stage', sortable: true}
            
 		   
	   ],
	   actionTexts: {
			addRecord:{
				buttonText: 'Beitragsverlauf hinzufügen',
				buttonTooltip: 'Fügt einen neuen Beitragsverlauf hinzu'
			},
			editRecord:{
				buttonText: 'Beitragsverlauf bearbeiten',
				buttonTooltip: 'Öffnet das Formular "Beitragsverlauf" zum Bearbeiten'
			},
			deleteRecord:{
				buttonText: 'Beitragsverlauf löschen',
				buttonTooltip: 'Löscht ausgewählte(n) Beitragsverläufe(verlauf)'
			}
	}};
};

Tine.Membership.SoMemberFeeProgressGridPanelNested = Ext.extend(Tine.widgets.grid.DependentEditFormGridPanel, {
	id: 'tine-membership-somemberfeeprogress-nested-gridpanel',
	stateId: 'tine-membership-somemberfeeprogress-nested-gridpanel',
	gridConfig: {
		gridID: 'tine-membership-somemberfeeprogress-nested-gridpanel-gp',
        loadMask: true
    },	
    crud:{
    	_add:false,
    	_edit:true,
    	_delete:false,
    	_copy:false
    },
	title: 'Beitragsverläufe',
    grouping: false,
    withFilterToolbar: true,
    withQuickFilter: false,
    parentRelation:{
		fKeyColumn: 'member_id',
		refColumn: 'id'
	},
	recordClass: Tine.Membership.Model.SoMemberFeeProgress,
    recordProxy: Tine.Membership.Model.soMemberFeeProgressBackend,
    initComponent : function() {
		this.actionTexts = Tine.Membership.getSoMemberFeeProgressGridConfig(this.app).actionTexts,
		this.filterModels = Tine.Membership.Model.SoMemberFeeProgress.getFilterModel();
		Tine.Membership.SoMemberFeeProgressGridPanelNested.superclass.initComponent.call(this);
	},

	getColumns: function() {
		return Tine.Membership.getSoMemberFeeProgressGridConfig(this.app).columns;
	}
});
Ext.reg('somemberfeeprogressnestedgrid', Tine.Membership.SoMemberFeeProgressGridPanelNested);

Tine.Membership.SoMemberFeeProgressGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-somemberfeeprogress-gridpanel',
	stateId: 'tine-membership-somemberfeeprogress-gridpanel',
    recordClass: Tine.Membership.Model.SoMemberFeeProgress,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'member_id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    crud:{
    	_add:false,
    	_edit:true,
    	_delete:false,
    	_copy:false
    },
    inDialog:false,
    useDetailsPanel: true,
    soMemberRecord: null,
    
    customExportActions: new Ext.util.MixedCollection(),
    initComponent: function() {
        this.recordProxy = Tine.Membership.soMemberFeeProgressBackend;
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        this.initDetailsPanel();
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.SoMemberFeeProgressGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
    	var plugins = [];
    	if(!this.inDialog){
    		plugins = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
    	}
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.SoMemberFeeProgress.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: plugins
        });
    },  
    initActions: function(){
    	if(!this.inDialog){
	    	var exportItems = [];
	    	
	    	var additionalExportItems = this.addCustomExports();
	    	
	    	additionalExportItems.each(function(item){
	    		this.push(item);
	    	},exportItems);
	    
	    	this.actions_export = new Ext.Action({
	        	allowMultiple: false,
	        	iconCls: 'action_export',
	        	//disabled:true,
	            text: 'Mitglieder exportieren',
	            menu:{
	            	items:[
	            	   exportItems 
			    	]
	            }
	        });
    	}
    	
    	
        this.actions_createFeeInvoice = new Ext.Action({
            requiredGrant: 'readGrant',
            text: 'Beitragsrechnung erzeugen',
            disabled: true,
            //actionType: 'edit',
            handler: this.createFeeInvoice,
            iconCls: 'action_edit',
            scope: this
        });
        this.actions_reverseInvoice = new Ext.Action({
            requiredGrant: 'readGrant',
            text: 'Beitragsrechnung stornieren',
            disabled: false,
            //actionType: 'edit',
            handler: this.reverseInvoice,
            iconCls: 'action_edit',
            scope: this
        });
        this.actions_openReceipt = new Ext.Action({
            actionType: 'edit',
            handler: this.openReceipt,
            text: 'Öffne Beitragsrechnung',
            iconCls: 'actionEdit',
            scope: this
        });
        
        this.actions_openSoMember = new Ext.Action({
            actionType: 'edit',
            handler: this.openSoMember,
            text: 'Öffne Mitglied',
            iconCls: 'actionEdit',
            scope: this
        });
        
        this.actions_progressiveCreateFeeInvoice = new Ext.Action({
            requiredGrant: 'readGrant',
            text: 'Beitragsnachberechnung erzeugen',
            disabled: false,
            //actionType: 'edit',
            handler: this.progressiveCreateFeeInvoice,
            iconCls: 'action_edit',
            scope: this
        });
        
        this.action_payInvoice = new Ext.Action({
            text: 'Bezahlung Beitragsrechnung',
           // disabled: true,
            actionType: 'edit',
            handler: this.payInvoice,
            actionUpdater: this.updatePayInvoiceAction,
            iconCls: 'action_edit',
            scope: this
        });
        
        this.actionUpdater.addActions([this.action_payInvoice]);
        
        this.supr().initActions.call(this);
        //Tine.Membership.SoMemberFeeProgressGridPanel.superclass.initActions.call(this);
    },
    getActionToolbarItems: function() {
    	if(this.inDialog){
    		return [];
    	}
    	return [
			{
                xtype: 'buttongroup',
                columns: 1,
                frame: false,
                items: [
                    this.actions_export
                ]
            }
        ];
    },
    addCustomExports: function(){
    	if(	Sopen.Config.Main.App.Membership!==undefined 
    		&& typeof(Sopen.Config.Main.App.Membership.getPredefinedExports)==='function')
    	{
    		var customExportsConfig = Sopen.Config.Main.App.Membership.getPredefinedExports();
    		var customExports = new Ext.util.MixedCollection();
    		customExports.addAll(customExportsConfig);
    		Ext.QuickTips.init();
    		customExports.each(function(config){
    			var exportAction = new Ext.Action({
    				text: config.title,
    				tooltip: config.description,
    				disabled:false,
    				handler: this.callCustomExport.createDelegate(this, [config], true),
    				iconCls: 'tinebase-action-export-csv',
    				scope: this
    			});
    			this.customExportActions.add(config.key, exportAction);
    		},this);
    		return this.customExportActions;
    	}else{
    		return new Ext.util.MixedCollection();
    	}
    	
    },
    callCustomExport: function(el, evt, exportDefinition){
    	if(exportDefinition.openActionDialog){
	    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
	    		panelTitle: exportDefinition.title + ' Mitglieder als Csv exportieren',
	    		actionType: 'customExport',
	    		customExportDefinition: exportDefinition,
	    		forFeeProgress: true
	    	});
    	}else{
    		var filterValue = Ext.util.JSON.encode(exportDefinition.filters);
    		var exportClassName = exportDefinition.exportClassName;
    		
    		var downloader = new Ext.ux.file.Download({
                params: {
                    method: 'Membership.exportMembersAsCustomCsv',
                    requestType: 'HTTP',
                    filters: filterValue,
                    exportClassName: exportClassName,
                    forFeeProgress: true
                }
            }).start();
    	}
    },
    updatePayInvoiceAction: function(action, grants, records) {
    	action.setDisabled(true);
        if (records.length == 1) {
            var rec = this.getSelectedRecord();
            if (! rec) {
                return false;
            }
            var isPayed = (rec.get('payment_state')=='PAYED');
            var isNotDue = (rec.get('payment_state')=='NOTDUE');
            
            if(!isPayed && !isNotDue){
            	action.setDisabled(false);
            }
        }
    },
    payInvoice: function(){
    	var record = this.getSelectedRecord();
    	if(!record){
    		return true;
    	}
        var receiptRecord = record.getForeignRecord(Tine.Billing.Model.Receipt,'invoice_receipt_id');
        var orderRecord = record.getForeignRecord(Tine.Billing.Model.Order,'order_id');
        //var debitorRecord = record.getForeignRecord(Tine.Billing.Model.Debitor,'debitor_id');
        receiptRecord.set('order_id', orderRecord);
        //receiptRecord.set('debitor_id', debitorRecord);
    	
        var win = Tine.Billing.PaymentEditDialog.openWindow({
    		record: null,
    		receiptRecord: receiptRecord,
    		//debitorRecord: debitorRecord,
			listeners: {
                scope: this,
                'update': function(record) {
                    this.grid.getStore().reload();
                }
            }
		});
    },
    getContextMenuItems: function(){
    	var items = [];
    	if(!this.inDialog){
    		items.push(this.actions_openSoMember);
    	}
    	items = items.concat(
    	[
    	  '-',
    	  //this.actions_createFeeInvoice,
    	  //this.actions_progressiveCreateFeeInvoice,
    	  this.actions_openReceipt,
    	  this.action_payInvoice,
    	  this.actions_reverseInvoice
    	]);
    	return items;
    },
    openReceipt: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		if(record.get('invoice_receipt_id')){
			var win = Tine.Billing.InvoiceEditDialog.openWindow({
	    		record: record.getForeignRecord(Tine.Billing.Model.Receipt, 'invoice_receipt_id')
			});
		}
	},
	
	openSoMember: function(){
		var record = this.getSelectedRecord();
		var win = Tine.Membership.SoMemberEditDialog.openWindow({
			record: record.getForeignRecord(Tine.Membership.Model.SoMember, 'member_id')
		});
	},
    createFeeInvoice: function(){
    	
    },
    onCreateFeeInvoice: function(){
    	
    },
    progressiveCreateFeeInvoice: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
		var feeProg = selectedRows[0];
		Ext.Ajax.request({
			scope: this,
			success: this.onProgressiveCreateFeeInvoice,
			params: {
				method: 'Membership.createFeeInvoiceForFeeProgress',
				feeProgressId: feeProg.get('id'),
				mode: 'progressive'
			},
			failure: function(){
				
			}
		});
    },
    onProgressiveCreateFeeInvoice: function(){
    	
    },
    reverseInvoice: function(memberId, feeProgressId, invoiceId){
    	if(!invoiceId){
    		
    		var selectedRows = this.grid.getSelectionModel().getSelections();
    		record = selectedRows[0];
    		if(!record.get('invoice_receipt_id')){
    			return;
    		}
    		invoiceId = record.getForeignId('invoice_receipt_id');
    		memberId = record.getForeignId('member_id');
    		feeProgressId = record.get('id');
    	}
		if(invoiceId){
			
			// TODO: get selected invoice id
			Ext.Ajax.request({
	            scope: this,
	            success: this.onReverseInvoice,
	            params: {
	                method: 'Membership.reverseInvoice',
	                memberId: memberId,
	                feeProgressId: feeProgressId,
	               	receiptId:  invoiceId
	            },
	            failure: this.onReverseInvoiceFailed
	        });
		}
	},
	onReverseInvoice: function(response){
		this.reverseInvoiceResponse = response;
		Ext.MessageBox.show({
            title: 'Erfolg', 
            msg: 'Die Rechnung wurde erfolgreich storniert.</br>Möchten Sie den Gutschriftsbeleg öffnen?',
            buttons: Ext.Msg.YESNO,
            scope:this,
            fn: this.showCreditDialog,
            icon: Ext.MessageBox.INFO
        });
	},
	onReverseInvoiceFailed: function(){
		Ext.MessageBox.show({
            title: 'Fehler', 
            msg: 'Das Stornieren der Rechnung ist fehlgeschlagen.',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.WARNING
        });
	},
	showCreditDialog: function(){
		var result = Ext.util.JSON.decode(this.reverseInvoiceResponse.responseText);
		if(result){
			var record = new Tine.Billing.Model.Receipt(result, result.id);
			Tine.Billing.CreditEditDialog.openWindow({
				record:record
			});
		}
	},
	getColumns: function() {
    	return Tine.Membership.getSoMemberFeeProgressGridConfig(this.app).columns;
	},
	onEditMembership: function(record){
		alert('edit member');
	},
	loadSoMember: function( soMemberRecord ){
    	this.soMemberRecord = soMemberRecord;
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
    	Tine.Membership.SoMemberFeeProgressGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.soMemberRecord || !this.inDialog){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.soMemberRecord){
    		filterOptions.record = this.soMemberRecord;
        	filterOptions.field = 'member_id';
    		filter = this.createForeignIdFilter(filterOptions);
    		options.params.filter.push(filter);
    	}
    },
    initDetailsPanel: function() {
    	if(!this.useDetailsPanel){
    		this.detailsPanel = null;
    		return;
    	}
    	
        this.detailsPanel = new Tine.widgets.grid.DetailsPanel({
            gridpanel: this,
            
            // use default Tpl for default and multi view
            defaultTpl: new Ext.XTemplate(
                '<div class="preview-panel-timesheet-nobreak">',
                    '<!-- Preview timeframe -->',           
                    '<div class="preview-panel preview-panel-timesheet-left">',
                        '<div class="bordercorner_1"></div>',
                        '<div class="bordercorner_2"></div>',
                        '<div class="bordercorner_3"></div>',
                        '<div class="bordercorner_4"></div>',
                        '<div class="preview-panel-declaration">' /*+ this.app.i18n._('timeframe')*/ + '</div>',
                        '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                            '<span class="preview-panel-bold">',
                            /*'First Entry'*/'<br/>',
                            /*'Last Entry*/'<br/>',
                            /*'Duration*/'<br/>',
                            '<br/>',
                            '</span>',
                        '</div>',
                        '<div class="preview-panel-timesheet-rightside preview-panel-left">',
                            '<span class="preview-panel-nonbold">',
                            '<br/>',
                            '<br/>',
                            '<br/>',
                            '</span>',
                        '</div>',
                    '</div>',
                    '<!-- Preview summary -->',
                    '<div class="preview-panel-timesheet-right">',
                        '<div class="bordercorner_gray_1"></div>',
                        '<div class="bordercorner_gray_2"></div>',
                        '<div class="bordercorner_gray_3"></div>',
                        '<div class="bordercorner_gray_4"></div>',
                        '<div class="preview-panel-declaration">'/* + this.app.i18n._('summary')*/ + '</div>',
                        '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                            '<span class="preview-panel-bold">',
                            this.app.i18n._('Anzahl Datensätze') + '<br/>',
                            this.app.i18n._('Summe Vorschau') + '<br/>',
                            this.app.i18n._('Summe sollgest') + '<br/>',
                            this.app.i18n._('Summe bezahlt') + '<br/>',
                            this.app.i18n._('Summe offen') + '<br/>',
                            '</span>',
                        '</div>',
                        '<div class="preview-panel-timesheet-rightside preview-panel-left">',
                            '<span class="preview-panel-nonbold">',
                            '{count}<br/>',
                            '{sum_preview}',
                            '{sum}',
                            '{sum_payed}',
                            '{sum_open}',
                            '</span>',
                        '</div>',
                    '</div>',
                '</div>'            
            ),
            
            showDefault: function(body) {
            	
				var data = {
				    count: this.gridpanel.store.proxy.jsonReader.jsonData.totalcount,
				    sum:  Sopen.Renderer.MonetaryNumFieldRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.sum),
				    sum_preview:  Sopen.Renderer.MonetaryNumFieldRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.sum_preview),
				    sum_payed:  Sopen.Renderer.MonetaryNumFieldRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.sum_payed),
				    sum_open:  Sopen.Renderer.MonetaryNumFieldRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.sum_open)
			    };
                
                this.defaultTpl.overwrite(body, data);
            },
            
            showMulti: function(sm, body) {
            	
                var data = {
                    count: sm.getCount(),
                    sum: 0,
                    sum_preview:0,
                    sum_payed: 0,
                    sum_open:0
                };
                sm.each(function(record){
                    data.sum = data.sum + parseFloat(record.data.sum_brutto);
                    data.sum_preview = data.sum + parseFloat(record.data.fee_to_calculate);
                    data.sum_payed = data.sum + parseFloat(record.data.payed_sum);
                    data.sum_open = data.sum + parseFloat(record.data.open_sum);
                });
                data.sum =  Sopen.Renderer.MonetaryNumFieldRenderer(data.sum);
                data.sum_preview =  Sopen.Renderer.MonetaryNumFieldRenderer(data.sum_preview);
                data.sum_payed =  Sopen.Renderer.MonetaryNumFieldRenderer(data.sum_payed);
                data.sum_open =  Sopen.Renderer.MonetaryNumFieldRenderer(data.sum_open);
                
                this.defaultTpl.overwrite(body, data);
            },
            
            tpl: new Ext.XTemplate(
        		'<div class="preview-panel-timesheet-nobreak">',	
        			'<!-- Preview beschreibung -->',
        			'<div class="preview-panel preview-panel-timesheet-left">',
        				'<div class="bordercorner_1"></div>',
        				'<div class="bordercorner_2"></div>',
        				'<div class="bordercorner_3"></div>',
        				'<div class="bordercorner_4"></div>',
        				'<div class="preview-panel-declaration">' /* + this.app.i18n._('Description') */ + '</div>',
        				'<div class="preview-panel-timesheet-description preview-panel-left" ext:qtip="{[this.encode(values.description)]}">',
        					'<span class="preview-panel-nonbold">',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'</span>',
        				'</div>',
        			'</div>',
        			'<!-- Preview detail-->',
        			'<div class="preview-panel-timesheet-right">',
        				'<div class="bordercorner_gray_1"></div>',
        				'<div class="bordercorner_gray_2"></div>',
        				'<div class="bordercorner_gray_3"></div>',
        				'<div class="bordercorner_gray_4"></div>',
        				'<div class="preview-panel-declaration">' /* + this.app.i18n._('Detail') */ + '</div>',
        				'<div class="preview-panel-timesheet-leftside preview-panel-left">',
        				// @todo add custom fields here
        				/*
        					'<span class="preview-panel-bold">',
        					'Ansprechpartner<br/>',
        					'Newsletter<br/>',
        					'Ticketnummer<br/>',
        					'Ticketsubjekt<br/>',
        					'</span>',
        			    */
        				'</div>',
        				'<div class="preview-panel-timesheet-rightside preview-panel-left">',
        					'<span class="preview-panel-nonbold">',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'</span>',
        				'</div>',
        			'</div>',
        		'</div>',{
                encode: function(value, type, prefix) {
                    if (value) {
                        if (type) {
                            switch (type) {
                                case 'longtext':
                                    value = Ext.util.Format.ellipsis(value, 150);
                                    break;
                                default:
                                    value += type;
                            }                           
                        }
                    	
                        var encoded = Ext.util.Format.htmlEncode(value);
                        encoded = Ext.util.Format.nl2br(encoded);
                        
                        return encoded;
                    } else {
                        return '';
                    }
                }
            })
        });
    }
});
Ext.reg('somemberfeeprogressgrid', Tine.Membership.SoMemberFeeProgressGridPanel);