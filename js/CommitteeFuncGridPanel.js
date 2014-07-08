Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.CommitteeFuncGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.CommitteeFunc,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    ddConfig:{
    	ddGroup: 'ddGroupSoMember'
    },
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    memberRecord: null,
    parentMemberRecord: null,
    associationRecord: null,
    committeeRecord: null,
    perspective: 'COMMON',
    enableAccountCreation:false,
    initComponent: function() {
    	this.addEvents(
    			'modifycommitteefunc',
    			'creatememberaccount'
    	);
        this.recordProxy = Tine.Membership.committeeFuncBackend;
        this.committeeRecord = new Tine.Membership.Model.CommitteeFunc({},0);
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        this.action_addCommitteeFunc = new Ext.Action({
            actionType: 'edit',
            handler: this.addCommitteeFunc,
            iconCls: 'actionAdd',
            scope: this
        });
        this.actions_createMemberAccount = new Ext.Action({
        	requiredGrant: 'readGrant',
            actionType: 'edit',
           // disabled:true,
            handler: this.onCreateMemberAccount,
            iconCls: 'actionEdit',
            scope: this
        });
        this.on('afterrender', this.onAfterRender, this);
        Tine.Membership.CommitteeFuncGridPanel.superclass.initComponent.call(this);
        this.pagingToolbar.add(
				 '->'
		);
		this.pagingToolbar.add(
			 Ext.apply(new Ext.Button(this.action_addCommitteeFunc), {
				 text: 'Funktion hinzufügen',
		         scale: 'small',
		         rowspan: 2,
		         iconAlign: 'left'
		     }
		));
		if(this.enableAccountCreation){
			this.pagingToolbar.add(
				 Ext.apply(new Ext.Button(this.actions_createMemberAccount), {
					 text: 'Online Zugang erzeugen',
			         scale: 'small',
			         rowspan: 2,
			         iconAlign: 'left'
			     }
			));
		}
    },
    onCreateMemberAccount: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        var record = selectedRows[0];
    	if(!record){
    		return;
    	}
        var member = null;
        try{
    		member = record.getForeignRecord(Tine.Membership.Model.SoMember,'member_id');
    		this.fireEvent('creatememberaccount', member);
    	}catch(e){
    		//
    	}
		
   },
    initFilterToolbar: function() {
    	var plugins = [];
    	if(this.perspective == 'COMMON'){
    		plugins = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
    	}
    	this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.CommitteeFunc.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: plugins
        });
    },
    loadMember: function( memberRecord ){
    	this.memberRecord = memberRecord;
    	var associationRecord = this.memberRecord.getForeignRecord(Tine.Membership.Model.Association, 'association_id');
    	if(associationRecord){
    		this.associationRecord = associationRecord;
    	}
    	this.store.reload();
    },
    loadParentMember: function( parentMemberRecord ){
    	this.parentMemberRecord = parentMemberRecord;
     	var associationRecord = this.parentMemberRecord.getForeignRecord(Tine.Membership.Model.Association, 'association_id');
    	if(associationRecord){
    		this.associationRecord = associationRecord;
    	}
    	this.store.reload();
    },
    loadAssociation: function( assocationRecord ){
    	this.assocationRecord = assocationRecord;
    	this.store.reload();
    },
    loadCommittee: function( committeeRecord ){
    	this.committeeRecord = committeeRecord;
    	this.store.reload();
    },
    
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.CommitteeFuncGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	if(!this.useImplicitForeignRecordFilter == true){
    		return;
    	}
    	
    	if(!this.memberRecord && !this.committeeRecord && !this.parentMemberRecord && !this.associationRecord){
    		return;
    	}
    	delete options.params.filter;
    	options.params.filter = [];
    	if(this.perspective == 'COMMITTEE' && this.committeeRecord && this.committeeRecord.id == 0){
    		this.store.removeAll();
    		return false;
    	}
    	if(this.perspective == 'FUNCTION' && this.memberRecord){
    		this.addForeignFilter('member_id', this.memberRecord, options);
    	}
    	if(this.perspective == 'COMMITTEE' && this.committeeRecord){
    		this.addForeignFilter('committee_id', this.committeeRecord, options);
    	}
    	if(this.perspective == 'FUNCTIONARY' && this.parentMemberRecord){
    		this.addForeignFilter('parent_member_id', this.parentMemberRecord, options);
    	}
    	if(this.perspective == 'FUNCTIONARY' &&this.associationRecord){
    		this.addForeignFilter('association_id', this.associationRecord, options);
    	}
    },
    addForeignFilter: function(field, record, options){
    	var filter = {	
			field:field,
			operator:'AND',
			value:[{
				field:'id',
				operator:'equals',
				value: record.get('id')}]
		};
        options.params.filter.push(filter);
    },
	getColumns: function() {
		return [
		   { id: 'committee_func_member_id', header: this.app.i18n._('Mitglied'), dataIndex: 'member_id',renderer:Tine.Membership.renderer.membershipRenderer, sortable:true  },
		   { id: 'committee_id', header: this.app.i18n._('Gremium'), dataIndex: 'committee_id', sortable:true, renderer: Tine.Membership.renderer.committeeRenderer },		               
		   { id: 'committee_function_id', header: this.app.i18n._('Funktion'), dataIndex: 'committee_function_id', sortable:true, renderer: Tine.Membership.renderer.committeeFunctionRenderer },
		   { id: 'description', header: this.app.i18n._('Bemerkung'), dataIndex: 'description', sortable:true, renderer: Tine.Tinebase.common.dateRenderer, sortable:true },
		   { id: 'begin_datetime', header: this.app.i18n._('Beginn'), dataIndex: 'begin_datetime', renderer: Tine.Tinebase.common.dateRenderer, sortable:true },
           { id: 'end_datetime', header: this.app.i18n._('Ende'), dataIndex: 'end_datetime', renderer: Tine.Tinebase.common.dateRenderer, width: 120, hidden: false },
           { id: 'management_mail', header: this.app.i18n._('Vorstandspost'), dataIndex: 'management_mail' },
           { id: 'treasure_mail', header: this.app.i18n._('Kassiererpost'), dataIndex: 'treasure_mail' }
          
		];
	},
	addCommitteeFunc: function(){
		this.addCommitteeFuncWin = Tine.Membership.CommitteeFuncEditDialog.openWindow({
			committeeRecord: this.committeeRecord,
			memberRecord: this.memberRecord
		});
		this.addCommitteeFuncWin.on('beforeclose',this.onUpdateCommitteeFunc,this);
	},
    onEditInNewWindow: function(button, event) {
        var record, memberRecord = null, committeeRecord = null; 
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
            memberRecord = this.memberRecord;
            committeeRecord = this.committeeRecord;
        }
        
        var popupWindow = Tine[this.app.appName][this.recordClass.getMeta('modelName') + 'EditDialog'].openWindow({
            record: record,
            committeeRecord: committeeRecord,
            memberRecord: memberRecord,
            grid: this,
            listeners: {
                scope: this,
                'update': function(record) {
                    this.onUpdateCommitteeFunc();
                }
            }
        });
    },
	onUpdateCommitteeFunc: function(){
		this.grid.store.reload();
		this.fireEvent('modifycommitteefunc');
	},
    onAfterDelete: function() {
    	Tine.Membership.CommitteeFuncGridPanel.superclass.onAfterDelete.call(this);
    	this.fireEvent('modifycommitteefunc');
    },
    onAfterRender: function(){
		this.initDropZone();
    },
    initDropZone: function(){
    	if(!this.ddConfig){
    		return;
    	}
		this.dd = new Ext.dd.DropTarget(this.el, {
			scope: this,
			ddGroup     : this.ddConfig.ddGroup,
			notifyEnter : function(ddSource, e, data) {
				this.scope.el.stopFx();
				this.scope.el.highlight();
			},
			onDragOver: function(e,id){
			},
			notifyDrop  : function(ddSource, e, data){
				return this.scope.onDrop(ddSource, e, data);
				//this.scope.addRecordFromArticle(data.selections[0]);
				//this.scope.fireEvent('drop',data.selections[0]);
				return true;
			}
		});
		// self drag/drop
		this.dd.addToGroup(this.gridConfig.ddGroup);
	},
	onDrop: function(ddSource, e, data){
		switch(ddSource.ddGroup){
		// if article gets dropped in: add new receipt position
		case 'ddGroupSoMember':
			return this.addFunctionFromMember(data.selections[0]);
			break;
		}
	},
	onAddFunction: function(){
		 this.grid.getStore().reload();
	},
	addFunctionFromMember: function(member){
		var parentMemberRecord = null;
		var associationRecord = null;
		if(this.perspective == 'FUNCTIONARY'){
			if(this.parentMemberRecord){
				parentMemberRecord = this.parentMemberRecord;
				associationRecord = this.parentMemberRecord.getForeignRecord(Tine.Membership.Model.Association, 'association_id');
			}else if(this.associationRecord){
				associationRecord = this.associationRecord;
			}
		}
    	this.soMemberWin = Tine.Membership.CommitteeFuncEditDialog.openWindow({
    		memberRecord: member,
    		parentMemberRecord: parentMemberRecord,
    		associationRecord: associationRecord,
			listeners: {
                scope: this,
                'update': function(record) {
                	this.onAddFunction();
                }
            }
		});
		this.soMemberWin.on('beforeclose',this.onAddFunction,this);
    }
});