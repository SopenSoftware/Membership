Ext.namespace('Tine.Membership');

Tine.Membership.MessageBroker = function(config){
	config = config || {};
    Ext.apply(this, config);
    Tine.Membership.MessageBroker.superclass.constructor.call(this);
};

Ext.extend(Tine.Membership.MessageBroker, Ext.util.Observable, {
	messageCheckInterval: 60000,
	messageTask: null,
	messageBuffer: null,
	messageBufferIndex: 0,
	taskManager: null,
	messageViewActive:false,
	isRunning: false,
	initialize: function(){
		this.messageBuffer = new Ext.util.MixedCollection();
		this.start();
	},
	
	start: function(){
		if(!this.isRunning){
			this.taskManager = Ext.TaskMgr.start({
	    	    run: function(){
	    	    	if(!this.messageViewActive){
	    	    		this.checkNewMessages.defer(10000,this);
	    	    	}
				},
			    interval: this.messageCheckInterval,
			    scope:this
	    	});
			this.isRunning = true;
		}
	},
	
	stop: function(){
		if(this.taskManager){
			Ext.TaskMgr.stop(this.taskManager);
		}
		this.isRunning = false;
	},
	
	beginMessageView: function(){
		//this.stop();
		this.messageViewActive = true;
	},
	
	terminateMessageView: function(){
		//this.start.defer(15000,this);
		this.messageViewActive = false;
	},
	
	checkNewMessages: function(){
		Ext.Ajax.request({
			scope:this,
            params: {
                method: 'Membership.checkNewMessages'
            },
            success: this.onNewMessagesChecked,
            failure: this.onNewMessagesFailure
        });
	},
	openAddWindow: function(newRecord, config){
		this.beginMessageView();
		config = {} || config;
		var dialog = Tine.Membership.MessageEditRecord.create(newRecord, config);
		var win = Tine.Membership.MessageEditRecord.openWindow(dialog);
    	win.on('beforeclose',this.terminateMessageView,this);
	},
	openEditWindow: function(record, config){
		this.beginMessageView();
		config = {} || config;
		var dialog = Tine.Membership.MessageEditRecord.edit(record, config);
		var win = Tine.Membership.MessageEditRecord.openWindow(dialog);
    	win.on('beforeclose',this.terminateMessageView,this);
	},
	openMessageQueueWindow: function(){
		this.beginMessageView();
		var dialog = Tine.Membership.MessageEditRecord.showNew(this.messageBuffer);
		var win = Tine.Membership.MessageEditRecord.openWindow(dialog);
    	win.on('beforeclose',this.terminateMessageView,this);
	},
	showBufferedMessages: function(){
		if(this.messageBuffer.getCount()>0){
			this.openMessageQueueWindow();
		}
	},
	onNewMessagesChecked: function(_result){
		var result = Ext.util.JSON.decode(_result.responseText);
		if (result.success) {
            if(result.totalcount && result.totalcount>0){
            	//this.stop();
        		this.messageBuffer.clear();
            	for(var i=0;i<result.results.length;i++ ){
            		this.messageBuffer.add(result.results[i].id, new Tine.Membership.Model.Message(result.results[i], result.results[i].id));
            	}
            	this.showBufferedMessages();
            }
        } else {
            Ext.MessageBox.show({
                title: 'Fehler',
                msg: response.errorMessage,
                buttons: Ext.MessageBox.OK,
                icon: Ext.MessageBox.ERROR  
            });
        }
	},
	onNewMessagesFailure: function(){
		 Ext.MessageBox.show({
             title: 'Fehler',
             msg: 'Fehler beim Abfragen von Nachrichten',
             buttons: Ext.MessageBox.OK,
             icon: Ext.MessageBox.ERROR  
         });
	},
	markMessageRead: function(messageId){
		Ext.Ajax.request({
			scope:this,
            params: {
                method: 'Membership.markMessageRead',
                messageId: messageId
            },
            success: this.onMarkMessageReadSuccess,
            failure: this.onMarkMessageReadFailure
        });
	},
	onMarkMessageReadSuccess: function(){
	
	},
	onMarkMessageReadFailure: function(){
		
	}
	/*,
	
	sendMessage: function(messageRecord){
		Membership.Application.getJsonInterface().doRequest(
			{
				scope: this,
				method: 'Membership.publicSendMessage',
				params:{
					messageData: Ext.util.JSON.encode(messageRecord.data)
				},
				onSuccess: this.onSendMessageSuccess,
				onFailure: this.onSendMessageFailure
			}
		);
	},
	onSendMessageSuccess: function(){
	
	},
	onSendMessageFailure: function(){		
	}*/
	
});