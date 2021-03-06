Ext.namespace('Tine.Membership');

Tine.Membership.PrintActionHistoryDialog = Ext.extend(Ext.form.FormPanel, {
	windowNamePrefix: 'PrintActionHistoryWindow_',
	appName: 'Membership',
	layout:'fit',
	predefinedFilter: null,
	preview: false,
	outputType: null,
	mainGrid: null,
	wait:false,
	/**
	 * {Tine.Membership.CreateTLAccountGridPanel}	positions grid
	 */
	grid: null,
	/**
	 * initialize component
	 */
	initComponent: function(){
		this.initActions();
		this.initToolbar();
		this.items = this.getFormItems();
		//this.on('afterrender', this.onAfterRender, this);
		Tine.Membership.PrintActionHistoryDialog.superclass.initComponent.call(this);
		
	},
	onAfterRender: function(){
//		if(this.wait != true){
//			this.onAfterRender.defer(500,this);
//			this.wait = true;
//			return;
//		}
		//console.log('rendered');
		this.setCurrentFilter();
	},
	setFilter: function(filter){
		this.filterPanel.setValue(filter);
	},
	initActions: function(){
        this.actions_print = new Ext.Action({
            text: 'Ok',
            disabled: false,
            iconCls: 'action_applyChanges',
            handler: this.printMemberships,
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
	printMemberships: function(){
		var membershipExportId = Ext.getCmp('membership_export').getValue();
		var exportDefOptions = {
			begin_datetime: Ext.getCmp('begin_datetime').getValue(),
			end_datetime: Ext.getCmp('end_datetime').getValue()
		};
		var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Membership.runPredefinedExportActionHistory',
                requestType: 'HTTP',
                membershipExportId:  membershipExportId,
                exportDefOptions: Ext.util.JSON.encode(exportDefOptions),
                actionHistoryFilter: Ext.util.JSON.encode(Ext.getCmp('fp').getValue())
            }
        }).start();
		
		var preview = '';
		if(this.preview){
			preview = '&preview=true';
		}
		var type = '&outputType=' + this.outputType;
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		
		var op = this.radioGroup.getValue().inputValue;
		var userOptions = '&userOptions=' +op
		var win = window.open(
				Sopen.Config.runtime.requestURI + '?method=Membership.printMembershipList&filters='+filterValue+userOptions+preview+type,
				"membersPDF",
				"menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes"
		);
	},
	/**
	 * Cancel and close window
	 */
	cancel: function(){
		this.purgeListeners();
        this.window.close();
	},
	setCurrentFilter: function(){
		if(this.mainGrid){
			this.filterBuffer = this.filterPanel.getValue();
			this.filterPanel.setValue(this.mainGrid.getGridFilterToolbar().getValue());
		}
	},
	unsetCurrentFilter: function(){
		this.filterPanel.setValue(this.filterBuffer);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var panel = {
	        xtype: 'panel',
	        region:'north',
	        anchor:'100%',
	        border: false,
	        frame:true,
	        height:140,
	        items:[{xtype:'columnform',items:[
				[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
						disabledClass: 'x-item-disabled-view',
						fieldLabel: 'Definierte Ausgaben',
					    disabledClass: 'x-item-disabled-view',
					    id:'membership_export',
					    name:'membership_export',
					    width: 400,
					    height:20,
					    disabled: false,
					    onAddEditable: true,	// only has effect in class:DependentEditForm
					    onEditEditable: true,	// only has effect in class:DependentEditForm
					    blurOnSelect: true,
					    recordClass: Tine.Membership.Model.MembershipExport
					})   
				],[
					{
						xtype:'extuxclearabledatefield',
						disabledClass: 'x-item-disabled-view',
						id: 'begin_datetime',
						name: 'begin_datetime',
						value: new Date(),
						fieldLabel: 'Stichtag/Beginn Zeitraum',
					    width:180
					},{
						xtype:'extuxclearabledatefield',
						disabledClass: 'x-item-disabled-view',
						id: 'end_datetime',
						name: 'end_datetime',
						fieldLabel: 'Zeitraum Ende',
					    width:180
					}
				],[
				 {xtype:'hidden',id:'filters', name:'filters', width:1}
				]
	        ]}]
	    };

		if(this.predefinedFilter == null){
			this.predefinedFilter = [];
		}
		this.filterPanel = new Tine.widgets.form.FilterFormField({
			 	id:'fp',
		    	filterModels: Tine.Membership.Model.ActionHistory.getFilterModel(),
		    	defaultFilter: 'query',
		    	filters:this.predefinedFilter
		});
		 
		this.filterPanel.on('afterrender', this.onAfterRender, this);
		
		var wrapper = {
			xtype: 'panel',
			layout: 'border',
			frame: true,
			items: [
			   panel,
			   {
					xtype: 'panel',
					title: 'Selektion Aktionshistorie',
					height:200,
					id:'filterPanel',
					region:'center',
					autoScroll:true,
					items: 	[this.filterPanel]
				}  
			]
		
		};
		return wrapper;
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.PrintActionHistoryDialog.openWindow = function (config) {
    // TODO: this does not work here, because of missing record
	record = {};
	var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 300,
        name: Tine.Membership.PrintActionHistoryDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.PrintActionHistoryDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};