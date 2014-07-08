Ext.ns('Tine.Membership');

Tine.Membership.FeeProgressGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	layout:'fit',
	evalGrants:false,
	formId: null,
	gridConfig: {
        loadMask: true
    },
    
	initComponent : function() {
		this.addEvents(
				'addfeeprogress',
				'editfeeprogress',
				'showfeeprogress'
		);
		
		this.app = Tine.Tinebase.appMgr.get('Membership');
		
		this.gridConfig.cm = this.getColumnModel();
        this.gridConfig.sm =  new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
        	rowselect: {
			scope: this,
			fn:	function(sm, row, rec) {
           			this.actions_editFeeProgress.enable();
           			this.showFeeProgress();
        		}
    	}
            }
        });
		this.initActions();
		
		Tine.Membership.FeeProgressGridPanel.superclass.initComponent.call(this);
		// add buttons to paging toolbar
		this.addToolbarButtons();
	},
	
	initActions: function(){
        this.actions_addFeeProgress = new Ext.Action({
            requiredGrant: 'readGrant',
            text: '',
            tooltip: this.app.i18n._('Neuer Beitragsverlauf'),
            disabled: true,
            iconCls: 'actionAdd',
            handler: this.addFeeProgress,
            scope: this
        });
        this.actions_editFeeProgress = new Ext.Action({
            requiredGrant: 'readGrant',
            text: '',
            tooltip: this.app.i18n._('Beitragsverlauf bearbeiten'),
            disabled: true,
            handler: this.editFeeProgress,
            iconCls: 'actionEdit',
            scope: this
        });
        this.actions_deleteFeeProgress = new Ext.Action({
            requiredGrant: 'readGrant',
            text: '',
            disabled: true,
            handler: this.deleteFeeProgress,
            tooltip: this.app.i18n._('Beitragsverlauf löschen'),
            iconCls: 'actionRemove',
            disabled: true,
            scope: this
        });
	},
	
    addToolbarButtons: function() {
		this.pagingToolbar.addSpacer();
        this.pagingToolbar.addButton(
            Ext.apply(new Ext.Button(this.actions_addFeeProgress), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
        this.pagingToolbar.addButton(
            Ext.apply(new Ext.Button(this.actions_editFeeProgress), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
        this.pagingToolbar.addButton(            
            Ext.apply(new Ext.Button(this.actions_deleteFeeProgress), {
                scale: 'small',
                rowspan: 2,
                iconAlign: 'left'
        }));
	},
	
	getColumnModel: function() {
		var columns = [
            {id:'member_nr',header: "Mitglied-Nr", width: 120, dataIndex: 'member_nr', sortable: true},
            {id:'fee_category',header: "Beitragsgruppe", dataIndex: 'fee_category', sortable: true},
            {id:'fee_from_datetime',header: "von", width: 115, dataIndex: 'fee_from_datetime', sortable: true, renderer: Tine.Tinebase.common.dateRenderer},
            {id:'fee_to_datetime',header: "bis", dataIndex: 'fee_to_datetime', sortable: true, renderer: Tine.Tinebase.common.dateRenderer},
            {id:'fee_year',header: "Beitr.jahr", dataIndex: 'fee_year', sortable: true},
            {id:'is_calculation_approved',header: "geprüft", dataIndex: 'is_calculation_approved', sortable: true,hidden:true},
            {id:'fee_period_notes',header: "Bemerkungen", dataIndex: 'fee_period_notes', sortable: true,hidden:true},
            {id:'fee_calc_datetime',header: "Berechnungsdatum", dataIndex: 'fee_calc_datetime', sortable: true,hidden:true, renderer: Tine.Tinebase.common.dateRenderer} 
		];
        return new Ext.grid.ColumnModel({ 
            defaults: {
                sortable: true
            },
            columns: columns
        });
	},
	
	getDetailsPanel: function() {
		// not used here
		/*
		return new Ext.Panel(
			{
				html:'<p>feeprogressship details</p>'
			}	
		);*/
	},
	loadStore: function(memberRecord){
		this.memberId = memberRecord.get('id');
		//this.getStore().removeAll();
		this.getStore().load();
	},
    onStoreBeforeload: function(store, options) {
        Tine.Membership.FeeProgressGridPanel.superclass.onStoreBeforeload.call(this,store,options); 
        // TODO HH: use proper filter model
		options.params.filter = options.params.filter.concat([{field:'member_id',operator:'AND',value:[{field:'id',operator:'equals',value:this.memberId}]}]);
		if(this.memberId===undefined){
			return false;
		}
    },
	onEditInNewWindow: function(button, event) {
		// TODO HH: better approach needed
		// this code is copied from parent class
        var record; 
        if (button.actionType == 'edit') {
            this.editFeeProgress();
        }else {
        	this.addFeeProgress();
        }
	},
	
	addFeeProgress: function(){
		this.actions_addFeeProgress.disable();
		this.actions_editFeeProgress.disable();
		var record = new this.recordClass(this.recordClass.getDefaultData(), 0);
		this.fireEvent('addfeeprogress', record);
	},
	showFeeProgress: function(){
		this.actions_addFeeProgress.enable();
		this.actions_editFeeProgress.enable();
        var selectedRows = this.grid.getSelectionModel().getSelections();
        record = selectedRows[0];
		this.fireEvent('showfeeprogress', record);
	},
	editFeeProgress: function(){
		this.actions_editFeeProgress.disable();
        var selectedRows = this.grid.getSelectionModel().getSelections();
        var record = selectedRows[0];
        this.fireEvent('editfeeprogress', record);
		
	},
	deleteFeeProgress: function(){
		//alert('delete');
	}
	
});
Ext.reg('membershipfeegrid', Tine.Membership.FeeProgressGridPanel);

