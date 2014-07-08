Ext.namespace('Tine.Membership');

Tine.Membership.ExpMembershipDialog = Ext.extend(Ext.form.FormPanel, {
	windowNamePrefix: 'ExpMembershipWindow_',
	//mode: 'local',
	appName: 'Membership',
	layout:'fit',
	//recordClass: Tine.Membership.Model.SoMember,
	predefinedFilter: null,
	/**
	 * {Tine.Membership.CreateTLAccountGridPanel}	positions grid
	 */
	grid: null,
	actionType: 'printMembers',
	customExportDefinition:null,
	/**
	 * initialize component
	 */
	initComponent: function(){
		this.title = this.initialConfig.panelTitle;
		this.actionType = this.initialConfig.actionType;
		this.initActions();
		this.initToolbar();
		this.items = this.getFormItems();
		//this.on('afterrender',this.onAfterRender,this);
		Tine.Membership.ExpMembershipDialog.superclass.initComponent.call(this);
	},
//	this.onAfterRender: function(){
//		switch(this.actionType){
//		case 'printMembers':
//			
//			break;
//		}
//	},
	setFilter: function(filter){
		this.filterPanel.setValue(filter);
	},
	initActions: function(){
        this.actions_print = new Ext.Action({
            text: 'Ok',
            disabled: false,
            iconCls: 'action_applyChanges',
            handler: this.doCall,
            scale:'small',
            iconAlign:'left',
            scope: this
        });
        this.actions_cancel = new Ext.Action({
            text: 'Abbrechen',
            disabled: false,
            iconCls: 'action_cancel',
            handler: this.cancel,
            scale:'small',
            iconAlign:'left',
            scope: this
        });        
	},
	doCall: function(){
		switch(this.actionType){
		case 'printMembers':
			this.printMembers();
			break;
		case 'exportMembersAsCsv':
			this.exportMembersAsCsv();
			break;
		case 'customExport':
			this.customExport();
			break;
		case 'batchCreateFeeInvoice':
			this.batchCreateFeeInvoice();
			break;
		}
	},
	/**
	 * init bottom toolbar
	 */
	initToolbar: function(){
		this.bbar = new Ext.Toolbar({
			height:48,
        	items: [
        	        '->',
                    Ext.apply(new Ext.Button(this.actions_cancel), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    }),
                    Ext.apply(new Ext.Button(this.actions_print), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    })
                ]
        });
	},
	/**
	 * save the order including positions
	 */
	printMembers: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		var win = window.open(
				Sopen.Config.runtime.requestURI + '?method=Membership.printMemberList&filters='+filterValue,
				"membersPDF",
				"menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes"
		);
	},
	onSelectExportFavorite: function(recordPicker){
		var record = recordPicker.selectedRecord;
		var assocFilterValue =  Ext.util.JSON.decode(record.get('filter_main_orga'));
		if(assocFilterValue){
			this.assocFilterPanel.setValue(assocFilterValue);
		}
		var societyFilterValue =  Ext.util.JSON.decode(record.get('filter_society'));
		if(societyFilterValue){
			this.societyFilterPanel.setValue(societyFilterValue);
		}
		var filterValue =  Ext.util.JSON.decode(record.get('filter_membership'));
		if(filterValue){
			this.filterPanel.setValue(filterValue);
		}
		this.getForm().loadRecord(record);
        this.getForm().clearInvalid();
	},
	exportMembersAsCsv: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		
		var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Membership.exportMembersAsCsv',
                requestType: 'HTTP',
                filters: filterValue//,
            }
        }).start();
	},
	customExport: function(){
		
		var favoriteRecord = new Tine.Membership.Model.MembershipExport({},0);
		var form = this.getForm();
        form.updateRecord(favoriteRecord);
        
        favoriteRecord.set('filter_main_orga', Ext.util.JSON.encode(this.assocFilterPanel.getValue()));
        favoriteRecord.set('filter_society', Ext.util.JSON.encode(this.societyFilterPanel.getValue()));
        favoriteRecord.set('filter_membership', Ext.util.JSON.encode(this.filterPanel.getValue()));
        
//        Ext.Ajax.request({
//            scope: this,
//            success: this.onSaveFavorite,
//            params: {
//                method: 'Membership.runPredefinedExport',
//               	recordData:  favoriteRecord.data
//            },
//            failure: this.onSaveFavoriteFailed
//        });
//		
		var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Membership.runPredefinedExport',
                requestType: 'HTTP',
                membershipExport:  Ext.util.JSON.encode(favoriteRecord.data)
            }
        }).start();
	},
	/**
	 * Cancel and close window
	 */
	cancel: function(){
		this.purgeListeners();
        this.window.close();
	},
	/**
	 * Get form items of subclass
	 */
	getAdditionalFormItemsA: function(){
		// maybe overriden in child classes
		return [[
		        {xtype: 'hidden', id:'id', name:'id', width:1},
				
		        new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Definierte Ausgaben',
				    disabledClass: 'x-item-disabled-view',
				    id:'membership_export',
				    name:'membership_export',
				    width: 400,
				    height:20,
				    disabled: false,
				    onAddEditable: true,	// only has effect in class:DependentEditForm
				    onEditEditable: true,	// only has effect in class:DependentEditForm
				    blurOnSelect: true,
				    recordClass: Tine.Membership.Model.MembershipExport,
				    listeners:{
				    	select:{
				    		fn: this.onSelectExportFavorite,
				    		scope:this
				    	}
				    }
				})
				,{
				    fieldLabel: 'Bezeichnung der Ausgabe (nur zum Abspeichern nötig)',
				    disabledClass: 'x-item-disabled-view',
				    id:'name',
				    name:'name',
				    width: 400
				 }
				],[
				 new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Druckvorlage',
					    id: 'output_template_id',
					    name: 'output_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 400
					}),
		        
				 Tine.Membership.Custom.getFilterSetRecordPicker('membership_export_filter_set_id',
					{
					    fieldLabel: 'Filter-Set',
					    id: 'membership_export_filter_set_id',
					    name:'filter_set_id',
					    width: 400
					})
		]];
	},
	getAdditionalFormItemsB: function(){
		return [
			{
				xtype: 'fieldset',
				title: 'Gliederungselemente',
				width:250,
				height:150,
				items:[
						{
							xtype:'checkbox',
							disabledClass: 'x-item-disabled-view',
							id: 'classify_main_orga',
							name: 'classify_main_orga',
							hideLabel:true,
							boxLabel: 'Gliederung Hauptorganisation',
						    width:180
						},{
							xtype:'checkbox',
							disabledClass: 'x-item-disabled-view',
							id: 'classify_society',
							name: 'classify_society',
							hideLabel:true,
							boxLabel: 'Gliederung Verein',
						    width:180
						},{
							xtype:'checkbox',
							disabledClass: 'x-item-disabled-view',
							id: 'classify_fee_group',
							name: 'classify_fee_group',
							hideLabel:true,
							boxLabel: 'Gliederung Beitragsgruppe',
						    width:180
						}  
					]
				},{
					xtype: 'fieldset',
					title: 'Ergebnismenge',
					width:250,
					height:150,
					items:[
							{
				    fieldLabel: 'Ergebnistyp',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'result_type',
				    name:'result_type',
				    width: 200,
				    xtype:'combo',
				    store:[['COUNT','Anzahlen'],['DATA','Daten']],
				    value: 'DATA',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Quelle',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'result_source',
				    name:'result_source',
				    width: 200,
				    xtype:'combo',
				    store:[['ASSET','Mitgliederstamm'],['FLOW','Mitgliederbewegung']],
				    value: 'ASSET',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
				    fieldLabel: 'Typ Kalkulation',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'calculation_type',
				    name:'calculation_type',
				    width: 200,
				    xtype:'combo',
				    store:[['UNSPECIFIED','unspezifiziert'],['FEEGROUPOVERVIEW','BG-Übersicht'],['FEEOVERVIEW','Beitrags-Übersicht']],
				    value: 'UNSPECIFIED',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				},{
					
				}
				 ]
			},{
				xtype: 'fieldset',
				title: 'Zeitraum/Stichtag',
				width:250,
				height:150,
				items:[
					{
						xtype:'extuxclearabledatefield',
						disabledClass: 'x-item-disabled-view',
						id: 'begin_datetime',
						name: 'begin_datetime',
						value: new Date(),
						fieldLabel: 'Stichtag/Beginn Zeitraum',
					    width:180
					},{
						xtype:'extuxclearabledatefield',
						disabledClass: 'x-item-disabled-view',
						id: 'end_datetime',
						name: 'end_datetime',
						fieldLabel: 'Zeitraum Ende',
					    width:180
					}
				]
			}      
		        
		];
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		switch(this.actionType){
		case 'printMembers':
			return this.getExpMembersFormItems();
			break;
		case 'exportMembersAsCsv':
		
			break;
		case 'customExport':
			return this.getExpMembersFormItems();
		}
		// use some fields from brevetation edit dialog
		
	},
	saveFavorite: function(){
		var favoriteRecord = new Tine.Membership.Model.MembershipExport({},0);
		var form = this.getForm();
        form.updateRecord(favoriteRecord);
        
        favoriteRecord.set('filter_main_orga', Ext.util.JSON.encode(this.assocFilterPanel.getValue()));
        favoriteRecord.set('filter_society', Ext.util.JSON.encode(this.societyFilterPanel.getValue()));
        favoriteRecord.set('filter_membership', Ext.util.JSON.encode(this.filterPanel.getValue()));
        
        Ext.Ajax.request({
            scope: this,
            success: this.onSaveFavorite,
            params: {
                method: 'Membership.saveMembershipExport',
               	recordData:  favoriteRecord.data
            },
            failure: this.onSaveFavoriteFailed
        });
	    
        //console.log(favoriteRecord);
        
	},
	onSaveFavorite: function(response){
		//console.log(response);
	},
	onSaveFavoriteFailed: function(){
		Ext.MessageBox.show({
            title: 'Fehler', 
            msg: 'Favorit konnte nicht gespeichert werden.',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.WARNING
        });
	},
	deleteFavorite: function(){
		var favoriteRecord = new Tine.Membership.Model.MembershipExport({},0);
		var form = this.getForm();
        form.updateRecord(favoriteRecord);
        
		Ext.Ajax.request({
         scope: this,
         success: this.onDeleteFavorite,
         params: {
             method: 'Membership.deleteMembershipExports',
             ids:  [favoriteRecord.getId()]
          },
          failure: this.onDeleteFavoriteFailed
      });
	},
	onDeleteFavorite: function(){
		var favoriteRecord = new Tine.Membership.Model.MembershipExport(Tine.Membership.Model.MembershipExport.getDefaultData(),0);
		this.assocFilterPanel.deleteAllFilters();
		this.societyFilterPanel.deleteAllFilters();
		this.filterPanel.deleteAllFilters();
		this.getForm().loadRecord(favoriteRecord);
        this.getForm().clearInvalid();
		this.getForm().findField('membership_export').setValue(null);
		
        Ext.MessageBox.show({
            title: 'Erfolg', 
            msg: 'Der Favorit wurde gelöscht.',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.INFO
        });
	},
	onDeleteFavoriteFailed: function(){
		Ext.MessageBox.show({
            title: 'Fehler', 
            msg: 'Favorit konnte nicht gelöscht werden.',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.WARNING
        });
	},
	getExpMembersFormItems: function(){
		var formItems = [
		                 {xtype:'hidden',id:'filters', name:'filters', width:1}
		];
		//formItems = formItems.concat(this.getAdditionalFormItems());
		
		var panel = {
	        xtype: 'panel',
	        border: false,
	        region:'north',
	        autoScroll:true,
	        height:260,
	        split:true,
	        frame:true,
	        layout:'border',
	        items:[
    	       {
    	    	   xtype:'panel',
    	    	   region:'north',
    	    	   height: 80,
    	    	   items:[{
    	    		   xtype:'columnform',
    	    		   items:  this.getAdditionalFormItemsA()
    	    	   }]
    	       },{
    	    	   xtype:'panel',
    	    	   region:'center',
    	    	   height:300,
    	    	   items:[{
    	    		   xtype:'columnform',
    	    		   items:  [this.getAdditionalFormItemsB()]
    	    	   }]
    	       }
	       	]
	        //}]
	    };

		if(this.predefinedFilter == null){
			this.predefinedFilter = [];
		}
		this.assocFilterPanel = new Tine.widgets.form.FilterFormField({
		 	id:'afp',
	    	filterModels: Tine.Membership.Model.Association.getFilterModel(),
	    	defaultFilter: 'query',
	    	region:'center'
		});
		this.societyFilterPanel = new Tine.widgets.form.FilterFormField({
		 	id:'sfp',
	    	filterModels: Tine.Membership.Model.SoMember.getFilterModel(),
	    	defaultFilter: 'query',
	    	region:'center'
		});
		this.filterPanel = new Tine.widgets.form.FilterFormField({
			 	id:'fp',
		    	filterModels: Tine.Membership.Model.SoMember.getFilterModel(),
		    	defaultFilter: 'membership_type',
		    	filters:this.predefinedFilter,
		    	region:'center'
		});
		 
		this.favoritesToolbar = new Ext.Toolbar({
			items:[
				new Ext.Button({
				    text: 'Favorit speichern',
				    disabled: false,
				    iconCls: 'action_applyChanges',
				    handler: this.saveFavorite,
				    scale:'small',
				    iconAlign:'left',
				    scope: this
				}),
				new Ext.Button({
				    text: 'Favorit löschen',
				    disabled: false,
				    iconCls: 'action_delete',
				    handler: this.deleteFavorite,
				    scale:'small',
				    iconAlign:'left',
				    scope: this
				})
			]
		});
		this.favoritesToolbar.setHeight(26);
		
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
		   ['person_age','Alter Mitglied'],
		   ['birth_date','Geburtsdatum'],
		   ['birth_year','Geburtsjahr'],
		   ['birth_month','Geburtsmonat'],
		   ['birth_day','Geburtstag'],
		   ['UNDEFINED','---']
		];
		
		var wrapper = {
			xtype: 'panel',
			layout: 'border',
			frame: true,
			tbar: this.favoritesToolbar,
			items: [
			   panel,
			   {
				   xtype:'panel',
				   layout:'vbox',
				   region:'center',
				   items:[
						{
							xtype: 'panel',
							title: 'Selektion Hauptorganisation',
							height:120,
							collapsible:true,
							id:'assocFilterPanel',
							region:'north',
							autoScroll:true,
							flex:1,
							layout:'border',
							items: 	[
							       	 
							       	 {
							       		 xtype:'columnform',
							       		 region:'north',
							       		 anchor:'100%',
							       		 height:38,
							       		 items:[[
											{
											    fieldLabel: 'Sortierung1',
											    disabledClass: 'x-item-disabled-view',
											    allowEdit:false,
											    id:'assoc_sortfield1',
											    name:'assoc_sortfield1',
											    width: 140,
											    xtype:'combo',
											    store:[['short_name','Kurzbezeichnung'],['assoc_nr','Nummer'],['UNDEFINED','---']],
											    value: 'assoc_nr',
												mode: 'local',
												displayField: 'name',
											    valueField: 'id',
											    triggerAction: 'all'
											},{
											    fieldLabel: 'Richtung',
											    disabledClass: 'x-item-disabled-view',
											    allowEdit:false,
											    id:'assoc_sortfield1_dir',
											    name:'assoc_sortfield1_dir',
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
											    id:'assoc_sortfield2',
											    name:'assoc_sortfield2',
											    width: 140,
											    xtype:'combo',
											    store:[['short_name','Kurzbezeichnung'],['assoc_nr','Nummer'],['UNDEFINED','---']],
											    value: 'UNDEFINED',
												mode: 'local',
												displayField: 'name',
											    valueField: 'id',
											    triggerAction: 'all'
											},{
											    fieldLabel: 'Richtung',
											    disabledClass: 'x-item-disabled-view',
											    allowEdit:false,
											    id:'assoc_sortfield2_dir',
											    name:'assoc_sortfield2_dir',
											    width: 100,
											    xtype:'combo',
											    store:[['ASC','aufsteigend'],['DESC','absteigend']],
											    value: 'ASC',
												mode: 'local',
												displayField: 'name',
											    valueField: 'id',
											    triggerAction: 'all'
											}
							       		 ]]
							       	 },
							       	 this.assocFilterPanel
							       	 
							       	
							]
						},
						{
							xtype: 'panel',
							title: 'Selektion Mitglieder',
							height:120,
							id:'filterPanel',
							region:'south',
							collapsible:true,
							flex:1,
							autoScroll:true,
							layout:'border',
							items: 	[
								{
									 xtype:'columnform',
									 region:'north',
									 anchor:'100%',
									 height:38,
									 items:[[
									{
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
									    value: 'UNDEFINED',
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
									}
									 ]]
								},       	 
							       	this.filterPanel
							]
						},   
						{
							xtype: 'panel',
							title: 'Selektion Verein',
							height:120,
							id:'societyFilterPanel',
							region:'center',
							flex:1,
							autoScroll:true,
							layout:'border',
							items: 	[
								{
										 xtype:'columnform',
										 region:'north',
										 anchor:'100%',
										 height:38,
										 items:[[
										{
										    fieldLabel: 'Sortierung1',
										    disabledClass: 'x-item-disabled-view',
										    allowEdit:false,
										    id:'society_sortfield1',
										    name:'society_sortfield1',
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
										    id:'society_sortfield1_dir',
										    name:'society_sortfield1_dir',
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
										    id:'society_sortfield2',
										    name:'society_sortfield2',
										    width: 140,
										    xtype:'combo',
										    store:memberSortStore,
										    value: 'UNDEFINED',
											mode: 'local',
											displayField: 'name',
										    valueField: 'id',
										    triggerAction: 'all'
										},{
										    fieldLabel: 'Richtung',
										    disabledClass: 'x-item-disabled-view',
										    allowEdit:false,
										    id:'society_sortfield2_dir',
										    name:'society_sortfield2_dir',
										    width: 100,
										    xtype:'combo',
										    store:[['ASC','aufsteigend'],['DESC','absteigend']],
										    value: 'ASC',
											mode: 'local',
											displayField: 'name',
										    valueField: 'id',
										    triggerAction: 'all'
										}
										 ]]
									 },
							    this.societyFilterPanel
							]
						}
						
				   ]
			   }
			   
			]
		
		};
		return wrapper;
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.ExpMembershipDialog.openWindow = function (config) {
    // TODO: this does not work here, because of missing record
	record = {};
	var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 1080,
        height: 900,
        name: Tine.Membership.ExpMembershipDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.ExpMembershipDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};