Ext.namespace('Tine.Membership');

Tine.Membership.CommitteeEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'CommitteeEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.Committee,
	recordProxy: Tine.Membership.committeeBackend,
	loadRecord: false,
	evalGrants: false,
	initComponent: function(){
		this.initDependentGrids();
		this.on('load',this.onLoadCommittee, this);
		Tine.Membership.CommitteeEditDialog.superclass.initComponent.call(this);
	},
	initDependentGrids: function(){
		this.committeeFuncGrid = new Tine.Membership.CommitteeFuncGridPanel({
			title:'Funktionen',
			region:'center',
			height:300,
			maxHeight:300,
			layout:'border',
			split:true,
			frame: true,
			perspective:'COMMITTEE',
			app: Tine.Tinebase.appMgr.get('Membership')
		});
	},
	onLoadCommittee: function(){
		if(!this.rendered){
			this.onLoadCommittee.defer(250,this);
		}
		this.committeeFuncGrid.loadCommittee(this.record);
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
					Tine.Membership.Custom.getRecordPicker('CommitteeKind','committee_kind_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Art Gremium',
					    name:'committee_kind_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:false
					}),
					Tine.Membership.Custom.getRecordPicker('CommitteeLevel','committee_level_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Ebene Gremium',
					    name:'committee_level_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:false
					})
//			     ],[
//					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
//					    fieldLabel: 'Mitglied',
//					    disabledClass: 'x-item-disabled-view',
//					    id:'committee_member_id',
//					    name: 'member_id',
//					    blurOnSelect: true,
//					    recordClass: Tine.Membership.Model.SoMember,
//					    width: 300
//					})
				],[
					{
					    fieldLabel: 'Gremium-Nr',
					    emptyText: '<automatisch>',
					    disabledClass: 'x-item-disabled-view',
					    id:'committee_nr',
					    name:'committee_nr',
					    value:null,
					    disabled:true,
					    width: 150
					},{
						fieldLabel: 'Bezeichnung',
					    id:'name',
					    name:'name',
					    value:null,
					    width: 400
					}
				 ],[	
					{
						fieldLabel: 'Aufgabe',
					    id:'challenge',
					    name:'challenge',
					    value:null,
					    width: 600
					} 
				 ],[
					{
						xtype:'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Datum Gründung',
						id:'begin_datetime',
						name:'begin_datetime',
						width: 150
					},{
						xtype:'datefield',
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Datum Auflösung',
						id:'end_datetime',
						name:'end_datetime',
						width: 150
					}
				 ],[
					{
						xtype:'textarea',
					    fieldLabel: 'Beschreibung',
					    id:'description',
					    name:'description',
					    value:null,
					    width: 550,
					    height:200
					}     
				
					
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
			        this.committeeFuncGrid
			]
		});
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.CommitteeEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.CommitteeEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.CommitteeEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};