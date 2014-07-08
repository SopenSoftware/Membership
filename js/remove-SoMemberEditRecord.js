Ext.ns('Tine.Membership');

Tine.Membership.SoMemberEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'SoMemberEditWindow_',
	stateful: true,
	stateId: 'sopen-membership-edit-record-panel-state',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.SoMember,
	recordProxy: Tine.Membership.soMemberBackend,
	loadRecord: false,
	evalGrants: false,
	editHandler: null,
	simplePanel: null,
	parentMemberRecord: null,
	parentContactRecord: null,
	associationRecord: null,
	contactRecord:null,
	parentMembershipLabel: '<keine übergeordnete Mitgliedschaft>',
	parentMembershipEnabled: false,
	preselectedMembershipKind: null,
	memKind:null,
	initComponent: function(){
		this.bankDataHelpers = new Ext.util.MixedCollection();
		this.analyzeMembership();
		//this.setTypeMembersGrid('SOCIETY');
		this.initWidgets();
		this.initDependentGrids();
		this.on('load',this.onLoadSoMember, this);
		this.on('afterrender',this.onAfterRender,this);
		
		this.action_addMembership = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddMembership,
            iconCls: 'action_edit',
            scope: this
        });
		
		this.action_newContact = new Ext.Action({
            actionType: 'edit',
            handler: this.newContactSimple,
            text: 'Neuen Kontakt verwenden',
            iconCls: 'action_add',
            scope: this
        });        
		
        this.action_editAddress = new Ext.Action({
            actionType: 'edit',
            handler: this.onEditAddress,
            text: 'Adresse bearbeiten',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_changeParentMember = new Ext.Action({
            actionType: 'edit',
            handler: this.changeParentMember,
            text: 'Übergeordnetes Mitglied (Verein)',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_changeFeeGroup = new Ext.Action({
            actionType: 'edit',
            handler: this.changeFeeGroup,
            text: 'Beitragsgruppe',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_addPayment = new Ext.Action({
            actionType: 'edit',
            handler: this.addPayment,
            text: 'Zahlung erfassen',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_createFeeInvoice = new Ext.Action({
            actionType: 'edit',
            handler: this.createFeeInvoice,
            text: 'Abrechnen',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_editContact = new Ext.Action({
            actionType: 'edit',
            handler: this.onEditContact,
            text: 'Kontakt bearbeiten (erweiterter Dialog)',
            iconCls: 'action_edit',
            scope: this
        });
        this.action_addSoMember = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddBMWithoutContact,
            text: 'Kontakt vorhanden',
            iconCls: 'actionAdd',
            scope: this
        });
        this.action_addBMWithContact = new Ext.Action({
            actionType: 'edit',
            handler: this.onAddBMWithContact,
            iconCls: 'actionAdd',
            text: 'Kontakt vorher anlegen',
            scope: this
        });
        
        this.actions_addNewMember = new Ext.Action({
         	allowMultiple: false,
         	text: 'Neues Mitglied',
         	iconCls: 'actionAdd',
         	tooltip: 'Ermöglicht die Neuanlage eines Mitgliedes im aktuellen Dialog.',
            menu:{
             	items:[
             	       this.action_addSoMember,
             	       this.action_addBMWithContact
             	]
             }
         });
        
        this.actions_createMemberAccount = new Ext.Action({
        	requiredGrant: 'readGrant',
            text: 'Online Zugang erzeugen',
            actionType: 'edit',
           // disabled:true,
            handler: this.onCreateMemberAccount,
            iconCls: 'actionEdit',
            scope: this
        });
        this.actions_editContacts = new Ext.Action({
         	allowMultiple: false,
         	text: 'Bearbeiten',
            menu:{
             	items:[
             	       this.action_editAddress,
             	       this.action_editContact
             	]
             }
         });
        this.actions_change = new Ext.Action({
         	allowMultiple: false,
         	text: 'Wechseln',
            menu:{
             	items:[
             	       this.action_changeParentMember,
 					   this.action_changeFeeGroup
             	]
             }
         });
	   
        this.actions_billing = new Ext.Action({
         	allowMultiple: false,
         	text: 'Abrechnung/Zahlung',
            menu:{
             	items:[
             	       this.action_addPayment,
             	       this.action_createFeeInvoice
             	]
             }
         });
        this.actions_printMembers = new Ext.Action({
            text: 'Mitgliederliste drucken',
			disabled: false,
            handler: this.printMembers,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
        
        this.actions_printBeginLetter = new Ext.Action({
            text: 'Aufnahmeanschreiben drucken',
			disabled: false,
            handler: this.printBeginLetter,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
        
        this.actions_printTerminationLetter = new Ext.Action({
            text: 'Kündigungsbestätigung drucken',
			disabled: false,
            handler: this.printTerminationLetter,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
        
        this.actions_printInsuranceLetter = new Ext.Action({
            text: 'Versicherungsbestätigung drucken',
			disabled: false,
            handler: this.printInsuranceLetter,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
        
        this.actions_printMemberCardLetter = new Ext.Action({
            text: 'Mitgliedsausweis drucken',
			disabled: false,
            handler: this.printMemberCardLetter,
            iconCls: 'action_exportAsPdf',
            scope: this
        });
        
        this.actions_exportDTA = new Ext.Action({
            text: 'DTA-Export',
			disabled: false,
            handler: this.exportDTA,
            iconCls: 'action_exportAsCsv',
            scope: this
        });
        this.actions_print = new Ext.Action({
         	allowMultiple: false,
         	text: 'Drucken',
            menu:{
             	items:[
             	       this.actions_printMembers,
             	       '-',
             	       this.actions_printBeginLetter,
             	       this.actions_printInsuranceLetter,
             	       this.actions_printTerminationLetter,
             	       this.actions_printMemberCardLetter
             	]
             }
         });
        //this.items = this.getFormItems();
		Tine.Membership.SoMemberEditDialog.superclass.initComponent.call(this);
	},
	analyzeMembership: function(){
		var dependencies = Tine.Membership.getArrayFromRegistry('MembershipKinds.dependencies');
		this.dependencies = new Ext.util.MixedCollection();
		this.dependencies.addAll(dependencies);
		var full = Tine.Membership.getArrayFromRegistry('MembershipKinds.full');
		this.full = new Ext.util.MixedCollection();
		this.full.addAll(full.results);
	},
	getFullMembershipKindById: function(kindId){
		return this.full.get(kindId);
	},
	hasParent: function(){
		var memKind = this.getMembershipKind();
		var dep = this.dependencies.get(memKind);
		return dep.hasParents;
	},
	getParent: function(){
		var memKind = this.getMembershipKind();
		var dep = this.dependencies.get(memKind);
		this.parent = this.getFullMembershipKindById(dep.parents[0]);
		return this.parent;
	},
	hasChildren: function(){
		var memKind = this.getMembershipKind();
		var dep = this.dependencies.get(memKind);
		return dep.hasChildren;
	},
	getChildren: function(){
		var memKind = this.getMembershipKind();

		var dep = this.dependencies.get(memKind);
		
		this.children = new Ext.util.MixedCollection();
		var children = dep.children;
		for(var i=0; i<children.length; i++){
			this.children.add(children[i], this.getFullMembershipKindById(children[i]));
		}
		return this.children;
	},
	onChangeMemberKind: function(){
		var memKind = Ext.getCmp('membership_membership_type').getValue();
		this.record.set('membership_type', memKind);
		this.analyzeMembership();
		this.processParentInformation();
		Ext.getCmp('membership_fee_group_id').setMembershipKind(memKind);
		this.checkDefaults();
	},
	onSelectParentMember: function(pmRecord){
		var parentMemberRecord;
		if(pmRecord.data){
			parentMemberRecordd = pmRecord;
		}else{
			parentMemberRecord = pmRecord.selectedRecord;
		}
		Ext.getCmp('membership_association_id').setValue(parentMemberRecord.getForeignRecord(Tine.Membership.Model.Association,'association_id'));
	},
	onSelectContact: function(cRecord){
		var contactRecord;
		if(cRecord.data){
			contactRecord = cRecord;
		}else{
			contactRecord = cRecord.selectedRecord;
		}
		Ext.getCmp('membership_sex').setValue(contactRecord.get('sex'));
		Ext.getCmp('membership_bank_name').setValue(contactRecord.get('bank_name'));
		Ext.getCmp('membership_bank_code').setValue(contactRecord.get('bank_name'));
		Ext.getCmp('membership_bank_account_nr').setValue(contactRecord.get('bank_account_number'));
		Ext.getCmp('membership_account_holder').setValue(contactRecord.get('bank_account_name'));
		
		if(contactRecord.get('bday')){
			Ext.getCmp('membership_birth_date').setValue(contactRecord.get('bday'));
		}else{
			this.alertNoBirthdate(contactRecord);
		}
	},
	onDropContact: function(cp, contactRecord,ddSource, e, data){
		Ext.getCmp('membership_bank_name').setValue(contactRecord.get('bank_name'));
		Ext.getCmp('membership_bank_code').setValue(contactRecord.get('bank_name'));
		Ext.getCmp('membership_bank_account_nr').setValue(contactRecord.get('bank_account_number'));
		Ext.getCmp('membership_account_holder').setValue(contactRecord.get('bank_account_name'));
		
		Ext.getCmp('membership_sex').setValue(contactRecord.get('sex'));
		if(contactRecord.get('bday')){
			Ext.getCmp('membership_birth_date').setValue(contactRecord.get('bday'));
		}else{
			this.alertNoBirthdate(contactRecord);
		}
		
	},
	alertNoBirthdate: function(contactRecord){
		if(Tine.Membership.Config.Alerts.NoBirthdateGiven.isActive){
			Ext.MessageBox.show({
	             title: 'Hinweis', 
	             msg: Tine.Membership.Config.Alerts.NoBirthdateGiven.message,
	             buttons: Ext.Msg.OK,
	             scope: this,
	             fn: this.focusBirthDate,
	             icon: Ext.MessageBox.INFO
	         });
		}
	},
	focusBirthDate: function(){
		Ext.getCmp('membership_birth_date').focus();
	},
	getMembershipKind: function(){
		if(this.record.get('membership_type')){
			return this.record.get('membership_type');
		}
		return null;
	},
	processParentInformation: function(){
		if(this.getMembershipKind()){
			this.memKind = this.getFullMembershipKindById(this.getMembershipKind());
			//Ext.getCmp('membership_member_nr').label.update(this.memKind.dialog_text_member_nr);
			Ext.getCmp('membership_member_nr').label.update('Mitglied-Nr');
			//Ext.getCmp('membership_member_ext_nr').label.update(this.memKind.dialog_text_member_ext_nr);
			Ext.getCmp('membership_member_ext_nr').label.update('Mitglied-Nr2');
			
			Ext.getCmp('membership_association_id').label.update(this.memKind.dialog_text_assoc);
			if(this.hasParent()){
				var parent = this.getParent();
				Ext.getCmp('membership_parent_member_id').label.update(parent.dialog_text);
				Ext.getCmp('membership_parent_member_id').enable();
				Ext.getCmp('membership_parent_member_id').setMembershipType(parent.id);
				// set text of change parent member button
				this.action_changeParentMember.setText(parent.dialog_text);
				//Ext.getCmp('membership_parent_member_id').getStore().reload();
			}else{
				Ext.getCmp('membership_parent_member_id').label.update('...');
				Ext.getCmp('membership_parent_member_id').disable();
			}
			this.memberMainPanel.setTitle('Stammdaten ' + this.memKind.dialog_text);
			
			// check whether feegroup is duty
			if(this.memKind.fee_group_is_duty == true){
				Ext.getCmp('membership_fee_group_id').allowBlank = false;
			}else{
				Ext.getCmp('membership_fee_group_id').allowBlank = true;
			}
			
			if(!this.simplePanel && this.record.id !==0){
				this.createChildPanels();
			}
			
			if(this.record.id == 0){
				//Ext.getCmp('membership_fee_group_id').enable();
			}else{
				//Ext.getCmp('membership_fee_group_id').disable();
			}
		}
		
		if(this.record.id !== 0){
			
			if(this.memKind){
				if(this.memKind.uses_fee_progress==1){
					this.feeProgressGrid.enable();
					this.feeProgressGrid.loadSoMember(this.record);
				}else{
					this.feeProgressGrid.disable();
				}
				if(this.memKind.uses_member_fee_groups==1){
					this.memberFeeGroupGrid.enable();
					this.memberFeeGroupGrid.loadSoMember(this.record);
				}else{
					this.memberFeeGroupGrid.disable();
				}
				
				if(this.memKind.has_functionaries==1){
					this.committeeFunctionaryGrid.enable();
					this.committeeFunctionaryGrid.loadParentMember(this.record);
				}else{
					this.committeeFunctionaryGrid.disable();
				}
				
				if(this.memKind.has_functions==1){
					this.committeeFuncGrid.enable();
					this.committeeFuncGrid.loadMember(this.record);
				}else{
					this.committeeFuncGrid.disable();
				}
				
				this.actionHistoryGrid.enable();
				this.actionHistoryGrid.loadSoMember(this.record);
				
				
				this.membershipAwardGrid.enable();
				this.membershipAwardGrid.loadMember(this.record);
				
				this.regularDonationGrid.enable();
				this.regularDonationGrid.loadMember(this.record);
				
				this.donationGrid.enable();
				this.donationGrid.loadMember(this.record);
				
				Ext.getCmp('membership_contact_id').disable();
				
				Ext.getCmp('membership_contact_id').configureOnDropDecision(
					true,
					'Hinweis',
					'Durch eine Zuweisung eines neuen Kontaktes </br>geht die Mitgliedschaft bei diesem Kontakt verloren.</br>Ist dies beabsichtigt?'
				);
			}else{
				Ext.getCmp('membership_contact_id').configureOnDropDecision(
					false,
					'',
					''
				);
				this.feeProgressGrid.disable();
				this.memberFeeGroupGrid.disable();
				
				this.debitorAccountGrid.disable();
				this.debitorAccountGrid.store.removeAll();
			}
			this.memberAccountGrid.enable();
			this.memberAccountGrid.loadRelatedMember(this.record);
			
			var contactRecord = this.record.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
			if(contactRecord.get('account_id')){
				this.actions_createMemberAccount.disable();
			}else{
				this.actions_createMemberAccount.enable();
			}
		}else{
			Ext.getCmp('membership_contact_id').enable();
		}
	},
	loadDebitor: function(){
		if(this.record.id !== 0 && this.record.get('member_nr')){
			Ext.Ajax
				.request({
					scope : this,
					params : {
						method : 'Membership.getDebitor',
						memberNr :  this.record.get('member_nr')
					},
					success : this.onLoadDebitor,
					failure : function(response) {
						//
					}
				});
		}
	},
	onLoadDebitor: function(response) {
		var result = Ext.util.JSON
				.decode(response.responseText);
		if (result.success) {
			if(result.debitor){
				this.debitorRecord = new Tine.Billing.Model.Debitor(result.debitor,result.debitor.id);
				this.debitorAccountGrid.enable();
				this.debitorAccountGrid.loadDebitor(this.debitorRecord);
			}
		} else {
			
			
		}
	},
    initButtons: function(){
    	Tine.Membership.SoMemberEditDialog.superclass.initButtons.call(this);
    		this.tbar = [
	      	   '->',
	      	 Ext.apply(new Ext.Button(this.action_newContact), {
  				 scale: 'small',
  	             rowspan: 2,
  	           iconCls: 'actionAdd',
  	             iconAlign: 'left'
  	        }),
	      	Ext.apply(new Ext.Button(this.actions_addNewMember), {
 				 scale: 'small',
 	             rowspan: 2,
 	            iconCls: 'actionAdd',
 	             iconAlign: 'left'
 	        }),
     	     
	      	   Ext.apply(new Ext.Button(this.actions_editContacts), {
	  				 text: 'Kontakt',
	  	             scale: 'small',
	  	           iconCls: 'action_edit',
	  	             rowspan: 2,
	  	             iconAlign: 'left'
	  	        }),
	      	   Ext.apply(new Ext.Button(this.actions_change), {
	  				 text: 'Wechseln',
	  	             scale: 'small',
	  	             rowspan: 2,
	  	             iconAlign: 'left'
	  	        }),
	  	      Ext.apply(new Ext.Button(this.actions_billing), {
	  				 text: 'Abrechnung/Zahlung',
	  	             scale: 'small',
	  	             rowspan: 2,
	  	             iconAlign: 'left'
	  	        }),
	      	   Ext.apply(new Ext.Button(this.actions_print), {
	  				 scale: 'small',
	  	             rowspan: 2,
	  	             iconAlign: 'left'
	  	        })/*,
	  	      Ext.apply(new Ext.Button(this.actions_createMemberAccount), {
					 scale: 'small',
		             rowspan: 2,
		             iconAlign: 'left'
		        }),
		        Ext.apply(new Ext.Button(this.actions_exportDTA), {
	  				 scale: 'small',
	  	             rowspan: 2,
	  	             iconAlign: 'left'
	  	        }),*/
	  	    ];
    	
        this.fbar = [
             '->',
             this.action_applyChanges,
             this.action_cancel,
             this.action_saveAndClose
        ];
    },
    printMembers: function(){
    	var membershipType = Ext.getCmp('membership_membership_type').getValue();
    	var memberNr = Ext.getCmp('membership_member_nr').getValue();
    	var predefinedFilter = null;
    	if(membershipType == 'SOCIETY'){
    		predefinedFilter = [
    		    {	
					field:'membership_type',
					operator:'equals',
					value: 'VIASOCIETY'
				},{	
					field:'member_nr',
					operator:'startswith',
					value: memberNr
				}
			];
    	}
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
    		predefinedFilter: predefinedFilter,
    		actionType: 'printMembers',
    		panelTitle: 'Mitgliederliste drucken'    			
    	});
    },
    
    printBeginLetter: function(){
		var downloader = new Ext.ux.file.Download({
			params: {
                method: 'Membership.printBeginLetter',
                requestType: 'HTTP',
                memberIds: Ext.util.JSON.encode([this.record.get('id')]),
                data: Ext.util.JSON.encode({})
            }
        }).start();
    },

    printInsuranceLetter: function(){
		var downloader = new Ext.ux.file.Download({
			params: {
                method: 'Membership.printInsuranceLetter',
                requestType: 'HTTP',
                memberIds: Ext.util.JSON.encode([this.record.get('id')]),
                data: Ext.util.JSON.encode({})
            }
        }).start();
    },
    
    printTerminationLetter: function(){
		var downloader = new Ext.ux.file.Download({
			params: {
                method: 'Membership.printTerminationLetter',
                requestType: 'HTTP',
                memberIds: Ext.util.JSON.encode([this.record.get('id')]),
                data: Ext.util.JSON.encode({})
            }
        }).start();
    },
    
    
    printMemberCardLetter: function(){
    	var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Mitgliedsausweis drucken',
    		actionType: 'printMemberSingleLetter',
    		memberIds: Ext.util.JSON.encode([this.record.get('id')]),
    		getAdditionalFormItems: function(){
    			return [/*{
				    fieldLabel: 'Art des Anschreibens',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'letter_type',
				    name:'letter_type',
				    width: 200,
				    xtype:'combo',
				    store:[['1','Aufnahmeanschreiben'],['2','Versicherungsbestätigung'],['3','Kündigungsbestätigung'],['4','Mitgliedsausweis']],
				    value: '1',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all',
				    listeners:{
				    	select: function(v){
				    		if(v == 4){
				    			Ext.getCmp('membercard_date').enable();
				    		}else{
				    			Ext.getCmp('membercard_date').disable();
				    		}
				    		
				    	},
				    	scope:this
				    }
				}*/
    			    {
    			    	xtype:'hidden',  id:'letter_type',
    				    name:'letter_type',
    				    value: 4
    			    },{
					xtype: 'hidden',
					//fieldLabel: 'Neudruck der Anschreiben vom',
					id:'reprint_date',
					name:'reprint_date',
					value: null//,
					//width: 210
				},{
					xtype: 'extuxclearabledatefield',
					fieldLabel: 'Jahr Mitgliedsausweis',
					id:'membercard_date',
					name:'membercard_date',
					format: 'Y',					
					disabled:false,
					value: new Date(),
					width: 210
				}];
    		}
		});
    },
    
    changeParentMember: function(){
    	Tine.Membership.ChangeMemberDataDialog.openWindow({
    		panelTitle: this.action_changeParentMember.getText() + ' ändern',
    		changeSet: 'ParentMember',
    		memberRecord: this.record,
    		parentMemberLabel: this.action_changeParentMember.getText()
    	});
    },
    changeFeeGroup: function(){
    	//var fields = Tine.Membership.SoMemberFormFields.get();
    	Tine.Membership.ChangeMemberDataDialog.openWindow({
    		panelTitle: 'Beitragsgruppe ändern',
    		changeSet: 'FeeGroup',
    		memberRecord: this.record
    	});
    },
    exportDTA: function(){
		var parentMemberId = this.record.get('id');
		
		var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Membership.exportDTACurrent',
                requestType: 'HTTP',
                parentMemberId: parentMemberId
            }
        }).start();
	},
    createChildPanels: function(){
    	if(this.hasChildren()){
    		var tabPanel = Ext.getCmp('dependentGridsTab');
    		tabPanel.removeAll();
			var children = this.getChildren();
			this.bufferChildPanelCreation = {
				i: 0,
				activeTab: 0
			};
			children.eachKey(function(key,item){
				
				var panel = new Tine.Membership.SoMemberSoMemberGridPanel({
					id: 'membership-so-member-so-member-grid-'+key,
					stateId: 'state-id-membership-so-member-so-member-grid-'+key,
					title:item.subject_plural,
					contactIdenticalToParent: (item.identical_contact==1),
					gridConfig:{
						id:'membership-so-member-so-member-grid-grid'+key,
				        enableDragDrop: true,
				        ddGroup: 'ddGroupSoMember'
					},
					forceLayout:true,
					layout:'border',
					deferredRender:false,
					frame: true,
					app: Tine.Tinebase.appMgr.get('Membership')
				});
				
				var association = new Tine.Membership.Model.Association(this.record.data.association_id, this.record.data.association_id.id);
				panel.loadParentMember(association, this.record, item.id, item.dialog_text + ' hinzufügen');
				panel.doLayout();
				Ext.getCmp('dependentGridsTab').add(panel);
				if(item.default_tab==1){
					this.bufferChildPanelCreation.activeTab = this.bufferChildPanelCreation.i;
				}
				this.bufferChildPanelCreation.i++;
			}, this);
			
			tabPanel.setActiveTab(this.bufferChildPanelCreation.activeTab);
			
			Ext.getCmp('southMembershipPanel').doLayout();
			Ext.getCmp('southMembershipPanel').expand();
			
			delete this.bufferChildPanelCration;
			
		}else{
			this.removeDependentGrids();
		}
    },
    removeDependentGrids: function(){
    	Ext.getCmp('dependentGridsTab').removeAll();
		Ext.getCmp('southMembershipPanel').collapse();
    },
    checkDefaults: function(){
    	
    	/*
    	 * actions:
    	  		
    	  	 this.action_newContact
		 	 this.action_editAddress
			 this.action_editContact
			 this.action_changeParentMember
			 this.action_changeFeeGroup
			 this.action_createFeeInvoice
			 this.action_addSoMember
			 this.action_addBMWithContact
			 
			 this.actions_billing
			 this.actions_change
			 this.actions_print
			 this.actions_exportDTA
    	 */
    	
    	if(this.record.isNew()){
    		var defaultEntryDate = Tine.Membership.Helpers.SoMember.getMemberEntryDefaultDate();
    		Ext.getCmp('membership_begin_datetime').setValue(defaultEntryDate);
    		this.action_newContact.enable();
    		 this.actions_billing.disable();
    		 this.actions_change.disable();
    		 this.actions_print.disable();
    	}else{
    		this.action_newContact.disable();
    		this.actions_billing.enable();
	   		this.actions_change.enable();
	   		this.actions_print.enable();
    	}
    },
	onLoadSoMember: function(){
		this.processParentInformation();
		
		if(this.soMemberWidget){
			this.soMemberWidget.onLoadSoMember(this.record);
		}
		
		this.loadDebitor();
		
		// do something like prefill fields etc.
		this.checkDefaults();
		
		if(this.record.getFeeGroupPrices()){
			var pp = this.record.getFeeGroupPrices();
			var sums = new Ext.util.MixedCollection();
			var aStore = [];
			var bStore = [];
			Ext.QuickTips.init();
		    
		    var xg = Ext.grid;

		    // shared reader
		    var reader = new Ext.data.ArrayReader({}, [
		       {name: 'b1'},
		       {name: 'c1', type: 'float'},
		       {name: 'b2'},
		       {name: 'c2', type: 'float'}
		    ]);
		    this.grid1 = new Ext.grid.GridPanel({
		        ds: new Ext.data.Store({
		            reader: reader,
		            data: bStore
		        }),
		        cm: new xg.ColumnModel([
		            {id:'b1',header: "Bez. (I)", width: 40, sortable: true, dataIndex: 'b1'},
		            {header: "Betrag(I)", width: 20, sortable: true, renderer: Ext.util.Format.euMoney, dataIndex: 'c1'},
		            {id:'b2',header: "Bez. (II)", width: 40, sortable: true, dataIndex: 'b2'},
		            {header: "Betrag(II)", width: 20, sortable: true, renderer: Ext.util.Format.euMoney, dataIndex: 'c2'}
		        ]),
		        viewConfig: {
		            forceFit:true
		        },
		        width: 580,
		        height: 200,
		        collapsible: true,
		        animCollapse: false,
		        title: 'Beitragsbestandteile',
		        iconCls: 'icon-grid'
		    });
			
			if(pp.items !== undefined && pp.items !=null){
				p = pp.items;
				var items = [];
				var j = 0;
				var k = 0;
				var label, value;
				var len = p.length;
				var sum = 0;
				
				var countC1 = 0, countC2 = 0;
				var aStore = [];
				
				
				for(var i=0;i<len;i++){
					if(p[i].category=='I'){
						countC1++;
					}else if(p[i].category=='II'){
						countC2++;
					}
				}
				
				
				countC1 += 3;
				var maxCount = Math.max(countC1+3, countC2);
				
				var iC1 = 0, iC2 = 0;
				
				var iItems = 0;
				
				for(var i=0;i<len;i++){
					
					if(p[i].category=='I'){
						if(aStore[iC1] === undefined){
							aStore[iC1] = [];
						}
						aStore[iC1][0] = p[i].label;
						aStore[iC1][1] = p[i].value;
						
						if(iC2 == Math.max(countC2-1,0)){
							aStore[iC1][2] = '';
							aStore[iC1][3] = 0;
						}
						iC1++;
					}else{
						if(aStore[iC2] === undefined){
							aStore[iC2] = [];
						}
						aStore[iC2][2] = p[i].label;
						aStore[iC2][3] = p[i].value;
						iC2++;
					}
				}
				if(aStore[maxCount+1] === undefined){
					aStore[++maxCount] = [];
				}
				aStore[maxCount][0] = 'Zusatz';
				aStore[maxCount][1] = parseFloat(this.record.get('additional_fee'));
				aStore[maxCount][2] = '';
				aStore[maxCount][3] = 0;
				if(aStore[maxCount+1] === undefined){
					aStore[++maxCount] = [];
				}
				aStore[maxCount][0] = 'Spende';
				aStore[maxCount][1] = parseFloat(this.record.get('donation'));
				aStore[maxCount][2] = '';
				aStore[maxCount][3] = 0;
				
				if(aStore[maxCount+1] === undefined){
					aStore[++maxCount] = [];
				}
				aStore[maxCount][0] = 'indiv.';
				aStore[maxCount][1] = parseFloat(this.record.get('individual_yearly_fee'));
				aStore[maxCount][2] = '';
				aStore[maxCount][3] = 0;
				var v;
				var zi = 0;
				for(var i=0;i<aStore.length;i++){
					v = aStore[i];
					if(typeof(v) == 'object'){
						bStore[zi++] = v;
					}
				}
				this.grid1.getStore().loadData(bStore);
			
				this.fgpPanel.removeAll();
				this.fgpPanel.add(this.grid1);
				
			
			
				this.fgpPanel.doLayout();
				this.grid1.expand();
				Ext.getCmp('somember-edit-dialog-formitems-panel').doLayout();
				this.doLayout();
				this.fgpPanel.show();
				this.fgpPanel.expand();
			}
					// set window name, to avoid multiply opening (twice) the same record
			
		}else{
			this.fgpPanel.removeAll();
			this.grid1 = null;
			this.fgpPanel.hide();
		}
		this.window.name = Tine.Membership.SoMemberEditDialog.prototype.windowNamePrefix + this.record.id;
	},
	getClubMembersPanel: function(){
		return Ext.getCmp('southMembershipPanel');
	},
	newContactSimple: function(){
		this.contactWin = Tine.Addressbook.ContactQuickEditDialog.openWindow({
			simpleDialog : true,
			additionalFields: [
			  ['bank_account_name','bank_account_number'],                 
			  ['bank_code','bank_name']                 
			],
			
			
			listeners : {
				update : {
					scope : this,
					fn : this.onUpdateContact
				}
			}
		});
	},
	onAddSoMember: function(){
		if(this.record && !this.record.isNew() && this.record.isDirty()){
			 Ext.MessageBox.show({
	             title: 'Aktueller Datensatz nicht gespeichert', 
	             msg: 'Der aktuelle Datensatz ist in Bearbeitung.<br />Möchten Sie diesen zuerst speichern, bevor Sie fortfahren?',
	             buttons: Ext.Msg.YESNO,
	             scope: this,
	             fn: this.saveBeforeClear,
	             icon: Ext.MessageBox.QUESTION
	         });
			 return false;
		}
		return true;
		
	},
	saveBeforeClear: function(btn){
		if(btn == 'yes'){
			this.on('update', this.clearAddNew, this);
			this.onApplyChanges();
		
			
		}else{
			this.clearAddNew();
		}
	},
	clearAddNew: function(){
		this.un('update', this.clearAddNew, this);
		this.clearDialog();
		Ext.getCmp('membership_contact_id').configureOnDropDecision(
			false,
			'',
			''
		);
		if(this.withContact){
			this.withContact = false;
			this.openNewContactDialog();
		}
	},
	clearDialog: function(){
		this.removeDependentGrids();
		this.getForm().reset();
		this.record = new Tine.Membership.Model.SoMember(Tine.Membership.Model.SoMember.getDefaultData(),0);
		this.initRecord();
	},
	openNewContactDialog: function(){
		this.contactWin = Tine.Addressbook.ContactQuickEditDialog.openWindow({
			simpleDialog : true,
			additionalFields: [
			  ['bank_account_name','bank_account_number'],                 
			  ['bank_code','bank_name']                 
			],
			
			fieldListeners:
			[
			   {field:'n_given'}
			],
			listeners : {
				update : {
					scope : this,
					fn : this.onUpdateContact
				}
			}
		});
		
		this.contactWin.on('close',this.onReloadSelectionGrid,this);
	},
	onAddBMWithContact: function(){
		this.withContact = true;
		if(this.onAddSoMember()){
			this.openNewContactDialog();
		}
	},
	onAddBMWithoutContact: function(){
		this.withContact = false;
		this.onAddSoMember();
	},
	onCreateMemberAccount: function(){
		var contactRecord = this.record.getForeignRecord(Tine.Addressbook.Model.Contact, 'contact_id');
		if(!contactRecord.get('account_id')){
			var popupWindow = Tine.Membership.CreateMemberAccountEditDialog.openWindow({
	   		 modal:true,
	   		 contactRecord: contactRecord,
	   		 relatedMemberId: this.record.get('id')
	        });
	        return popupWindow;
		}
   },
	onUpdateContact: function(contact){
		var contactData = Ext.util.JSON.decode(contact);
		var contactRecord = new Tine.Addressbook.Model.Contact(contactData, contactData.id);
		this.record.data.contact_id = contactRecord;
		Ext.getCmp('membership_contact_id').setValue(contactRecord);
		
		Tine.Membership.Helpers.SoMember.onUpdateContact(this, contactRecord);
	},
	onReloadSelectionGrid: function(){
		this.getContactSelectionGrid().grid.getStore().reload();
	},
	onEditContact: function(){
		this.contactWin = Tine.Addressbook.ContactEditDialog.openWindow({
			record: new Tine.Addressbook.Model.Contact(this.record.data.contact_id,this.record.data.contact_id.id)
		});
	},
	addPayment: function(){
		try{
    		var memberNr = this.record.get('member_nr');
    		var win = Tine.Billing.PaymentEditDialog.openWindow({
        		record: null,
        		memberNr: memberNr
    		});
    	}catch(e){
    		
    	}
		/*this.loadMask.show();
		var contactId = this.record.getForeignId('contact_id');
		Ext.Ajax.request({
            scope: this,
            params: {
                method: Tine.Billing.Config.QuickOrder.Strategy.Debitor.method,
    	        contactId: contactId,
    	        additionalData:null
            },
            success: function(response){
            	var result = Ext.util.JSON.decode(response.responseText);
            	if(result.success){
            		this.loadMask.hide();
            		this.onGetDebitorAddPayment(new Tine.Billing.Model.Debitor(result.result, result.result.id));
            	}else{
	        		Ext.Msg.alert(
            			'Hinweis', 
                        'Kein Kundensatz vorhanden.'
                    );
            	}
        	},
        	failure: function(response){
        		var result = Ext.util.JSON.decode(response.responseText);
        		Ext.Msg.alert(
        			'Fehler', 
                    'Kunde konnte nicht abgefragt werden'
                );
        	}
        });*/

	},
	createFeeInvoice: function(){
		var selIds = [this.record.get('id')];
		
		var win = Tine.Membership.PrintMembershipDialog.openWindow({
			panelTitle: 'Beitragsabrechnung für Mitgliedsnummer ' + this.record.get('member_nr'),
			selectedMemberIds: selIds,
    		actionType: 'createFeeInvoiceForSelectedMembers',
    		getAdditionalFormItems: function(){
    			return [{
					xtype: 'textfield',
					fieldLabel: 'Beitragsjahr',
					id:'fee_year',
					name:'fee_year',
					width: 150
				},{
					xtype: 'datefield',
					fieldLabel: 'Stichtag',
					id:'due_date',
					name:'due_date',
					value: new Date(),
					width: 110
				},{
				    fieldLabel: 'Aktion',
				    disabledClass: 'x-item-disabled-view',
				    allowEdit:false,
				    id:'action',
				    name:'action',
				    width: 200,
				    xtype:'combo',
				    store:[['FEEPROGRESS','Beitragsverläufe erzeugen'],['FEEINVOICE','Hauptabrechnung'],['FEEINVOICECOMPLETE','Verläufe+Hauptabrechnung'],['FEEINVOICECURRENT','Folgeabrechnung (unterjährig)']],
				    value: 'FEEPROGRESS',
					mode: 'local',
					displayField: 'name',
				    valueField: 'id',
				    triggerAction: 'all'
				}];
    		}
		});
	},
	
	onGetDebitorAddPayment: function(debitorRecord){
		this.addPaymentWin = Tine.Billing.PaymentEditDialog.openWindow({
			debitorRecord: debitorRecord
		});
		this.addPaymentWin.on('beforeclose',this.onUpdateDebitorAccount,this);
	},
	
	onEditAddress: function(){
		this.contactWin = Tine.Addressbook.ContactEditDialog.openWindow({
			simpleDialog:true,
			record: new Tine.Addressbook.Model.Contact(this.record.data.contact_id,this.record.data.contact_id.id)
		});
	},
	initWidgets: function(){
		// dont use widget in simple panel (club member get displayed)
		if(!this.simplePanel){
			this.getSoMemberWidget();
		}
	},
	/**
	 *  initialize dependent gridpanels
	 */
	initDependentGrids: function(){
		
		this.feeProgressGrid = new Tine.Membership.SoMemberFeeProgressGridPanel({
			title:'Beitragsverläufe',
			layout:'border',
			closable:true,
			disabled:true,
			frame: true,
			inDialog:true,
			//useDetailsPanel: false,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.memberFeeGroupGrid = new Tine.Membership.MembershipFeeGroupGridPanel({
			title:'spezifische Beiträge',
			layout:'border',
			disabled:true,
			closable:true,
			frame: true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.actionHistoryGrid = new Tine.Membership.ActionHistoryGridPanel({
			title:'Historie',
			layout:'border',
			useImplicitForeignRecordFilter: true,
			disabled:true,
			frame: true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		
		this.membershipAwardGrid = new Tine.Membership.MembershipAwardGridPanel({
			title:'Ehrungen',
			layout:'border',
			disabled:true,
			closable:true,
			useImplicitForeignRecordFilter: true,
			frame: true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
        this.customFieldsPanel = new Tine.Tinebase.widgets.customfields.CustomfieldsPanel({
            layout:'fit',
        	recordClass: Tine.Membership.Model.SoMember,
            //disabled: (Tine.Addressbook.registry.get('customfields').length === 0),
            quickHack: {record: this.record}
        });
        
        this.regularDonationGrid = new Tine.Donator.RegularDonationGridPanel({
			title:'Spendenaufträge',
			layout:'border',
			perspective: 'FUNDMASTER',
			useImplicitForeignRecordFilter: true,
			disabled:false,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Donator')
		});
        
        this.donationGrid = new Tine.Donator.FundMasterDonationGridPanel({
			title:'Spenden',
			layout:'fit',
			disabled:true,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Donator')
		});
	},
	getFormItems: function(){
		this.fgpPanel = new Ext.Panel({
			   id:'fgp_panel',
			   header:false,
			   border:true,
			   width:500,
			   height:100,
			   layout:'fit',
			   collapsible:true,
			   collapseMode:'mini',
			   collapsed:true,
			   frame:true,
			   items:[
			      // gets optionally filled in case of feegroup prices being present
			   ]
		});
		this.memberMainPanel = new Ext.Panel({
		    border: false,
		    frame:true,
		    layout:'border',
		    title:'Stammdaten ',
		    autoScroll:true,
		    items: Tine.Membership.getSoMemberEditPanel(this.fgpPanel)
		});
		
		var grids = 
		[
			this.memberMainPanel
		];
		
		this.debitorAccountGrid = new Tine.Billing.DebitorAccountGridPanel({
			title:'Kundenkonto',
			layout:'border',
			useImplicitForeignRecordFilter: true,
			disabled:true,
			frame: true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Billing')
		});
		grids.push(this.debitorAccountGrid);
		
		this.committeeFunctionaryGrid = new Tine.Membership.CommitteeFuncGridPanel({
			title:'Funktionäre',
			perspective: 'FUNCTIONARY',
			committeeTarget: 'PARENTMEMBER',
			useImplicitForeignRecordFilter: true,
			layout:'border',
			disabled:true,
			frame: true,
			closable:true,
			enableAccountCreation:true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		
		grids.push(this.committeeFunctionaryGrid);
	
		this.committeeFuncGrid = new Tine.Membership.CommitteeFuncGridPanel({
			title:'Funktionen',
			perspective: 'FUNCTION',
			useImplicitForeignRecordFilter: true,
			layout:'border',
			disabled:true,
			frame: true,
			closable:true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		
		this.memberAccountGrid = new Tine.Membership.MembershipAccountGridPanel({
			title:'Online-Zugänge',
			layout:'border',
			disabled:true,
			frame: true,
			useImplicitForeignRecordFilter: true,
			doInitialLoad: false,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.committeeFunctionaryGrid.on('creatememberaccount', this.memberAccountGrid.createAccountFromMember, this.memberAccountGrid);
		
		var contactCRMAffinityColumnForm = {
	            xtype:'columnform',
	            id:'contactCRMAffinityCForm',
	            //deferredRender:false,
				items: [
				    [
			        	{
			        		xtype: 'checkbox',
							boxLabel: 'Ist Werber',
							hideLabel:true,
							columnWidth: 0.5,
							id:'is_affiliator',
				            name:'is_affiliator'
						},{
			        		xtype: 'checkbox',
							boxLabel: 'wurde geworben',
							hideLabel:true,
							columnWidth: 0.5,
							id:'is_affiliated',
				            name:'is_affiliated'
						}
						
			       	],				   
			       	[
 			        	Tine.Addressbook.Custom.getRecordPicker('Contact','affiliate_contact_id',{
							disabledClass: 'x-item-disabled-view',
							columnWidth: 0.98,
							fieldLabel: 'Werber',
						    name:'affiliate_contact_id',
						    disabled: false,
						    blurOnSelect: true,
						    allowBlank:true,
						    ddConfig:{
					        	ddGroup: 'ddGroupContact'
					        }
						})
			       	],
			       	[
			        	{
							xtype: 'extuxclearabledatefield',
							disabledClass: 'x-item-disabled-view',
							id: 'affiliator_provision_date',
							name: 'affiliator_provision_date',
							fieldLabel: 'Ausz.datum Werberprov.',
						    columnWidth:0.4

						},{
					 		xtype: 'sopencurrencyfield',
					    	fieldLabel: 'Werberprovision', 
						    id:'affiliator_provision',
						    name:'affiliator_provision',
					    	disabledClass: 'x-item-disabled-view',
					    	blurOnSelect: true,
					 	    width:180
					 	}
			       	],[
						{
						    fieldLabel: 'Anzahl Zeitungen',
						    name: 'count_magazines',
						    xtype:'uxspinner',
						    strategy: new Ext.ux.form.Spinner.NumberStrategy({
						        incrementValue : 1,
						        allowDecimals : false
						    })
						},{
						    fieldLabel: 'Anzahl zusätzl. Zeitungen',
						    name: 'count_additional_magazines',
						    xtype:'uxspinner',
						    strategy: new Ext.ux.form.Spinner.NumberStrategy({
						        incrementValue : 1,
						        allowDecimals : false
						    })
						}      
			       	],[
						{
							xtype: 'extuxclearabledatefield',
							disabledClass: 'x-item-disabled-view',
							id: 'info_letter_date',
							name: 'info_letter_date',
							fieldLabel: 'Datum Infoschreiben',
						    columnWidth:0.4
						
						}   
			       	]
			     ]
		};
		
		this.crmPanel = new Ext.Panel({
			layout:'fit',
			frame:true,
			title: 'Werbung',
			items: [contactCRMAffinityColumnForm]
		});
		
		grids.push(this.crmPanel);
		grids.push(this.committeeFuncGrid);
		
		grids = grids.concat([
			this.actionHistoryGrid,
			this.feeProgressGrid,
			this.regularDonationGrid,
			this.donationGrid,
			this.membershipAwardGrid,
			this.memberFeeGroupGrid,
			this.customFieldsPanel,
			this.memberAccountGrid
		]);
		
		var items = [
			{
				   xtype:'tabpanel',
				   activeItem:0,
				   region:'center',
				   enableTabScroll : true,
				   items: grids
			}   
		];
		
		if(!this.simplePanel){
			var panel = {
				xtype:'panel',
				region:'south',
				header:false,
				id:'southMembershipPanel',
				height: 300,
				collapsible:true,
				collapsed: true,
				collapseMode:'mini',
				layout:'fit',
				split:true,
				items:[
				       {
				    	   xtype:'tabpanel',
				    	   id:'dependentGridsTab',
				    	   forceLayout:true,
				    	   deferredRender:false,
				    	   layoutOnTabChange:true,
				    	   items:[
				    	          
				    	   ]
				       }
				 ]
			};
			items.push(panel);
		}
		return new Ext.Panel({
			region:'center',
			layout:'border',
			items:[
			       items
	    ]});
	},
	onAfterRender: function(){
		Ext.getCmp('membership_contact_id').on('select', this.onSelectContact, this);
		Ext.getCmp('membership_contact_id').on('change', this.onSelectContact, this);
		Ext.getCmp('membership_contact_id').on('afterdrop', this.onDropContact, this);
		
		Ext.getCmp('membership_parent_member_id').on('select', this.onSelectParentMember, this);
		Ext.getCmp('membership_parent_member_id').on('change', this.onSelectParentMember, this);
	
		Ext.getCmp('membership_membership_type').on('select',this.onChangeMemberKind, this);
		Ext.getCmp('membership_membership_type').on('change',this.onChangeMemberKind, this);
		if(this.record.id == 0){
			if(this.preselectedMembershipKind){
				Ext.getCmp('membership_membership_type').setValue(this.preselectedMembershipKind);
			}
			if(this.parentMemberRecord){
				Ext.getCmp('membership_parent_member_id').setValue(this.parentMemberRecord);
			}
			if(this.contactRecord){
				Ext.getCmp('membership_contact_id').setValue(this.contactRecord);
			}
			if(this.associationRecord){
				Ext.getCmp('membership_association_id').setValue(this.associationRecord);
			}
			Ext.getCmp('membership_fee_payment_method').selectDefault();
			Ext.getCmp('membership_entry_reason_id').selectDefault();
			Ext.getCmp('membership_termination_reason_id').selectDefault();
		}
    	this.initDropZone();
    	
    	if(this.bankDataHelpers.getCount()==0){
    		this.bankDataHelpers.add(new Tine.Billing.BankDataHelper().initialize(
    				'membership_bank_code', 'membership_bank_name', 'membership_bank_account_nr' , 'membership_account_holder' 
    		));
    	}else{
    		this.bankDataHelpers.each(
				function(item){
					item.updateFromForm();
				},
				this
			);
    	}
    	
    	Ext.getCmp('membership_account_holder').addListener('focus', this.presetBankAccountName, this);
    },
    presetBankAccountName: function(){
    	var bankName = Ext.getCmp('membership_account_holder').getValue();
    	if(!bankName){
    		//this.onRecordUpdate();
    		var contact = this.record.getForeignRecord(Tine.Addressbook.Model.Contact,'contact_id');
    		Ext.getCmp('membership_account_holder').setValue(contact.get('n_fileas'));
    	}
    },
    initDropZone: function(){
    	if(!this.ddConfig){
    		return;
    	}
		this.dd = new Ext.dd.DropTarget(this.el, {
			scope: this,
			ddGroup     : this.ddConfig.ddGroupContact,
			notifyEnter : function(ddSource, e, data) {
				this.scope.el.stopFx();
				this.scope.el.highlight();
			},
			notifyDrop  : function(ddSource, e, data){
				return this.scope.onDrop(ddSource, e, data);
			}
		});
		this.dd.addToGroup(this.ddConfig.ddGroupGetContact);
	},
	
	extractRecordFromDrop: function(ddSource, e, data){
		var source = data.selections[0];
		var record = null;
		switch(ddSource.ddGroup){
		case 'ddGroupSoMember':
			var source = data.selections[0];
			record = source;
			break;
			
		case 'ddGroupGetSoMember':
			if(source.getSoMember !== undefined && typeof(source.getSoMember)==='function'){
				record = source.getSoMember();
			}
			break;
		}
		return record;
	},
	
	onDrop: function(ddSource, e, data){
		var record = this.extractRecordFromDrop(ddSource, e, data);
		if(!record){
			return false;
		}
		this.record = record;
		this.initRecord();
		return true;
	},
	getSoMemberWidget: function(){
		if(!this.soMemberWidget){
			this.soMemberWidget = new Tine.Membership.SoMemberWidget({
					region: 'north',
					layout:'fit',
					height:40,
					editDialog: this
			});
		}
		return this.soMemberWidget;
	},
	getContactSelectionGrid: function(){
		return Ext.getCmp('SoMemberContactSelectionGrid');
	}
});

Tine.Membership.SoMemberEditDialogPanel = Ext.extend(Ext.Panel, {
	panelManager:null,
	windowNamePrefix: 'SoMemberEditWindow_',
	appName: 'Membership',
	layout:'fit',
	bodyStyle:'padding:0px;padding-top:5px',
	forceLayout:true,
	initComponent: function(){
		this.initSelectionGrids();
		
		Ext.apply(this.initialConfig,{region:'center'});
		
		var regularDialog = new Tine.Membership.SoMemberEditDialog(this.initialConfig);
		regularDialog.doLayout();
		this.items = this.getItems(regularDialog);
		Tine.Membership.SoMemberEditDialogPanel.superclass.initComponent.call(this);
	},
	initSelectionGrids: function(){
		this.soMemberSelectionGrid = new Tine.Membership.SoMemberSelectionGrid({
			title:'Mitglieder',
			layout:'border',
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		//SOPENTESTVAR = this;
	},
	getItems: function(regularDialog){
		var recordChoosers = [
			this.soMemberSelectionGrid,
			{
				xtype:'contactselectiongrid',
				id: 'SoMemberContactSelectionGrid',
				title:'Kontakte',
				layout:'border',
				app: Tine.Tinebase.appMgr.get('Addressbook')
			}                    
		];
		
		// use some fields from soMember edit dialog
		 var recordChooserPanel = {
				 xtype:'panel',
				 layout:'accordion',
				 region:'east',
				 title: 'Auswahlübersicht',
				 width:600,
				 collapsible:true,
				 bodyStyle:'padding:8px;',
				 split:true,
				 items: recordChoosers
		 };
		return [{
			xtype:'panel',
			layout:'border',
			items:[
			       // display creditor widget north
			       regularDialog.getSoMemberWidget(),
			       // tab panel containing creditor master data
			       // + dependent panels
			       regularDialog,
			       // place record chooser east
			       recordChooserPanel
			]
		}];
	}
});

Tine.Membership.SoMemberEditDialogSimplePanel = Ext.extend(Ext.Panel, {
	panelManager:null,
	windowNamePrefix: 'SoMemberEditWindow_',
	appName: 'Membership',
	layout:'fit',
	bodyStyle:'padding:0px;padding-top:5px',
	forceLayout:true,
	initComponent: function(){
		this.initSelectionGrids();
		
		Ext.apply(this.initialConfig,{region:'center'});
		
		var regularDialog = new Tine.Membership.SoMemberEditDialog(this.initialConfig);
		regularDialog.doLayout();
		this.items = this.getItems(regularDialog);
		Tine.Membership.SoMemberEditDialogSimplePanel.superclass.initComponent.call(this);
	},
	initSelectionGrids: function(){
		this.soMemberSelectionGrid = new Tine.Membership.SoMemberSelectionGrid({
			title:'Mitglieder',
			layout:'border',
			app: Tine.Tinebase.appMgr.get('Membership')
		});
	},
	getItems: function(regularDialog){
//		var recordChoosers = [
//			{
//				xtype:'contactselectiongrid',
//				id: 'SoMemberContactSelectionGrid',
//				title:'Kontakte',
//				layout:'border',
//				app: Tine.Tinebase.appMgr.get('Addressbook')
//			}                    
//		];
		
		// use some fields from soMember edit dialog
//		 var recordChooserPanel = {
//				 xtype:'panel',
//				 layout:'accordion',
//				 region:'east',
//				 title: 'Auswahlübersicht',
//				 width:600,
//				 collapsible:true,
//				 bodyStyle:'padding:8px;',
//				 split:true,
//				 items: recordChoosers
//		 };
		return [{
			xtype:'panel',
			layout:'border',
			items:[
			       regularDialog//,
			       // place record chooser east
			       //recordChooserPanel
			]
		}];
	}
});

Tine.Membership.SoMemberEditDialog.openWindow = function (config) {
	var constructor = 'Tine.Membership.SoMemberEditDialogPanel';
	var width = 1280;
	var height = 780;
	if(config.simplePanel !== undefined && config.simplePanel == true){
		constructor = 'Tine.Membership.SoMemberEditDialogSimplePanel';
		width = 680;
	}
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: width,
        height: height,
        name: Tine.Membership.SoMemberEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: constructor,
        contentPanelConstructorConfig: config
    });
    return window;
};

Tine.Membership.SoMemberEditRecord = Ext.extend(Tine.widgets.dialog.DependentEditForm, {
	id: 'sopen-somember-edit-record-form',
	className: 'Tine.Membership.SoMemberEditRecord',
	key: 'SoMemberEditRecord',
	recordArray: Tine.Membership.Model.SoMemberArray,
	recordClass: Tine.Membership.Model.SoMember,
    recordProxy: Tine.Membership.soMemberBackend,
    
    parentRecordClass: Tine.Addressbook.Model.Contact,
    parentRelation: {
		fkey: 'contact_id',
		references: 'id'
	},
    useGrid: false,
    useChildPanels:true,
    splitViewToggle: true,
    gridPanelClass: Tine.Membership.SoMemberGridPanelNested,
	formFieldPrefix: 'membership_',
	formPanelToolbarId: 'somember-edit-record-panel-toolbar',
	initComponent: function(){
		this.app = Tine.Tinebase.appMgr.get('Membership');
		this.gridPanelClass = Tine.Membership.SoMemberGridPanelNested;
		this.recordProxy = Tine.Membership.soMemberBackend;
		this.parentRecordClass = Tine.Addressbook.Model.Contact;
		this.parentRelation = {
			fkey: 'contact_id',
			references: 'id'
		};
		Tine.Membership.SoMemberEditRecord.superclass.initComponent.call(this);
		// register parent action events
		// this record events are handled by parent class
    	this.on('beforeaddrecord', this.onBeforeAddRecord, this);
    	this.on('addrecord', this.onAddRecord, this);
    	this.registerGridEvent('addparentrecord',this.onAddMembership, this);
    	this.registerGridEvent('editparentrecord',this.onEditMembership, this);
	},
	initChildPanels: function(){
		this.registerChildPanel('SoMemberFeeProgressEditRecord', Tine.Membership.getSoMemberFeeProgressEditRecordAsTab());
		//Tine.Membership.SoMemberEditRecord.superclass.initChildPanels.call(this);
	},
	onBeforeAddRecord: function(record){
		// parent record picker in this form -> unload parent, oterwise addRecord will fail
		//this.unloadParent();
		Tine.Membership.SoMemberEditRecord.superclass.onBeforeAddRecord.call(this);
	},
	onAddRecord: function(record){
		var contactSelector = Ext.getCmp('somember_contact_id');
		var parentRecord = this.getParentRecord();
		if(parentRecord !== undefined && parentRecord.data !== undefined && parentRecord.data.id !== undefined){
		//	contactSelector.disable();
		//	contactSelector.setValue(parentRecord);
		}
	},
	exchangeEvents: function(observable){
		this.checkObservableBreak(observable);
		switch(observable.className){
		case 'Tine.Membership.FeeProgressEditRecord':
			observable.on('aftersavesuccess',this.onAfterSaveFeeProgress, this);
			
			// don't call observable.exchangeEvents again here in parent
			// -> recursion
			return true;
		}
		return false;
	},
	onAfterSaveFeeProgress: function(){
		try{
			this.getGrid().reload();
		}catch(e){
			// IE craziness
		}
	},
	onAddMembership: function(){
	    var record = new Tine.Addressbook.Model.Contact(Tine.Addressbook.Model.Contact.getDefaultData(), 0);
	    var popupWindow = Tine.Addressbook.ContactEditDialog.openWindow({
	        record: record,
	        listeners: {
	            scope: this,
	            'update': function(record) {
	                this.load(true, true, true);
	            }
	        }
	    });
	},
	onEditMembership: function(record){
        record = new Tine.Addressbook.Model.Contact(record.data.contact_id,record.data.contact_id.id);
        var popupWindow = Tine.Addressbook.ContactEditDialog.openWindow({
        	activeContactTab: 'Mitglieddaten',
        	externalRecord: record,
        	init: function(){
        	},
	        record: record,
	        listeners: {
	            scope: this,
	            'update': function(record) {
	                this.load(true, true, true);
	            }
	        }
	    });
	},	

	getFormContents: function(){
		return Tine.Membership.getSoMemberEditDialogPanel(this.getComponents());
	}
});

Tine.Membership.getSoMemberEditRecordAsTab = function(){
	var grid = new Tine.Membership.SoMemberSoMemberGridPanel({
    	withFilterToolbar: false,
    	app: Tine.Tinebase.appMgr.get('Membership')
       });
	return new Ext.Panel({
		xtype:'panel',
		title: 'Mitglieddaten',
		layout:'border',
		region:'center',
		memberGrid:grid,
		getRecordChooserItems: function(){
			return [{
	        	xtype: 'memberselectiongrid',
	        	title:'Mitglieder',
	        	layout:'border',
	        	app: Tine.Tinebase.appMgr.get('Membership')
	        }];
		},
		loadParentContact: function(contact){
			this.memberGrid.loadParentContact(contact);
		},
		items:[
		    grid   
		]
	});
	
	return new Tine.Membership.SoMemberEditRecord(
		{
			title: 'Mitglieddaten',
			withFilterToolbar: false,
			useGrid:true,
			disabled: true,
			closable:true,
			getRecordChooserItems: function(){
				return [{
		        	xtype: 'memberselectiongrid',
		        	title:'Mitglieder',
		        	layout:'border',
		        	app: Tine.Tinebase.appMgr.get('Membership')
		        }];
			}
		}
	);
};

Tine.Membership.getSoMemberEditRecordPanel = function(){
	return new Tine.Membership.SoMemberEditRecord(
		{
			title: ' ',
			header: true,
			bodyStyle:'padding:0',
			withFilterToolbar:true,
			useGrid:true//,
			//forceLoadParent: true
		}
	);
};

Tine.Membership.getSoMemberEditDialogPanel = function(components){
	var editPanel = Tine.Membership.getSoMemberEditPanelEmbedded();
	var tabPanelItems = [
	    editPanel
	];

	if(components.childPanels.SoMemberFeeProgressEditRecord){
		tabPanelItems.push(components.childPanels.SoMemberFeeProgressEditRecord);
	}
	
	var editDialogPanel = {
		xtype:'panel',
		layout:'fit',
		id: 'somember-edit-dialog-panel',
		items: [
		{
		    xtype:'panel',
			layout:'fit',
			cls: 'tw-editdialog',
			border:false,
			items:[{
			    xtype: 'tabpanel',
			    id: 'somember-edit-dialog-childpanel-container',
			    border: false,
			    plain:true,
			    layoutOnTabChange: true,
			    border:false,
			    activeTab: 0,
			    items: tabPanelItems
			}]
		}]}; 
	
	
	var contentPanelItems = [{
 	   xtype:'panel',
	   region:'center',
	   header:false,
	   border:false,
	   frame:true,
	   layout:'fit',
	   items:[editDialogPanel]
   }];
	
	if(components.grid.useGrid){
		var gridWrapperItem = {
    	   xtype:'panel',
    	   id:'somember-edit-dialog-grid-container',
    	   region:'north',
    	   height:180,
    	   header:false,
    	   border:false,
    	   split:true,
    	   collapsible:true,
    	   collapseMode:'mini',
    	   collapsed:false,
    	   layout:'fit',
    	   items:[components.grid.grid]
		};
		contentPanelItems.push(gridWrapperItem);
	}
	
	return [{
		xtype:'panel',
		layout:'fit',
		id: 'somember-main-content-panel',
		items: [{
	  	   xtype:'panel',
	  	   header: false,
	  	   border:false,
	 	   layout:'border',
	 	   items: contentPanelItems
	    }]
	}];
};

Tine.Membership.getSoMemberEditPanel = function(fgpPanel){
	return {
		xtype: 'panel',
		id: 'Membership-edit-dialog-panel',
		region:'center',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		layout:'border',
		items:[{
			xtype:'panel',
			region:'center',
			id: 'somember-edit-dialog-inner-panel',
			autoScroll: true,
			border:false,
		    items: Tine.Membership.getSoMemberFormItems(fgpPanel)
		}]};
}

Tine.Membership.getSoMemberEditPanelEmbedded = function(fgpPanel){
	return {
		xtype: 'panel',
		id: 'somember-edit-dialog-panel',
		title: 'Mitglied Stammdaten',
		border: false,
		//frame: true,
		layout:'border',
		items:[ 
		    {
				xtype: 'panel',
				id: 'somember-edit-dialog-inner-panel',
				border:false,
				frame:true,
				region:'center',
				layout:'fit',
				autoScroll: true,
				tbar: new Ext.Toolbar({id:'somember-edit-record-panel-toolbar-tb',height:26}),
				items: Tine.Membership.getSoMemberFormItems(fgpPanel)
			}
		]
	};
}

Tine.Membership.getSoMemberFormItems = function(fgpPanel){
	var fields = Tine.Membership.SoMemberFormFields.get();
	
	return [{
		xtype:'panel',
		layout:'fit',
		id: 'somember-edit-dialog-formitems-panel',
		defaults: {
		    disabledClass: 'x-item-disabled-view'
		},
		items:[
	        {xtype:'columnform', id:'membership-column-form', items:[[
   		       fields.id,
   		       fields.begin_progress_nr,
   		       fields.contact_id,
   		       fields.sex
   		   ],[
   		       fields.member_nr,
		       fields.parent_member_id,
		   ],[
		       fields.member_ext_nr,
		       fields.association_id
           ],[
              fields.membership_type,
              fields.fee_group_id,
              fields.membership_status
			],[
				fields.birth_date,
				fields.person_age,
				fields.member_age,
				fields.exp_membercard_datetime,
				fields.member_card_year
			],[
			   {
				   xtype: 'fieldset',
				   collapsible: true,
				   title: 'Eintritt',
				   width:500,
				   layout:'fit',
				   items:
					[
			          	{xtype:'columnform', id:'membership-entry-column-form', 
			          		items:[[
								fields.begin_datetime,
								fields.entry_reason_id
			          	    ]]                                                            
			          	}
				   ]
			   }
			 ],[
				{
					   xtype: 'fieldset',
					   collapsible: true,
					   title: 'Austritt',
					   width:500,
					   layout:'fit',
					   items:[
					          	{xtype:'columnform', id:'membership-termination-column-form', 
					          		items:
					          		[[
										fields.discharge_datetime,
										fields.termination_datetime,
										fields.termination_reason_id
					          		]]
					          	}
					   ]
				}
			 ],[
			   {
				   xtype: 'fieldset',
				   collapsible: true,
				   title: 'Bank-/Zahlungsdaten',
				   width:500,
				   layout:'fit',
				   items:[
				          	{xtype:'columnform', id:'membership-bank-column-form', 
				          		items:[[
				          		     fields.fee_payment_interval,
				          		     fields.fee_payment_method,
				          		     fields.debit_auth_date
				      		     ],[
				      		         fields.bank_code,
				      		         fields.bank_name
				      	        ],[
				      		        fields.bank_account_nr,
				      		        fields.account_holder
				          	    ]]                                                            
				          	}
				   ]
			   }
			   ],[
				   {
					   xtype: 'fieldset',
					   collapsible: true,
					   title: 'Indiv. Beitrag',
					   width:500,
					   layout:'fit',
					   items:[
					          	{xtype:'columnform', id:'membership-indiv-column-form', 
					          		items:[
					          		[
					          		 	fields.invoice_fee,
					          		    fields.pays_admission_fee,
					          		    fields.has_individual_yearlyfee
									],[
										fields.individual_yearly_fee,
										fields.donation,
										fields.additional_fee
					          	    ]]                                                            
					          	}
					   ]
				   }
		],[
			fgpPanel   				
		],[
			fields.member_notes    
		],[
			fields.public_comment
		],[
		    fields.fee_from_date,
			fields.fee_to_date,
			fields.admission_fee_payed
		],[
		    fields.print_reception_date,
			fields.print_discharge_date,
			fields.print_confirmation_date
		],[
		    fields.ext_system_modified,
			fields.ext_system_username
		],[
	       fields.n_fileas,
	       fields.society_sopen_user,
	       fields.is_online_user
	     ]]}
	]}];
}

Ext.ns('Tine.Membership.SoMemberFormFields');

Tine.Membership.SoMemberFormFields.get = function(){
	return {
		// hidden fields
		id: 
			{xtype: 'hidden',id:'membership_id',name:'id'},
		begin_progress_nr:
			{xtype: 'hidden',id:'begin_progress_nr',name:'begin_progress_nr'},
		contact_id:
			Tine.Addressbook.Custom.getRecordPicker('Contact','membership_contact_id',{
				disabledClass: 'x-item-disabled-view',
				width: 400,
				fieldLabel: 'Kontakt Mitglied',
			    name:'contact_id',
			    disabled: false,
			    //displayField: 'full_company_ap',
			    onAddEditable: true,
			    onEditEditable: false,
			    blurOnSelect: true,
			    allowBlank:false,
			    ddConfig:{
		        	ddGroup: 'ddGroupContact'
		        }
			}),
		sex:
			{
				fieldLabel: 'Geschlecht',
			    disabledClass: 'x-item-disabled-view',
			    id:'membership_sex',
			    name:'sex',
			    width: 100,
			    xtype:'combo',
			    store:[['MALE','männlich'],['FEMALE','weiblich'],['NEUTRAL','neutral']],
			    value: 'MALE',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		member_nr:
			{
			    fieldLabel: 'Mitglied-Nummer',
			    id:'membership_member_nr',
			    name:'member_nr',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:null,
			    width: 100
			},
		parent_member_id:
			Tine.Membership.Custom.getRecordPicker('SoMember','membership_parent_member_id',{
				//disabledClass: 'x-item-disabled-view',
				width: 400,
				fieldLabel: 'Übergeordnete Mitgliedschaft',
			    name:'parent_member_id',
			    disabled: true,
			   // displayField: 'org_name',
			    onAddEditable: true,
			    onEditEditable: false,
			    blurOnSelect: true,
			    allowBlank:true,
			    ddConfig:{
		        	ddGroup: 'ddGroupContact'
		        }
			}),
		member_ext_nr:
			{
			    fieldLabel: 'Mitglied-Nummer 2',
			    id:'membership_member_ext_nr',
			    name:'member_ext_nr',
			    disabledClass: 'x-item-disabled-view',
			    disabled:false,
			    value:null,
			    width: 100
			},
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
			membership_type:
			{
			    fieldLabel: 'Mitgliedschaft',
			    disabledClass: 'x-item-disabled-view',
			    id:'membership_membership_type',
			    name:'membership_type',
			    width: 180,
			    xtype:'combo',
			    store:Tine.Membership.getStore('MembershipKind'),
			    value: null,
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
			fee_group_id:
				Tine.Membership.Custom.getRecordPicker('FeeGroup','membership_fee_group_id',{
					//disabledClass: 'x-item-disabled-view',
					width: 220,
					fieldLabel: 'Beitragsgruppe',
				    name:'fee_group_id',
				    onAddEditable: true,
				    onEditEditable: false,
				    blurOnSelect: true,
				    allowBlank:true
				}),
			membership_status:
			{
				fieldLabel: 'Status',
			    disabledClass: 'x-item-disabled-view',
			    id:'membership_membership_status',
			    name:'membership_status',
			    width: 100,
			    xtype:'combo',
			    store:[['ACTIVE','aktiv'],['PASSIVE','passiv'],['DISCHARGED', 'gekündigt'],['TERMINATED','ausgetreten']],
			    value: 'ACTIVE',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
			birth_date:
			{
	            fieldLabel: 'Geburts-/Gründungsdatum', 
	            disabledClass: 'x-item-disabled-view',
	            name:'birth_date',
	            width: 150,
	            id:'membership_birth_date',
	            xtype: 'extuxclearabledatefield'
	        },
	        person_age:
	        {
	        	xtype: 'numberfield',
	        	id:'membership_person_age',
	        	name: 'person_age',
	        	disabledClass: 'x-item-disabled-view',
	        	disabled:true,
	        	width: 60,
	        	fieldLabel: 'Alter',
	        	
	        },
	        member_age:
	        {
	        	xtype: 'numberfield',
	        	id:'membership_member_age',
	        	name: 'member_age',
	        	disabledClass: 'x-item-disabled-view',
	        	disabled:true,
	        	width: 70,
	        	fieldLabel: 'Mitgl.Jahre',
	        },
	        exp_membercard_datetime:
	        {
	            fieldLabel: 'Exp.Mitglieds-Ausw.', 
	            disabledClass: 'x-item-disabled-view',
	            name:'exp_membercard_datetime',
	            width: 110,
	            id:'membership_exp_membercard_datetime',
	            xtype: 'extuxclearabledatefield'
	        },
	        member_card_year:
	        {
	        	xtype: 'numberfield',
	        	id:'membership_member_card_year',
	        	name: 'member_card_year',
	        	disabledClass: 'x-item-disabled-view',
	        	//disabled:true,
	        	width: 110,
	        	fieldLabel: 'Jahr letzt.Ausw.',
	        },
	        begin_datetime:
	        {
				xtype: 'datefield',
				disabledClass: 'x-item-disabled-view',
				fieldLabel: 'Eintritt', 
			    name:'begin_datetime',
			    id:'membership_begin_datetime',
			    width:100,
			    allowBlank:false
			},
			entry_reason_id:
				Tine.Membership.Custom.getRecordPicker('EntryReason', 'membership_entry_reason_id',
				{
				    fieldLabel: 'Eintrittsgrund',
				    name:'entry_reason_id',
				    width:200,
				    allowBlank:false,
				    autoSelectDefault:false
				}),
			discharge_datetime:
				{
					xtype: 'extuxclearabledatefield',
				    fieldLabel: 'Kündigung', 
				    disabledClass: 'x-item-disabled-view',
				    id:'membership_discharge_datetime',
				    name:'discharge_datetime',
				    width: 140
				},
			termination_datetime:
				{
					xtype:'extuxclearabledatefield',
				    fieldLabel: 'Austritt',
				    disabledClass: 'x-item-disabled-view',
				    id:'membership_termination_datetime',
				    name:'termination_datetime',
				    width: 140
				},
			termination_reason_id:
				Tine.Membership.Custom.getRecordPicker('TerminationReason', 'membership_termination_reason_id',
				{
				    fieldLabel: 'Austrittsgrund',
				    name:'termination_reason_id',
				    width:200,
				    allowBlank:false,
				    autoSelectDefault:false
				}),
			fee_payment_interval:	
				 {
  		        	width:160,
  		            fieldLabel: 'Zahlungsweise', 
  		            disabledClass: 'x-item-disabled-view',
  		            id:'membership_fee_payment_interval',
  		            xtype:'combo',
  				    store:[['NOVALUE','...keine Auswahl...'],['YEAR','jährlich'],['HALF','halbjährlich'],['QUARTER','quartalsweise'],['MONTH','monatlich']],
  				    value: 'YEAR',
  		            name:'fee_payment_interval',
  			        mode: 'local',
  	            	displayField: 'name',
                      valueField: 'id',
                      triggerAction: 'all'
  		        },
  		     fee_payment_method:
  		        Tine.Billing.Custom.getRecordPicker('PaymentMethod', 'membership_fee_payment_method',
          		{
          		    fieldLabel: 'Zahlungsart',
          		    id: 'membership_fee_payment_method',
          		    name:'fee_payment_method',
          		    columnwidth: 100,
          		    autoSelectDefault:false
          		}),
          	bank_code:
          		{
  		        	xtype:'textfield',
  		        	disabledClass: 'x-item-disabled-view',
  		        	width:200,
  		            fieldLabel: 'BLZ', 
  		            id:'membership_bank_code',
  		            name:'bank_code'
  		        },
  		     bank_name:
	  		   {
	  	          	xtype:'combo',
	  	          	disabledClass: 'x-item-disabled-view',
		        	width:200,
	  	        	hideTrigger:true,
	  	        	store:[],
	  	        	fieldLabel: 'Bank-Name', 
		            id:'membership_bank_name',
		            name:'bank_name'
	  	      	},
  		     bank_account_nr:    
  		        {
  		        	xtype:'textfield',
  		        	disabledClass: 'x-item-disabled-view',
  		        	width:200,
  		            fieldLabel: 'Kontonummer', 
  		            id:'membership_bank_account_nr',
  		            name:'bank_account_nr'
  		        },
  		     account_holder:    
  		        {
  		        	xtype:'textfield',
  		        	disabledClass: 'x-item-disabled-view',
  		        	width:200,
  		            fieldLabel: 'Kontoinhaber', 
  		            id:'membership_account_holder',
  		            name:'account_holder'
  		        },
  		     invoice_fee:   
  		        {
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_invoice_fee',
					name: 'invoice_fee',
					hideLabel:true,
				    boxLabel: 'Rechnung Beitrag',
				    width:150
				},
			pays_admission_fee:	
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_pays_admission_fee',
					name: 'pays_admission_fee',
					hideLabel:true,
					boxLabel: 'Bezahlt Aufnahmegebühr',
				    width:150
				},
			has_individual_yearlyfee:	
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_has_individual_yearlyfee',
					name: 'has_individual_yearlyfee',
					hideLabel:true,
					boxLabel: 'Bezahlt indiv. Jahresbeitrag',
				    width:180
				},
			individual_yearly_fee:
				{
					xtype: 'sopencurrencyfield',
					fieldLabel: 'indiv. Jahresbeitr.', 
				    id:'membership_individual_yearly_fee',
				    name:'individual_yearly_fee',
					disabledClass: 'x-item-disabled-view',
					blurOnSelect: true,
					width:150
				},
			donation:
				{
					xtype: 'sopencurrencyfield',
					fieldLabel: 'Spende', 
				    id:'membership_donation',
				    name:'donation',
					disabledClass: 'x-item-disabled-view',
					blurOnSelect: true,
					width:150
				},
			additional_fee:
				{
					xtype: 'sopencurrencyfield',
					fieldLabel: 'Zusatzbeitrag', 
				    id:'membership_additional_fee',
				    name:'additional_fee',
					disabledClass: 'x-item-disabled-view',
					blurOnSelect: true,
					width:150
				},
			member_notes:
				{
					fieldLabel: 'Mitgliedsbemerkungen',
					disabledClass: 'x-item-disabled-view',
					xtype: 'textarea',
					id: 'membership_member_notes',
					name: 'member_notes',
					width:500
				},
			public_comment:
				{
					fieldLabel: 'Öffentliche/Externe Bemerkung',
					disabledClass: 'x-item-disabled-view',
					xtype: 'textarea',
					id: 'membership_public_comment',
					name: 'public_comment',
					width:500
				},				
			fee_from_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Beitr.pflicht ab',
					id:'membership_fee_from_date',
					name:'fee_from_date',
					width: 150
				},
			fee_to_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Beitr.pflicht bis',
					id:'membership_fee_to_date',
					name:'fee_to_date',
					width: 150
				},
			admission_fee_payed:	
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_admission_fee_payed',
					name: 'admission_fee_payed',
					hideLabel:true,
					boxLabel: 'Aufnahmegeb. ist bez.',
				    width:150
				},
			n_fileas:	
				 new Tine.Addressbook.SearchCombo({
		                allowBlank: true,
		                width: 300,
		                disabled: false,
		                useAccountRecord: true,
		                internalContactsOnly: true,
		                nameField: 'n_fileas',
		                fieldLabel: 'Account',
		                name: 'account_id'
		            }),
		    society_sopen_user:	    
			    {
					xtype: 'hidden',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_society_sopen_user',
					name: 'society_sopen_user',
					value:0,
					hideLabel:true,
				    boxLabel: 'Verein nutzt sopen',
				    width:0
				},
			is_online_user:		
				{
					xtype: 'hidden',
					disabledClass: 'x-item-disabled-view',
					id: 'membership_is_online_user',
					name: 'is_online_user',
					value:0,
					hideLabel:true,
				    boxLabel: 'online-User',
				    width:0
				},
			ext_system_username:    
  		        {
  		        	xtype:'textfield',
  		        	disabledClass: 'x-item-disabled-view',
  		        	width:200,
  		        	disabled:true,
  		            fieldLabel: 'Benutzer Altsystem', 
  		            id:'membership_ext_system_username',
  		            name:'ext_system_username'
  		        },				
			ext_system_modified:	
				{
					xtype:'datefield',
					disabledClass: 'x-item-disabled-view',
					disabled:true,
					fieldLabel: 'Letzte Änderung Altsystem',
					id:'membership_ext_system_modified',
					name:'ext_system_modified',
					width: 150
				},	
			print_reception_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Druck Aufnahmeanschreiben',
					id:'membership_print_reception_date',
					name:'print_reception_date',
					width: 150
				},	
			print_discharge_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Druck Kündigungsbestät.',
					id:'membership_print_discharge_date',
					name:'print_discharge_date',
					width: 150
				},	
			print_confirmation_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Druck Vers.best.',
					id:'membership_print_confirmation_date',
					name:'print_confirmation_date',
					width: 150
				},
			debit_auth_date:	
				{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Bank EZ-Ermächt.',
					id:'membership_debit_auth_date',
					name:'debit_auth_date',
					width: 140
				}
				
	};
}