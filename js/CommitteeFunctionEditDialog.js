Ext.namespace('Tine.Membership');

Tine.Membership.CommitteeFunctionEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'CommitteeFunctionEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.CommitteeFunction,
	recordProxy: Tine.Membership.committeeFunctionBackend,
	loadRecord: false,
	evalGrants: false,

	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
	    return {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	                {
						xtype: 'checkbox',
						disabledClass: 'x-item-disabled-view',
						id: 'is_default',
						name: 'is_default',
						hideLabel:true,
					    boxLabel: 'als Voreinstellung verwenden',
					    width: 250
					}
				 ],[
					{
						fieldLabel: 'Bezeichnung',
					    id:'name',
					    name:'name',
					    value:null,
					    width: 500
					} 
	             ],[
					Tine.Membership.Custom.getRecordPicker('CommitteeKind','committee_kind_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Art Gremium',
					    name:'committee_kind_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:false
					})   
	             ]
	        ]}]
	    };
	}
});


/**
 * Membership Edit Popup
 */
Tine.Membership.CommitteeFunctionEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 850,
        height: 300,
        name: Tine.Membership.CommitteeFunctionEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.CommitteeFunctionEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

