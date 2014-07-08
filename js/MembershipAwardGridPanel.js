Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.MembershipAwardGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.MembershipAward,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    memberRecord: null,
    useImplicitForeignRecordFilter: false,
    initComponent: function() {
    	this.addEvents(
    			'modifymembershipaward'
    	);
        this.recordProxy = Tine.Membership.membershipAwardBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        this.action_addMembershipAward = new Ext.Action({
            actionType: 'edit',
            handler: this.addMembershipAward,
            iconCls: 'actionAdd',
            scope: this
        });
        Tine.Membership.MembershipAwardGridPanel.superclass.initComponent.call(this);
        this.pagingToolbar.add(
				 '->'
		);
		this.pagingToolbar.add(
			 Ext.apply(new Ext.Button(this.action_addMembershipAward), {
				 text: 'Auszeichnung hinzufügen',
		         scale: 'small',
		         rowspan: 2,
		         iconAlign: 'left'
		     }
		));
    },
    
    initFilterToolbar: function() {
    	var plugins = [];
    	if(!this.useImplicitForeignRecordFilter){
    		plugins = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
    	}
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.MembershipAward.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: plugins
        });
    },
    loadMember: function( memberRecord ){
    	this.memberRecord = memberRecord;
    	this.store.reload();
    },
    
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.MembershipAwardGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	if(!this.useImplicitForeignRecordFilter){
			return;
		}
    	
    	delete options.params.filter;
    	options.params.filter = [];
    	if(!this.memberRecord || this.memberRecord.id == 0){
    		this.store.removeAll();
    		return false;
    	}
    	var filter = {	
			field:'member_id',
			operator:'AND',
			value:[{
				field:'id',
				operator:'equals',
				value: this.memberRecord.get('id')}]
		};
        options.params.filter.push(filter);
    },
	getColumns: function() {
		return [
		   { id: 'member_id', header: this.app.i18n._('Mitglied'), dataIndex: 'member_id',renderer:Tine.Membership.renderer.membershipRenderer, sortable:true  },
		   { id: 'award_list_id', header: this.app.i18n._('Auszeichnung'), dataIndex: 'award_list_id', sortable:true, renderer: Tine.Membership.renderer.awardListRenderer },
		   { id: 'award_datetime', header: this.app.i18n._('Verleihung am'), dataIndex: 'award_datetime', renderer: Tine.Tinebase.common.dateRenderer }
		];
	},
	addMembershipAward: function(){
		this.addMembershipAwardWin = Tine.Membership.MembershipAwardEditDialog.openWindow({
			memberRecord: this.memberRecord
		});
		this.addMembershipAwardWin.on('beforeclose',this.onUpdateMembershipAward,this);
	},
    onEditInNewWindow: function(button, event) {
        var record; 
        if (button.actionType == 'edit') {
            if (! this.action_editInNewWindow || this.action_editInNewWindow.isDisabled()) {
                // if edit action is disabled or not available, we also don't open a new window
                return false;
            }
            var selectedRows = this.grid.getSelectionModel().getSelections();
            record = selectedRows[0];
            
        } else if (button.actionType == 'copy') {
            var selectedRows = this.grid.getSelectionModel().getSelections();
            record = this.copyRecord(selectedRows[0].data);

        } else {
            record = new this.recordClass(this.recordClass.getDefaultData(), 0);
        }
        
        var popupWindow = Tine[this.app.appName][this.recordClass.getMeta('modelName') + 'EditDialog'].openWindow({
            record: record,
            memberRecord: this.memberRecord,
            grid: this,
            listeners: {
                scope: this,
                'update': function(record) {
                    this.onUpdateMembershipAward();
                }
            }
        });
    },
	onUpdateMembershipAward: function(){
		this.grid.store.reload();
		this.fireEvent('modifymembershipaward');
	},
    onAfterDelete: function() {
    	Tine.Membership.MembershipAwardGridPanel.superclass.onAfterDelete.call(this);
    	this.fireEvent('modifymembershipaward');
    }
});