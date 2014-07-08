Ext.namespace('Tine.Membership');

Tine.Membership.MembershipFeeGroupEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'MembershipFeeGroupEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.MembershipFeeGroup,
	recordProxy: Tine.Membership.membershipFeeGroupBackend,
	loadRecord: false,
	evalGrants: false,
	soMemberRecord: null,
	feeGroupRecord: null,
	initComponent: function(){
		this.on('load',this.onLoadMemFeeGroup, this);
		Tine.Membership.MembershipFeeGroupEditDialog.superclass.initComponent.call(this);
	},
	onLoadMemFeeGroup: function(){
		if(!this.rendered){
			this.onLoadMemFeeGroup.defer(250,this);
		}
		if(this.soMemberRecord){
			Ext.getCmp('member_id').setValue(this.soMemberRecord);
			this.record.set('member_id', this.soMemberRecord.get('id'));
		}
		if(this.feeGroupRecord){
			Ext.getCmp('fee_group_id').disable();
			Ext.getCmp('fee_group_id').setValue(this.feeGroupRecord);
			this.record.set('fee_group_id', this.feeGroupRecord.get('id'));
		}
	},
    initButtons: function(){
    	Tine.Membership.MembershipFeeGroupEditDialog.superclass.initButtons.call(this);
        this.fbar = [
             '->',
             this.action_applyChanges,
             this.action_cancel,
             this.action_saveAndClose
        ];
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
	               {xtype:'hidden', id:'member_id', name:'member_id'},
				 ],[		
					Tine.Membership.Custom.getRecordPicker('FeeGroup','fee_group_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Beitragsgruppe',
					    name:'fee_group_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:true
					})
				 ],[	
					Tine.Billing.Custom.getRecordPicker('Article', 'article_id',{
				   		name: 'article_id',
						width:300,
						allowBlank:true
				   	})
				 ],[
					{
						xtype: 'datetimefield',
					    fieldLabel: 'Gültig ab', 
					    disabledClass: 'x-item-disabled-view',
					    id:'valid_from_datetime',
					    name:'valid_from_datetime',
					    width: 180
					}
				 ],[
					{
						xtype: 'datetimefield',
					    fieldLabel: 'Gültig bis', 
					    disabledClass: 'x-item-disabled-view',
					    id:'valid_to_datetime',
					    name:'valid_to_datetime',
					    width: 180
					}
	             ],[
				   	new Sopen.CurrencyField({
					    fieldLabel: 'Beitrag',
					    id: 'price',
					    name: 'price',
					    allowBlank:false
					})
			   	],[
				   	{
					    fieldLabel: 'Kategorie (I-V)',
					    disabledClass: 'x-item-disabled-view',
					    id:'category',
					    name:'category',
					    width: 100,
					    xtype:'combo',
					    store:[['I','I'],['II','II'],['III','III'],['IV','IV'],['V','V']],
					    mode: 'local',
						displayField: 'name',
					    valueField: 'id',
					    triggerAction: 'all'
					}
				],[
				   	{
						xtype: 'checkbox',
						disabledClass: 'x-item-disabled-view',
						id: 'summarize',
						name: 'summarize',
						hideLabel:true,
						boxLabel: 'In Summe einbeziehen',
					    width:250
					}			   	
	             ]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.MembershipFeeGroupEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.MembershipFeeGroupEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.MembershipFeeGroupEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};