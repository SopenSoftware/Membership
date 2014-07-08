Ext.namespace('Tine.Membership');

Tine.Membership.AssociationEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'AssociationEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.Association,
	recordProxy: Tine.Membership.associationBackend,
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
					Tine.Addressbook.Custom.getRecordPicker('Contact','association_contact_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Kontakt Hauptorganisation',
					    name:'contact_id',
					    disabled: false,
					    addressbookFilter: Tine.Membership.registry.get('preferences').get('addressbookAssociations'),
					    onAddEditable: true,
					    onEditEditable: true,
					    blurOnSelect: true,
					    allowBlank:true,
					    ddConfig:{
				        	ddGroup: 'ddGroupContact'
				        }
					})
				],[	
					{
						fieldLabel: 'Hauptorganisation-Nr',
					    emptyText: '<automatisch>',
					    disabledClass: 'x-item-disabled-view',
					    id:'association_nr',
					    name:'association_nr',
					    value:null,
					    disabled:false,
					    width: 150
					},{
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
					    id:'association_name',
					    name:'association_name',
					    value:null,
					    width: 500
					} 
				 ],[
					{
						fieldLabel: 'Kurzbezeichnung',
					    id:'short_name',
					    name:'short_name',
					    value:null,
					    width: 200
					} 
	             ]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.AssociationEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.AssociationEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.AssociationEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};