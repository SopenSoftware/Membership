Ext.namespace('Tine.Membership');

Tine.Membership.MembershipAccountEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'MembershipAccountEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.MembershipAccount,
	recordProxy: Tine.Membership.membershipAccountBackend,
	accountId: null,
	contactId: null,
	memberId: null,
	relatedMemberRecord: null,
	loadRecord: false,
	evalGrants: false,
	
	initComponent: function(){
		this.i18n = Tine.Tinebase.appMgr.get('Billing').i18n;
		this._initActions();
		this.tbarItems = [this.resendAccountDataButton];
		this.on('load',this.onLoadLocal, this);
		this.on('afterrender', this.onAfterRender, this);
		Tine.Membership.MembershipAccountEditDialog.superclass.initComponent.call(this);
	},
	_initActions: function(){
		var resendAccountData = this.i18n._('Neue Zugangsdaten schicken');
		this.resendAccountDataButton = new Ext.Action({
            id: 'resendAccountDataButton',
            text: this.i18n._('Neue Zugangsdaten schicken'),
            handler: this.resendAccountData,
            iconCls: 'action_edit',
            disabled: true,
            scope: this
        });
	},
	onLoadLocal: function(){
		if(this.record.isNew()){
			this.resendAccountDataButton.disable();
		}else{
			this.resendAccountDataButton.enable();
		}
		return true;
	},
	onAfterRender: function(){
		if(this.accountId){
			Ext.getCmp('membership_account_account_id').setValue(this.accountId);
		}
		
		if(this.memberId){
			Ext.getCmp('membership_account_member_id').setValue(this.memberId);
		}
		
		if(this.contactRecord){
			Ext.getCmp('membership_account_contact_id').setValue(this.contactRecord);
		}
		
		if(this.relatedMemberRecord){
			Ext.getCmp('membership_account_related_member_id').setValue(this.relatedMemberRecord);
		}
	},
	resendAccountData: function(){
		
		Ext.Ajax.request({
            scope: this,
            success: this.onResendAccountData,
            params: {
                method: 'Membership.resendAccountData',
                id:  this.record.getId(),
                accountId:  this.record.getForeignId('account_id', 'accountId')
            },
            failure: this.onResendAccountDataFailed
        });
	},
	onResendAccountData: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		var check;
		if(result.success == true){
			 Ext.MessageBox.show({
	             title: 'Erfolg', 
	             msg: 'Es wurden neue Zugangsdaten an den Benutzer versendet.',
	             buttons: Ext.Msg.OK,
	             icon: Ext.MessageBox.INFO
	         });
		}else{
			this.onResendAccountDataFailed(response);
		}
	},
	onResendAccountDataFailed: function(response){
		Ext.MessageBox.show({
            title: 'Fehler', 
            msg: 'Die Zugangsdaten konnten nicht generiert bzw. versendet werden.',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.ERROR
        });
	},
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
					Tine.Addressbook.Custom.getRecordPicker('Contact','membership_account_contact_id',{
						disabledClass: 'x-item-disabled-view',
						width: 400,
						fieldLabel: 'Benutzer',
					    name:'contact_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:false
					}),
					{
						xtype:'hidden',
						id:'membership_account_account_id',
						name:'account_id'
					},{
						xtype:'hidden',
						id:'membership_account_member_id',
						name:'member_id'
					}
				 ],[
						Tine.Membership.Custom.getRecordPicker('SoMember','membership_account_related_member_id',{
						//disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Zugang zu Mitgliedschaft',
					    name:'related_member_id',
					    disabled: true,
					   // displayField: 'org_name',
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:true,
					    ddConfig:{
				        	ddGroup: 'ddGroupContact'
				        }
					})
				],[
					{
					   	xtype: 'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'gültig von', 
						id:'membership_account_valid_from_datetime',
						name:'valid_from_datetime',
					    width: 150,
					    value: new Date()
					},{
					   	xtype: 'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'gültig bis', 
						id:'membership_account_valid_to_datetime',
						name:'valid_to_datetime',
					    width: 150
					}
				 ],[
					{
					   	xtype: 'textfield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Benutzername', 
						id:'membership_account_account_loginname',
						name:'account_loginname',
						disabled:true
					}
				],[	
					{
					   	xtype: 'textfield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Benutzer Email-Adresse', 
						id:'membership_account_account_emailadress',
						name:'account_emailadress',
						disabled:true
					}
				 ],[
					{
					   	xtype: 'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Letzter Login', 
						id:'membership_account_account_lastlogin',
						name:'account_lastlogin',
					    width: 150,
					    disabled:true
					},{
					   	xtype: 'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Letzte PW-Änderung', 
						id:'membership_account_account_lastpasswordchange',
						name:'account_lastpasswordchange',
					    width: 150,
						disabled:true
					}
				 ]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.MembershipAccountEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.MembershipAccountEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.MembershipAccountEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};