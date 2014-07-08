Ext.namespace('Tine.Membership');

Tine.Membership.CommitteeFuncEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'CommitteeFuncEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.CommitteeFunc,
	recordProxy: Tine.Membership.committeeFuncBackend,
	loadRecord: false,
	evalGrants: false,
	memberRecord: null,
	parentMemberRecord: null,
	associationRecord: null,
	committeeRecord: null,
	initComponent: function(){
		this.on('afterrender', this.onAfterRender, this);
		Tine.Membership.CommitteeFuncEditDialog.superclass.initComponent.call(this);
	},
	onRecordLoad: function() {
		Tine.Membership.CommitteeFuncEditDialog.superclass.onRecordLoad.call(this);
		if(this.record.id == 0){
			if(this.memberRecord && this.memberRecord.get('id')!=0){
				this.record.set('member_id',this.memberRecord.get('id'));
			}
			if(this.parentMemberRecord && this.parentMemberRecord.get('id')!=0){
				this.record.set('parent_member_id',this.parentMemberRecord.get('id'));
			}
			if(this.associationRecord && this.associationRecord.get('id')!=0){
				this.record.set('association_id',this.associationRecord.get('id'));
			}
			if(this.committeeRecord && this.committeeRecord.get('id')!=0){
				this.record.set('committee_id',this.committeeRecord.get('id'));
			}
		}
	},
	onAfterRender: function(){
		if(this.record.id == 0){
			if(this.memberRecord){
				var memberSelect = Ext.getCmp('committee_func_member_id');
				memberSelect.setValue(this.memberRecord);
				memberSelect.disable();
			}
			if(this.parentMemberRecord){
				var parentMemberSelect = Ext.getCmp('committee_func_parent_member_id');
				parentMemberSelect.setValue(this.parentMemberRecord);
				parentMemberSelect.disable();
			}
			if(this.associationRecord && this.associationRecord.id!=0){
				var associationSelect = Ext.getCmp('committee_func_association_id');
				associationSelect.setValue(this.associationRecord);
				associationSelect.disable();
			}
			if(this.committeeRecord && this.committeeRecord.id!=0){
				var committeeSelect = Ext.getCmp('committee_func_committee_id');
				committeeSelect.setValue(this.committeeRecord);
				//committeeSelect.disable();
			}
		
		}
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var fields = Tine.Membership.CommitteeFuncFormFields.get();
		return [Tine.Membership.CommitteeFuncForm.getColumnForm(
		[
		 	[fields.member_id, fields.id],
		 	[fields.parent_member_id, fields.association_id],
		 	[fields.committee_id, fields.committee_function_id],
		 	[fields.begin_datetime, fields.end_datetime],
		 	[fields.management_mail, fields.treasure_mail],
		 	[fields.description]
	    ])];
	}
});


/**
 * Membership Edit Popup
 */
Tine.Membership.CommitteeFuncEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 850,
        height: 300,
        name: Tine.Membership.CommitteeFuncEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.CommitteeFuncEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

Ext.ns('Tine.Membership.CommitteeFuncForm');

Tine.Membership.CommitteeFuncForm.getColumnForm = function(formFields){
	return {
        xtype: 'panel',
        border: false,
        frame:true,
        items:[{xtype:'columnform',items:[
           formFields                               	
        ]}]
    };
};

Ext.ns('Tine.Membership.CommitteeFuncFormFields');

Tine.Membership.CommitteeFuncFormFields.get = function(){
	return{
		// hidden fields
		id: 
			{xtype: 'hidden',id:'committee_func_id',name:'id'},
		member_id:
			new Tine.Tinebase.widgets.form.RecordPickerComboBox({
		        fieldLabel: 'Mitglied',
		        disabledClass: 'x-item-disabled-view',
		        id:'committee_func_member_id',
		        name: 'member_id',
		        blurOnSelect: true,
		        recordClass: Tine.Membership.Model.SoMember,
		        width: 200,
		        allowBlank:false
		    }),
	    parent_member_id:
			new Tine.Tinebase.widgets.form.RecordPickerComboBox({
		        fieldLabel: 'Verein',
		        disabledClass: 'x-item-disabled-view',
		        id:'committee_func_parent_member_id',
		        name: 'parent_member_id',
		        blurOnSelect: true,
		        recordClass: Tine.Membership.Model.SoMember,
		        width: 200,
		        allowBlank:true
		    }),
		association_id:
			Tine.Membership.Custom.getRecordPicker('Association','committee_func_association_id',{
				disabledClass: 'x-item-disabled-view',
				width: 300,
				fieldLabel: 'Verband',
			    name:'association_id',
			    disabled: false,
			    onAddEditable: true,
			    onEditEditable: false,
			    blurOnSelect: true,
			    allowBlank:true
			}),
		committee_id:
			Tine.Membership.Custom.getRecordPicker('Committee','committee_func_committee_id',{
				disabledClass: 'x-item-disabled-view',
				width: 200,
				fieldLabel: 'Gremium',
			    name:'committee_id',
			    disabled: false,
			    onAddEditable: true,
			    onEditEditable: true,
			    blurOnSelect: true,
			    allowBlank:false
			}),
		committee_function_id:
			Tine.Membership.Custom.getRecordPicker('CommitteeFunction','committee_func_committee_function_id',{
				disabledClass: 'x-item-disabled-view',
				width: 200,
				fieldLabel: 'Funktion im Gremium',
			    name:'committee_function_id',
			    disabled: false,
			    onAddEditable: true,
			    onEditEditable: true,
			    blurOnSelect: true,
			    allowBlank:false
			}),
		description:
		{
			xtype:'textarea',
		    fieldLabel: 'Beschreibung',
		    id:'committee_func_description',
		    name:'description',
		    value:null,
		    width: 550,
		    height:200
		},
		begin_datetime:
		{
			xtype: 'datefield',
			disabledClass: 'x-item-disabled-view',
			fieldLabel: 'Beginn Tätigkeit', 
		    name:'committee_func_begin_datetime',
		    id:'begin_datetime',
		    value: new Date(),
		    width:100,
		    allowBlank:true
		},
		end_datetime:
		{
			xtype: 'datefield',
			disabledClass: 'x-item-disabled-view',
			fieldLabel: 'Ende Tätigkeit', 
		    name:'committee_func_end_datetime',
		    id:'end_datetime',
		    width:100,
		    allowBlank:true
		},
		management_mail:
		{
			xtype: 'checkbox',
			disabledClass: 'x-item-disabled-view',
			id: 'committee_func_management_mail',
			name: 'management_mail',
			hideLabel:true,
		    boxLabel: 'Vorstandspost',
		    width: 250
		},
		treasure_mail:
		{
			xtype: 'checkbox',
			disabledClass: 'x-item-disabled-view',
			id: 'committee_func_treasure_mail',
			name: 'treasure_mail',
			hideLabel:true,
		    boxLabel: 'Kassiererpost',
		    width: 250
		}
	};
};