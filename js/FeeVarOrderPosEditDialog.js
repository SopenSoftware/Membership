Ext.namespace('Tine.Membership');

Tine.Membership.FeeDefFilterEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FeeDefFilterEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FeeDefFilter,
	recordProxy: Tine.Membership.feeDefBackend,
	loadRecord: false,
	evalGrants: false,
	
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var fields = Tine.Membership.FeeDefFilterFormFields.get();
	    return {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	              	fields.id,fields.filters,
	              	
					fields.fee_definition_id
				],[
					fields.name
				],[
					fields.type
				]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FeeDefFilterEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 500,
        height: 700,
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
			    width: 150
			},
		type:
			{
			    fieldLabel: 'Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'type',
			    name:'type',
			    width: 200,
			    xtype:'combo',
			    store:[['COUNT','Zähler']],
			    value: 'COUNT',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			}		
	};
}