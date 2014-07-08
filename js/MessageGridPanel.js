Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.MessageGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-message-gridpanel',
    recordClass: Tine.Membership.Model.Message,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'created_datetime', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'subject'
    },
    messageBrokerRegistered: false,
    
    initComponent: function() {
    	
    	this.recordProxy = Tine.Membership.messageBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        
        Tine.Membership.MessageGridPanel.superclass.initComponent.call(this);
        this.registerMessageBroker();
        
    },
    registerMessageBroker: function(){
    	//this.on('beforeopendialog', this.app.getMessageBroker().onBeginMessageView, this.app.getMessageBroker());
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.Message.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    initActions: function(){
    	
        this.actions_openSoMember = new Ext.Action({
            actionType: 'edit',
            handler: this.openSoMember,
            actionUpdater: this.updateMessageAction,
            text: 'Öffne Mitglied',
            iconCls: 'actionEdit',
            scope: this
        });
        
        this.actionUpdater.addActions([this.action_payInvoice]);
        
        this.supr().initActions.call(this);
    },
    updateMessageAction: function(action, grants, records) {
    	action.setDisabled(true);
        if (records.length == 1) {
            var rec = this.getSelectedRecord();
            if (! rec) {
                return false;
            }
            var memberId;
            if(memberId = rec.getTicketItemBreakNull('memberId')){
            	action.setDisabled(false);
        	}           
        }
    },
    openSoMember: function(){
    	var record = this.getSelectedRecord();
    	var memberId = record.getTicketItemBreakNull('memberId');
    	if(memberId){
	    	var memberToLoad = new Tine.Membership.Model.SoMember({id:memberId}, memberId);
			var win = Tine.Membership.SoMemberEditDialog.openWindow({
				record: memberToLoad
			});
    	}
    },
    getActionToolbarItems: function() {
    	return [
			{
                xtype: 'button',
                columns: 1,
                frame: false,
                items: [
                    this.actions_openSoMember
                ]
            }
        ];
    },
    getContextMenuItems: function(){
    	var items = [];
    	items = items.concat(
    	[
    	  '-',
  			this.actions_openSoMember
    	]);
    	return items;
    },
   getColumns: function() {
		return [
		   { header: this.app.i18n._('Typ'), width:60, dataIndex: 'direction', renderer: Tine.Membership.renderer.messageType},
		   { header: this.app.i18n._('gesendet von'), width:60, dataIndex: 'sender_account_id', renderer: Tine.Tinebase.common.usernameRenderer },
		   { header: this.app.i18n._('Betreff'), dataIndex: 'subject', sortable:true },
		   { header: this.app.i18n._('Nachricht'), dataIndex: 'message', sortable:true },
		   { header: this.app.i18n._('Empfängerkreis'),width:120,  dataIndex: 'receiver_type', renderer: Tine.Membership.renderer.messageReceiverType},
		   { header: this.app.i18n._('gesendet am'),width:120, dataIndex: 'created_datetime', renderer:Tine.Tinebase.common.dateTimeRenderer, sortable:true },
		   { header: this.app.i18n._('läuft ab am'),width:120, dataIndex: 'expiry_datetime', renderer:Tine.Tinebase.common.dateRenderer, sortable:true }
	    ];
	}

});