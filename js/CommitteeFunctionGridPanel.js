Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.CommitteeFunctionGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.CommitteeFunction,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    initComponent: function() {
    	this.recordProxy = Tine.Membership.committeeFunctionBackend;
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        Tine.Membership.CommitteeFunctionGridPanel.superclass.initComponent.call(this);
    },
    
    initFilterToolbar: function() {
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.CommitteeFunction.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },
	getColumns: function() {
		return [
		 	   { id: 'name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'name', sortable:true },		               
			   { id: 'is_default', header: this.app.i18n._('als Voreinstellung'), renderer: Tine.Membership.renderer.isDefault, dataIndex: 'is_default', sortable:false}
	    ];
	}
});