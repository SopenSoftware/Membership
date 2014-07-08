Ext.namespace('Tine.Membership');

Tine.Membership.JobEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'JobEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.Job,
	recordProxy: Tine.Membership.jobBackend,
	loadRecord: false,
	evalGrants: false,
	autoUpdate: false,
	autoUpdateInterval: 30000,
	closeWindowOnLoad: null,
	initComponent: function(){
		this.initActions();
		this.initButtons();
		this.initDependentGrids();
		this.setAutoUpdateText();
		this.on('afterrender', this.onAfterRender, this);
		this.on('load', this.closeObjWindowOnLoad, this);
		Tine.Membership.JobEditDialog.superclass.initComponent.call(this);
	},
	closeObjWindowOnLoad: function(){
		if(this.closeWindowOnLoad){
			this.closeWindowOnLoad.close();
			this.closeWindowOnLoad = null;
		}
		
		if(this.record.get('job_state')=='PROCESSED' && this.record.get('job_category') == 'MANUALEXPORT'){
			this.buttonDownloadFile.enable();
		}
	},
	setAutoUpdateText: function(){
		if(this.autoUpdate){
			this.autoUpdateText = 'Autoupdate an';
		}else{
			this.autoUpdateText = 'Autoupdate aus'
		}
	},
	getAutoUpdateText: function(){
		return this.autoUpdateText;
	},
	initActions: function(){
		this.action_printInvoice = new Ext.Action({
            actionType: 'edit',
            iconCls: '',
            text: 'Rechnungen drucken',
            handler: this.printInvoices,
            disabled:true,
            scope: this
        });
		this.action_printVerificationList = new Ext.Action({
            actionType: 'edit',
            iconCls: '',
            handler: this.printVerificationList,
            text: 'Nachweisliste drucken',
            disabled:true,
            scope: this
        });
		
        this.action_autoUpdater = new Ext.Action({
            actionType: 'edit',
            iconCls: '',
            text: this.getAutoUpdateText(),
            scope: this
        });
        
        this.action_downloadFile = new Ext.Action({
            actionType: 'edit',
            iconCls: '',
            text: 'Datei herunterladen',
            handler: this.downloadFile,
            disabled:true,
            scope: this
        });
        this.buttonDownloadFile = Ext.apply(new Ext.Button(this.action_downloadFile), {
            scale: 'small',
            rowspan: 2,
            iconAlign: 'top',
            iconCls: 'action_stockFlowDec'
        });
        
        Tine.Membership.JobEditDialog.superclass.initActions.call(this);
    },
    initButtons: function(){
    	Tine.Membership.JobEditDialog.superclass.initButtons.call(this);
    	this.toggleAutoUpdateButton = Ext.apply(new Ext.Button(this.action_autoUpdater), {
		    scale: 'large',
		    width:150,
		    rowspan: 3,
		    pressed: this.autoUpdate,
		    enableToggle:true,
		    text: this.autoUpdateText,
		    iconAlign: 'left',
		    arrowAlign:'right'
		});
		this.toggleAutoUpdateButton.on('toggle', this.onToggleAutoUpdate, this);
    	
		this.printInvoiceButton = Ext.apply(new Ext.Button(this.action_printInvoice), {
		    scale: 'large',
		    width:150,
		    rowspan: 3,
		    text: 'Rechnungen drucken',
		    iconAlign: 'left',
		    arrowAlign:'right'
		});
		
		this.printInvoiceListButton = Ext.apply(new Ext.Button(this.action_printVerificationList), {
		    scale: 'large',
		    width:150,
		    rowspan: 3,
		    text: 'Nachweisliste drucken',
		    iconAlign: 'left',
		    arrowAlign:'right'
		});
		
		this.toggleAutoUpdateButton = Ext.apply(new Ext.Button(this.action_autoUpdater), {
		    scale: 'large',
		    width:150,
		    rowspan: 3,
		    pressed: this.autoUpdate,
		    enableToggle:true,
		    text: this.autoUpdateText,
		    iconAlign: 'left',
		    arrowAlign:'right'
		});
		
    	this.tbar = [
    	     this.toggleAutoUpdateButton,
    	     this.buttonDownloadFile,
    	     this.printInvoiceButton,
    	     this.printInvoiceListButton
    	];
    	
        this.fbar = [
             '->',
             this.action_applyChanges,
             this.action_cancel,
             this.action_saveAndClose
        ];
    },
    onToggleAutoUpdate: function(){
    	var buttonState = this.toggleAutoUpdateButton.pressed;
		if(buttonState == true){
			this.autoUpdate = true;
			this.createAutoUpdater();
		}else{
			this.autoUpdate = false;
			this.stopAutoUpdater();
		}
		this.setAutoUpdateText();
		this.toggleAutoUpdateButton.setText(this.autoUpdateText);
    },
    isJobRunning: function(){
    	if(this.record && this.record.id && this.record.get('job_state')=='RUNNING'){
			return true;
		}
		return false;
    },
    downloadFile: function(){
    	//var selectedRows = this.getGrid().getSelectionModel().getSelections();
    	
    	var jobId = this.record.get('id');
    	
    	var jobCategory = this.record.get('job_category');
    	var params;
    	switch(jobCategory){
    	case 'MANUALEXPORT':
    		params = {
                method: 'Membership.downloadJobExportFile',
                customExportCsvJobId: jobId
            };
    		break;
    	default:
    		params = {
                method: 'Membership.getPrintJobResult',
                printJobId: jobId
            }
    		method = 'Membership.getPrintJobResult';
    		break;
    	}
		var downloader = new Ext.ux.file.Download({
            params: params
        }).start();
    },
	createAutoUpdater: function(){
		if(this.isJobRunning()){
			if(this.autoUpdate == true && !this.autoUpdaterRunning){
				this.autoUpdaterRunning = true;
				Ext.TaskMgr.start({
		    	    run: function(){
						this.doAutoUpdate();
					},
				    interval: this.autoUpdateInterval,
				    scope:this
		    	});
			}
		}
	},
	stopAutoUpdater: function(){
		if(this.autoUpdaterRunning == true){
			this.autoUpdaterRunning = false;
			Ext.TaskMgr.stop({
	    	    run: function(){
					this.doAutoUpdate();
				},
			    interval: this.autoUpdateInterval,
			    scope:this
	    	});
		}
	},
	doAutoUpdate: function(){
		if(this.record && this.record.id){
			 this.loadRequest = this.recordProxy.loadRecord(this.record, {
                 scope: this,
                 success: function(record) {
                     this.record = record;
                     this.onRecordLoad();
                 }
             });
		}
	},
	printInvoices: function(){
		if(this.record.get('job_category')=='FEEINVOICE'){
			
	    	var parentJobId = this.record.get('id');
	//		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
	//		var feeYear = Ext.getCmp('fee_year').getValue();
	//		var action = Ext.getCmp('action').getValue();
			Ext.Ajax.request({
				scope: this,
				success: this.onRequestJob,
				params: {
					method: 'Membership.requestPrintInvoiceJob',
					parentJobId: parentJobId
				},
				failure: function(){
					Ext.MessageBox.show({
			            title: 'Fehler', 
			            msg: 'Es konnte kein Job erzeugt werden',
			            buttons: Ext.Msg.OK,
			            icon: Ext.MessageBox.ERROR
			        });
				}
			});
		}
    	
    },
    onRequestJob: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		this.job = new Tine.Membership.Model.Job(result, result.id);

		var id = Ext.Ajax.request({
			scope: this,
			success: this.onRequestChildJob,
			timeout:3000,
			params: {
				method: 'Membership.runJob',
				jobId: this.job.get('id')
			},
			failure: this.onRequestChildJob
		});
		
		this.transactionId = id.tId;
	}, 
	onRequestChildJob: function(){
		Ext.getCmp('subjobPanel').setActiveTab(1);
		this.jobGrid.grid.store.reload();
	},
    printVerificationList: function(){
    	if(this.record.get('job_category')=='FEEINVOICE'){
			var parentJobId = this.record.get('id');
			Ext.Ajax.request({
				scope: this,
				success: this.onRequestJob,
				timeout:360000,
				params: {
					method: 'Membership.requestPrintVerificationListJob',
					parentJobId: parentJobId
				},
				failure: function(){
					Ext.MessageBox.show({
			            title: 'Fehler', 
			            msg: 'Es konnte kein Job erzeugt werden',
			            buttons: Ext.Msg.OK,
			            icon: Ext.MessageBox.ERROR
			        });
				}
			});
		}
    },
	onAfterRender: function(){
		this.actionHistoryGrid.enable();
		this.actionHistoryGrid.loadJob(this.record);
		this.jobGrid.enable();
		this.jobGrid.loadJob(this.record);
		
		if(this.record.get('job_category') == 'FEEINVOICE'){
			this.action_printInvoice.enable();
			this.action_printVerificationList.enable();
		}
		this.createAutoUpdater();
	},
	initDependentGrids: function(){
		this.actionHistoryGrid = new Tine.Membership.ActionHistoryGridPanel({
			title:'Aktionshistorie',
			layout:'border',
			useImplicitForeignRecordFilter: true,
			disabled:true,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.jobGrid = new Tine.Membership.JobGridPanel({
			title:'Subjobs',
			layout:'border',
			useImplicitForeignRecordFilter: true,
			disabled:true,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		var editPanel = {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [	
	             // 	this.toggleAutoUpdateButton
	             //],
	             //[
					{
						fieldLabel: 'Job-Nr',
					    emptyText: '<automatisch>',
					    disabledClass: 'x-item-disabled-view',
					    id:'job_nr',
					    name:'job_nr',
					    value:null,
					    disabled:true,
					    width: 150
					},{
						fieldLabel: 'Kategorie',
						disabledClass: 'x-item-disabled-view',
						id: 'job_category',
						disabledClass: 'x-item-disabled-view',
					    disabled:true,
						name: 'job_category',
						width: 120
					}
				 ],[
					{
						fieldLabel: 'Bezeichnung1',
					    id:'job_name1',
					    name:'job_name1',
					    value:null,
					    width: 500
					} 
				 ],[
					{
						fieldLabel: 'Bezeichnung2',
					    id:'job_name2',
					    name:'job_name2',
					    value:null,
					    width: 500
					} 
				 ],[
					{
						fieldLabel: 'Status',
					    id:'job_state',
					    name:'job_state',
					    disabledClass: 'x-item-disabled-view',
					    
					    disabled:true,
					    value:null,
					    width: 130
					},{
						fieldLabel: 'Ergebnisstatus',
					    id:'job_result_state',
					    name:'job_result_state',
					    disabledClass: 'x-item-disabled-view',
					    
					    disabled:true,
					    value:null,
					    width: 130
					} 
	             ]
	        ]}]
	    };
		return [
	 			{
	 				   xtype:'panel',
	 				   layout:'border',
	 				   frame:true,
	 				   items:[
							{
								 xtype:'tabpanel',
								 id:'subjobPanel',
								 region:'south',
								 split:true,
								 activeTab:0,
								 height:340,
								 items:[
								        this.actionHistoryGrid,
								        this.jobGrid
								 ]
							 },{
	 				        	 xtype: 'panel',
	 				        	 region:'center',
	 				        	 autoScroll:true,
	 				        	 items: editPanel
	 				         }
	 				         
	 				         
	 				   ]
	 			}   
	 		];
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.JobEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 900,
        height: 600,
        name: Tine.Membership.JobEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.JobEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};