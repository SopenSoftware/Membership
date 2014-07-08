Ext.ns('Tine.Membership');

Tine.Membership.EditRecord = Ext.extend(Ext.Panel, {
	id: 'sopen-membership-edit-record-form',
	className: 'Tine.Membership.EditRecord',
	recordArray: Tine.Membership.Model.SoMemberArray,
	recordCollection: null,
	recordClass: Tine.Membership.Model.SoMember,
    recordProxy: Tine.Membership.membershipBackend,
	record: null,
	contactRecord:null,
	editing: false,
	formFieldPrefix: 'membership_',
    bodyStyle:'padding:5px',
    layout: 'fit',
    border: false,
    deferredRender: true,
    // Nasty stuff:
    // forceLayout here was the only way to get the panel rendered 
    // in combination with layoutOnTabChange (inside the parent - tabpanel)
    forceLayout:true,
    bufferResize: 500,
    defaults: {
        frame: true
    },
    withFilterToolbar: true,
    initComponent: function(){
		this.addEvents(
			'loadmember',
			'beforesavemember',
			'savemember',
			'cancelmember',
			'memberviewmode'
		);
    	this.record = new this.recordClass(this.recordClass.getDefaultData(), 0);
    	this.memberGrid = new Tine.Membership.MembershipGridPanel(
		{
			memberForm: this,
			formId: this.id,
			withFilterToolbar: this.withFilterToolbar
			//recordClass: this.recordClass,
			//recordProxy: this.recordProxy
		});
    	
    	this.initFormItems();
    	
    	this.memberGrid.on('showmember',this.onShowMember,this);
    	this.memberGrid.on('addmember',this.onAddMember,this);
    	this.memberGrid.on('editmember',this.onEditMember,this);
    	this.historyPanel = new Tine.widgets.activities.ActivitiesTabPanel({
    		id: 'membership_history',
            app: 'Membership',
            record_id: (this.record) ? this.record.id : '',
            record_model: 'Membership_Model_SoMember'
        });
    	this.feeProgressPanel = Tine.Membership.getFeeProgressEditRecordAsTab();
    	this.feeProgressPanel.exchangeEvents(this);
    	
		this.historyPanel.disable();
		this.feeProgressPanel.disable();
		
    	this.initActions();
    	this.initButtons();
    	
    	this.items = this.getFormContents();
    	this.on('afterrender',this.onAfterRender,this);
    	Tine.Membership.EditRecord.superclass.initComponent.call(this);
//		this.setViewMode();
	},
	initFormItems: function(){
		this.recordCollection = new Ext.util.MixedCollection();
		this.recordCollection.addAll(this.recordArray);
	},
	initialLoad: function() {
		this.memberGrid.initialLoad();
	},
	/**
	 * Hand through store from member grid. Needed for PickerPanel 
	 * @return {Ext.data.Store}
	 **/
	getStore: function(){
		return this.memberGrid.getStore();
	},
	exchangeEvents: function(observable){
		if((typeof(observable)!='object') || (observable.className === undefined)){
			return false;
		}
		switch(observable.className){
		case 'Tine.Membership.FeeProgressEditRecord':
			observable.on('addfeeprogress',this.onDependentEditing, this);
			observable.on('editfeeprogress',this.onDependentEditing, this);
			return true;
		}
		return false;
	},
	onAfterRender: function(){
		this.setViewMode();
	},
	setViewMode: function(){
		this.editing = false;
		this.historyPanel.enable();
		this.feeProgressPanel.enable();
		this.recordCollection.each(
			function(item){
				Ext.getCmp(this.formFieldPrefix+item.name).disable();
			},this
		);
		this.fireEvent('memberviewmode');
	},
	setEditMode: function(){
		this.editing = true;
		this.recordCollection.each(
			function(item){
				var formField = Ext.getCmp(this.formFieldPrefix+item.name);
				if((formField.infoField === undefined ) || (formField.infoField === false)){
					formField.enable();
				}
			},this
		);
	},
	clearForm: function(){
		this.recordCollection.each(
			function(item){
				Ext.getCmp(this.formFieldPrefix+item.name).setValue(null);
			},this
		);
	},
	initActions: function(){
        this.action_applyChanges =new Ext.Action({
            requiredGrant: 'editGrant',
            text: _('Apply'),
//            disabled:true,
            //tooltip: 'Save changes',
            minWidth: 70,
            handler: this.handlerApplyChanges,
            iconCls: 'action_applyChanges',
            scope: this
        });
        
        this.action_cancel = new Ext.Action({
            text: _('Cancel'),
            //tooltip: 'Reject changes and close this window',
            minWidth: 70,
//            disabled:true,
            handler: this.handlerCancel,
            iconCls: 'action_cancel',
            scope: this
        });
        
        this.action_saveAndClose =new Ext.Action({
            requiredGrant: 'editGrant',
            text: _('Ok'),
//            disabled:true,
            //tooltip: 'Save changes',
            minWidth: 70,
            handler: this.handlerApplyChanges,
            iconCls: 'action_applyChanges',
            scope: this
        });
	},
	
	initButtons: function(){
		this.buttons = [
            this.action_applyChanges,
            this.action_cancel,
            this.action_saveAndClose
       ];
	},
	
	handlerApplyChanges: function(){
		if(this.editing && this.fireEvent('beforesavemember')){
			this.updateRecord();
			this.saveRecord();
		}
		this.memberGrid.enable();
		this.fireEvent('savemember', record);
	},
	
	handlerCancel: function(){
		this.clearForm();
		this.setViewMode();
		this.memberGrid.enable();
		this.fireEvent('cancelmember');
	},
	saveRecord: function(){
        if(this.isValid()) {
            //this.loadMask.show();
            
            this.updateRecord();
            
            if (this.mode !== 'local') {
                this.recordProxy.saveRecord(this.record, {
                    scope: this,
                    success: function(record) {
                        // override record with returned data
                		this.onSaveSuccess(record);
                    },
                    failure: this.onRequestFailed,
                    timeout: 150000 // 3 minutes
                });
            } else {
                this.loadRecord(this.record);
                //
                this.fireEvent('update', Ext.util.JSON.encode(this.record.data));
            }
        } else {
            Ext.MessageBox.alert(_('Errors'), _('Please fix the errors noted.'));
        }
    },
	onSaveSuccess: function(record){
    	this.loadRecord(record);
    	this.memberGrid.loadStore(this.contactRecord);
    },
	load: function(contact){
    	this.contactRecord = new Tine.Addressbook.Model.Contact(contact.data,contact.data.id);
		this.memberGrid.loadStore(this.contactRecord);
	},
	loadAll: function(){
		this.memberGrid.loadStoreAll();
	},
	
	/**
	 * on add handler (event fired by this.memberGrid)
	 * new record 
	 * @param: {Tine.Membership.Model.SoMember} new record 
	 */
	onAddMember: function(record){
		this.historyPanel.disable();
		this.feeProgressPanel.disable();
		this.memberGrid.disable();
		this.setEditMode();
		this.loadRecord(record);
	},
	onShowMember: function(record){
		this.setViewMode();
		this.loadRecord(record);
	},
	/**
	 * on edit handler (event fired by this.memberGrid)
	 * selected record
	 * @param: {Tine.Membership.Model.SoMember} selected record
	 */
	onEditMember: function(record){
		this.memberGrid.disable();
		this.historyPanel.enable();
		this.feeProgressPanel.enable();
		this.setEditMode();
		this.loadRecord(record);
	},
	
	onDependentEditing: function(){
		this.memberGrid.disable();
	},
	save: function(contact){
		this.contactRecord = contact;
		this.saveRecord();
	},
	
	loadRecord: function(record){
		this.record = record;
		this.clearForm();
		//this.calcVarFields();
		//this.getForm().loadRecord(this.record);
		this.loadForm();
		this.fireEvent('loadmember',this.record);
	},
	
	loadForm: function(){
		var att;
		var value;
		var field;
		for(var i in Tine.Membership.Model.SoMemberArray){
			att = Tine.Membership.Model.SoMemberArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = this.record.data[att.name];
				
				if(att.type == 'date'){
					value = (value ? Date.parseDate(value, att.dateFormat): value);
				}
				if(value){
					this.loadField('membership_'+att.name,value);
				}
			}
		}
	},
	
	loadField: function(field,value){
		try{
		Ext.getCmp(field).setValue(value);
		}catch(e){}
	},
	
	updateRecord: function(){
		var att;
		var value;
		var field;
		for(var i in Tine.Membership.Model.SoMemberArray){
			att = Tine.Membership.Model.SoMemberArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = Ext.getCmp('membership_'+att.name).getValue();
				if(value){
					this.record.data[att.name] = value;
				}
			}
		}
		this.record.set('contact_id',this.contactRecord);
	},
	
	getFormContents: function(){
		return Tine.Membership.getEditDialogPanel(this.memberGrid,this.feeProgressPanel,this.historyPanel);
	},
	
    isValid: function() {
		var att;
		var value;
		var field;
		for(var i in Tine.Membership.Model.SoMemberArray){
			att = Tine.Membership.Model.SoMemberArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				if(!Ext.getCmp('membership_'+att.name).validate()){
					return false;
				}
			}
		}
		return true;
    },
    
    /**
     * generic request exception handler
     * 
     * @param {Object} exception
     */
    onRequestFailed: function(exception) {
        Tine.Tinebase.ExceptionHandler.handleRequestException(exception);
    },
    
    getMemberGrid: function(){
    	return this.memberGrid;
    },
    addFilter: function(key,filter){
    	this.memberGrid.addFilter(key,filter);
    },
    removePreviousFilter: function(){
    	this.memberGrid.removePreviousFilter();
    }
});

Tine.Membership.getEditRecordAsTab = function(closable){
	return new Tine.Membership.EditRecord(
			{
				title: 'Mitgliedsdaten',
				disabled: Sopen.Config.apps.Addressbook.GUI.MemberPanel.disabled,
				withFilterToolbar: false
			}
	);
};

Tine.Membership.getEditRecordPanel = function(){
	return new Tine.Membership.EditRecord(
			{
				title: null,
				header: false,
				bodyStyle:'padding:0'
			}
	);
};

Tine.Membership.getEditDialogPanel = function(memberGrid, tabFeeProgressEditRecord,tabHistoryPanel){
	var editPanel = {
		xtype: 'panel',
		id: 'membership-edit-dialog-panel',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		layout:'fit',
		//labelAlign: 'left',
		//labelSeparator: ':',
		//labelWidth: 150,
		autoScroll: true,
		defferedRender:true,
	    defaults: {
	        xtype: 'fieldset',
	        autoHeight: 'auto',
	        layout:'fit',
	        disabledClass: 'x-item-disabled-view',
	        //defaults: {width: 800},
	        defaultType: 'textfield'
	    },
		items:[
		   		      {title:'',checkboxToggle:false,border:false,items:[{xtype:'columnform',items:[[
		   		       {xtype: 'hidden',id:'membership_id',name:'id'},
		   		       {xtype: 'hidden',id:'membership_contact_id',name:'contact_id'},
	                   {
    					    fieldLabel: 'Mitglied-Nummer',
    					    id:'membership_member_nr',
    					    name:'member_nr',
    					    disabledClass: 'x-item-disabled-view',
    					    value:null,
    					    columnwidth: 0.19
    //listeners:->TODO HH: check unique constraint in background{scope: this, 'change': Tine.Addressbook.onYearlyFeeChange}
    					},{
    						xtype: 'memberselect',
	                        columnwidth: 0.4,
    					    fieldLabel: 'Verein',
    					    disabledClass: 'x-item-disabled-view',
    					    id:'membership_society_contact_id',
    					    name:'society_contact_id'
    					},{
    						xtype: 'memberselect',
	                        columnwidth: 0.4,
    					    fieldLabel: 'Verband', 
    					    disabledClass: 'x-item-disabled-view',
    					    id:'membership_association_contact_id',
    					    name:'association_contact_id'
    					}
    				 ],[
    				    {
    						xtype: 'memberselect',
	                        columnwidth: 0.4,
    					    fieldLabel: 'geworben durch',
    					    disabledClass: 'x-item-disabled-view',
    					    id:'membership_affiliate_contact_id',
    					    name:'affiliate_contact_id'
    					},{
    					    fieldLabel: 'Mitgliedschaft',
    					    disabledClass: 'x-item-disabled-view',
    					    id:'membership_membership_type',
    					    name:'membership_type',
    					    columnwidth: 0.25,
    					    xtype:'combo',
    					    store:[['NOVALUE','...keine Auswahl...'],['SINGLE','Einzelmitgliedschaft'],['FAMILY','Familienmitgliedschaft'],['VIASOCIETY','Vereinsmitgliedschaft'],['SOCIETY','Verein']],
    					    value: 'VIASOCIETY',
    						mode: 'local',
    						displayField: 'name',
    					    valueField: 'id',
    					    triggerAction: 'all'
    					}/*,{
    						xtype: 'memberselect',
    						disabledClass: 'x-item-disabled-view',
	                        fieldLabel: 'Familienmitglied', 
    					    id:'membership_family_member_id',
    					    name:'family_member_id'
    					}*/
    				],[
    					{
    					    fieldLabel: 'Status',
    					    disabledClass: 'x-item-disabled-view',
    					    id:'membership_membership_status',
    					    name:'membership_status',
    					    columnwidth: 0.2,
    					    xtype:'combo',
    					    store:[['ACTIVE','aktiv'],['PASSIVE','passiv']],
    					    value: 'ACTIVE',
    						mode: 'local',
    						displayField: 'name',
    					    valueField: 'id',
    					    triggerAction: 'all'
    					}, {
    			            fieldLabel: 'Exp.Mitglieds-Ausw.', 
    			            disabledClass: 'x-item-disabled-view',
    			            name:'exp_membercard_datetime',
    			            columnwidth: 100,
    			            id:'membership_exp_membercard_datetime',
    			            xtype: 'datefield'
    			        }
    				 ],[
    					{
    						xtype: 'checkbox',
    						disabledClass: 'x-item-disabled-view',
    						id: 'membership_invoice_fee',
    						name: 'invoice_fee',
    						hideLabel:true,
    					    boxLabel: 'Rechnung Beitrag',
    					    columnWidth: 0.2
    					},{
    						xtype: 'checkbox',
    						disabledClass: 'x-item-disabled-view',
    						id: 'membership_society_sopen_user',
    						name: 'society_sopen_user',
    						hideLabel:true,
    					    boxLabel: 'Verein nutzt sopen',
    					    columnWidth: 0.2
    					},{
    						xtype: 'checkbox',
    						disabledClass: 'x-item-disabled-view',
    						id: 'membership_is_online_user',
    						name: 'is_online_user',
    						hideLabel:true,
    					    boxLabel: 'online-User',
    					    columnWidth: 0.2
    					},{
    						xtype: 'checkbox',
    						disabledClass: 'x-item-disabled-view',
    						id: 'membership_is_family_leading',
    						name: 'is_family_leading',
    						hideLabel:true,
    						disabled:true,
    						infoField:true,
    					    boxLabel: 'Führendes Familienmitglied',
    					    columnWidth: 0.2
    					}
    				],[
    					{
    						fieldLabel: 'Mitgliedsbemerkungen',
    						disabledClass: 'x-item-disabled-view',
    						xtype: 'textarea',
    						id: 'membership_member_notes',
    						name: 'member_notes',
    					    columnWidth: 0.9
    					}
    		    ]]}]},
    		      {title:'Zahlungsinformation',checkboxToggle:true,border:false,items:[{xtype:'columnform',items:[[
    			        {
    			        	width:200,
    			            fieldLabel: 'Zahlungsweise', 
    			            disabledClass: 'x-item-disabled-view',
    			            id:'membership_fee_payment_interval',
    			            xtype:'combo',
    					    store:[['NOVALUE','...keine Auswahl...'],['YEAR','jährlich'],['QUARTER','quartalsweise'],['MONTH','monatlich']],
    					    value: 'NOVALUE',
    			            name:'fee_payment_interval',
    				        mode: 'local',
    		            	displayField: 'name',
                            valueField: 'id',
                            triggerAction: 'all'
    			        },
    			        {
    			            fieldLabel: 'Zahlungsart',
    			            disabledClass: 'x-item-disabled-view',
    			            id:'membership_fee_payment_method',
    			            name:'fee_payment_method',
    			            width:200,
    			            xtype:'combo',
    			            store:[['NOVALUE','...keine Auswahl...'],['DEBIT','Lastschrift'],['BANKTRANSFER','Überweisung']],
    		            	value: 'NOVALUE',
    			            mode: 'local',
    		            	displayField: 'name',
                            valueField: 'id',
                            triggerAction: 'all'
    			        }
    			     ],[
    			        {
    			        	xtype:'textfield',
    			        	disabledClass: 'x-item-disabled-view',
    			        	width:200,
    			            fieldLabel: 'BLZ', 
    			            id:'membership_bank_code',
    			            name:'bank_code'
    			        },{
    			        	xtype:'textfield',
    			        	disabledClass: 'x-item-disabled-view',
    			        	width:200,
    			            fieldLabel: 'Bank-Name', 
    			            id:'membership_bank_name',
    			            name:'bank_name'
    			        }
    		        ],[
    			        {
    			        	xtype:'textfield',
    			        	disabledClass: 'x-item-disabled-view',
    			        	width:200,
    			            fieldLabel: 'Kontonummer', 
    			            id:'membership_bank_account_nr',
    			            name:'bank_account_nr'
    			        },{
    			        	xtype:'textfield',
    			        	disabledClass: 'x-item-disabled-view',
    			        	width:200,
    			            fieldLabel: 'Kontoinhaber', 
    			            id:'membership_account_holder',
    			            name:'account_holder'
    			        }			        
    			 ]]}]},
    		      {title:'Eintritt',checkboxToggle:true,border:false,items:[{xtype:'columnform',items:[[
    					{
    						xtype: 'datefield',
    						disabledClass: 'x-item-disabled-view',
    						fieldLabel: 'Eintritt', 
    					    name:'begin_datetime',
    					    id:'membership_begin_datetime',
    					    width:100
        					    
    					},{
    					    fieldLabel: 'Eintrittsgrund',
    					    disabledClass: 'x-item-disabled-view',
    					    context: 'entry_reason_id',
    						xtype: 'sogenericstatefield',
    						width:200,
    						id:'membership_entry_reason_id',
    			            name:'entry_reason_id',
    		            	allowBlank:false
    					}
      			 ]]}]},
    		      {title:'Austritt',checkboxToggle:true,border:false,items:[{xtype:'columnform',items:[[
       		        {
       		        	xtype: 'datefield',
    		            fieldLabel: 'Kündigung', 
    		            disabledClass: 'x-item-disabled-view',
    		            id:'membership_discharge_datetime',
    		            name:'discharge_datetime',
    		            width: 100
    		        },{
    		        	xtype:'datefield',
    		            fieldLabel: 'Austritt',
    		            disabledClass: 'x-item-disabled-view',
    		            id:'membership_termination_datetime',
    		            name:'termination_datetime',
    		            width: 100
    		        },{
					    fieldLabel: 'Austrittsgrund',
					    disabledClass: 'x-item-disabled-view',
					    context: 'termination_reason_id',
						xtype: 'sogenericstatefield',
						width:200,
						id:'membership_termination_reason_id',
			            name:'termination_reason_id',
		            	allowBlank:false
					}
    		     ]]}]}		    	          
		]};
	var editDialogPanel = 		{
			xtype:'panel',
			layout:'fit',
			id: 'member-edit-dialog-panel',
			items: [
			{
	    	    xtype:'panel',
				layout:'fit',
				cls: 'tw-editdialog',
				border:false,
				items:[
			       {
		  			    xtype: 'tabpanel',
					    border: false,
					    plain:true,
					    layoutOnTabChange: true,
					    border:false,
					    activeTab: 0,
					    items:[
					        {
							   xtype:'panel',
							   title: 'Mitglied-Stammdaten',
							   border:false,
							   layout:'fit',
							   items:[ editPanel ]
							},
							tabFeeProgressEditRecord,
							tabHistoryPanel
					    ]
			       }
				]
			}]}; 
	
	return [{
		xtype:'panel',
		layout:'fit',
		id: 'member-main-content-panel',
		items: [{
     	   xtype:'panel',
     	   header: false,
     	   border:true,
    	   layout:'border',
    	   items:[{
	    	   xtype:'panel',
	    	   region:'center',
	    	   header:false,
	    	   border:false,
	    	   frame:true,
	    	   layout:'fit',
	    	   items:[editDialogPanel]
	       },{
	    	   xtype:'panel',
	    	   region:'north',
	    	   header:false,
	    	   height:140,
	    	   split:true,
	    	   layout:'fit',
	    	   items:[ memberGrid ]
	       }]
       }]
	}];
};

