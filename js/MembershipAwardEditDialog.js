Ext.namespace('Tine.Membership');

Tine.Membership.MembershipAwardEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'MembershipAwardEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.MembershipAward,
	recordProxy: Tine.Membership.membershipAwardBackend,
	loadRecord: false,
	evalGrants: false,
	memberRecord: null,
	initComponent: function(){
		this.on('afterrender', this.onAfterRender, this);
		Tine.Membership.MembershipAwardEditDialog.superclass.initComponent.call(this);
	},
	onRecordLoad: function() {
		Tine.Membership.MembershipAwardEditDialog.superclass.onRecordLoad.call(this);
		//this.record.set('event_id',this.eventRecord.get('id'));
	},
	onAfterRender: function(){
		if(this.record.id == 0){
			if(this.memberRecord){
				var memberSelect = Ext.getCmp('membership_award_member_id');
				memberSelect.setValue(this.memberRecord);
				memberSelect.disable();
			}
		}
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var fields = Tine.Membership.MembershipAwardFormFields.get();
		return [Tine.Membership.MembershipAwardForm.getColumnForm(
		[
		 	[fields.member_id, fields.id],
		 	[fields.award_list_id, fields.award_datetime]
	    ])];
	}
});


/**
 * Membership Edit Popup
 */
Tine.Membership.MembershipAwardEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 850,
        height: 300,
        name: Tine.Membership.MembershipAwardEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.MembershipAwardEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

Ext.ns('Tine.Membership.MembershipAwardForm');

Tine.Membership.MembershipAwardForm.getColumnForm = function(formFields){
	return {
        xtype: 'panel',
        border: false,
        frame:true,
        items:[{xtype:'columnform',items:[
           formFields                               	
        ]}]
    };
};

Ext.ns('Tine.Membership.MembershipAwardFormFields');

Tine.Membership.MembershipAwardFormFields.get = function(){
	return{
		// hidden fields
		id: 
			{xtype: 'hidden',id:'committee_func_id',name:'id'},
		member_id:
			new Tine.Tinebase.widgets.form.RecordPickerComboBox({
		        fieldLabel: 'Mitglied',
		        disabledClass: 'x-item-disabled-view',
		        id:'membership_award_member_id',
		        name: 'member_id',
		        blurOnSelect: true,
		        recordClass: Tine.Membership.Model.SoMember,
		        width: 200,
		        allowBlank:false
		    }),
		award_list_id:
			Tine.Membership.Custom.getRecordPicker('AwardList','membership_award_award_list_id',{
				disabledClass: 'x-item-disabled-view',
				width: 200,
				fieldLabel: 'Auszeichnung',
			    name:'award_list_id',
			    disabled: false,
			    onAddEditable: true,
			    onEditEditable: true,
			    blurOnSelect: true,
			    allowBlank:false
			}),
		award_datetime:
		{
			xtype: 'datefield',
			disabledClass: 'x-item-disabled-view',
			fieldLabel: 'Verliehen am', 
		    name:'membership_award_award_datetime',
		    id:'award_datetime',
		    width:100,
		    allowBlank:false
		}
	};
};