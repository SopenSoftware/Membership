Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.MembershipFeeGroupGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.MembershipFeeGroup,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    gridConfig: {
    	clicksToEdit: 'auto',
        loadMask: true,
        autoExpandColumn: 'title'
    },
    soMemberRecord: null,
    feeGroupRecord: null,
    referencesMembership:true,
    useEditorGridPanel: true,
    remoteSort: false,
    initComponent: function() {
        this.recordProxy = Tine.Membership.membershipFeeGroupBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Membership.MembershipFeeGroupGridPanel.superclass.initComponent.call(this);
        this.pagingToolbar.add( Ext.apply(new Ext.Button(this.action_addInNewWindow), {
        	iconCls:'actionAdd',
        	scale: 'small',
            iconAlign: 'left',
            arrowAlign:'right'
        }));
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.MembershipFeeGroup.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    
	getColumns: function() {
		return [
		   //{ id: 'member_id', header: this.app.i18n._('Mitglied'), dataIndex: 'member_id', renderer: Tine.Membership.renderer.membershipRenderer, sortable:true },		               
		   { id: 'fee_group_key', header: this.app.i18n._('BG Schlüssel'),  dataIndex: 'fee_group_key', sortable:true},
		   { id: 'fee_group_id', header: this.app.i18n._('Beitragsgruppe'), renderer: Tine.Membership.renderer.feeGroupRenderer, dataIndex: 'fee_group_id', sortable:false},
		   
		   {id: 'article_id', header: 'Artikel', dataIndex: 'article_id', width: 120,renderer: Tine.Billing.renderer.articleRenderer,sortable:false},
		   { 
			   id: 'price', 
			   header: this.app.i18n._('Beitrag'), 
			   renderer: Sopen.Renderer.MonetaryNumFieldRenderer , 
			   dataIndex: 'price', 
			   sortable:false,
			   editor: new Sopen.CurrencyField({
	                allowBlank:false
	           })
		   },{
	            id: 'valid_from_date',
	            header: this.app.i18n._("Gültig ab"),
	            width: 60,
	            dataIndex: 'valid_from_datetime',
	            renderer: Tine.Tinebase.common.dateRenderer,
	            editor: new Ext.ux.form.ClearableDateField({}),
	            sortable:true
	        }
		];
	},
	loadSoMember: function( soMemberRecord ){
    	this.soMemberRecord = soMemberRecord;
    	this.store.reload();
    },
    loadFeeGroup: function(feeGroupRecord){
    	this.feeGroupRecord = feeGroupRecord;
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
    	Tine.Membership.MembershipFeeGroupGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.soMemberRecord && this.referencesMembership){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.soMemberRecord){
    		filterOptions.record = this.soMemberRecord;
        	filterOptions.field = 'member_id';
    		filter = this.createForeignIdFilter(filterOptions);
    	}else{
    		filter = {	
				field: 'no_member',
				operator:'isnull',
				value: ''
			};
    		
    		filterOptions.record = this.feeGroupRecord;
        	filterOptions.field = 'fee_group_id';
    		var feeGroupFilter = this.createForeignIdFilter(filterOptions);
    		options.params.filter.push(feeGroupFilter);
    	}
    	options.params.filter.push(filter);
    },
    /**
     * generic edit in new window handler
     */
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
        var soMemberRecord;
        var feeGroupRecord;
        
        if(this.referencesMembership){
        	soMemberRecord = this.soMemberRecord;
            feeGroupRecord = null;
        }else{
        	soMemberRecord = null;
            feeGroupRecord = this.feeGroupRecord;
        }
        
        var popupWindow = Tine[this.app.appName][this.recordClass.getMeta('modelName') + 'EditDialog'].openWindow({
            record: record,
            soMemberRecord: soMemberRecord,
            feeGroupRecord: feeGroupRecord,
            grid: this,
            listeners: {
                scope: this,
                'update': function(record) {
                    this.loadData(true, true, true);
                }
            }
        });
    }
});