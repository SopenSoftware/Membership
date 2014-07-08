Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.CommitteeGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.Committee,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'committee_nr', direction: 'ASC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.committeeBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Membership.CommitteeGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.Committee.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    
	getColumns: function() {
		return [
		   { id: 'committee_nr', header: this.app.i18n._('Gremium-Nr'), dataIndex: 'committee_nr', sortable:true },
		   { id: 'name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'name', sortable:true },
		   { id: 'committee_kind_id', header: this.app.i18n._('Art'), dataIndex: 'committee_kind_id', renderer:Tine.Membership.renderer.committeeKindRenderer, sortable:true },
		   { id: 'committee_level_id', header: this.app.i18n._('Ebene'), dataIndex: 'committee_level_id', renderer:Tine.Membership.renderer.committeeLevelRenderer, sortable:true },
		   { id: 'challenge', header: this.app.i18n._('Aufgabe'), dataIndex: 'challenge', sortable:true },
		   { id: 'description', header: this.app.i18n._('Bemerkung'), dataIndex: 'description', sortable:true },
		   { id: 'begin_datetime', header: this.app.i18n._('Gründung'), dataIndex: 'begin_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true },
		   { id: 'end_datetime', header: this.app.i18n._('Auflösung'), dataIndex: 'end_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true }
	    ];
	}
});