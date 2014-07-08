Ext.namespace('Tine.Membership');

Tine.Membership.Model.VoteArray = 
[
	{name: 'id'},
	{name: 'member_nr'},
	{name: 'member_id'},
	{name: 'on_site'},
	{name: 'transfer_member_id'},
	{name: 'association_id'},
	{name: 'original_votes'},
	{name: 'become_votes'},
	{name: 'transferred_votes'},
	{name: 'total_votes'},
	{name: 'vote_permission'},
	{name: 'order_votes'},
	{name: 'active_members'}
];

Tine.Membership.Model.Vote = Tine.Tinebase.data.Record.create(Tine.Membership.Model.VoteArray, {
   appName: 'Membership',
   modelName: 'Vote',
   idProperty: 'id',
   recordName: 'Vereins-Stimmrecht',
   recordsName: 'Vereins-Stimmrechte',
   containerProperty: null,
   useTitleMethod:true,
   getTitle: function(){
		try{
	   return this.getForeignRecord(Tine.Membership.Model.SoMember, 'member_id').get('member_nr');
		}catch(e){
			return 'no title';
		}
   }
});

Tine.Membership.Model.Vote.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.Vote.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: _('Verein'),          field: 'member_nr',       operators: ['contains','equals']}
    ];
};

Tine.Membership.Model.VoteTransferArray = 
[
	{name: 'id'},
	{name: 'member_id'},
	{name: 'from_member_nr'},
	{name: 'from_member_id'},
	{name: 'transferred_votes'}
];

Tine.Membership.Model.VoteTransfer = Tine.Tinebase.data.Record.create(Tine.Membership.Model.VoteTransferArray, {
   appName: 'Membership',
   modelName: 'VoteTransfer',
   idProperty: 'id',
   recordName: 'Vereins-Stimmrecht',
   recordsName: 'Vereins-Stimmrechte',
   containerProperty: null,
   useTitleMethod:true,
   getTitle: function(){
		return this.getForeignRecord(Tine.Membership.Model.SoMember, 'member_id').get('member_nr');
   }
});

Tine.Membership.Model.VoteTransfer.getDefaultData = function(){
	return {
	};
};

Tine.Membership.Model.VoteTransfer.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Membership');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: _('Verein'),          field: 'member_nr',       operators: ['equals']}
        
    ];
};


Tine.Membership.voteBackend = new Tine.Tinebase.data.RecordProxy({
	   appName: 'Membership',
	   modelName: 'Vote',
	   recordClass: Tine.Membership.Model.Vote
	});

Tine.Membership.voteTransferBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'VoteTransfer',
   recordClass: Tine.Membership.Model.VoteTransfer
});


Tine.Membership.VoteGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-vote-gridpanel',
    recordClass: Tine.Membership.Model.Vote,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'member_nr', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'member_id'
    },
    feeDefinitionRecord: null,
    initComponent: function() {
    	this.app = Tine.Tinebase.appMgr.get('Membership');
        this.recordProxy = Tine.Membership.voteBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        

        Tine.Membership.VoteGridPanel.superclass.initComponent.call(this);

   },
    initFilterToolbar: function() {
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.Vote.getFilterModel(),
            defaultFilter: 'member_nr',
            filters: [{field:'member_nr',operator:'contains',value:''}],
            plugins: []
        });
    },  
  
	getColumns: function() {
		return [
		   { id: 'member_nr', header: this.app.i18n._('Vereins-Nr'), dataIndex: 'member_nr', sortable:true },
		   { id: 'vote_permission', header: this.app.i18n._('Stimmrechtsausübung'), dataIndex: 'vote_permission', sortable:true },
		   { id: 'total_votes', header: this.app.i18n._('Stimmen GESAMT'), dataIndex: 'total_votes', sortable:true },
		   { id: 'original_votes', header: this.app.i18n._('originäre Stimmen'), dataIndex: 'original_votes', sortable:true },
		   { id: 'become_votes', header: this.app.i18n._('erhaltene Stimmen'), dataIndex: 'become_votes', sortable:true },
		   { header: this.app.i18n._('Verein'), dataIndex: 'member_id',renderer:Tine.Membership.renderer.membershipRenderer },
		   { header: this.app.i18n._('Übertragen an Verein'), dataIndex: 'transfer_member_id',renderer:Tine.Membership.renderer.membershipRenderer },
           { header: this.app.i18n._('Verband'), dataIndex: 'association_id',renderer:Tine.Membership.renderer.associationRenderer },
           { id: 'transferred_votes', header: this.app.i18n._('übertragene Stimmen'), dataIndex: 'transferred_votes', sortable:true },
		   { header: this.app.i18n._('Mitgl.gem.Rechn.'), dataIndex: 'order_votes', sortable:true },
		   { header: this.app.i18n._('pfl.Mitglieder'), dataIndex: 'active_members', sortable:true }
	   ];
	}

});

Tine.Membership.VoteTransferGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-vote-transfer-gridpanel',
    recordClass: Tine.Membership.Model.VoteTransfer,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'member_nr', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'member_id'
    },
    feeDefinitionRecord: null,
    initComponent: function() {
    	this.app = Tine.Tinebase.appMgr.get('Membership');
        this.recordProxy = Tine.Membership.voteBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        /*this.action_addDefFilter = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddDefFilter,
            iconCls: 'actionAdd',
            scope: this
        });*/
        Tine.Membership.VoteTransferGridPanel.superclass.initComponent.call(this);
        /*this.pagingToolbar.add(
				 '->'
		 );
		 this.pagingToolbar.add(
				 Ext.apply(new Ext.Button(this.action_addDefFilter), {
					 text: 'Abfrageergebnis hinzufügen',
		             scale: 'small',
		             rowspan: 2,
		             iconAlign: 'left'
		        }
		 ));*/
   },
    initFilterToolbar: function() {
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.VoteTransfer.getFilterModel(),
            defaultFilter: 'member_nr',
            filters: [{field:'member_nr',operator:'contains',value:''}],
            plugins: []
        });
    },  
    loadVote: function( voteRecord ){
    	this.voteRecord = voteRecord;
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
    	Tine.Membership.VoteTransferGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	
    	if(!this.voteRecord){
    		return true;
    	}
    	var filterOptions = {};
    	var filter;
    	if(this.voteRecord){
    		filterOptions.record = this.voteRecord;
        	filterOptions.field = 'member_id';
    		filter = this.createForeignIdFilter(filterOptions);
    		options.params.filter.push(filter);
    	}
    },
	getColumns: function() {
		return [
		   { id: 'from_member_nr', header: this.app.i18n._('von Vereins-Nr'), dataIndex: 'from_member_nr', sortable:true },
		   { id: 'transferred_votes', header: this.app.i18n._('erhaltene Stimmen'), dataIndex: 'transferred_votes', sortable:true }
	   ];
	}

});

Tine.Membership.VDSTMgvClubVotesDialog = Ext.extend(Ext.form.FormPanel, {
	region:'center',
	recordClass: Tine.Membership.Model.Vote,
	recordProxy: Tine.Membership.voteBackend,
	loadRecord: false,
	evalGrants: false,
	initComponent: function(){
		this.initActions();
		this.items = this.getFormItems();
		Tine.Membership.VDSTMgvClubVotesDialog.superclass.initComponent.call(this);
	},
	
	initActions: function(){
		this.action_searchClub = new Ext.Action({
            handler: this.searchClub,
            scope: this
        });
		
		this.action_transferVotes = new Ext.Action({
            handler: this.transferVotes,
            scope: this
        });
		
		this.buttonSearchClub = Ext.apply(new Ext.Button(this.action_searchClub), {
		    scale: 'small',
		    tooltip: 'Verein suchen',
		    iconCls: 'search-club'	
		});
		
		this.buttonTransferVotes = Ext.apply(new Ext.Button(this.action_transferVotes), {
		    scale: 'small',
		    tooltip: 'Stimmen transferieren',
		    iconCls: 'transfer-votes',			
		});
		
		//Tine.Membership.VDSTMgvClubVotesDialog.superclass.initActions.call(this);
	},
	getButtonSearchClub: function(){
		return this.buttonSearchClub;
	},
	
	getButtonTransferVotes: function(){
		return this.buttonTransferVotes;
	},
	
	cancel: function(){
		
	},
	
	searchClub: function(){
		
	},
	
	transferVotes: function(){
		
	},
	
	
    
	getFormItems: function(){
		// vereine stimmrecht erfassen
		return Tine.Membership.VDSTMgvClubVotesFormItems(this);
		
		// übersicht landesverbände mit vereinen
	}
});


Tine.Membership.VDSTMgvDialog = Ext.extend(Ext.Panel, {
	panelManager:null,
	windowNamePrefix: 'PaymentEditWindow_',
	appName: 'Membership',
	//bodyStyle:'padding:0px;padding-top:5px',
	//forceLayout:true,
	layout:'fit',
	
	initComponent: function(){
		this.initActions();
		this.initGrids();
		
		this.initToolbar();
		this.items = this.getItems();
		Tine.Membership.VDSTMgvDialog.superclass.initComponent.call(this);
	},
	
	initActions: function(){
		this.action_processVotes = new Ext.Action({
            handler: this.processVotes,
            scope: this
        });
		
		this.buttonProcessVotes = Ext.apply(new Ext.Button(this.action_processVotes), {
		    scale: 'small',
		    tooltip: 'Stimmen auslesen',
		    iconCls: 'search-club'	
		});
	},
	initToolbar: function(){
		this.tbar = new Ext.Toolbar({height:26});
		this.tbar.add('->');
		this.tbar.add(this.buttonProcessVotes);
	},
	initGrids: function(){
		this.voteGrid = new Tine.Membership.VoteGridPanel({
			layout:'border',
			height:400,
			split:true,
			region:'south'
		});
		this.voteTransferGrid = new Tine.Membership.VoteTransferGridPanel({
			width:400,
			layout:'border',
			region:'east',
			split:true
		});
	},
	
	getVoteGrid: function(){
		return this.voteGrid;
	},

	getVoteTransferGrid: function(){
		return this.voteTransferGrid;
	},
	
	processVotes: function(){
		Ext.Ajax.request({
            scope: this,
            success: this.onProcessVotes,
            params: {
                method: 'Membership.buildMemberVotes',
                id:null
            },
            failure: this.onProcessVotesFailure
        });	
	},
	
	onProcessVotes: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		var state = result.state;
		if(state!=='success'){
			Ext.MessageBox.show({
	            title: 'Fehler', 
	            msg: 'Die Stimmentabelle konnte nicht aufgebaut werden',
	            buttons: Ext.Msg.OK,
	            scope:this,
	            icon: Ext.MessageBox.ERROR
	        });
			
		}else{
			Ext.MessageBox.show({
	            title: 'Erfolg', 
	            msg: 'Die Stimmentabelle wurde aufgebaut und kann jetzt verwendet werden',
	            buttons: Ext.Msg.OK,
	            scope:this,
	            icon: Ext.MessageBox.ERROR
	        });
			this.getVoteGrid().refresh();
		}
	},

	onProcessVotesFailure: function(response){
		Ext.MessageBox.show({
            title: 'Fehler', 
            msg: 'Der Aufbau der Stimmentabelle konnte nicht beauftragt werden, oder es ist eine Zeitüberschreitung aufgetreten.',
            buttons: Ext.Msg.OK,
            scope:this,
            icon: Ext.MessageBox.ERROR
        });
	},
	
	getItems: function(){
		// vereine stimmrecht erfassen
		return [
		        {
		        	xtype:'panel',
		        	layout:'border',
		        	items:[
		        	       
						{
							xtype:'panel',
							layout:'border',
							region:'center',
							items:[
							     	new Tine.Membership.VDSTMgvClubVotesDialog({
										frame:true,
										border:false
									}),
									this.voteTransferGrid
							 ]
						},
						this.voteGrid
		        	]
		        }
		        
		       
		            
		];
		
		// übersicht landesverbände mit vereinen
	}
});

Tine.Membership.VDSTMgvDialog.openWindow = function (config) {
    //var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 1024,
        height: 600,
        title: 'Mitgliederversammlung 2013 - Stimmrechtverwaltung',
        name: Tine.Membership.VDSTMgvDialog.prototype.windowNamePrefix,
        contentPanelConstructor: 'Tine.Membership.VDSTMgvDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

Tine.Membership.VDSTMgvClubVotesFormItems = function(dialog){
	var fields = Tine.Membership.VDSTMgvClubVotesFormFields.get();
	return [{
		xtype:'panel',
		layout:'fit',
		frame:true,
		defaults:{
			xtype:'textfield'
		},
		items:[
			{xtype:'columnform',border:false,items:
		   	[[
		   	  	fields.club_nr, dialog.getButtonSearchClub() 
		   	],[
		   	   	fields.transfer_club_nr, dialog.getButtonTransferVotes()
		   	 ],[
		   	   	fields.on_site, fields.vote_permission, fields.total_votes
		   	 ],[
		   	   	fields.original_votes, fields.become_votes, fields.transferred_votes
		   	 ],[
		   	   	fields.member_id
		   	 ],[
		   	   	fields.association_id
		   	 ],[
		   	   	fields.transfer_member_id
		   	]]
		}]
	}];
}


Ext.ns('Tine.Membership.VDSTMgvClubVotesFormFields');

Tine.Membership.VDSTMgvClubVotesFormFields.get = function(){
	return {
		club_nr:
			{
			    fieldLabel: 'Vereins-Nummer',
			    id:'club_nr',
			    name:'club_nr',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:null,
			    width: 200
			},
		transfer_club_nr:
			{
			    fieldLabel: 'Vereins-Nummer (Überträger)',
			    id:'transfer_club_nr',
			    name:'transfer_club_nr',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:null,
			    width: 200
			},
		member_id:
			Tine.Membership.Custom.getRecordPicker('SoMember','membership_member_id',{
				width: 400,
				fieldLabel: 'Verein',
			    name:'member_id',
			    disabled: true,
			    onAddEditable: true,
			    onEditEditable: false,
			    blurOnSelect: true,
			    allowBlank:true
			}),
		transfer_member_id:
				Tine.Membership.Custom.getRecordPicker('SoMember','transfer_member_id',{
					width: 400,
					fieldLabel: 'abgetreten an Verein',
				    name:'transfer_member_id',
				    disabled: true,
				    onAddEditable: true,
				    onEditEditable: false,
				    blurOnSelect: true,
				    allowBlank:true
				}),
		association_id:
			Tine.Membership.Custom.getRecordPicker('Association','membership_association_id',{
				disabledClass: 'x-item-disabled-view',
				width: 400,
				fieldLabel: 'Verband',
			    name:'association_id',
			    disabled: false,
			    onAddEditable: true,
			    onEditEditable: false,
			    blurOnSelect: true,
			    allowBlank:false,
			    ddConfig:{
		        	ddGroup: 'ddGroupContact'
		        }
			}),
		original_votes:
			{
				xtype: 'numberfield',
			    fieldLabel: 'originär Stimmen',
			    id:'original_votes',
			    name:'original_votes',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:0,
			    width: 200
			},
		become_votes:
			{
				xtype: 'numberfield',
			    fieldLabel: 'erhaltene Stimmen',
			    id:'become_votes',
			    name:'become_votes',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:0,
			    width: 200
			},
		transferred_votes:
			{
				xtype: 'numberfield',
			    fieldLabel: 'abgetretene Stimmen',
			    id:'transferred_votes',
			    name:'transferred_votes',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:0,
			    width: 200
			},
		total_votes:
			{
				xtype: 'numberfield',
			    fieldLabel: 'gesamt Stimmen',
			    id:'total_votes',
			    name:'total_votes',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:0,
			    width: 200
			},	
		vote_permission:
			{
				fieldLabel: 'Status',
			    disabledClass: 'x-item-disabled-view',
			    id:'membership_membership_status',
			    name:'membership_status',
			    width: 100,
			    xtype:'combo',
			    store:[['OWN','Selbstausübung'],['TRANSFERMAIN','Landesverband'],['TRANSFERCLUB', 'anderer Verein'],['NOREACTION','keine Reaktion']],
			    value: 'ACTIVE',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		on_site:   
		        {
				xtype: 'checkbox',
				disabledClass: 'x-item-disabled-view',
				id: 'on_site',
				name: 'on_site',
				hideLabel:true,
			    boxLabel: 'anwesend',
			    width:150
			}
	};
}
