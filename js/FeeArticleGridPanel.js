Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.FeeArticleGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-fee-article-gridpanel',
    recordClass: Tine.Membership.Model.FeeArticle,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'fee_base_category', direction: 'DESC'},
    feeDefinitionRecord: null,
    gridConfig: {
	   clicksToEdit: 'auto',
       loadMask: true,
       quickaddMandatory: 'fee_base_category',
       autoExpandColumn: 'fee_base_category'
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.feeArticleBackend;
        // init with empty feeDefinition record
        // -> hopefully filter causes no feeDefinition feeArticles to be displayed
        // -> TODO: test this
        this.feeDefinitionRecord = new Tine.Membership.Model.FeeDefinition({},0);
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);  

        Tine.Membership.FeeArticleGridPanel.superclass.initComponent.call(this);
        
        this.initGridEvents();
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.FeeArticle.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []//quickFilter
        });
    },  
    
    loadFeeDefinition: function( feeDefinitionRecord ){
    	this.feeDefinitionRecord = feeDefinitionRecord;
    	this.store.reload();
    },
    
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.FeeArticleGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	if(!this.feeDefinitionRecord || this.feeDefinitionRecord.id == 0){
    		return false;
    	}
    	var filter = {	
			field:'membership_fee_def_id',
			operator:'AND',
			value:[{
				field:'id',
				operator:'equals',
				value: this.feeDefinitionRecord.get('id')}]
		};
        options.params.filter.push(filter);
    },
    initGridEvents: function() {    
        this.grid.on('newentry', function(feeArticleData){
        	var data = this.recordClass.getDefaultData();
        	Ext.apply(data, feeArticleData);
        	data.membership_fee_def_id = this.feeDefinitionRecord.get('id');
        	
            var feeArticle = new this.recordClass(data,0);
            
            Tine.Membership.feeArticleBackend.saveRecord(feeArticle, {
                scope: this,
                success: function() {
                    this.loadData(true, true, true);
                },
                failure: function () { 
                    Ext.MessageBox.alert(this.app.i18n._('Failed'), this.app.i18n._('Could not save article feeArticle.')); 
                }
            });
            return true;
        }, this);
    },
    onStoreUpdate: function(store, record, operation) {
    	record.data.price_group_id = record.data.price_group_id['id'];
    	Tine.Membership.FeeArticleGridPanel.superclass.onStoreUpdate.call(this,store, record, operation);
    },    
	getColumns: function() {
		var articleEditCombo = new Tine.Tinebase.widgets.form.RecordPickerComboBox({
			id:'articleEditorField',
			disabledClass: 'x-item-disabled-view',
			recordClass: Tine.Billing.Model.Article,
		    allowBlank:false,
		    autoExpand: true,
		    triggerAction: 'all',
		    selectOnFocus: true,
		    editable: false,
		    lazyInit: false,
			ddConfig:{
		        	ddGroup: 'ddGroupArticle'
		        }
            }
         );
		var articleAddCombo = new Tine.Tinebase.widgets.form.RecordPickerComboBox({
			id:'articleAddField',
			disabledClass: 'x-item-disabled-view',
			recordClass: Tine.Billing.Model.Article,
		    allowBlank:false,
		    autoExpand: true,
		    triggerAction: 'all',
		    selectOnFocus: true,
		    editable: false,
		    lazyInit: false,
			ddConfig:{
		        	ddGroup: 'ddGroupArticle'
		        }
            }
         );
		var priceGroupCombo = new Tine.Tinebase.widgets.form.RecordPickerComboBox({
			disabledClass: 'x-item-disabled-view',
			recordClass: Tine.Billing.Model.PriceGroup,
		    allowBlank:false,
		    autoExpand: true
		});
		return [
		   { id: 'fee_base_category', header: 'Bezeichung Beitragsartikel', width:100, dataIndex: 'fee_base_category', 
			   quickaddField: new Ext.form.TextField({
	                emptyText: 'Neuer Beitragsartikel...'
	            }),
	           editor: new Ext.form.TextField(),
			   sortable:true },
		   { 
			   	id: 'article_id', 
			   	header: 'Artikel', 
			   	width:100, 
			   	dataIndex: 'article_id', 
			   	renderer: Tine.Billing.renderer.articleRenderer,
			   	quickaddField: articleAddCombo,
	            editor: articleEditCombo
		   },{ 
			   	id: 'price_group_id', 
			   	header: 'Preisgruppe', 
			   	width:100, 
			   	dataIndex: 'price_group_id', 
			   	renderer: Tine.Membership.renderer.priceGroupRenderer,
			   	quickaddField: priceGroupCombo,
	            editor: priceGroupCombo
		   }
	    ];
	}

});

Ext.reg('feearticlegridpanel',Tine.Membership.FeeArticleGridPanel);