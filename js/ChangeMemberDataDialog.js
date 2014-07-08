Ext.namespace('Tine.Membership');

Tine.Membership.ChangeMemberDataDialog = Ext.extend(Ext.form.FormPanel, {
	windowNamePrefix: 'ChangeMemberDataWindow_',
	appName: 'Membership',
	layout:'fit',
	memberRecord: null,
	panelTitle: '--undefiniert--',
	parentMemberLabel: null,
	/**
	 * initialize component
	 */
	initComponent: function(){
		this.initActions();
		this.initToolbar();
		this.items = this.getFormItems();
		this.on('afterrender', this.onAfterRender, this);
		Tine.Membership.ChangeMemberDataDialog.superclass.initComponent.call(this);
		
	},
	onAfterRender: function(){
		switch(this.changeSet){
		case 'FeeGroup':
			
			Ext.getCmp('old_fee_group_id').setValue(this.memberRecord.getForeignRecord(Tine.Membership.Model.FeeGroup, 'fee_group_id'));
			
			break;

		case 'ParentMember':
			var parentMember = this.memberRecord.getForeignRecord(Tine.Membership.Model.SoMember, 'parent_member_id');
			Ext.getCmp('old_parent_member_id').setValue(parentMember);
			Ext.getCmp('new_parent_member_id').setMembershipType(parentMember.getForeignId('membership_type'));
			break;
		}
	},
	initActions: function(){
        this.actions_print = new Ext.Action({
            text: 'Ok',
            disabled: false,
            iconCls: 'action_applyChanges',
            handler: this.requestDataChange,
            scale:'small',
            iconAlign:'left',
            scope: this
        });
        this.actions_cancel = new Ext.Action({
            text: 'Abbrechen',
            disabled: false,
            iconCls: 'action_cancel',
            handler: this.cancel,
            scale:'small',
            iconAlign:'left',
            scope: this
        });        
	},
	/**
	 * init bottom toolbar
	 */
	initToolbar: function(){
		this.bbar = new Ext.Toolbar({
			height:48,
        	items: [
        	        '->',
                    Ext.apply(new Ext.Button(this.actions_cancel), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    }),
                    Ext.apply(new Ext.Button(this.actions_print), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    })
                ]
        });
	},
	/**
	 * save the order including positions
	 */
	requestDataChange: function(){
		Ext.Ajax.request({
			scope:this,
            params: {
                method: 'Membership.requestMemberDataChange',
                memberId:  this.memberRecord.get('id'),
                data: Ext.util.JSON.encode(this.getData()),
                validDate: Ext.getCmp('valid_datetime').getValue(),
                changeSet: this.changeSet
            },
            success: this.onRequestSuccess,
            failure: this.onRequestFailure
        });
	},
	onRequestSuccess: function(){
		
	},
	onRequestFailure: function(){
		
	},
	
	getData: function(){
		switch(this.changeSet){
		case 'FeeGroup':
			return {
				fee_group_id: Ext.getCmp('new_fee_group_id').getValue()
			};
	
		case 'ParentMember':
			return {
				parent_member_id: Ext.getCmp('new_parent_member_id').getValue()
			};
		
		}
	},
	/**
	 * 
	 * 
	 */
	getDataFields: function(){
		
		switch(this.changeSet){
		
		case 'FeeGroup':
			return [
				Tine.Membership.Custom.getRecordPicker('FeeGroup','old_fee_group_id',{
					//disabledClass: 'x-item-disabled-view',
					fieldLabel: 'bisherige Beitragsgruppe',
				    name:'old_fee_group_id',
				    disabled:true,
				    allowBlank:false
				}),
				Tine.Membership.Custom.getRecordPicker('FeeGroup','new_fee_group_id',{
					//disabledClass: 'x-item-disabled-view',
					fieldLabel: 'neue Beitragsgruppe',
				    name:'new_fee_group_id',
				    allowBlank:false
				})
				];
			break;
			
		case 'ParentMember':
			return [
				Tine.Membership.Custom.getRecordPicker('SoMember','old_parent_member_id',{
					//disabledClass: 'x-item-disabled-view',
					fieldLabel: this.parentMemberLabel + ' alt',
				    name:'old_parent_member_id',
				    disabled:true,
				    allowBlank:false
				}),
				Tine.Membership.Custom.getRecordPicker('SoMember','new_parent_member_id',{
					//disabledClass: 'x-item-disabled-view',
					fieldLabel: this.parentMemberLabel + ' neu',
				    name:'new_parent_member_id',
				    allowBlank:false
				})
				];
			break;
		}
		
	},
	
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var fields = Tine.Membership.SoMemberFormFields.get();
		
		var dialogItems = [
			   {
				   xtype: 'datefield',
				   id: 'valid_datetime',
				   fieldLabel: 'gültig ab',
				   name: 'valid_datetime',
				   value: new Date()
			   }
			
		];
		var items = dialogItems.concat(this.getDataFields())  ;
		console.log(items);
		var panel ={
			title: this.panelTitle,
			header:true,
	        xtype: 'panel',
	        layout:'fit',
	        anchor:'100%',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform', items:[
	        	 items  
	        ]}]
	    };
		
		return [panel];
		
		var wrapper = {
			xtype: 'panel',
			layout: 'border',
			frame: true,
			items: [
			   panel
			]
		
		};
		return wrapper;
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.ChangeMemberDataDialog.openWindow = function (config) {
    // TODO: this does not work here, because of missing record
	record = {};
	var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 300,
        name: Tine.Membership.ChangeMemberDataDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.ChangeMemberDataDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};