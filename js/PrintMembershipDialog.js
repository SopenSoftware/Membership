Ext.namespace('Tine.Membership');

Tine.Membership.PrintMembershipDialog = Ext.extend(Ext.form.FormPanel, {
	windowNamePrefix: 'PrintMembershipWindow_',
	//mode: 'local',
	appName: 'Membership',
	layout:'fit',
	//recordClass: Tine.Membership.Model.SoMember,
	predefinedFilter: null,
	/**
	 * {Tine.Membership.CreateTLAccountGridPanel}	positions grid
	 */
	grid: null,
	actionType: 'printMembers',
	customExportDefinition:null,
	aborted: false,
	transactionId: null,
	runJob: false,
	mainGrid: null,
	useFilter:true,
	forFeeProgress:false,
	
	/**
	 * initialize component
	 */
	initComponent: function(){
		this.formFieldMap = new Ext.util.MixedCollection();
		this.title = this.initialConfig.panelTitle;
		this.actionType = this.initialConfig.actionType;
		this.initJobPanelTpl();
		this.initActions();
		this.initToolbar();
		this.items = this.getFormItems();
		
		Tine.Membership.PrintMembershipDialog.superclass.initComponent.call(this);
		this.on('afterrender', this.onAfterRender, this);
	},
	setFilter: function(filter){
		console.log(filter);
		this.filterPanel.setValue(filter);
	},
	initActions: function(){
        this.actions_print = new Ext.Action({
            text: 'Ok',
            disabled: false,
            iconCls: 'action_applyChanges',
            handler: this.doCall,
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
	onAfterRender: function(){
		switch(this.actionType){
		
			
			
		case 'printMemberMultiLetters':
			//Ext.getCmp('filterPanel').hide();
			this.templateSelector = Ext.getCmp('multiletter_letter_template');
			this.templateSelector.on('select', this.onSelectTemplate, this);
			this.templateSelector.on('change', this.onSelectTemplate, this);
			
			/*Ext.getCmp('printMemberMainPanel').add(
				{
					xtype:'panel',
					frame:false,
					region:'south',
					forceLayout:true,
					items:[
					     this.editorContainer
					]
				}	
			);*/
			break;
			
		case 'createFeeInvoiceForSelectedMembers':
		case 'printMemberLetters':
		case 'printMemberSingleLetter':
			//Ext.getCmp('filterPanel').hide();
			
			break;
		}
		
		
	},
	onAfterRenderFilterPanel: function(){
		this.setCurrentFilter();
	},
	doCall: function(){
		this.actions_print.disable();
		switch(this.actionType){
		case 'printMembers':
			this.printMembers();
			break;
		case 'printMemberLetters':
			this.printMemberLetters();
			break;
		case 'printMemberSingleLetter':
			this.printMemberSingleLetter();
			break;
		case 'printMemberMultiLetters':
			this.printMemberMultiLetters();
			break;
		case 'printLabels':
			this.printLabels();
			break;
		case 'exportMembersAsCsv':
			this.exportMembersAsCsv();
			break;
		case 'customExport':
			this.customExport();
			break;
		case 'batchCreateFeeInvoice':
			this.batchCreateFeeInvoice();
			break;
		case 'createFeeInvoiceForSelectedMembers':
			this.createFeeInvoiceForSelectedMembers();
			break;
			
		case 'execDueTasks':
			this.execDueTasks();
			break;
		}
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
                    Ext.apply(new Ext.Button(this.actions_print), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'left',
                        arrowAlign:'right'
                    })
                ]
        });
	},
	/**
	 * save the order including positions
	 */
	printMembers: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		var win = window.open(
				Sopen.Config.runtime.requestURI + '?method=Membership.printMemberList&filters='+filterValue,
				"membersPDF",
				"menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes"
		);
	},
	
	printMemberSingleLetter: function(){
		
		// for membercard
		var method;
		var letterType = Ext.getCmp('letter_type').getValue();
		var data = {};
		if( letterType == 4){
			 method = 'Membership.printMemberCardLetter';
			 data.memberYear = Ext.getCmp('membercard_date').getValue();
			 data.reprintDate = Ext.getCmp('reprint_date').getValue();
		}else{
			return;
		}
		 
		
		var downloader = new Ext.ux.file.Download({
			timeout: 360000,
            params: {
                method: method,
                requestType: 'HTTP',
                memberIds: this.memberIds,
                data: Ext.util.JSON.encode(data)
            }
        }).start();
	},
	
	printMemberLetters: function(){
		var additionalFilter = {};
		var sort1 = Ext.getCmp('member_sortfield1').getValue();
		var dir1 = Ext.getCmp('member_sortfield1_dir').getValue();
		var sort2 = Ext.getCmp('member_sortfield2').getValue();
		var dir2 = Ext.getCmp('member_sortfield2_dir').getValue();
		var name = Ext.getCmp('multiletter_letter_name').getValue();
		var description = Ext.getCmp('multiletter_letter_description').getValue();
		var data = {
			sort: {
				fields: [ sort1, sort2],
				dir: dir1
			}	
		}
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		data.memberYear = Ext.getCmp('membercard_date').getValue();
		
		if((this.runJob === undefined) || !this.runJob){
			var downloader = new Ext.ux.file.Download({
				timeout: 360000,
	            params: {
	                method: 'Membership.printDueMemberLetters',
	                requestType: 'HTTP',
	                letterType: Ext.getCmp('letter_type').getValue(),
	                reprintDate: Ext.getCmp('reprint_date').getValue(),
	                filters: filterValue,
	                additionalFilter: Ext.util.JSON.encode(additionalFilter),
	                data: Ext.util.JSON.encode(data)
	            }
	        }).start();
		}else{
			data.letterType = Ext.getCmp('letter_type').getValue();
			data.reprintDate = Ext.getCmp('reprint_date').getValue();
			data.additionalFilter = additionalFilter;
			
			Ext.Ajax.request({
				scope: this,
				success: this.onRequestJob,
				timeout:10000,
				params: {
					method: 'Membership.requestPrintDueMemberLettersJob',
					name: name,
					description: description,
					filters: filterValue,
					/*sort: sort,
					dir: dir,
					templateId: templateId,*/
					data: Ext.util.JSON.encode(data)
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
	printMemberMultiLetters: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		var sort = Ext.getCmp('member_sortfield1').getValue();
		var dir = Ext.getCmp('member_sortfield1_dir').getValue();
		var name = Ext.getCmp('multiletter_letter_name').getValue();
		var description = Ext.getCmp('multiletter_letter_description').getValue();
		var templateId = Ext.getCmp('multiletter_letter_template').getValue();
		
		var data = {};
		this.formFieldMap.eachKey(function(id,name){
			this[name] = Ext.getCmp(id).getValue();
		},data); 
		
		if((this.runJob === undefined) || !this.runJob){
			var downloader = new Ext.ux.file.Download({
				timeout: 360000,
	            params: {
	            	method: 'Membership.requestPrintMultiLettersJob',
					name: name,
					description: description,
	            	filters: filterValue,
					sort: sort,
					dir: dir,
					templateId: templateId,
					data: Ext.util.JSON.encode(data)
	            }
	        }).start();
		}else{
			Ext.Ajax.request({
				scope: this,
				success: this.onRequestJob,
				timeout:10000,
				params: {
					method: 'Membership.requestPrintMultiLettersJob',
					name: name,
					description: description,
					filters: filterValue,
					sort: sort,
					dir: dir,
					templateId: templateId,
					data: Ext.util.JSON.encode(data)
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
	printLabels: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		
		var downloader = new Ext.ux.file.Download({
			timeout: 360000,
            params: {
                method: 'Membership.printLabels',
                requestType: 'HTTP',
                filters: filterValue
            }
        }).start();
	},
	exportMembersAsCsv: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		
		var downloader = new Ext.ux.file.Download({
			timeout: 360000,
            params: {
                method: 'Membership.exportMembersAsCsv',
                requestType: 'HTTP',
                filters: filterValue//,
            }
        }).start();
	},
	customExport: function(){
		var filters = this.filterPanel.getValue();
		filters = filters.concat(this.customExportDefinition.filters);
		var filterValue = Ext.util.JSON.encode(filters);
		var exportClassName = this.customExportDefinition.exportClassName;
		if((this.customExportDefinition.runJob === undefined) || !this.customExportDefinition.runJob){
			var downloader = new Ext.ux.file.Download({
				timeout: 360000,
	            params: {
	                method: 'Membership.exportMembersAsCustomCsv',
	                requestType: 'HTTP',
	                filters: filterValue,
	                exportClassName: exportClassName,
	                forFeeProgress:this.forFeeProgress
	            }
	        }).start();
		}else{
			Ext.Ajax.request({
				scope: this,
				success: this.onRequestJob,
				timeout:10000,
				params: {
					method: 'Membership.requestCustomExportAsCsvJob',
					filters: filterValue,
					exportClassName: exportClassName,
					jobName1: this.customExportDefinition.title,
					jobName2: '',
	                forFeeProgress:this.forFeeProgress
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
	execDueTasks: function(){

		Ext.Ajax.request({
			scope: this,
			success: this.onRequestJob,
			timeout:10000,
			params: {
				method: 'Membership.requestDueTasksJob',
				validDate: Ext.getCmp('valid_date').getValue(),
				action: Ext.getCmp('action').getValue(),
				jobName2:  Ext.getCmp('job_name2').getValue()
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
	createFeeInvoiceForSelectedMembers: function(){
		//var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		var feeYear = Ext.getCmp('fee_year').getValue();
		var dueDate = Ext.getCmp('due_date').getValue();
		var action = Ext.getCmp('action').getValue();
		Ext.Ajax.request({
			scope: this,
			success: this.onRequestJob,
			timeout:3000,
			params: {
				method: 'Membership.requestBillingJobForSelectedMembers',
				memberIds: this.selectedMemberIds,
				feeYear: feeYear,
				action: action,
				dueDate: dueDate
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
	batchCreateFeeInvoice: function(){
		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
		var feeYear = Ext.getCmp('fee_year').getValue();
		var dueDate = Ext.getCmp('due_date').getValue();
		var action = Ext.getCmp('action').getValue();
		Ext.Ajax.request({
			scope: this,
			success: this.onRequestJob,
			timeout:3000,
			params: {
				method: 'Membership.requestBillingJob',
				filters: filterValue,
				feeYear: feeYear,
				action: action,
				dueDate: dueDate
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
//		var filterValue = Ext.util.JSON.encode(this.filterPanel.getValue());
//		var feeYear = Ext.getCmp('fee_year').getValue();
//		var action = Ext.getCmp('action').getValue();
//		Ext.Ajax.request({
//			scope: this,
//			success: this.onBatchCreateFeeInvoice,
//			timeout:360000,
//			params: {
//				method: 'Membership.batchCreateFeeInvoice',
//				filters: filterValue,
//				feeYear: feeYear,
//				action: action
//			},
//			failure: function(){
//				Ext.MessageBox.show({
//		            title: 'Fehler', 
//		            msg: 'Das Erzeugen der Beitragsverläufe/Beitragsrechnungen ist fehlgeschlagen',
//		            buttons: Ext.Msg.OK,
//		            icon: Ext.MessageBox.ERROR
//		        });
//			}
//		});
	},
	onRequestJob: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		this.job = new Tine.Membership.Model.Job(result, result.id);

		this.transactionId = Ext.Ajax.request({
			scope: this,
			success: this.onRunJob,
			timeout:3000,
			params: {
				method: 'Membership.runJob',
				jobId: this.job.get('id')
			},
			failure: function(){
				this.abortJobStart();
			}
		});
		
		
	}, 
	abortJobStart: function(){
		if(this.transactionId && !this.aborted){
			this.actions_print.enable();
			Ext.Ajax.abort(this.transactionId);
			this.transactionId = null;
			this.onRunJob();
		}
	},
	onRunJob: function(){
		this.aborted = true;
		Tine.Membership.JobEditDialog.openWindow({
			autoUpdate: true,
			closeWindowOnLoad: this.window,
			record: this.job
		});
		
		this.actions_print.enable();
		//this.window.close();
		//this.actions_print.handler = this.cancel;
		
		/*Ext.TaskMgr.start({
    	    run: function(){
				this.updateJob();
			},
		    interval: 5000,
		    scope:this
    	});*/
	},
	updateJob: function(){
		/*Ext.Ajax.request({
			scope: this,
			success: this.onUpdateJob,
			timeout:10000,
			params: {
				method: 'Membership.getJob',
				id: this.job.get('id')
			},
			failure: this.onUpdateJob
		});*/
	},
	onUpdateJob: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		this.job = new Tine.Membership.Model.Job(result, result.id);
		//this.jobPanelTpl.overwrite(this.body, this.job.data);
		if(this.job.get('job_state') == 'PROCESSED'){
			Ext.TaskMgr.stop({
	    	    run: function(){
					this.updateJob();
				},
			    interval: 5000,
			    scope:this
	    	});
		}
//		console.log('on update job');
//		console.log(this.job);
	},
	onBatchCreateFeeInvoice: function(response){
		var result = Ext.util.JSON.decode(response.responseText);
		Ext.MessageBox.show({
            title: 'Beitragsrechnungen/Beitragsverläufe erzeugt', 
            msg: 'Korrekt: ' + result.info.successCount + ' Fehlerhaft: ' + result.info.failCount + '                       ',
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.INFO
        });
	},
	/**
	 * Cancel and close window
	 */
	cancel: function(){
		this.purgeListeners();
        this.window.close();
	},
	onSelectTemplate: function(){
		this.requestTemplateVars();
	},
	renderTextBlockEditors: function(results){
		
		this.editorContainer.enable();
		var formField;
		try{
			this.editorContainer.removeAll();
		}catch(e){
			
		}
		var fields = [];
		this.formFieldMap = new Ext.util.MixedCollection();
		var id;
		for(var i in results){
			if(results[i] && results[i].name){
				id = 'editor_' + results[i].name;
				formField = new Ext.form.TextArea({
	    			columnWidth: 0.9,
	    			name: results[i].name,
	    			id: id,
	    			height: 120,
	    			fieldLabel: results[i].name,
	    			value: results[i].data
	    		});
				fields.push(formField);
				this.formFieldMap.add(id, results[i].name);
			}
    	}
		var newForm = {xtype:'columnform', items:[fields]};
		
		
		this.editorContainer.add(newForm);
		
		
		//newForm.doLayout();
		this.editorContainer.doLayout();
		//Ext.getCmp('contentPanel').doLayout();
		//newForm.doLayout();
		this.editorContainer.show();
		this.editorContainer.expand();
		
		//newForm.doLayout();
		
		//this.editorContainer.doLayout();
		this.doLayout();
		//Ext.getCmp('contentPanel').doLayout();
		
		
		//Ext.getCmp('contentContainer').doLayout();
		
		
	},
	requestTemplateVars: function(){
		var templateId = this.templateSelector.getValue();
		Ext.Ajax.request({
            scope: this,
            params: {
                method: 'DocManager.getTextBlocks',
                templateId: templateId
            },
            success: function(response){
            	var results = Ext.util.JSON.decode(response.responseText);
            	this.renderTextBlockEditors(results);
            	
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
	
	/**
	 * Get form items of subclass
	 */
	getAdditionalFormItems: function(){
		return [];
		
	},
	initJobPanelTpl: function(){
		 
		this.jobPanelTpl = new Ext.XTemplate(
	            '<tpl for=".">',
       		 '<div class="contact-widget">',                
                '<div class="bordercorner_1"></div>',
                '<div class="bordercorner_2"></div>',
                '<div class="bordercorner_3"></div>',
                '<div class="bordercorner_4"></div>',
                '<div class="contact-widget-contact">',
                	'<span class="preview-panel-bold">Job-Nr:{[values.job_nr]}</span><br/>',
                    '<span class="preview-panel-bold">Job: {[values.job_name1]}</span>',
                '</div>',
                '<div class="contact-widget-address">',
	              	  'Status: {[values.job_state]}<br/>',
	                  'Ergebnis: {[values.job_result_state]}',
	              '</div>',
            '</div>',
        '</div>',
        '</tpl>' 		
        );
	},
	setCurrentFilter: function(){
		//console.log(filter);
		if(this.mainGrid){
			var filterValue = this.mainGrid.getGridFilterToolbar().getValue();
			//console.log(filterValue);
			this.filterBuffer = this.filterPanel.getValue();
			this.filterPanel.deleteAllFilters();
			this.filterPanel.setValue(filterValue);
		}
	},
	unsetCurrentFilter: function(){
		this.filterPanel.setValue(this.filterBuffer);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
		// use some fields from brevetation edit dialog
		var formItems = [
		                 {xtype:'hidden',id:'filters', name:'filters', width:1}
		];
		formItems = formItems.concat(this.getAdditionalFormItems());
		
		var panel = {
	        xtype: 'panel',
	        border: false,
	        region:'north',
	        autoHeight:true,
	        frame:true,
	        items:[{xtype:'columnform',items:[
				formItems
	        ]}]
	    };

		if(this.predefinedFilter == null){
			this.predefinedFilter = [];
		}

		if(!this.forFeeProgress){
			this.filterPanel = new Tine.widgets.form.FilterFormField({
				 	id:'fp',
				 	hidden: (!this.useFilter),
			    	filterModels: Tine.Membership.Model.SoMember.getFilterModel(),
			    	defaultFilter: 'membership_type',
			    	filters:this.predefinedFilter
			});
		}else{
			this.filterPanel = new Tine.widgets.form.FilterFormField({
			 	id:'fp',
			 	hidden: (!this.useFilter),
		    	filterModels: Tine.Membership.Model.SoMemberFeeProgress.getFilterModel(),
		    	defaultFilter: 'fee_year',
		    	filters:this.predefinedFilter
		});
		}
		this.filterPanel.on('afterrender', this.onAfterRenderFilterPanel, this);
		
		this.editorContainer = new Ext.Panel({
			frame:false,
			region:'south',
			//layout:'fit',
			//header:true,
			height:300,
			autoScroll:true,
			title:'Dokument schreiben',
			disabled:true,
			hidden:true,
			forceLayout:true,
			items:[
			       {xtype:'hidden', name:'initial'}
			]
		});
		
		//this.editorContainer.doLayout();
		
		var wrapper = {
			xtype: 'panel',
			id:'printMemberMainPanel',
			layout:'border',
			frame: true,
			items: [
			   panel,
			   {
				   xtype:'panel',
				   layout:'border',
				   region:'center',
				   items:[
						{
							xtype: 'panel',
							title: (this.useFilter?'Selektion Mitglieder':false),
							height:200,
							id:'filterPanel',
							region:'center',
							autoScroll:true,
							items: 	[this.filterPanel]
						}   
				   ]
			   }
			   ,this.editorContainer
			]
		
		};
		return wrapper;
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.PrintMembershipDialog.openWindow = function (config) {
    // TODO: this does not work here, because of missing record
	record = {};
	var id = (config.record && config.record.id) ? config.record.id : 0;
	var height = 300;
	if(config.height !== undefined){
		height = config.height;
	}
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: height,
        name: Tine.Membership.PrintMembershipDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.PrintMembershipDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};