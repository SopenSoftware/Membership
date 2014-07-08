Ext.namespace('Tine.Membership');

Tine.Membership.MessageEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'MessageEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.Message,
	recordProxy: Tine.Membership.messageBackend,
	loadRecord: false,
	evalGrants: false,
	initComponent: function(){
		Tine.Membership.MessageEditDialog.superclass.initComponent.call(this);
		this.on('afterrender', this.onAfterRender, this);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	onAfterRender: function(){
		Ext.getCmp('receiver_type').on('select', this.onSelectReceiverType, this);
	},
	onSelectReceiverType: function(){
		var type = Ext.getCmp('receiver_type').getValue();
		
		switch(type){
			case 'GROUP':
				Ext.getCmp('message_parent_member_id').disable();
				Ext.getCmp('message_parent_member_id').setValue('');
				Ext.getCmp('message_parent_member_id').allowBlank = true;
				Ext.getCmp('message_parent_member_id').validate();
				
				Ext.getCmp('message_receiver_account_id').disable();
				Ext.getCmp('message_receiver_account_id').setValue('');
				Ext.getCmp('message_receiver_account_id').allowBlank = true;
				Ext.getCmp('message_receiver_account_id').validate();
				
				Ext.getCmp('message_receiver_group_id').enable();
				Ext.getCmp('message_receiver_group_id').allowBlank = false;
				Ext.getCmp('message_receiver_group_id').validate();
			
				break;
			case 'USER':
				Ext.getCmp('message_parent_member_id').disable();
				Ext.getCmp('message_parent_member_id').setValue('');
				Ext.getCmp('message_parent_member_id').allowBlank = true;
				Ext.getCmp('message_parent_member_id').validate();
				
				Ext.getCmp('message_receiver_account_id').enable();
				Ext.getCmp('message_receiver_account_id').allowBlank = false;
				Ext.getCmp('message_receiver_account_id').validate();
				
				Ext.getCmp('message_receiver_group_id').disable();
				Ext.getCmp('message_receiver_group_id').setValue('');
				Ext.getCmp('message_receiver_group_id').allowBlank = true;
				Ext.getCmp('message_receiver_group_id').validate();
				break;
			case 'PARENTMEMBER':
				Ext.getCmp('message_parent_member_id').enable();
				Ext.getCmp('message_parent_member_id').allowBlank = false;
				Ext.getCmp('message_parent_member_id').validate();
				
				Ext.getCmp('message_receiver_account_id').disable();
				Ext.getCmp('message_receiver_account_id').setValue('');
				Ext.getCmp('message_receiver_account_id').allowBlank = true;
				Ext.getCmp('message_receiver_account_id').validate();
				
				Ext.getCmp('message_receiver_group_id').disable();
				Ext.getCmp('message_receiver_group_id').setValue('');
				Ext.getCmp('message_receiver_group_id').allowBlank = true;
				Ext.getCmp('message_receiver_group_id').validate();
				break;
		}
	},
	onRecordLoad: function(){
		/*if(this.feeDefinitionRecord){
			this.record.set('fee_definition_id', this.feeDefinitionRecord.get('id'));
		}*/
		this.supr().onRecordLoad.call(this);
	},
	getFormItems: function() {
		var fields = Tine.Membership.MessageFormFields.get();
	    return {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	              	fields.id, fields.send_mail
	             ],[	              	
	              	fields.receiver_type, fields.created_datetime, fields.expiry_datetime
	             ],[
	                fields.receiver_group_id, fields.receiver_account_id
	             ],[
	                fields.parent_member_id
	             ],[
					fields.subject
				],[
					fields.message
			   	]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.MessageEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 650,
        height: 700,
        name: Tine.Membership.MessageEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.MessageEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};


Tine.Membership.MessageEditRecord= function(config) {
    Ext.apply(this, config);	
    Tine.Membership.MessageEditRecord.superclass.constructor.call(this);
};

Ext.extend(Tine.Membership.MessageEditRecord, Tine.widgets.dialog.SimpleEditRecord, {
	id: 'sav-app-gui-message-editrecord',
	record: null,
	recordClass: Tine.Membership.Model.Message,
	recordProxy: Tine.Membership.messageBackend,
	tbarItems: [],
	messageBuffer: null,
	layout:'fit',
	frame: false,
	border:false,
	loadMode: 'local',
	initialLoad: false,
	initComponent: function(){
		
		if(this.messageBuffer != null){
			if(!this.initialLoad){
				this.initialLoad = true;
				this.loadNextRecord();
			}
		}
		
		this.on('afterrender', this.onAfterRender, this);	
		this.on('load', this.onLoadMessage, this);
		
		Tine.Membership.MessageEditRecord.superclass.initComponent.call(this);
	},
	onAfterRender: function(){
		this.rendered = true;
		//Ext.apply(this.window.items, this);
		//Ext.apply(this,this.window.items);
	},
	loadNextRecord: function(){
		if(this.hasNextBufferedRecord()){
			this.record = this.messageBuffer.first();
			this.messageBuffer.remove(this.record);
			return true;
		}
		return false;
	},
	closeWindow: function(){
    	if(this.fireEvent('beforeclosewindow',this)){
    		this.purgeListeners();
    		this.window.close();
    	}
    },
	hasNextBufferedRecord: function(){
		if(this.getCountBufferedRecords()>0){
			return true;
		}
		return false;
	},
	getCountBufferedRecords: function(){
		return this.messageBuffer.getCount();
	},
	getWindowContent: function(){
		return this.window.items;
	},
	initActions: function() {
        
        this.action_markRead = new Ext.Action({
            text: 'Als gelesen markieren',
            minWidth: 70,
            scope: this,
            disabled:true,
            handler: this.onMarkRead,
            iconCls: 'action_edit'
        });
        
        this.action_sendMessage = new Ext.Action({
            text: 'Nachricht senden',
            disabled:true,
            minWidth: 70,
            scope: this,
            handler: this.onSendMessage,
            iconCls: 'action_edit'
        });
        this.action_showNext = new Ext.Action({
            text: 'Nächste Nachricht',
            minWidth: 70,
            scope: this,
            disabled:true,
            handler: this.onShowNext,
            iconCls: 'action_edit'
        });
        
        Tine.Membership.MessageEditRecord.superclass.initActions.call(this);
        
    },
	onLoadMessage: function(){
		if(this.record && !this.record.isNew()){
			Ext.getCmp('message_subject').disable();
			Ext.getCmp('message_message').disable();
			//Ext.getCmp('message_created_datetime').disable();
			//Ext.getCmp('message_read_datetime').disable();
			
			this.action_sendMessage.disable();
			
			if(this.messageBuffer != null){
				var count = this.messageBuffer.getCount()+1;
				if(count==1){
					title = 'Sie haben eine neue Nachricht';
				}else if(count>1){
					title = 'Sie haben '+count+' neue Nachrichten';
				}
				try{
					this.window.setTitle(title);
				}catch(e){
					//
				}
				if(!this.hasNextBufferedRecord()){
					this.action_showNext.setText('Fertig');
				}
				this.action_markRead.disable();
				this.action_showNext.enable();
			}else{
				this.action_markRead.enable();
				this.action_showNext.disable();
			}
		}else{
			this.action_sendMessage.enable();
			this.action_markRead.disable();
			this.action_showNext.disable();
		}
	},
	onMarkRead: function(){
		Tine.Tinebase.appMgr.get('Membership').getMessageBroker().markMessageRead(this.record.getId());
	},
	onSendMessage: function(){
		/*this.onRecordUpdate();
		Tine.Membership.Application.getClient().getMessageBroker().sendMessage(this.record);
		this.closeWindow();*/
	},
	onShowNext: function(){
		this.onMarkRead();
		if(!this.hasNextBufferedRecord()){
			this.closeWindow();
		}
		
		if(this.loadNextRecord()){
			this.getForm().loadRecord(this.record);
			this.onLoadMessage();
		}
	},
	initButtons: function() {
        this.fbar = [
            this.action_cancel,
            this.action_markRead,
            this.action_showNext,
            this.action_sendMessage
       ];
    },
    onCancel: function(){
    	this.setViewMode();
        this.fireEvent('cancel');
        this.closeWindow();
    },
    onApplyChanges: function(button, event, closeWindow) {
    	if(this.isValid()) {
            this.onRecordUpdate();
            
            Tine.Membership.Application.getJsonInterface().doRequest(
        			{
        				scope: this,
        				method: 'Membership.savePublicMessage',
        				params:{
        					recordData: this.record.data
        				},
        				onSuccess: function(){
        					this.fireEvent('update', this.record);
        					this.closeWindow();
        				},
        				onFailure: function(){
        					alert('fehler');
        				}
        			}	
        		);
        } else {
            Ext.MessageBox.alert('Fehler', 'Bitte korrigieren Sie die markierten Felder.');
        }
    },
	getFormItems: function(){
		var formItems = Tine.Membership.getMessageEditFormItems();
		return formItems;
		
		var panel = {
    	   xtype:'panel',
    	   header:false,
    	   frame:true,
    	   border:false,
    	   layout:'border',
    	   items:[
    	          formItems
    	   ]
		};
		return panel;
	}
});

Tine.Membership.MessageEditRecord.create = function(newRecord, config){
	return Ext.applyIf({
		record: newRecord,
		title:'Neue Nachricht verfassen'
	},config);
};

Tine.Membership.MessageEditRecord.edit = function(record, config){
	return Ext.applyIf({
		record: record,
		title:'Nachricht lesen'
	},config);
};

Tine.Membership.MessageEditRecord.showNew = function(messageBuffer){
	var count = messageBuffer.getCount();
	var title;
	if(count==1){
		title = 'Sie haben eine neue Nachricht';
	}else if(count>1){
		title = 'Sie haben '+count+' neue Nachrichten';
	}
	return {
		messageBuffer: messageBuffer,
		title:title
	};
};

Tine.Membership.getMessageEditFormItems = function(){
	var messageColumnForm = {
		 region: 'center',
		 xtype:'columnform',
		 labelAlign: 'top',
		 bodyStyle:'padding:1px',
		 frame:false,
		 border:false,
		 formDefaults: {
             xtype:'textfield',
             anchor: '100%',
             labelSeparator: ''
         },
		 items:[	
		   [
			    {xtype:'hidden',id:'func_id',name:'id'},
		     	{xtype:'hidden',id:'func_member_id',name:'member_id'},
		     	{
	         		xtype:'textfield',
	         		id: 'message_subject',
	         		name: 'subject',
	         		disabledClass:'x-item-disabled-view',
	         		fieldLabel: 'Betreff',
	         		columnWidth:0.99
		        }
	     	],[
		     	{
	         		xtype:'textarea',
	         		disabledClass:'x-item-disabled-view',
	         		id: 'message_message',
	         		name: 'message',
	         		fieldLabel: 'Nachricht',
	         		columnWidth:0.98,
	         		height:220
		        }
	 		]
		]
	};

	return {
			id: 'sav-edit-form-panel-message',
			region:'center',
            autoScroll: true,
            border: false,
            title:null,
            frame: false,
            items: [
                    messageColumnForm
	         ]
	};
}

Tine.Membership.MessageEditRecord.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var windowFactory = new Ext.ux.WindowFactory({
    	windowType:'Ext'
    });
    
    var window = windowFactory.getWindow({
        width: 650,
        height: 520,
        name: 'Message-Editrecord' + id,
        border:false,
        bodyStyle:'padding:2px',
        contentPanelConstructor: 'Tine.Membership.MessageEditRecord',
        contentPanelConstructorConfig: config
    });
    return window;
};

Ext.ns('Tine.Membership.MessageFormFields');

Tine.Membership.MessageFormFields.get = function(){
	return{
		// hidden fields
		id: 
			{xtype: 'hidden',id:'id',name:'id'},
		receiver_group_id:
			new Tine.Tinebase.widgets.form.RecordPickerComboBox({
				id:'message_receiver_group_id',
                fieldLabel: 'Empfänger: Benutzergruppe',
                name: 'receiver_group_id',
                blurOnSelect: true,
                allowBlank:false,
                recordClass: Tine.Tinebase.Model.Group
            }),
		receiver_account_id:
			new Tine.Addressbook.SearchCombo({
				id:'message_receiver_account_id',
                allowBlank: false,
                columnWidth: 1,
                disabled: true,
                useAccountRecord: true,
                internalContactsOnly: true,
                nameField: 'n_fileas',
                fieldLabel: 'Empfänger: Benutzer',
                name: 'receiver_account_id'
            }),
		parent_member_id:
			Tine.Membership.Custom.getRecordPicker('SoMember','message_parent_member_id',{
				//disabledClass: 'x-item-disabled-view',
				width: 400,
				fieldLabel: 'Empfänger: Verein',
				membershipType:'SOCIETY',
			    name:'parent_member_id',
			    disabled: true,
			    blurOnSelect: true,
			    allowBlank:true
			}),
		member_id:
			Tine.Membership.Custom.getRecordPicker('SoMember','message_member_id',{
				//disabledClass: 'x-item-disabled-view',
				width: 400,
				fieldLabel: 'Empfänger:Mitglied',
			    name:'member_id',
			    disabled: true,
			    blurOnSelect: true,
			    allowBlank:true
			}),
		send_mail:
			{
				xtype: 'checkbox',
				disabledClass: 'x-item-disabled-view',
				id: 'membership_send_mail',
				name: 'send_mail',
				hideLabel:true,
			    boxLabel: 'zusätzlich als Email',
			    width:150
			},
		subject:	
			{
			    fieldLabel: 'Betreff',
			    id:'subject',
			    name:'subject',
			    value:null,
			    allowBlank:false,
			    width: 600
			},
		message:	
			{
				xtype:'textarea',
			    fieldLabel: 'Nachricht',
			    id:'message',
			    name:'message',
			    value:null,
			    allowBlank:false,
			    width: 600,
			    height:400
			},
		receiver_type:
			{
			    fieldLabel: 'Empfängerkreis',
			    disabledClass: 'x-item-disabled-view',
			    id:'receiver_type',
			    name:'receiver_type',
			    width: 200,
			    xtype:'combo',
			    store:[['GROUP','Gruppe'],['USER','Benutzer'],['PARENTMEMBER','Verein']],
			    value: 'GROUP',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},		
		created_datetime:
		{
			xtype:'datetimefield',
			disabledClass: 'x-item-disabled-view',
			disabled:true,
			fieldLabel: 'gesendet am',
			id:'message_created_datetime',
			name:'created_datetime',
			width: 150
		},		
		expiry_datetime:
		{
			xtype:'extuxclearabledatefield',
			disabledClass: 'x-item-disabled-view',
			fieldLabel: 'Läuft ab am',
			id:'message_expiry_datetime',
			name:'expiry_datetime',
			width: 150
		}
			
	};
}