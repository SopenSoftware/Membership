Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.FilterResultGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-fee-def-filter-gridpanel',
    recordClass: Tine.Membership.Model.FilterResult,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    filterSetRecord: null,
    initComponent: function() {
        this.recordProxy = Tine.Membership.filterResultBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        this.action_addDefFilter = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddDefFilter,
            iconCls: 'actionAdd',
            scope: this
        });
        Tine.Membership.FilterResultGridPanel.superclass.initComponent.call(this);
        this.pagingToolbar.add(
				 '->'
		 );
		 this.pagingToolbar.add(
				 Ext.apply(new Ext.Button(this.action_addDefFilter), {
					 text: 'Abfrageergebnis hinzufügen',
		             scale: 'small',
		             rowspan: 2,
		             iconAlign: 'left'
		        }
		 ));
   },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.FilterResult.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    loadFilterSet: function( filterSetRecord ){
    	this.filterSetRecord = filterSetRecord;
    	this.store.reload();
    },
    createForeignIdFilter: function( filterOptions){
    	if(!filterOptions.record){// || filterOptions.record.id == 0){
    		return false;
    	}
    	var recordId = filterOptions.record.get('id');
    	if(recordId == 0 || recordId == undefined){
    		recordId = -1;
    	}
    	//alert(recordId);
    	var filter = {	
			field: filterOptions.field,
			operator:'AND',
			value:[{
				field:'id',
				operator:'equals',
				value: recordId }]
		};
    	return filter;
    },
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.FilterResultGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.filterSetRecord){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.filterSetRecord){
    		filterOptions.record = this.filterSetRecord;
        	filterOptions.field = 'filter_set_id';
    		filter = this.createForeignIdFilter(filterOptions);
    		options.params.filter.push(filter);
    	}
    },
    onAddDefFilter: function(){
    	this.defFilterWin = Tine.Membership.FilterResultEditDialog.openWindow({
    		filterSetRecord: this.filterSetRecord
		});
		this.defFilterWin.on('beforeclose',this.onDefFilterAdded,this);
    },
    onDefFilterAdded: function(){
    	//this.getStore().reload();
    },
	getColumns: function() {
		return [
		   { id: 'name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'name', sortable:true }
	    ];
	}

});