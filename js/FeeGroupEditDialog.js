Ext.namespace('Tine.Membership');

Tine.Membership.FeeGroupEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FeeGroupEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FeeGroup,
	recordProxy: Tine.Membership.feeGroupBackend,
	loadRecord: false,
	evalGrants: false,
	initComponent: function(){
		this.initDependentGrids();
		this.on('load',this.onLoadFeeGroup, this);
		Tine.Membership.FeeGroupEditDialog.superclass.initComponent.call(this);
	},
	initDependentGrids: function(){
		this.memberFeeGroupGrid = new Tine.Membership.MembershipFeeGroupGridPanel({
			title:'Beiträge',
			referencesMembership:false,
			region:'center',
			height:300,
			maxHeight:300,
			layout:'border',
			split:true,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		
		this.customFieldsPanel = new Tine.Tinebase.widgets.customfields.CustomfieldsPanel({
            layout:'fit',
        	recordClass: Tine.Membership.Model.FeeGroup,
            //disabled: (Tine.Addressbook.registry.get('customfields').length === 0),
            quickHack: {record: this.record}
        });
	},
	onLoadFeeGroup: function(){
		if(!this.rendered){
			this.onLoadFeeGroup.defer(250,this);
		}
		this.memberFeeGroupGrid.loadFeeGroup(this.record);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
	    var formItems = {
	        xtype: 'panel',
	        region:'center',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	              {
						fieldLabel: 'Bezeichnung',
					    id:'name',
					    name:'name',
					    value:null,
					    width: 220
					},{
						fieldLabel: 'Schlüssel',
					    id:'key',
					    name:'key',
					    value:null,
					    width: 200
					} 
				 ],[		
					Tine.Membership.Custom.getRecordPicker('MembershipKind','membership_kind_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'gehört zu Mitgliedsart',
					    name:'membership_kind_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:false
					})
				 ],[	
					Tine.Billing.Custom.getRecordPicker('Article', 'article_id',{
				   		name: 'article_id',
						width:300,
						allowBlank:false
				   	})
	             ]
	        ]}]
	    };
		return new Ext.Panel({
			layout:'border',
			items: [
			        {
			        	xtype:'panel',
			        	region:'north',
			        	frame:false,
			        	border: false,
			        	height:150,
			        	minHeight:150,
			        	split:true,
			        	layout:'border',
			        	items:[
			        	       formItems  
			        	]
			        },
			        {
			        	xtype:'tabpanel',
			        	region:'center',
			        	activeTab:0,
			        	items:[
			        	    this.memberFeeGroupGrid,
			        	    this.customFieldsPanel
			        	]
			        }
			        //this.memberFeeGroupGrid
			]
		});
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FeeGroupEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.FeeGroupEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FeeGroupEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};