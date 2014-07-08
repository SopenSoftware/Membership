Ext.ns('Tine.Membership.Helpers');

Tine.Membership.Helpers.SoMember = {
		
	getMemberEntryDefaultDate: function(){
		return new Date();
	},
	
	onUpdateContact: function(memberDialog, contactRecord){
		var memberRecord = memberDialog.getSelectedRecord();
		if(memberRecord.isNew()){
			
			Ext.getCmp('membership_birth_date').setValue(contactRecord.get('bday'));
			Ext.getCmp('membership_bank_code').setValue(contactRecord.get('bank_code'));
			Ext.getCmp('membership_bank_account_nr').setValue(contactRecord.get('bank_account_number'));
			Ext.getCmp('membership_bank_name').setValue(contactRecord.get('bank_name'));
			Ext.getCmp('membership_account_holder').setValue(contactRecord.get('bank_account_name'));

		}else{
			
		}
	}
	
};