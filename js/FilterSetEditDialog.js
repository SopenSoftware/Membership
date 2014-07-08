Ext.namespace('Tine.Membership');

Tine.Membership.FilterSetEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FilterSetEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FilterSet,
	recordProxy: Tine.Membership.filterSetBackend,
	loadRecord: false,
	evalGrants: false,
	
	initComponent: function(){
		this.initPanels();
		Tine.Membership.FilterSetEditDialog.superclass.initComponent.call(this);
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
		this.filterResultPanel = new Tine.Membership.FilterResultGridPanel({
			frame: true,
			region:'center',
			height:360,
			disabled:true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
	},
	onAfterRender: function(){
		// add field listeners
	
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
			this.filterResultPanel.enable();
			this.filterResultPanel.loadFilterSet(this.record);
		}
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
				    height:180,
				    split:true,
					collapsible:true,
				    items:[{xtype:'columnform',items:[
				         [
				          {id: 'id', xtype:'hidden', width:1, name:'id'},
							{
							    fieldLabel: 'Bezeichnung',
							    id:'name',
							    name:'name',
							    value:null,
							    width: 400,
							    allowBlank: false
							}
						],[
							{
								fieldLabel: 'Verknüpfung',
							    disabledClass: 'x-item-disabled-view',
							    id:'filter_set_conjunction',
							    name:'conjunction',
							    width: 100,
							    xtype:'combo',
							    store:[['AND','und'],['OR','oder'],['XOR','extkl. oder']],
							    value: 'AND',
								mode: 'local',
								displayField: 'name',
							    valueField: 'id',
							    triggerAction: 'all'	
							},{
								fieldLabel: 'Ergebnistyp',
							    disabledClass: 'x-item-disabled-view',
							    id:'filter_set_result_type',
							    name:'result_type',
							    width: 150,
							    xtype:'combo',
							    store:[['SCALAR','Skalar'],['SCALARSET','Skalar-Menge'],['DATAOBJECT','Datenobjekt'],['DATAOBJECTCOLLECTION','Datenobjekt-Menge']],
							    value: 'SCALARSET',
								mode: 'local',
								displayField: 'name',
							    valueField: 'id',
							    triggerAction: 'all'
							},{
								fieldLabel: 'Transform',
							    disabledClass: 'x-item-disabled-view',
							    id:'filter_set_transform',
							    name:'transform',
							    width: 150,
							    xtype:'combo',
							    store:[['PERCENTAGE','Prozentverteilung'],['UNDEFINED','undefiniert']],
							    value: 'UNDEFINED',
								mode: 'local',
								displayField: 'name',
							    valueField: 'id',
							    triggerAction: 'all'
							}
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
				},
//				{
//					xtype: 'panel',
//					region: 'center',
//					layout:'border',
//					height:360,
//					items:[
						{
							xtype: 'panel',
							title: 'zugehörige Filter zur Abfrage',
							id:'filterResultPanel',
							region:'center',
							layout:'border',
							autoScroll:true,
							items: 	[this.filterResultPanel]
						}
//					]
//				}
				
				
		]};
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FilterSetEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 1024,
        height: 500,
        name: Tine.Membership.FilterSetEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FilterSetEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};