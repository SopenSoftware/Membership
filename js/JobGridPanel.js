Ext.namespace('Tine.Membership');


Tine.Membership.getJobStatusIcon = function(value, meta, record){
	var qtip, icon;
	var state = record.get('job_state');
	switch(state){
	case 'RUNNING':
		qtip = 'Wird ausgeführt';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'ajax-loader.gif';
	
		break;
	case 'TOBEPROCESSED':
		qtip = 'Warten';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'gg_offline.png';
	
		break;
	case 'PROCESSED':
		qtip = 'Ausgeführt';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'greenled.png';
	
		break;
	case 'ABANDONED':
	case 'USERCANCELLED':
		qtip = 'Abgebrochen';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'agt_stop.png';
	
		break;

	}
	return '<img class="TasksMainGridStatus" src="' + icon + '" ext:qtip="' + qtip + '">';
};

Tine.Membership.getJobResultStatusIcon = function(value, meta, record){
	var qtip, icon;
	var state = record.get('job_result_state');
	switch(state){
	case 'UNDEFINED':
		qtip = 'undefiniert';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'status_unknown.png';
	
		break;
	case 'OK':
		qtip = 'Ok';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'agt_action_success.png';
	
		break;
	case 'PARTLYERROR':
	case 'ERROR':
		qtip = 'Fehler';
		icon = Sopen.Config.runtime.resourceUrl.tine.images + 'agt_action_fail.png';
	
		break;

	}
	return '<img class="TasksMainGridStatus" src="' + icon + '" ext:qtip="' + qtip + '">';
};


/**
 * Timeaccount grid panel
 */
Tine.Membership.JobGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'membership-job-grid-panl',
    recordClass: Tine.Membership.Model.Job,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'job_nr', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    useImplicitForeignRecordFilter: false,
    jobRecord: null,
    crud:{
    	_add:false,
    	_edit:true,
    	_delete:false,
    	_copy:false
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.jobBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Membership.JobGridPanel.superclass.initComponent.call(this);
    },
    initActions: function(){
    	this.actions_downloadPrintedDocument = new Ext.Action({
            text: 'Dokument herunterladen',
			//disabled: true,
            handler: this.downloadPrintedDocument,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updatePrintBilling
        });
    	
    	this.actions_downloadErrorLog = new Ext.Action({
            text: 'Logdatei herunterladen',
			//disabled: true,
            handler: this.downloadErrorLog,
            iconCls: 'action_exportAsCsv',
            scope: this,
            actionUpdater: this.updatePrintBilling
        });
    	
    	this.actions_printInvoices = new Ext.Action({
            text: 'Rechnungen drucken',
			//disabled: true,
            handler: this.printInvoices,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updatePrintBilling
        });
    	
    	this.actions_printVerficationList = new Ext.Action({
            text: 'Nachweisliste drucken',
			//disabled: true,
            handler: this.printVerificationList,
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updatePrintBilling
        });
    	
        this.actions_printJobs = new Ext.Action({
        	allowMultiple: false,
        	//disabled:true,
            text: 'Druckaufträge',
            iconCls: 'action_exportAsPdf',
            scope: this,
            actionUpdater: this.updatePrintJobs,
            menu:{
            	items:[
            	       this.actions_printInvoices,
            	       this.actions_printVerficationList
		    	]
            }
        });
        this.actionUpdater.addActions([
           this.updatePrintJobs,
           this.updatePrintBilling
        ]);
        this.supr().initActions.call(this);
    },
    printInvoices: function(){
    	var selectedRows = this.getGrid().getSelectionModel().getSelections();
    	var parentJobId = selectedRows[0].id;
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
    },
    printVerificationList: function(){
    	var selectedRows = this.getGrid().getSelectionModel().getSelections();
    	var parentJobId = selectedRows[0].id;
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
    },
    downloadPrintedDocument: function(){
    	var selectedRows = this.getGrid().getSelectionModel().getSelections();
    	var jobId = selectedRows[0].id;
    	
    	var jobCategory = selectedRows[0].data.job_category;
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
    downloadErrorLog: function(){
    	var selectedRows = this.getGrid().getSelectionModel().getSelections();
    	var jobId = selectedRows[0].id;
    	
    	var jobCategory = selectedRows[0].data.job_category;
    	var params;
    	switch(jobCategory){
    	case 'MANUALEXPORT':
    		params = {
                method: 'Membership.downloadJobErrorFile',
                customExportCsvJobId: jobId
            };
    		break;
    	default:
    		return;
    	}
		var downloader = new Ext.ux.file.Download({
            params: params
        }).start();
    },
    
    onRequestJob: function(response){
    	var result = Ext.util.JSON.decode(response.responseText);
		this.job = new Tine.Membership.Model.Job(result, result.id);

		Ext.Ajax.request({
			scope: this,
			success: this.onRunJob,
			timeout:6000,
			params: {
				method: 'Membership.runJob',
				jobId: this.job.get('id')
			},
			failure: this.onRunJob
		});
    },
    onRunJob: function(){
    	this.grid.getStore().reload();
    },
    getActionToolbarItems: function() {
    	return [
			Ext.apply(new Ext.Button(this.actions_printJobs), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                iconCls: 'action_exportAsPdf'
            })
        ];
    },
    
    getContextMenuItems: function(){
    	return [
			'-',
			this.actions_downloadPrintedDocument     
    	];
    },
    
    updatePrintJobs: function(action, grants, records) {
    	action.setDisabled(true);
        if (records.length == 1) {
            var obj = records[0];
            if (! obj) {
                return false;
            }
            action.setDisabled(false);
        }
    },
    updatePrintBilling: function(action, grants, records) {
    	action.setDisabled(true);
    	try{
	        if (records.length == 1) {
	            var obj = records[0];
	            if (! obj) {
	                return false;
	            }
	            if(obj.get('job_type')=='FEEINVOICE'){
	            	action.setDisabled(false);
	            }
	        }
    	}catch(e){
    		action.setDisabled(true);
    	}
    },
    initFilterToolbar: function() {
    	var plugins = [];
    	if(!this.useImplicitForeignRecordFilter){
    		plugins = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
    	}
		
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.Job.getFilterModel(),
            defaultFilter: 'query',
            filters: [{field:'query',operator:'contains',value:''}],
            plugins: plugins
        });
    },  
    
	getColumns: function() {
		return [
		   { id: 'job_nr', header: this.app.i18n._('Job-Nr'), dataIndex: 'job_nr', sortable:true },		               
		   { id: 'job_name1', header: this.app.i18n._('Bezeichnung1'), dataIndex: 'job_name1', sortable:false },
		   { id: 'job_name2', header: this.app.i18n._('Bezeichnung2'), dataIndex: 'job_name2', sortable:false },
		   { id: 'job_category', header: this.app.i18n._('Art'), dataIndex: 'job_category', sortable:false },
		   { id: 'job_type', header: this.app.i18n._('Typ'), dataIndex: 'job_type', sortable:false },
		   { id: 'job_data', header: this.app.i18n._('Daten'), dataIndex: 'job_data', sortable:false },
		   { id: 'job_state', header: this.app.i18n._('Status'), dataIndex: 'job_state', sortable:false, renderer: Tine.Membership.getJobStatusIcon },
		   { id: 'job_result_state', header: this.app.i18n._('Ergebnisstatus'), dataIndex: 'job_result_state', sortable:false, renderer: Tine.Membership.getJobResultStatusIcon },
		   { id: 'on_error', header: this.app.i18n._('bei Fehler'), dataIndex: 'on_error', sortable:false },
		   {
	            id: 'process_percentage',
	            header: 'fertig %',
	            width: 50,
	            dataIndex: 'process_percentage',
	            renderer: Ext.ux.PercentRenderer
		   },
		   { id: 'process_info', header: this.app.i18n._('Info'), dataIndex: 'process_info', sortable:false },
		   { id: 'error_info', header: this.app.i18n._('Fehlerinfo'), dataIndex: 'error_info', sortable:false },
		   { id: 'ok_count', header: this.app.i18n._('Anz. OK'), dataIndex: 'ok_count', sortable:false },
		   { id: 'error_count', header: this.app.i18n._('Anz. Fehler'), dataIndex: 'error_count', sortable:false },
		   { id: 'schedule_datetime', header: this.app.i18n._('geplant am'), dataIndex: 'schedule_datetime', renderer:Tine.Tinebase.common.dateTimeRenderer, sortable:false },
		   { id: 'start_datetime', header: this.app.i18n._('gestartet'), dataIndex: 'start_datetime', renderer:Tine.Tinebase.common.dateTimeRenderer, sortable:true },
		   { id: 'end_datetime', header: this.app.i18n._('beendet am'), dataIndex: 'end_datetime', renderer:Tine.Tinebase.common.dateTimeRenderer, sortable:true },
		   { id: 'account_id', header: this.app.i18n._('Benutzer'), dataIndex: 'account_id',renderer:Tine.Membership.renderer.contactRenderer, sortable:true  },
		   { id: 'modified_datetime', header: this.app.i18n._('zuletzt geändert'), dataIndex: 'modified_datetime', renderer:Tine.Tinebase.common.dateTimeRenderer, sortable:true },
		   { id: 'task_count', header: this.app.i18n._('Aufgaben gesamt'), dataIndex: 'task_count'}
		   
        ];
	},
	// load job in order to be able to retrieve subjobs
	loadJob: function(jobRecord){
    	this.jobRecord = jobRecord;
    	this.store.reload();
    },
	 onStoreBeforeload: function(store, options) {
	    	Tine.Membership.JobGridPanel.superclass.onStoreBeforeload.call(this, store, options);
	    	if(!this.useImplicitForeignRecordFilter){
	    		return true;
	    	}
	    	
	    	if(!this.jobRecord){
	    		return true;
	    	}
	    	
	    	var filter = {
        		field: 'job_id',
        		operator: 'equals',
        		value: this.jobRecord.get('id')
        	};
	    	options.params.filter.push(filter);
	    }
});