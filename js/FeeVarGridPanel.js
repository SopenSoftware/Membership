Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.FeeVarGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-fee-var-gridpanel',
    recordClass: Tine.Membership.Model.FeeVar,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    feeProgressRecord: null,
    initComponent: function() {
        this.recordProxy = Tine.Membership.feeVarBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        
        this.plugins = this.plugins || [];
        Tine.Membership.FeeVarGridPanel.superclass.initComponent.call(this);
    },
    loadFeeProgress: function( feeProgressRecord ){
    	this.feeProgressRecord = feeProgressRecord;
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
    	Tine.Membership.FeeVarGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.feeProgressRecord){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.feeProgressRecord){
//    		filterOptions.record = this.feeProgressRecord;
//        	filterOptions.field = 'fee_progress_id';
//    		filter = this.createForeignIdFilter(filterOptions);
    		var filter = {	
				field: 'fee_progress_id',
				operator:'equals',
				value:this.feeProgressRecord.get('id')
			};
    		options.params.filter.push(filter);
    	}
    },
	getColumns: function() {
		return [
		   { id: 'name', header: this.app.i18n._('Bezeichnung'), dataIndex: 'fee_var_config_id', renderer: Tine.Membership.renderer.feeVarConfigLabelRenderer, sortable:true },
		   { id: 'value', header: this.app.i18n._('Wert'),renderer: Tine.Membership.renderer.feeVarValueRenderer, dataIndex: 'value' }
	    ];
	}

});