Ext.namespace('Tine.Membership');

Tine.Membership.FilterResultEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FilterResultEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FilterResult,
	recordProxy: Tine.Membership.filterResultBackend,
	loadRecord: false,
	evalGrants: false,
	filterSetRecord: null,
	initComponent: function(){
		this.initPanels();
		Tine.Membership.FilterResultEditDialog.superclass.initComponent.call(this);
	},
	initPanels: function(){
		 this.filterPanel = new Tine.widgets.form.FilterFormField({
			 	id:'fp',
		    	filterModels: Tine.Membership.Model.SoMember.getFilterModelForDynamicExport(),
		    	defaultFilter: 'membership_type',
		    	filters:[]
		});
		this.filterPanel.on('changefilter', this.onFilterChange, this);
	},
	onFilterChange: function(filterPanel){
		var filterValue = Ext.util.JSON.encode(filterPanel.getValue());
		Ext.getCmp('filters').setValue(filterValue);
	},
	onRecordLoad: function(){
		var filterValue =  Ext.util.JSON.decode(this.record.get('filters'));
		if(filterValue){
			this.filterPanel.setValue(filterValue);
		}
		if(this.filterSetRecord){
			this.record.set('filter_set_id', this.filterSetRecord.get('id'));
		}
		this.supr().onRecordLoad.call(this);
	},
	onRecordUpdate: function(){
		var filterValue =  Ext.util.JSON.encode(this.filterPanel.getValue());
		Ext.getCmp('filters').setValue(filterValue);
		this.supr().onRecordUpdate.call(this);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var fields = Tine.Membership.FilterResultFormFields.get();
	    return {
			xtype:'panel',
			layout:'border',
			items:[
				{
				    xtype: 'panel',
				    region: 'north',
				    frame:true,
				    border: false,
				    height:160,
			        items:[{xtype:'columnform',items:[
			             [
			              	fields.id,fields.filters,
			              	
							fields.filter_set_id
						],[
							fields.name, fields.key, fields.sort_order
						],[
						   	fields.sum_category, fields.scalar_formula1, fields.scalar_formula2
						],[
							fields.type, fields.sub_type
						]
			        ]}]
				},{
					xtype: 'panel',
					region: 'center',
					height:360,
					items:[
					       this.filterPanel
					]
				}
	    ]};
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FilterResultEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 300,
        name: Tine.Membership.FilterResultEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FilterResultEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};


Ext.ns('Tine.Membership.FilterResultFormFields');

Tine.Membership.FilterResultFormFields.get = function(){
	return{
		// hidden fields
		id: 
			{xtype: 'hidden',id:'id',name:'id'},
		filters:
			{xtype: 'hidden',id:'filters',name:'filters'},
		name:	
			{
			    fieldLabel: 'Bezeichnung',
			    id:'name',
			    name:'name',
			    value:null,
			    allowBlank:false,
			    width: 300
			},
		sort_order:	
			{
			    fieldLabel: 'Sortierreihenfolge',
			    id:'sort_order',
			    name:'sort_order',
			    value:null,
			    allowBlank:false,
			    width: 100
			},
		key:	
			{
			    fieldLabel: 'Schlüssel',
			    id:'key',
			    name:'key',
			    value:null,
			    allowBlank:false,
			    width: 200
			},
		type:
			{
			    fieldLabel: 'Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'type',
			    name:'type',
			    width: 300,
			    xtype:'combo',
			    store:[['COUNT','Anzahl'],['DATA','Daten'],['TRANSFORM','Transformation']],
			    value: 'COUNT',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		sub_type:
			{
			    fieldLabel: 'Sub-Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'sub_type',
			    name:'sub_type',
			    width: 300,
			    xtype:'combo',
			    store:[['TOTAL','Gesamt'],['PART','Anteil'],['UNDEFINED','undefiniert']],
				value: 'UNDEFINED',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		sum_category:
			{
			    fieldLabel: 'Kategorie Summe',
			    disabledClass: 'x-item-disabled-view',
			    id:'sum_category',
			    name:'sum_category',
			    width: 100,
			    xtype:'combo',
			    store:[['1','1'],['2','2'],['3','3']],
			    value: '1',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		scalar_formula1:	
			{
			    fieldLabel: 'Formel 1',
			    id:'scalar_formula1',
			    name:'scalar_formula1',
			    value:null,
			    allowBlank:true,
			    width: 150
			},
		scalar_formula2:	
			{
			    fieldLabel: 'Formel 2',
			    id:'scalar_formula2',
			    name:'scalar_formula2',
			    value:null,
			    allowBlank:true,
			    width: 150
			}
	};
}