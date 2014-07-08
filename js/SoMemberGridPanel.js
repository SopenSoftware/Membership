Ext.ns('Tine.Membership');

Tine.Membership.getSoMemberGridConfig = function(app){
	return {
	    recordClass: Tine.Membership.Model.SoMember,
		recordProxy: Tine.Membership.Model.soMemberBackend,
		columns: [
		   { header: app.i18n._('Kontakt'), dataIndex: 'contact_id',renderer:Tine.Membership.renderer.contactRenderer, sortable:true  },
		   { header: app.i18n._('Mitglieds-Nr.'), dataIndex: 'member_nr', sortable:true },		               
		   { header: app.i18n._('Mitglieds-Nr.(numerisch)'), dataIndex: 'member_nr_numeric', sortable:true },		               
           
		   { header: app.i18n._('Verein'), dataIndex: 'parent_member_id',renderer:Tine.Membership.renderer.membershipRenderer },
           { header: app.i18n._('Verband'), dataIndex: 'association_id',renderer:Tine.Membership.renderer.associationRenderer,hidden:true },
           { header: app.i18n._('Geburtsdatum'), dataIndex: 'birth_date', renderer: Tine.Tinebase.common.dateRenderer, sortable:true },
           { header: app.i18n._('Geburts-/Gründ.jahr'), dataIndex: 'birth_year', sortable:true },
           { header: app.i18n._('Alter'), dataIndex: 'person_age', sortable:true,hidden:true },
           { header: app.i18n._('Eintrittsdatum'), dataIndex: 'begin_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true },
           { header: app.i18n._('Mitglied Jahre'), dataIndex: 'member_age', sortable:true,hidden:true },
           { header: app.i18n._('Kündigungsdatum'), dataIndex: 'discharge_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true,hidden:true },
           { header: app.i18n._('Austrittsdatum'), dataIndex: 'termination_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true,hidden:true },
           { header: app.i18n._('Eintrittsgrund'), dataIndex: 'entry_reason_id',hidden:true },
           { header: app.i18n._('Austrittsgrund'), dataIndex: 'termination_reason_id',hidden:true },
           { header: app.i18n._('Export Mitgl.-Ausweis'), dataIndex: 'exp_membercard_datetime', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { header: app.i18n._('Bemerkungen'), dataIndex: 'member_notes',hidden:true },
           { header: app.i18n._('Rechnung Beitrag'), dataIndex: 'invoice_fee',hidden:true },
           { header: app.i18n._('Art Mitgliedschaft'), dataIndex: 'membership_type', renderer: Tine.Membership.renderer.memshipType },
           { header: app.i18n._('Beitragsgruppe'), dataIndex: 'fee_group_id', renderer: Tine.Membership.renderer.feeGroupRenderer },
           { header: app.i18n._('Status'), dataIndex: 'membership_status', renderer: Tine.Membership.renderer.memshipStatus, sortable:true },
           { header: app.i18n._('Verein n. sopen'), dataIndex: 'society_sopen_user',hidden:true },
           { header: app.i18n._('Zahlungsintervall'), dataIndex: 'fee_payment_interval', sortable:true, renderer: Tine.Membership.renderer.feePaymentInterval,hidden:true },
           { header: app.i18n._('Zahlungsmethode'), dataIndex: 'fee_payment_method', sortable:true, renderer: Tine.Billing.renderer.paymentMethodRenderer},
           { 
			   id: 'additional_fee', header: app.i18n._('Zusatzbeitrag'), dataIndex: 'additional_fee', sortable:true,
			   renderer: Sopen.Renderer.MonetaryNumFieldRenderer,hidden:true
           },
           { 
			   id: 'donation', header: app.i18n._('Spende'), dataIndex: 'donation', sortable:true,
			   renderer: Sopen.Renderer.MonetaryNumFieldRenderer,hidden:true
           },
           { header: app.i18n._('Bank-EZErm'), dataIndex: 'debit_auth_date', renderer: Tine.Tinebase.common.dateRenderer, sortable:true,hidden:true },
           { header: app.i18n._('IBAN'), dataIndex: 'iban', sortable:true},
           { header: app.i18n._('BIC'), dataIndex: 'bic', sortable:true },
           { header: app.i18n._('BLZ'), dataIndex: 'bank_account_bank_code', sortable:true },
           { header: app.i18n._('Bank'), dataIndex: 'bank_account_bank_name', sortable:true,hidden:true },
           { header: app.i18n._('Kto.nummer'), dataIndex: 'bank_account_number', sortable:true},
           { header: app.i18n._('Kto.inhaber'), dataIndex: 'bank_account_name', sortable:true },
           { header: app.i18n._('Dat. SEPA-Unterschrift'), dataIndex: 'sepa_signature_date', sortable:true },
           { header: app.i18n._('Kto.inhaber'), dataIndex: 'bank_account_name', sortable:true },
           
           { header: app.i18n._('online-User'), dataIndex: 'is_online_user', sortable:true,hidden:true },
           {
               header: 'besitzt Onlinezugang',
               width: 45,
               dataIndex: 'has_account',
               renderer: Tine.Membership.getAccountStatusIcon, 
               sortable:true,hidden:true
           },
           { id: 'is_affiliator', header: app.i18n._('Ist Werber'), dataIndex: 'is_affiliator', hidden:true, sortable:true },
           { id: 'is_affiliated', header: app.i18n._('wurde geworben'), dataIndex: 'is_affiliated', hidden:true, sortable:true },
           { id: 'affiliate_contact_id', header: app.i18n._('Werber-Nr'), dataIndex: 'affiliate_contact_id', hidden:true, sortable:true },
           { id: 'affiliator_provision_date', header: app.i18n._('Werb.prov.Ausz'), dataIndex: 'affiliator_provision_date', hidden:true, sortable:true, renderer: Tine.Tinebase.common.dateRenderer  },
           { id: 'affiliator_provision', header: app.i18n._('Werberprovision'), dataIndex: 'affiliator_provision', hidden:true, sortable:true },
           { id: 'count_magazines', header: app.i18n._('Anz.Zeitungen'), dataIndex: 'count_magazines', hidden:true, sortable:true },
           { id: 'count_additional_magazines', header: app.i18n._('zus. Zeitungen'), dataIndex: 'count_additional_magazines', hidden:true, sortable:true },
           { header: app.i18n._('Benutzer Altsystem'), dataIndex: 'ext_system_username', sortable:true,hidden:true },
           { id: 'ext_system_modified', header: 'Änderung Altsystem', dataIndex: 'ext_system_modified', renderer: Tine.Tinebase.common.dateTimeRenderer, sortable:true,hidden:true },
           { id: 'print_reception_date', header: 'Druck Aufnahmebestät.', dataIndex: 'print_reception_date', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { id: 'print_discharge_date', header: 'Druck Künd.bestät.', dataIndex: 'print_discharge_date', renderer: Tine.Tinebase.common.dateRenderer,hidden:true },
           { id: 'print_confirmation_date', header: 'Druck Vers.bestät.', dataIndex: 'print_confirmation_date', renderer: Tine.Tinebase.common.dateRenderer,hidden:true }
           
        ],
		actionTexts: {
			addRecord:{
				buttonText: 'Mitglied Stammdaten hinzufügen',
				buttonTooltip: 'Fügt einen neuen Mitglied-Stammdatensatz hinzu'
			},
			editRecord:{
				buttonText: 'Mitglied Stammdaten bearbeiten',
				buttonTooltip: 'Öffnet das Formular "Mitgliedstammdaten" zum Bearbeiten'
			},
			deleteRecord:{
				buttonText: 'Mitglied Stammdaten löschen',
				buttonTooltip: 'Löscht ausgewählte(n) Mitglied'
			}
	   }};
};

Tine.Membership.getSoMemberEconomicGridConfig = function(app){
	var soMemberColumns = Tine.Membership.getSoMemberGridConfig(app).columns;
	soMemberColumns = soMemberColumns.concat(
	[
		{ header: app.i18n._('Kunde'), dataIndex: 'debitor_id', sortable:true, renderer: Tine.Billing.renderer.debitorRenderer  },
		{ header: app.i18n._('Off. Posten'), dataIndex: 'count_open_items', sortable:true, },
		{ header: app.i18n._('Soll'), dataIndex: 's_brutto',renderer: Sopen.Renderer.MonetaryNumFieldRendererS, bodyStyle:'color:#FF0000;', sortable:true  },
		{ header: app.i18n._('Haben'), dataIndex: 'h_brutto',renderer: Sopen.Renderer.MonetaryNumFieldRendererH, sortable:true  },
		{ header: app.i18n._('Saldo'), dataIndex: 'saldation',renderer: Sopen.Renderer.MonetaryNumFieldRendererH, sortable:true  },
		{ header: 'letzte Rechnung', dataIndex: 'last_receipt_date', renderer: Tine.Tinebase.common.dateRenderer }                                  
	]);
	return {
		
		recordClass: Tine.Membership.Model.SoMemberEconomic,
		recordProxy: Tine.Membership.Model.soMemberEconomicBackend,
		columns: soMemberColumns
	};

};

Tine.Membership.getAccountStatusIcon = function(value){
	var qtip, icon;
	if(value == 1){
		qtip = 'Besitzt einen Onlinezugang';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'oxygen/16x16/actions/ok.png';
	}else{
		qtip = 'Besitzt keinen Onlinezugang';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'oxygen/16x16/actions/dialog-cancel.png';
	}
	return '<img class="TasksMainGridStatus" src="' + icon + '" ext:qtip="' + qtip + '">';
};

/**
 * dependent edit form grid panel, to be shown in a dependent edit form
 */
Tine.Membership.SoMemberGridPanelNested = Ext.extend(Tine.widgets.grid.DependentEditFormGridPanel, {
	id: 'tine-membership-somember-nested-gridpanel',
	stateId: 'tine-membership-somember-nested-gridpanel',
	gridConfig: {
		gridID: 'tine-membership-somember-nested-gridpanel-gp',
        loadMask: true
    },	
	title: 'Mitglied',
	titlePrefix: 'Mitglied Stammdaten ',
    grouping: false,
    withFilterToolbar: true,
    parentRelation:{
		fKeyColumn: 'contact_id',
		refColumn: 'id'
	},
    recordClass: Tine.Membership.Model.SoMember,
    recordProxy: Tine.Membership.Model.soMemberBackend,
	initComponent : function() {
		this.actionTexts = Tine.Membership.getSoMemberGridConfig(this.app).actionTexts,
		this.filterModels = Tine.Membership.Model.SoMember.getFilterModel();
		
		Tine.Membership.SoMemberGridPanelNested.superclass.initComponent.call(this);
	},

	getColumns: function() {
		return Tine.Membership.getSoMemberGridConfig(this.app).columns.concat(this.getCustomfieldColumns());
	}
});
Ext.reg('somembernestedgrid', Tine.Membership.SoMemberGridPanelNested);

/**
 * regular grid panel
 */
Tine.Membership.SoMemberGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-somember-gridpanel',
	stateId: 'tine-membership-somember-gridpanel',
    recordClass: Tine.Membership.Model.SoMember,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'contact_id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    customExportActions: new Ext.util.MixedCollection(),
    initComponent: function() {
    	if(!this.recordProxy){
    		this.recordProxy = Tine.Membership.soMemberBackend;
    	}
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.SoMemberGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.SoMember.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: quickFilter
        });
    },  
    initActions: function(){
        this.actions_printMembers = new Ext.Action({
            text: 'Mitgliederliste drucken',
			disabled: false,
            handler: this.printMembers,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updateMemberPrinter
        });
        this.actions_printMemberLetters = new Ext.Action({
            text: 'Mitgliederanschreiben drucken',
			disabled: false,
            handler: this.printMemberLetters,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updateMemberPrinter
        });
        this.actions_printMemberMultiLetters = new Ext.Action({
            text: 'Mitglieder-Serienbrief drucken',
			disabled: false,
            handler: this.printMemberMultiLetters,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updateMemberPrinter
        });
        this.actions_printLabels = new Ext.Action({
            text: 'Adressaufkleber drucken',
			disabled: false,
            handler: this.printLabels,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updateMemberPrinter
        });
        this.actions_printExtMembers = new Ext.Action({
            text: 'Auswertung/Export',
			disabled: false,
            handler: this.printExtMembers,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updateMemberPrinter
        });
        this.actions_exportMembersAsCsv = new Ext.Action({
            text: 'Csv',
			disabled: false,
            handler: this.exportMembersAsCsv,
            iconCls: 'tinebase-action-export-csv',
            scope: this//,
            //actionUpdater: this.updateMemberPrinter
        });
    	this.actions_exportMembersSportdiverCsv = new Ext.Action({
   		 text: 'Sporttaucher',
			disabled: false,
            handler: this.exportMembersSportdiverCsv,
            iconCls: 'tinebase-action-export-csv',
            scope: this//,
    	});
        this.actions_tdImport = new Ext.Action({
            text: 'TD Import',
			disabled: false,
            handler: this.tdImport,
            iconCls: 'action_import',
            scope: this
        });
    	this.actions_print = new Ext.Action({
        	allowMultiple: false,
        	//disabled:true,
            text: 'Druckaufträge',
            menu:{
            	items:[
            	       this.actions_printMembers,
            	       this.actions_printMemberLetters,
            	       this.actions_printMemberMultiLetters,
            	       this.actions_printLabels,
            	       this.actions_printExtMembers
		    	]
            }
        });
    	
    	this.actions_execDueTasks = new Ext.Action({
            text: 'Fällige Aufgaben ausführen',
			disabled: false,
			tooltip: 'Hiermit können fällige Aufgaben, wie z.B. die Finalisierung von Mitgliederaustritten, durchgeführt werden.<br/>Der Prozess erfolgt innerhalb eines Jobs. Die durchgeführten Aktionen können hierdurch nachvollzogen werden.',
            handler: this.execDueTasks,
            scope: this
        });
    	
    	 this.actions_payInvoice = new Ext.Action({
             text: 'Rechnung bezahlen',
 			disabled: false,
             handler: this.payInvoice,
             iconCls: 'action_edit',
             scope: this,
             actionUpdater: this.updateMemberPrinter
         });
    	
    	var exportItems = [this.actions_exportMembersAsCsv];
    	
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

	    this.actions_createFeeInvoice = new Ext.Action({
	        text: 'Ausgewählte abrechnen',
	        disabled: false,
	        handler: this.createFeeInvoiceForSelectedMembers,
	        scope: this
	    });
	    
	    this.actions_batchCreateFeeInvoice = new Ext.Action({
	        text: 'Stapelabrechnung',
	        disabled: false,
	        handler: this.batchCreateFeeInvoice,
	        scope: this,
	    });
      
      this.actions_feeInvoice = new Ext.Action({
      	allowMultiple: false,
      	iconCls: 'action_execBilling',
      	  text: 'Beitragsabrechnung',
          menu:{
          	items:[
          	       this.actions_createFeeInvoice,
          	       this.actions_batchCreateFeeInvoice
		    	]
          }
      });
        
        this.actionUpdater.addActions([
           this.actions_printMembers//,
           //this.actions_exportMembersAsCsv
        ]);
        
        /*this.actions_MGV = new Ext.Action({
            text: 'Mitglieder-Hauptversammlung',
			disabled: false,
            handler: this.MGV,
            iconCls: 'action_edit',
            scope: this
        });*/
        Tine.Membership.SoMemberGridPanel.superclass.initActions.call(this);
    },
    /*MGV: function(){
    	var win = Tine.Membership.VDSTMgvDialog.openWindow({
			panelTitle: 'Mitglieder - Hauptversammlung 2013 Stimmrechtsverwaltung',
			layout:'fit'
		});
    	
    },*/
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
	    		customExportDefinition: exportDefinition
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
                    forFeeProgress: false
                }
            }).start();
    	}
    },
    tdImport: function(btn) {
        var popupWindow = Tine.Membership.TDImportDialog.openWindow({
            appName: 'Membership',
            // update grid after import
            listeners: {
                scope: this,
                'update': function(record) {
                    this.loadData(true);
                }
            },
            record: new Tine.Membership.Model.TDImportJob({
            }, 0)
        });
    },
	getSelectedIds: function(){
		 var selectedRows = this.grid.getSelectionModel().getSelections();
		 var result = [];
		 for(var i in selectedRows){
			 result.push(selectedRows[i].id);
		 }
		 return result;
	},
	execDueTasks: function(){
		var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Fällige Aufgaben durchführen',
    		actionType: 'execDueTasks',
    		useFilter:false,
    		getAdditionalFormItems: function(){
    			return [{
					xtype: 'datefield',
					fieldLabel: 'Für Gültigkeitsdatum',
					id:'valid_date',
					name:'valid_date',
					value: new Date(),
					width: 150
				},{
				    fieldLabel: 'Aktion',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'action',
				    name:'action',
				    width: 200,
				    xtype:'combo',
				    //store: Tine.Membership.getStore('Action'), -> to much entries which couldn't be used
				    store: [['TERMINATION','Austritt'],['FEEGROUPCHANGE','Beitragsgruppenwechsel'],['PARENTCHANGE','Vereinswechsel']],
				    value: 'TERMINATION',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
					xtype: 'textfield',
					fieldLabel: 'zusätzl. Jobbezeichnung',
					id:'job_name2',
					name:'job_name2',
					width: 250
				}];
    		}
		});
	},
    createFeeInvoiceForSelectedMembers: function(){
    	var selIds = this.getSelectedIds();
    	    	
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Beitragsabrechnung für ausgewählte Mitglieder',
			selectedMemberIds: selIds,
    		actionType: 'createFeeInvoiceForSelectedMembers',
    		getAdditionalFormItems: function(){
    			return [{
					xtype: 'textfield',
					fieldLabel: 'Beitragsjahr',
					id:'fee_year',
					name:'fee_year',
					width: 150
				},{
					xtype: 'datefield',
					fieldLabel: 'Stichtag',
					id:'due_date',
					name:'due_date',
					value: new Date(),
					width: 110
				},{
				    fieldLabel: 'Aktion',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'action',
				    name:'action',
				    width: 200,
				    xtype:'combo',
				    store:[['FEEPROGRESS','Beitragsverläufe erzeugen'],['FEEINVOICE','Hauptabrechnung'],['FEEINVOICECOMPLETE','Verläufe+Hauptabrechnung'],['FEEINVOICECURRENT','Folgeabrechnung (unterjährig)']],
				    value: 'FEEPROGRESS',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				}];
    		}
		});
    	/*Ext.Ajax.request({
            scope: this,
            success: this.onSuccessCreateFeeInvoiceForSelectedMembers,
            params: {
                method: 'Membership.createFeeInvoiceForSelectedMembers',
               	memberIds:  selIds
            },
            failure: this.onFailureCreateFeeInvoiceForSelectedMembers
        });*/
    },
    onSuccessCreateFeeInvoiceForSelectedMembers: function(){
    	alert('success');
    },
    onFailureCreateFeeInvoiceForSelectedMembers: function(){
    	alert('failure');
    },
    
    updateMemberPrinter: function(action, grants, records) {
    	action.setDisabled(true);
        if (records.length == 1) {
            var obj = records[0];
            if (! obj) {
                return false;
            }
            action.setDisabled(false);
        }
    },
    printMemberLetters: function(){
    	var memberSortStore = 
		[
		   ['member_nr','Mitglied-Nr'],
		   ['n_given','Vorname'],
		   ['n_family','Nachname'],
		   ['n_fileas','angezeigter Name'],
		   ['adr_one_postalcode','PLZ:Adresse1'],
		   ['adr_one_locality','Ort:Adresse1'],
		   ['adr_one_countryname','Land:Adresse1'],
		   ['member_age','Mitgliedsjahre'],
		   ['person_age','Alter Mitglied']
		];
    	
    	
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Mitgliederanschreiben drucken',
    		actionType: 'printMemberLetters',
    		runJob:true,
    		getAdditionalFormItems: function(){
    			return [{
				    fieldLabel: 'Art des Anschreibens',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'letter_type',
				    name:'letter_type',
				    width: 200,
				    xtype:'combo',
				    store:[['1','Aufnahmeanschreiben'],['2','Versicherungsbestätigung'],['3','Kündigungsbestätigung'],['4','Mitgliedsausweis']],
				    value: '1',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all',
				    listeners:{
				    	select: function(v){
				    		if(v == 4){
				    			Ext.getCmp('membercard_date').enable();
				    		}else{
				    			Ext.getCmp('membercard_date').disable();
				    		}
				    		
				    	},
				    	scope:this
				    }
				},{
					xtype: 'extuxclearabledatefield',
					fieldLabel: 'Neudruck der Anschreiben vom',
					id:'reprint_date',
					name:'reprint_date',
					value: null,
					width: 210
				},{
					xtype: 'extuxclearabledatefield',
					fieldLabel: 'Jahr Mitgliedsausweis',
					id:'membercard_date',
					name:'membercard_date',
					format:'Y',
					disabled:false,
					value: null,
					width: 210
				},{
				    fieldLabel: 'Sortierung1',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield1',
				    name:'member_sortfield1',
				    width: 140,
				    xtype:'combo',
				    store:memberSortStore,
				    value: 'member_nr',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Richtung',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield1_dir',
				    name:'member_sortfield1_dir',
				    width: 100,
				    xtype:'combo',
				    store:[['ASC','aufsteigend'],['DESC','absteigend']],
				    value: 'ASC',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Sortierung2',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield2',
				    name:'member_sortfield2',
				    width: 140,
				    xtype:'combo',
				    store:memberSortStore,
				    value: 'member_nr',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Richtung',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield2_dir',
				    name:'member_sortfield2_dir',
				    width: 100,
				    xtype:'combo',
				    store:[['ASC','aufsteigend'],['DESC','absteigend']],
				    value: 'ASC',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
    			    fieldLabel: 'Bezeichnung',
    			    height:40,
    			    id: 'multiletter_letter_name',	
    			    
    			    width: 400
    			},{
    			    fieldLabel: 'Bezeichnung Zusatz',
    			    height:40,
    			    id: 'multiletter_letter_description',	
    			    
    			    width: 400
    			}];
    		}
		});
    },
    printMemberMultiLetters: function(){
    	var memberSortStore = 
    		[
    		   ['member_nr','Mitglied-Nr'],
    		   ['n_given','Vorname'],
    		   ['n_family','Nachname'],
    		   ['n_fileas','angezeigter Name'],
    		   ['member_age','Mitgliedsjahre'],
    		   ['person_age','Alter Mitglied']
    		];
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Mitglieder-Serienbrief drucken',
    		actionType: 'printMemberMultiLetters',
    		runJob:true,
    		mainGrid: this,
    		height:600,
    		getAdditionalFormItems: function(){
    			return [
    			 {
    				xtype:'recordpickercombo',
    			    fieldLabel: 'Vorlage',
    			    height:40,
    			    id: 'multiletter_letter_template',	
    			    blurOnSelect: true,
    			    allowBlank:false,
    			    recordClass: Tine.DocManager.Model.Template,
    			    width: 400
    			},{
				    fieldLabel: 'Sortierung1',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield1',
				    name:'member_sortfield1',
				    width: 140,
				    xtype:'combo',
				    store:memberSortStore,
				    value: 'member_nr',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Richtung',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'member_sortfield1_dir',
				    name:'member_sortfield1_dir',
				    width: 100,
				    xtype:'combo',
				    store:[['ASC','aufsteigend'],['DESC','absteigend']],
				    value: 'ASC',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
    			    fieldLabel: 'Bezeichnung',
    			    height:40,
    			    id: 'multiletter_letter_name',	
    			    
    			    width: 400
    			},{
    			    fieldLabel: 'Bezeichnung Zusatz',
    			    height:40,
    			    id: 'multiletter_letter_description',	
    			    
    			    width: 400
    			}];
    		}
		});
    },
    payInvoice: function(){
    	try{
    		var memberNr = this.getSelectedRecord().get('member_nr');
    		var win = Tine.Billing.PaymentEditDialog.openWindow({
        		record: null,
        		memberNr: memberNr
    		});
    	}catch(e){
    		
    	}
    },
    printMembers: function(){
		var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Mitgliederliste drucken',
    		actionType: 'printMembers'
		});
    },
	printLabels: function(){
		var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Adressaufkleber drucken',
			actionType: 'printLabels'
		});
	},
    printExtMembers: function(){
		var win = Tine.Membership.ExpMembershipDialog.openWindow({
			panelTitle: 'Auswertung/Export',
    		actionType: 'customExport'
		});
    },
    exportMembersAsCsv: function(){
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
    		panelTitle: 'Mitglieder als Csv exportieren',
    		actionType: 'exportMembersAsCsv'
    	});
    },
    batchCreateFeeInvoice: function(){
		var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Beitragsabrechnung im Stapel',
    		actionType: 'batchCreateFeeInvoice',
    		getAdditionalFormItems: function(){
    			return [{
					xtype: 'numberfield',
					fieldLabel: 'Beitragsjahr',
					id:'fee_year',
					name:'fee_year',
					value: Tine.Membership.Config.Jobs.BatchFeeInvoice.FeeYearDefault,
					minValue:Tine.Membership.Config.Jobs.BatchFeeInvoice.FeeYearMin,
					maxValue:Tine.Membership.Config.Jobs.BatchFeeInvoice.FeeYearMax,
					width: 150
				},{
					xtype: 'datefield',
					fieldLabel: 'Stichtag',
					id:'due_date',
					name:'due_date',
					value: new Date(),
					width: 110
				},{
				    fieldLabel: 'Aktion',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'action',
				    name:'action',
				    width: 200,
				    xtype:'combo',
				    store:[['FEEPROGRESS','Beitragsverläufe erzeugen'],['FEEINVOICE','Hauptabrechnung'],['FEEINVOICECOMPLETE','Verläufe+Hauptabrechnung'],['FEEINVOICECURRENT','Folgeabrechnung (unterjährig)']],
				    value: Tine.Membership.Config.Jobs.BatchFeeInvoice.ActionDefault,
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				}];
    		}
		});
    },
	getColumns: function() {
    	return Tine.Membership.getSoMemberGridConfig(this.app).columns.concat(this.getCustomfieldColumns());
	},
    getActionToolbarItems: function() {
    	return [
			{
                xtype: 'buttongroup',
                columns: 1,
                frame: false,
                items: [
                    this.actions_export,
                    this.actions_tdImport
                ]
            },
            Ext.apply(new Ext.Button(this.actions_print), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                iconCls: 'action_exportAsPdf'
            }),
            Ext.apply(new Ext.Button(this.actions_feeInvoice), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            }),
            Ext.apply(new Ext.Button(this.actions_execDueTasks), {
                scale: 'medium',
                iconCls: 'action_execDueTasksMedium',
                rowspan: 2,
                iconAlign: 'top'
            })/*,
            Ext.apply(new Ext.Button(this.actions_MGV), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            })*/
            
             
            
        ];
    },
    
    getContextMenuItems: function(){
    	return [/*'-',this.actions_payInvoice*/];
    }
});
Ext.reg('soMembergrid', Tine.Membership.SoMemberGridPanel);

/**
 * regular grid panel
 */
Tine.Membership.SoMemberEconomicGridPanel = Ext.extend(Tine.Membership.SoMemberGridPanel, {
	id: 'tine-membership-somember-economic-gridpanel',
	stateId: 'tine-membership-somember-economic-gridpanel',
    recordClass: Tine.Membership.Model.SoMemberEconomic,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'contact_id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'contact_id'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.soMemberEconomicBackend;
        this.initFilterToolbar();
        
        Tine.Membership.SoMemberEconomicGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		var filterModels = Tine.Membership.Model.SoMember.getFilterModelForEconomicView();
		console.log(filterModels);
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: filterModels,
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: quickFilter
        });
    },  
    initActions: function(){
    	this.supr().initActions.call(this);
    },
    addCustomExports: function(){
    	return this.supr().addCustomExports();
    },
    getColumns: function() {
    	return Tine.Membership.getSoMemberEconomicGridConfig(this.app).columns.concat(this.getCustomfieldColumns());
	}
});

Tine.Membership.SoMemberSoMemberGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-somember-somember-gridpanel',
	stateId: 'tine-membership-somember-somember-gridpanel',
	region:'center',
    recordClass: Tine.Membership.Model.SoMember,
    evalGrants: false,
    parentContactRecord: null,
    parentMemberRecord: null,
    // grid specific
    defaultSortInfo: {field: 'member_nr', direction: 'DESC'},
    ddConfig:{
    	ddGroup: 'ddGroupContact'
    },
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    actionNewText: '',
    contactIdenticalToParent: false,
    withFilterToolbar: true,
    initComponent: function() {
        this.recordProxy = Tine.Membership.soMemberBackend;
        //this.actionToolbarItems = this.getToolbarItems();
        this.parentContactRecord = new Tine.Addressbook.Model.Contact({},0);
        this.gridConfig.columns = this.getColumns();
        this.gridConfig.plugins = [];
        if(this.withFilterToolbar){
        	this.initFilterToolbar();
        	 this.plugins = this.plugins || [];
             this.plugins.push(this.filterToolbar);    
        }
           
        this.action_addSoMember = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddSoMember,
            text: this.actionNewText,
            iconCls: 'actionAdd',
            scope: this
        });
        this.addMemberButton =  Ext.apply(new Ext.Button(this.action_addSoMember), {
			 scale: 'small',
             rowspan: 2,
             iconAlign: 'left'
        });
        this.on('afterrender', this.onAfterRender, this);
        Tine.Membership.SoMemberSoMemberGridPanel.superclass.initComponent.call(this);
		 this.pagingToolbar.add(
				 '->'
		 );
		 this.pagingToolbar.add(
				 this.addMemberButton
		 );
    },
    onAddSoMember: function(){
    	var contactRecord = this.parentContactRecord;
    	
    	if(this.contactIdenticalToParent){
    		contactRecord = this.parentMemberRecord.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
    	}
    	this.soMemberWin = Tine.Membership.SoMemberEditDialog.openWindow({
    		simplePanel:true,
    		preselectedMembershipKind: this.membershipKind,
    		parentMemberRecord: this.parentMemberRecord,
    		parentContactRecord: this.parentContactRecord,
    		contactRecord: contactRecord,
			associationRecord: this.associationRecord,
			listeners: {
                scope: this,
                'update': function(record) {
                    this.onReloadSoMember();
                }
            }
		});
		this.soMemberWin.on('beforeclose',this.onReloadSoMember,this);
    },
    addMemberFromContact: function(contact){
    	this.soMemberWin = Tine.Membership.SoMemberEditDialog.openWindow({
    		simplePanel:true,
    		preselectedMembershipKind: this.membershipKind,
    		contactRecord: contact,
    		parentMemberRecord: this.parentMemberRecord,
    		parentContactRecord: this.parentContactRecord,
			associationRecord: this.associationRecord,
			listeners: {
                scope: this,
                'update': function(record) {
                    this.onReloadSoMember();
                }
            }
		});
		this.soMemberWin.on('beforeclose',this.onReloadSoMember,this);
    },
    onEditInNewWindow: function(button, event) {
        var record; 
        if (button.actionType == 'edit') {
            if (! this.action_editInNewWindow || this.action_editInNewWindow.isDisabled()) {
                // if edit action is disabled or not available, we also don't open a new window
                return false;
            }
            var selectedRows = this.grid.getSelectionModel().getSelections();
            record = selectedRows[0];
            
        } else if (button.actionType == 'copy') {
            var selectedRows = this.grid.getSelectionModel().getSelections();
            record = this.copyRecord(selectedRows[0].data);

        } else {
            record = new this.recordClass(this.recordClass.getDefaultData(), 0);
        }
        
        this.soMemberWin = Tine.Membership.SoMemberEditDialog.openWindow({
    		simplePanel:true,
    		preselectedMembershipKind: this.membershipKind,
    		record:record,
			listeners: {
                scope: this,
                'update': function(record) {
                    this.onRelaodSoMember();
                }
            }
		});
    },
    loadParentContact: function(contact){
    	this.parentContactRecord = contact;
    	this.store.reload();
    },
    onReloadSoMember: function(){
    	this.store.reload();
    },
    initActions: function(){
    	
       this.supr().initActions.call(this);
    },
    /**
     * add custom items to action toolbar
     * 
     * @return {Object}
     */
    getActionToolbarItems: function() {
        return [
            Ext.apply(new Ext.Button(this.actions_print), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                iconCls: 'action_exportAsPdf'
            })
        ];
    },
    /**
     * add custom items to context menu
     * 
     * @return {Array}
     */
    getContextMenuItems: function() {
        var items = [
            '-'
        ];
        
        return items;
    },
    
    initFilterToolbar: function() {
    	if(this.withFilterToolbar){
			var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
			this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
	            app: this.app,
	            filterModels: Tine.Membership.Model.SoMember.getReducedFilterModel(),
	            defaultFilter: 'query',
	            filters: [{field:'query',operator:'contains',value:''}],
	            plugins: []
	        });
    	}
    },
	getColumns: function() {
    	return Tine.Membership.getSoMemberGridConfig(this.app).columns;
	},
	
	loadParentMember: function( association, parentMember, membershipKind, actionNewText ){
		this.parentMemberRecord = parentMember;
		this.actionNewText = actionNewText;
		this.addMemberButton.setText(this.actionNewText);
		this.membershipKind = membershipKind;
		this.parentContactRecord = this.parentMemberRecord.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
		this.associationRecord = new Tine.Membership.Model.Association(association.data);
		this.store.reload();
	},	
	loadContact: function(contact){
		this.contactRecord = contact;
	},
	onStoreBeforeload: function(store, options) {
    	Tine.Membership.SoMemberSoMemberGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	delete options.params.filter;
    	options.params.filter = [];
    	if(this.parentMemberRecord){
	    	if(this.parentMemberRecord.id == 0){
	    		return true;
	    	}
	    	var filter = {	
				field:'parent_member_id',
				operator:'AND',
				value:[{
					field:'id',
					operator:'equals',
					value: this.parentMemberRecord.get('id')}]
			};
	
	        options.params.filter.push(filter);
	        
	    	var filter = {	
				field:'membership_type',
				operator:'equals',
				value: this.membershipKind
			};
	
	        options.params.filter.push(filter);
	        
	        return true;
    	}
    	
    	if(this.parentContactRecord){
	    	if(this.parentContactRecord.id == 0){
	    		return true;
	    	}
	    	var filter = {	
				field:'contact_id',
				operator:'AND',
				value:[{
					field:'id',
					operator:'equals',
					value: this.parentContactRecord.get('id')}]
			};
	
	        options.params.filter.push(filter);
	        
	        return true;
    	}
    },

    onAfterRender: function(){
		this.initDropZone();
    },
    initDropZone: function(){
    	if(!this.ddConfig){
    		return;
    	}
		this.dd = new Ext.dd.DropTarget(this.el, {
			scope: this,
			ddGroup     : this.ddConfig.ddGroup,
			notifyEnter : function(ddSource, e, data) {
				this.scope.el.stopFx();
				this.scope.el.highlight();
			},
			onDragOver: function(e,id){
			},
			notifyDrop  : function(ddSource, e, data){
				return this.scope.onDrop(ddSource, e, data);
				//this.scope.addRecordFromArticle(data.selections[0]);
				//this.scope.fireEvent('drop',data.selections[0]);
				return true;
			}
		});
		// self drag/drop
		this.dd.addToGroup(this.gridConfig.ddGroup);
	},
	onDrop: function(ddSource, e, data){
		switch(ddSource.ddGroup){
		// if article gets dropped in: add new receipt position
		case 'ddGroupContact':
			return this.addMemberFromContact(data.selections[0]);
			break;
		}
	}
});
Ext.reg('somembersomembergrid', Tine.Membership.SoMemberSoMemberGridPanel);


/**
 * regular grid panel
 */
Tine.Membership.SoMemberSelectionGrid = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-somember-selection-grid',
	stateId: 'tine-membership-somember-selection-grid',
    recordClass: Tine.Membership.Model.SoMember,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'contact_id', direction: 'DESC'},
    useQuickSearchPlugin: false,
    
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title',
        // drag n drop
        enableDragDrop: true,
        ddGroup: 'ddGroupSoMember'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.soMemberBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.filterToolbar = this.getFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.SoMemberSelectionGrid.superclass.initComponent.call(this);
    },
    
	getColumns: function() {
    	return Tine.Membership.getSoMemberGridConfig(this.app).columns;
	}
});
Ext.reg('memberselectiongrid', Tine.Membership.SoMemberSelectionGrid);