Ext.ns('Tine.Membership');

Tine.Membership.AddressbookPlugin = Ext.extend(Tine.Tinebase.AppPlugin, {
	pluginName: 'MembershipAddressbookPlugin',
	contactEditDialog: null,
	memberEditDialog: null,
	
	getEditDialogMainTabs: function(contactEditDialog, navigate){
		this.navigate = navigate;
		this.registerContactEventListeners(contactEditDialog);
		this.contactEditDialog = contactEditDialog;
		this.memberEditDialog = Tine.Membership.getSoMemberEditRecordAsTab(true);
		this.memberEditDialog.disable();
		/*if(this.navigate !== undefined && this.navigate !== null){
			this.memberEditDialog.on('gridreloaded',this.doNavigation, this);
		}*/
		//this.doNavigation();
		return [this.memberEditDialog];
	},
	
	doNavigation: function(){
		this.memberEditDialog.un('gridreloaded',this.doNavigation, this);
		this.navigate.member.params.grid = this.memberEditDialog.grid;
		this.navigate.member.func(this.navigate.member.params);
		
		return false;
	},
	
	registerContactEventListeners: function(contactEditDialog){
		contactEditDialog.on('loadcontact',this.onLoadContact,this);
	},
	
	onLoadContact: function(contact){
		if(contact.id != 0){
			this.memberEditDialog.enable();
			this.memberEditDialog.loadParentContact(contact);
		}
		return true;
	},
	
	onUpdateContact: function(contact){
		//alert('update');
		this.memberEditDialog.save(contact);
		return true;
	},
	
	onCancelContactEditDialog: function(){
		//alert('cancel');
		this.unsetMemberEditDialog();
		return true;
	},
	onSaveAndCloseContactDialog: function(){
		this.onSaveContact();
		this.unsetMemberEditDialog();
		return true;
	},
	unsetMemberEditDialog: function(){
		this.memberEditDialog = null;
	}
});