Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.FeeDefFilterGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-fee-def-filter-gridpanel',
    recordClass: Tine.Membership.Model.FeeDefFilter,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    feeDefinitionRecord: null,
    initComponent: function() {
        this.recordProxy = Tine.Membership.feeDefFilterBackend;
        
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
        Tine.Membership.FeeDefFilterGridPanel.superclass.initComponent.call(this);
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
            filterModels: Tine.Membership.Model.FeeDefFilter.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    loadFeeDefinition: function( feeDefinitionRecord ){
    	this.feeDefinitionRecord = feeDefinitionRecord;
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
    	Tine.Membership.FeeDefFilterGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.feeDefinitionRecord){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.feeDefinitionRecord){
    		filterOptions.record = this.feeDefinitionRecord;
        	filterOptions.field = 'fee_definition_id';
    		filter = this.createForeignIdFilter(filterOptions);
    		options.params.filter.push(filter);
    	}
    },
    onAddDefFilter: function(){
    	this.defFilterWin = Tine.Membership.FeeDefFilterEditDialog.openWindow({
    		feeDefinitionRecord: this.feeDefinitionRecord
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