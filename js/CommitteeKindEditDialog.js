Ext.namespace('Tine.Membership');

Tine.Membership.CommitteeKindEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'CommitteeKindEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.CommitteeKind,
	recordProxy: Tine.Membership.committeeKindBackend,
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
	             ]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.CommitteeKindEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.CommitteeKindEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.CommitteeKindEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};