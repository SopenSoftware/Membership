Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.AssociationGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'membership-association-grid-panl',
    recordClass: Tine.Membership.Model.Association,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'association_nr', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.associationBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Membership.AssociationGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.Association.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: quickFilter
        });
    },  
    
	getColumns: function() {
		return [
		   { id: 'contact_id', header: this.app.i18n._('Kontakt'), dataIndex: 'contact_id',renderer:Tine.Membership.renderer.contactRenderer, sortable:true  },
		   { id: 'association_nr', header: this.app.i18n._('Hauptorganisation-Nr'), dataIndex: 'association_nr', sortable:true },		               
		   { id: 'association_name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'association_name', sortable:true }
        ];
	}
});