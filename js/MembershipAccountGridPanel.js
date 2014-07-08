Ext.namespace('Tine.Membership');

/**
 * Timeaccount grid panel
 */
Tine.Membership.MembershipAccountGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    recordClass: Tine.Membership.Model.MembershipAccount,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    relatedMemberRecord: null,
    useImplicitForeignRecordFilter: false,
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    ddConfig:{
    	ddGroup: 'ddGroupSoMember',
    	ddGroupContact: 'ddGroupContact'
    },
    setRelatedMember: function(relatedMember){
    	this.relatedMemberRecord = relatedMember;
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.membershipAccountBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        this.on('afterrender', this.onAfterRender, this);
        Tine.Membership.MembershipAccountGridPanel.superclass.initComponent.call(this);
    },
    initFilterToolbar: function() {
		//var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.MembershipAccount.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: []
        });
    },  
    
	getColumns: function() {
		return [
		   //{ id: 'account_id', header: this.app.i18n._('Benutzer'), dataIndex: 'account_id', sortable:true,renderer: Tine.Tinebase.common.usernameRenderer  },		               
		   { id: 'ma_contact_id', header: this.app.i18n._('Kontakt'), renderer:Tine.Membership.renderer.contactRenderer, dataIndex: 'contact_id', sortable:false},
		   { id: 'related_member_id', header: this.app.i18n._('Zugang zu Mitgliedschaft'), renderer: Tine.Membership.renderer.membershipRenderer, dataIndex: 'related_member_id', sortable:true},
		   { id: 'member_id', header: this.app.i18n._('Mitglied'), renderer: Tine.Membership.renderer.membershipRenderer, dataIndex: 'member_id', sortable:false},
		   { id: 'valid_from_date', header: this.app.i18n._('gültig von'), dataIndex: 'valid_from_date', renderer: Tine.Tinebase.common.dateRenderer, sortable:true},
	       { id: 'valid_to_date', header: this.app.i18n._('gültig bis'), dataIndex: 'valid_to_date', renderer: Tine.Tinebase.common.dateRenderer,hidden:true, sortable:true },
		   { id: 'account_loginname', header: this.app.i18n._('Benutzername'), dataIndex: 'account_loginname', sortable:true },
		   { id: 'account_emailadress', header: this.app.i18n._('Benutzer Email-Adresse'), dataIndex: 'account_emailadress',sortable:true },
		   { id: 'account_lastlogin', header: this.app.i18n._('Letzter Login'), dataIndex: 'account_lastlogin', renderer: Tine.Tinebase.common.dateRenderer, sortable:true},
	       { id: 'account_lastpasswordchange', header: this.app.i18n._('Letzte PW-Änderung'), dataIndex: 'account_lastpasswordchange', renderer: Tine.Tinebase.common.dateRenderer,hidden:true, sortable:true }
          ];
	},
	loadRelatedMember: function( relatedMemberRecord ){
		this.relatedMemberRecord = relatedMemberRecord;
    	this.store.reload();
    },
    
    onStoreBeforeload: function(store, options) {
    	Tine.Membership.MembershipAccountGridPanel.superclass.onStoreBeforeload.call(this, store, options);
    	if(!this.useImplicitForeignRecordFilter == true){
    		return;
    	}
    	
    	if(!this.relatedMemberRecord){
    		return;
    	}
    	delete options.params.filter;
    	options.params.filter = [];
    	
    	this.addForeignFilter('related_member_id', this.relatedMemberRecord, options);
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
				return true;
			}
		});
		// self drag/drop
		this.dd.addToGroup(this.ddConfig.ddGroupContact);
	},
	onDrop: function(ddSource, e, data){
		switch(ddSource.ddGroup){
		// if article gets dropped in: add new receipt position
		case 'ddGroupSoMember':
			return this.createAccountFromMember(data.selections[0]);
			break;
		case 'ddGroupContact':
			return this.createAccountFromContact(data.selections[0]);
			break;
		}
	},
	onAddMembershipAccount: function(){
		 this.grid.getStore().reload();
	},
	
	createAccountFromMember: function(member){
		var contact = member.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
		var parentMember = null;
		try{
			parentMember = member.getForeignRecord(Tine.Membership.Model.SoMember, 'parent_member_id');
		}catch(e){
			parentMember = null;
		}
		
		this.createAccountFromContact(contact, member, parentMember);
	},
	doAccountCreation: function(contactRecord, relatedMemberId){
		
		this.bufferCreation.popupWindow = Tine.Membership.CreateMemberAccountEditDialog.openWindow({
   		 	modal:true,
   		 	contactRecord: contactRecord,
   		 	relatedMemberId: this.relatedMemberRecord.get('id'),
   		 	listeners:{
   		 		accountcreated: {
   		 			scope: this,
   		 			fn: this.onAccountCreated
   		 		}
   		 	}
        });
		//this.bufferCreation.popupWindow.on('accountcreated', this.onAccountCreated, this);
		
	},
	onAccountCreated: function(result){
		//alert('hammas');
		this.accountRecord = result.data;
		//console.log(this.accountRecord);
	},
	createAccountFromContact: function(contactRecord, member, parentMember){
		if(!this.relatedMemberRecord){
			return;
		}
		
		this.bufferCreation = {
			contact: contactRecord,
			member: member,
			parentMember: parentMember
		};
		
		if(!contactRecord.get('account_id')){
			this.doAccountCreation(contactRecord);
			return;
		}
		
		this.assignAccount();
    },
    assignAccount: function(){
    	this.bufferCreation.association = null;
		try{
			if(this.bufferCreation.member !== null){
				this.bufferCreation.association = this.bufferCreation.member.getForeignRecord(Tine.Membership.Model.Association, 'association_id');
			}
		}catch(e){
			this.bufferCreation.association = null;
		}
		this.soMemberWin = Tine.Membership.MembershipAccountEditDialog.openWindow({
    		relatedMemberRecord: this.relatedMemberRecord,
    		contactRecord: this.bufferCreation.contact,
    		memberId: (this.bufferCreation.member?this.bufferCreation.member.get('id'):null),
    		accountId: this.bufferCreation.contact.get('account_id'),
			listeners: {
                scope: this,
                'update': function(record) {
                	this.onAddMembershipAccount();
                }
            }
		});
		this.soMemberWin.on('beforeclose',this.onAddFunction,this);
    }
});