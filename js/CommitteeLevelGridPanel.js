Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.CommitteeLevelGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.CommitteeLevel,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.committeeLevelBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Membership.CommitteeLevelGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.CommitteeLevel.getFilterModel(),
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