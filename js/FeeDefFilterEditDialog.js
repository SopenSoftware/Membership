Ext.namespace('Tine.Membership');

Tine.Membership.FeeDefFilterEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FeeDefFilterEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FeeDefFilter,
	recordProxy: Tine.Membership.feeDefFilterBackend,
	loadRecord: false,
	evalGrants: false,
	feeDefinitionRecord: null,
	initComponent: function(){
		this.initPanels();
		Tine.Membership.FeeDefFilterEditDialog.superclass.initComponent.call(this);
	},
	initPanels: function(){
		 this.filterPanel = new Tine.widgets.form.FilterFormField({
			 	id:'fp',
		    	filterModels: Tine.Membership.Model.SoMember.getFilterModelForFeeDefinition(),
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
		if(this.feeDefinitionRecord){
			this.record.set('fee_definition_id', this.feeDefinitionRecord.get('id'));
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
		var fields = Tine.Membership.FeeDefFilterFormFields.get();
	    return {
			xtype:'panel',
			layout:'border',
			items:[
				{
				    xtype: 'panel',
				    region: 'north',
				    frame:true,
				    border: false,
				    height:120,
			        items:[{xtype:'columnform',items:[
			             [
			              	fields.id,fields.filters,
			              	
							fields.fee_definition_id
						],[
							fields.name, fields.is_invoice_component
						],[
							fields.type, fields.related_membership
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
Tine.Membership.FeeDefFilterEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 300,
        name: Tine.Membership.FeeDefFilterEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FeeDefFilterEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};


Ext.ns('Tine.Membership.FeeDefFilterFormFields');

Tine.Membership.FeeDefFilterFormFields.get = function(){
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
		type:
			{
			    fieldLabel: 'Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'type',
			    name:'type',
			    width: 300,
			    xtype:'combo',
			    store:[['COUNT','Zähler']],
			    value: 'COUNT',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		related_membership:
			{
			    fieldLabel: 'bezogene Mitgliedschaft',
			    disabledClass: 'x-item-disabled-view',
			    id:'related_membership',
			    name:'related_membership',
			    width: 300,
			    xtype:'combo',
			    store:[['OWN','beitr.pflicht. Mitgliedschaft'],['PARENT','übergeordn. Mitgliedschaft']],
			    value: 'OWN',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		is_invoice_component:
			{
				xtype: 'checkbox',
				disabledClass: 'x-item-disabled-view',
				id: 'is_invoice_component',
				name: 'is_invoice_component',
				hideLabel:true,
				boxLabel: 'abrechnungsrelevant',
			    width:200
			}
	};
}