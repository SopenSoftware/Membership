Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.FilterSetGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.FilterSet,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.filterSetBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.FilterSetGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.FilterSet.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: quickFilter
        });
    },  

	getColumns: function() {
		return [
		   { id: 'name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'name', sortable:true },
		   { id: 'description', header: this.app.i18n._('Beschreibung'), dataIndex: 'description', sortable:true }
	    ];
	}
});