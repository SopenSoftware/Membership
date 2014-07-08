Ext.namespace('Tine.Membership');

Tine.Membership.FeeDefinitionEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FeeDefinitionEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FeeDefinition,
	recordProxy: Tine.Membership.feeDefinitionBackend,
	loadRecord: false,
	evalGrants: false,
	
	initComponent: function(){
		this.initPanels();
		Tine.Membership.FeeDefinitionEditDialog.superclass.initComponent.call(this);
		this.on('afterrender', this.onAfterRender, this);
	},
	initButtons: function(){
		this.fbar = [
             '->',
             this.action_applyChanges,
             this.action_cancel,
             this.action_saveAndClose
        ];
	},
	initPanels: function(){
		this.filterPanelIterator = new Tine.widgets.form.FilterFormField({
				id:'fpIterator',
		    	disabled:false,
		    	filterModels: Tine.Membership.Model.SoMember.getFilterModelForFeeDefinitionIterator(),
		    	defaultFilter: 'membership_type',
		    	filters:[]
		});
		this.feeDefFilterPanel = new Tine.Membership.FeeDefFilterGridPanel({
			frame: true,
			height:200,
			disabled:true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.feeVarConfigPanel = new Tine.Membership.FeeVarConfigGridPanel({
			frame: true,
			height:200,
			disabled:true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.orderTemplateGridpanel = new Tine.Billing.QuickOrderGridPanel({
			title:'Positionen der Beitragsrechnung',
			region:'center',
			perspective:'orderTemplate',
			storeAtOnce: true,
			disabled:true,
			app: Tine.Tinebase.appMgr.get('Billing'),
			layout:'fit'
		});
		
		this.orderTemplateGridpanel.on('selectrow', this.selectOrderPosition, this);
		
		this.varOrderPosPropertyGrid = new Tine.Membership.FeeVarConfigOrderPosPropertyGridPanel({
		    title: 'Zuordnung Ergebniswerte',
		    recordClass: Tine.Membership.Model.FeeVarOrderPos,
		    autoHeight: true,
		    split:true,
		    collapsible:true,
		    collapseMode:'mini',
		    disabled:true,
		    region:'east',
		    layout:'fit',
		    width: 300
		});
	},
	onAfterRender: function(){
//		if(this.record.get('filters')!=''){
//			this.filterPanel.enable();
//		}
		// add field listeners
		Ext.getCmp('order_template_id').addListener('select',this.onChangeOrderTemplate, this);
		
//		if(this.record.id!==0){
//			this.feeDefFilterPanel.enable();
//			this.feeDefFilterPanel.loadFeeDefinition(this.record);
//			this.feeVarConfigPanel.enable();
//			this.feeVarConfigPanel.loadFeeDefinition(this.record);
//			this.orderTemplateGridpanel.enable();
//			var orderTemplateRecord = this.record.getForeignRecord(Tine.Billing.Model.OrderTemplate,'order_template_id');
//			this.orderTemplateGridpanel.loadDebitorData({
//				orderTemplateRecord:orderTemplateRecord,
//				debitorRecord: orderTemplateRecord.getForeignRecord(Tine.Billing.Model.Debitor,'debitor_id')
//			});
//			this.varOrderPosPropertyGrid.loadFeeDefinition(this.record);
//		}
	},
	/**
	 * intermediate: onRecordUpdate
	 * get value of fee class filter panel
	 * 
	 */
	onRecordLoad: function(){
		if(!this.rendered){
			this.onRecordLoad.defer(250,this);
			return;
		}
		var iteratorFilterValue =  Ext.util.JSON.decode(this.record.get('iterator_filters'));
		if(iteratorFilterValue){
			this.filterPanelIterator.setValue(iteratorFilterValue);
		}
		this.supr().onRecordLoad.call(this);
		if(this.record.id !== 0){
			this.feeDefFilterPanel.enable();
			this.feeDefFilterPanel.loadFeeDefinition(this.record);
			this.feeVarConfigPanel.enable();
			this.feeVarConfigPanel.loadFeeDefinition(this.record);
			this.orderTemplateGridpanel.enable();
			var orderTemplateRecord = this.record.getForeignRecord(Tine.Billing.Model.OrderTemplate,'order_template_id');
			this.orderTemplateGridpanel.loadDebitorData({
				orderTemplateRecord:orderTemplateRecord,
				debitorRecord: orderTemplateRecord.getForeignRecord(Tine.Billing.Model.Debitor,'debitor_id')
			});
			this.varOrderPosPropertyGrid.loadFeeDefinition(this.record);
			this.orderTemplateGridpanel.enable();
			this.orderTemplateGridpanel.getGrid().getStore().reload();
		}
	},
	/**
	 * intermediate: onRecordUpdate
	 * get value of fee class filter panel
	 * 
	 */
	onRecordUpdate: function(){
		var iteratorFilterValue =  Ext.util.JSON.encode(this.filterPanelIterator.getValue());
		Ext.getCmp('iterator_filters').setValue(iteratorFilterValue);
		this.supr().onRecordUpdate.call(this);
	},
	// field listeners
	onChangeOrderTemplate: function(orderTemplateField){
		var selOrderTemplate = orderTemplateField.selectedRecord;
		this.orderTemplateGridpanel.loadDebitorData({
			orderTemplateRecord:selOrderTemplate,
			debitorRecord: selOrderTemplate.getForeignRecord(Tine.Billing.Model.Debitor,'debitor_id')
		});
	},
	/**
	 *  listener for order template position panel single selection
	 *  @param	Ext.grid.RowSelectionModel sm
	 */
	selectOrderPosition: function(sm){
		// should only be one record in array (single selection)
		var selRecords = sm.getSelections();
		var selRecord = selRecords[0];
		// load property grid according to selected position
		this.varOrderPosPropertyGrid.enable();
		this.varOrderPosPropertyGrid.setOrderPostionRecord(selRecord);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		return {
			xtype:'panel',
			layout:'border',
			items:[
				{
				    xtype: 'panel',
				    region: 'north',
				    frame:true,
				    border: false,
				    height:150,
				    items:[{xtype:'columnform',items:[
				         [
							{
							    fieldLabel: 'Bezeichnung',
							    id:'name',
							    name:'name',
							    value:null,
							    width: 400,
							    allowBlank: false
							},
							Tine.Billing.Custom.getRecordPicker('OrderTemplate', 'order_template_id',{
								name: 'order_template_id',
								width:400
							}),
							{xtype:'hidden',id:'iterator_filters', name:'iterator_filters', width:1}
						],[
							{
								xtype: 'textarea',
							    fieldLabel: 'Beschreibung',
							    id:'description',
							    name:'description',
							    width: 800,
							    height: 40
							}
						]
				    ]}]
				},{
					xtype: 'panel',
					region: 'center',
					layout:'border',
					height:360,
					items:[
						{
							xtype: 'panel',
							title: 'Selektion beitragspflichtige Mitgliedschaft',
							height:110,
							id:'filterPanelIterator',
							region:'north',
							autoScroll:true,
							items: 	[this.filterPanelIterator]
						},{
							xtype: 'panel',
							title: 'Zu ermittelnde Daten über abhängige Mitglieder',
							id:'feeDefFilterPanel',
							region:'center',
							width:300,
							autoScroll:true,
							items: 	[this.feeDefFilterPanel]
						},{
							xtype: 'panel',
							title: 'Definition Ergebniswerte',
							id:'feeVarConfigPanel',
							region:'east',
							split:true,
							width:300,
							autoScroll:true,
							items: 	[this.feeVarConfigPanel]
						}
					]
				},{
					xtype:'panel',
					region:'south',
					height:300,
					layout:'border',
					items:[
						this.orderTemplateGridpanel,
						this.varOrderPosPropertyGrid
					]
				}
				
				
		]};
	}, 
	onChangeCumulativeFlag: function(f, v){
		switch(v){
		
		case true:
			this.filterPanel.enable();
			break;
			
		case false:
			this.filterPanel.disable();
			this.filterPanel.setValue({});
			break;
		}
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FeeDefinitionEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 1024,
        height: 800,
        name: Tine.Membership.FeeDefinitionEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FeeDefinitionEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};