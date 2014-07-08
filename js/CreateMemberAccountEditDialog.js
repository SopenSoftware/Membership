Ext.namespace('Tine.Membership');

Tine.Membership.CreateMemberAccountEditDialog = Ext.extend(Ext.form.FormPanel, {
	windowNamePrefix: 'CreateMemberAccountEditWindow_',
	title: 'Mitglieds-Zugang erfassen',
	//mode: 'local',
	appName: 'Membership',
	layout:'fit',
	recordClass: Tine.Membership.Model.CreateMemberAccount,
	loadRecord: false,
	evalGrants: false,
	contactRecord: null,
	memberId: null,
	relatedMemberId: null,
	/**
	 * {Tine.Membership.CreateMemberAccountGridPanel}	positions grid
	 */
	grid: null,
	/**
	 * initialize component
	 */
	initComponent: function(){
		this.addEvents(
			'accountcreated',
			'accountcreationfailed'
		);
		this.initActions();
		this.initToolbar();
		var contactRecord = (this.contactRecord !== undefined) ? this.contactRecord : null;
		this.record = new Tine.Membership.Model.CreateMemberAccount({
			contact_id: contactRecord.get('id')
		},0);
		this.items = this.getFormItems();
		Tine.Membership.CreateMemberAccountEditDialog.superclass.initComponent.call(this);
		
		this.getForm().loadRecord(this.record);
		this.getForm().clearInvalid();
		
	},
	
	onRender: function(ct, position){
		Tine.Membership.CreateMemberAccountEditDialog.superclass.onRender.call(this, ct, position);
		this.loadMask = new Ext.LoadMask(ct, {msg: 'Überprüfe Mitglieds-Daten'});
		this.loadMask.show();
		this.checkMemberData();
	},
	
	/**
	 * init bottom toolbar
	 */
	initToolbar: function(){
		this.bbar = new Ext.Toolbar({
			height:48,
        	items: [
        	        '->',
                    Ext.apply(new Ext.Button(this.actions_cancel), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    }),
                    Ext.apply(new Ext.Button(this.actions_saveCreateMemberAccount), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    })
                ]
        });
	},
	/**
	 * init toolbar (button) actions
	 */
	initActions: function(){
        this.actions_saveCreateMemberAccount = new Ext.Action({
            text: 'Ok',
            disabled: false,
            iconCls: 'action_applyChanges',
            handler: this.saveCreateMemberAccount,
            scale:'small',
            iconAlign:'left',
            scope: this
        });
        this.actions_cancel = new Ext.Action({
            text: 'Abbrechen',
            disabled: false,
            iconCls: 'action_cancel',
            handler: this.cancel,
            scale:'small',
            iconAlign:'left',
            scope: this
        });        
	},
	
	checkMemberData: function(){
		Ext.Ajax.request({
            scope: this,
            params: {
                method: 'Membership.checkMemberData',
                contactId: this.contactRecord.get('id'),
                relatedMemberId: this.releatedMemberId
            },
            success: function(response){
            	var result = Ext.util.JSON.decode(response.responseText);
            	if(result.success){
            		var eMail = result.data.email;
            		var existingAccounts = result.data.existingAccounts;
            		this.firstRespondedAccount = result.data.firstAccount;
            		
            		this.record.set('member_account_mail', eMail);
            		this.getForm().loadRecord(this.record);
            		this.loadMask.hide();
            		if(existingAccounts>0){
            			Ext.MessageBox.show({
           	             title: 'Hinweis', 
           	             msg: 'Für diesen Kontakt ist bereits ein Mitgliederzugang vorhanden.</br>Wollen Sie diesen Datensatz nun aufrufen?',
           	             buttons: Ext.Msg.YESNO,
           	             scope: this,
           	             fn: this.decideShowDialog,
           	             icon: Ext.MessageBox.QUESTION
           	         });
            		}else if(!eMail){
            			Ext.Msg.alert(
                			'Hinweis', 
                            'Bisher war beim Kontakt keine Emailadresse erfasst!<br/>Bitte geben Sie eine Emailadresse als Benutzernamen ein.<br/>Diese wird zugleich in den zugrundeliegenden Kontakt übernommen.'
                        );
            		}
            		
            	}else{
            		var isMember = result.data.tlStatus;
            		var noMail = result.data.noMail;
            		
	        		Ext.Msg.alert(
            			'Fehler', 
                        'Der Status kann nicht ermittelt werden.'
                    );
            	}
        	},
        	failure: function(response){
        		var result = Ext.util.JSON.decode(response.responseText);
        		Ext.Msg.alert(
        			'Fehler', 
                    'Die erforderlichen Daten können nicht abgefragt werden' + result
                );
        	}
        });
	},
	
	decideShowDialog: function(btn, text){
		if(btn == 'yes'){
			var win = Tine.Membership.MembershipAccountEditDialog.openWindow({
				record: new Tine.Membership.Model.MembershipAccount(
							this.firstRespondedAccount,
							this.firstRespondedAccount.id
				)
			});	
		}
		this.window.close();
	},
	
	/**
	 * save the order including positions
	 */
	saveCreateMemberAccount: function(){
		var data = {
				contactId: this.contactRecord.get('id'),
				relatedMemberId: this.relatedMemberId,
				memberId: this.memberId,
				userEmailAsLoginName: Ext.getCmp('member_user_email_as_login_name').getValue(),
				userName: Ext.getCmp('member_user_name').getValue(),
				autoCreatePass: true,
				email: Ext.getCmp('member_member_account_mail').getValue()
		}
		Ext.Ajax.request({
            scope: this,
            params: {
                method: 'Membership.createMemberAccount',
                data: data
            },
            success: function(response){
            	var result = Ext.util.JSON.decode(response.responseText);
            	if(result.success){
            		this.fireEvent('accountcreated', result);
            		this.window.close();
            	}else{
            		this.fireEvent('accountcreationfailed', result);
            		var errorState = result.errorState;
	        		var msg;
	        		switch(errorState){
	        		case 'USERNAME_ALREADY_EXISTS':
	        			msg = 'Der Benutzername existiert bereits, er kann kein zweites Mal verwendet werden.';
	        			break;
	        		case 'INVALID_EMAIL_ADDRESS':
	        			
	        			msg = 'Die Email-Adresse ist ungültig';
	        			break;
	        		default: 
	        			msg = 'Es ist ein Fehler aufgetreten. Der Zugang wurde nicht angelegt';
	        		}
	        		
	        		Ext.Msg.alert(
	        			'Fehler', 
	                    msg
	                );
            	}
        	},
        	failure: function(response){
        		var result = Ext.util.JSON.decode(response.responseText);
        		this.fireEvent('accountcreationfailed', result);
        		Ext.Msg.alert(
        			'Fehler', 
                    'Der Account kann nicht angelegt werden.' + result
                );
        	}
        });
	},
	/**
	 * Cancel and close window
	 */
	cancel: function(){
		this.purgeListeners();
        this.window.close();
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		// use some fields from member edit dialog
		var panel = {
	        xtype: 'panel',
	        border: false,
	        region:'center',
	        height: 150,
	        frame:true,
	        items:[{xtype:'columnform',items:[
				[
					Tine.Addressbook.Custom.getRecordPicker('Contact','membership_account_contact_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Benutzerkontakt',
					    name:'contact_id',
					    disabled:true,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:true,
					    ddConfig:{
					    	ddGroup: 'ddGroupContact'
					    }
					})
				   	],[
				   	{
						xtype: 'checkbox',
						disabledClass: 'x-item-disabled-view',
						id: 'member_auto_create_pass',
						name: 'auto_create_pass',
						hideLabel:true,
						value:true,
						checked:true,
						disabled:true,
					    boxLabel: 'Passwort automatisch generieren',
					    width: 220
					}
				   	],[
					   	{
							xtype: 'checkbox',
							disabledClass: 'x-item-disabled-view',
							id: 'member_user_email_as_login_name',
							name: 'user_email_as_login_name',
							hideLabel:true,
							value:true,
							checked:true,
							boxLabel: 'Email-Adresse als Benutzername verwenden',
						    width: 400
						}
				   	],[
				   	{
						xtype: 'textfield',
						disabledClass: 'x-item-disabled-view',
						id: 'member_member_account_mail',
						name: 'member_account_mail',
						fieldLabel:'Email-Adresse',
						vtype: 'email',
						allowBlank: false,
					    width: 300
					}
					],[
					   	{
							xtype: 'textfield',
							disabledClass: 'x-item-disabled-view',
							id: 'member_user_name',
							name: 'user_name',
							fieldLabel:'Benutzername (wenn nicht Email-Adresse):',
							allowBlank: false,
						    width: 300
						}
				 ]
	        ]}]
	    };

		var wrapper = {
			xtype: 'panel',
			layout: 'border',
			frame: true,
			items: [
			   panel
			]
		
		};
		return wrapper;
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.CreateMemberAccountEditDialog.openWindow = function (config) {
    // TODO: this does not work here, because of missing record
	var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 500,
        name: Tine.Membership.CreateMemberAccountEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.CreateMemberAccountEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};