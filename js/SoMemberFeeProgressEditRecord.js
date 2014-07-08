Ext.ns('Tine.Membership');

Tine.Membership.SoMemberFeeProgressEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'SoMemberFeeProgresstEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.SoMemberFeeProgress,
	recordProxy: Tine.Membership.soMemberFeeProgressBackend,
	loadRecord: false,
	evalGrants: false,
	initComponent: function(){
		this.initDependentGrids();
		this.action_openReceipt = new Ext.Action({
            actionType: 'edit',
            handler: this.openReceipt,
            disabled:true,
            text: 'Öffne Beitragsrechnung',
            iconCls: 'actionEdit',
            scope: this
        });
		
		this.action_reverseInvoice = new Ext.Action({
            actionType: 'edit',
            handler: this.reverseInvoice,
            disabled:true,
            text: 'Beitragsrechnung stornieren',
            iconCls: 'actionEdit',
            scope: this
        });
		
        this.openReceiptButton =  Ext.apply(new Ext.Button(this.action_openReceipt), {
			 scale: 'small',
             rowspan: 2,
             iconAlign: 'left'
        });
        this.tbar = new Ext.Toolbar();
        this.tbar.add(this.openReceiptButton);
        //this.on('afterrender', this.onAfterRender, this);
        this.on('load', this.onLoadProgress, this);
        
        Tine.Membership.SoMemberFeeProgressEditDialog.superclass.initComponent.call(this);
	},
	initDependentGrids: function(){
		this.feeVarGrid = new Tine.Membership.FeeVarGridPanel({
			title:'Berechnete Daten',
			region:'south',
			height:200,
			split:true,
			frame: true,
			app: Tine.Tinebase.appMgr.get('Membership')
		});
		this.invoicePositionGridpanel = new Tine.Billing.QuickOrderGridPanel({
			title:'Beitragsrechnung',
			app: this.app,
			perspective:'receipt',
			storeAtOnce: true,
			region:'center',
			layout:'fit'
		});
	},
	onLoadProgress: function(){
		if(this.record.get('invoice_receipt_id')){
			this.openReceiptButton.enable();
		}
		this.feeVarGrid.loadFeeProgress(this.record);
		var receipt = this.record.getForeignRecord(Tine.Billing.Model.Receipt, 'invoice_receipt_id');
		if(receipt){
			var order = this.record.getForeignRecord(Tine.Billing.Model.Order, 'order_id');
			debitor = order.getForeignRecord(Tine.Billing.Model.Debitor, 'debitor_id');
			this.invoicePositionGridpanel.loadDebitorData({
				debitorRecord: debitor,
				contactRecord: null,
				orderRecord: order,
				receiptRecord: receipt
			});
		}
	},
	openReceipt: function(){
		if(this.record.get('invoice_receipt_id')){
			var win = Tine.Billing.InvoiceEditDialog.openWindow({
	    		record: this.record.getForeignRecord(Tine.Billing.Model.Receipt, 'invoice_receipt_id')
			});
		}
	},
	reverseInvoice: function(){
		if(this.record.get('invoice_receipt_id')){
			this.grid.reverseInvoice(
				record.getForeignId('member_id'),
				record.get('id'),
				record.getForeignId('invoice_receipt_id')
			);
		}
	},
	getFormItems: function(){
		return [
 			{
 				   xtype:'panel',
 				   layout:'border',
 				   frame:true,
 				   items:[
						{
							 xtype:'tabpanel',
							 region:'south',
							 split:true,
							 activeTab:0,
							 items:[
							        this.feeVarGrid,
							        this.invoicePositionGridpanel
							 ]
						 },{
 				        	 xtype: 'panel',
 				        	 region:'center',
 				        	 autoScroll:true,
 				        	 items: Tine.Membership.getSoMemberFeeProgressEditPanel()
 				         }
 				         
 				         
 				   ]
 			}   
 		];
	}
});

Tine.Membership.SoMemberFeeProgressEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 750,
        name: Tine.Membership.SoMemberFeeProgressEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.SoMemberFeeProgressEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

Tine.Membership.SoMemberFeeProgressEditRecord =  Ext.extend(Tine.widgets.dialog.DependentEditForm, {
	id: 'sopen-Membership-soMemberFeeProgress-edit-record-form',
	className: 'Tine.Membership.SoMemberFeeProgressEditRecord',
	key: 'SoMemberFeeProgressEditRecord',
	recordArray: Tine.Membership.Model.SoMemberFeeProgressArray,
	recordCollection: null,
	recordClass: Tine.Membership.Model.SoMemberFeeProgress,
    recordProxy: Tine.Membership.soMemberFeeProgressBackend,
    parentRecordClass: Tine.Membership.Model.SoMember,
    parentRelation: {
		fkey: 'member_id',
		references: 'id'
	},
    gridPanelClass: Tine.Membership.SoMemberFeeProgressGridPanelNested,
	formFieldPrefix: 'feeprogress_',
	useButtons:false,
	// TODO: dirty, id bound to form definition
	formPanelToolbarId: 'donator-soMemberFeeProgress-edit-dialog-panel-toolbar',
	initComponent: function(){
		this.app = Tine.Tinebase.appMgr.get('Membership');
		this.recordArray = Tine.Membership.Model.SoMemberFeeProgress.getFieldDefinitions();
		this.parentRecordClass = Tine.Membership.Model.SoMember;
		this.gridPanelClass = Tine.Membership.SoMemberFeeProgressGridPanelNested;
		//Ext.apply(this);
		this.parentRelation = {
			fkey: 'member_id',
			references: 'id'
		};
		Tine.Membership.SoMemberFeeProgressEditRecord.superclass.initComponent.call(this);
	},
	exchangeEvents: function(observable){
		this.checkObservableBreak(observable);
		switch(observable.className){
		case 'Tine.Membership.SoMemberEditRecord':
			observable.on('applychanges',this.handlerApplyChanges,this);
			observable.on('loadform', this.onLoadParent, this);
			observable.on('initeditmode', this.onDependentEditing, this);
			observable.on('initviewmode', this.onDependentEndEditing, this);
			observable.on('cancel',this.handlerCancel, this);
			observable.exchangeEvents(this);
			return true;
		}
		return false;
	},
	getFormContents: function(){
		return Tine.Membership.getSoMemberFeeProgressEditDialogPanelNested(this.getComponents());
	}
});

Tine.Membership.getSoMemberFeeProgressEditRecordAsTab = function(){
	return new Tine.Membership.SoMemberFeeProgressEditRecord (
		{
			title: 'Beitragsdaten',
			withFilterToolbar: true,
			useGrid:true
		}
	);
};

Tine.Membership.getSoMemberFeeProgressEditRecordPanel = function(){
	return new Tine.Membership.SoMemberFeeProgressEditRecord(
			{
				title: null,
				header: false,
				bodyStyle:'padding:0'
			}
	);
};

Tine.Membership.getSoMemberFeeProgressEditDialogPanelNested = function(components){
	return [{
		xtype:'panel',
		layout:'fit',
		id: 'brevetation-soMemberFeeProgress-main-content-panel',
		//frame:true,
		border:false,
		items: [{
    	   xtype:'panel',
    	   header:false,
    	   border:false,
    	   //frame:true,
    	   layout:'border',
    	   items:[{
	    	   xtype:'panel',
	    	   region:'center',
	    	   header:false,
	    	   border:false,
	    	   frame:true,
	    	   layout:'fit',
	    	   items:[Tine.Membership.getSoMemberFeeProgressEditPanelEmbedded()]
	       },{
	    	   xtype:'panel',
	    	   region:'north',
	    	   height:220,
	    	   collapsible:true,
	    	   collapseMode:'mini',
	    	   split:true,
	    	   layout:'fit',
	    	   items:[ components.grid.grid ]
	       }]
		}]
	}];
};

Tine.Membership.getSoMemberFeeProgressEditPanelEmbedded = function(){
	return {
		xtype: 'panel',
		id: 'Membership-soMemberFeeProgress-edit-dialog-panel',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		layout:'border',
		defferedRender:true,
		//forceLayout:true,
		items:[
			/*{
				xtype: 'panel',
				id: 'donator-soMemberFeeProgress-edit-dialog-panel-toolbar',
				height: 26,
				layout:'fit',
				region:'north',
				tbar: new Ext.Toolbar({id:'donator-soMemberFeeProgress-edit-dialog-panel-toolbar-tb'})
			},*/{
				xtype:'panel',
				region:'center',
				layout:'fit',
				autoScroll: true,
				border:false,
				width: 400,
				tbar: new Ext.Toolbar({id:'donator-soMemberFeeProgress-edit-dialog-panel-toolbar-tb',height:26}),
				/*defaults: {
			        xtype: 'fieldset',
			        // -> never: IE killer
			        //autoHeight: 'auto',
			        layout:'fit',
			        defaultType: 'textfield'
			    },*/
			    items: Tine.Membership.getSoMemberFeeProgressFormItems()
		}]};
}

Tine.Membership.getSoMemberFeeProgressEditPanel = function(){
	return {
		xtype: 'panel',
		id: 'Membership-soMemberFeeProgress-edit-dialog-panel',
		region:'center',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		//layout:'border',
		//defferedRender:true,
		autoScroll:true,
		items:[
		       Tine.Membership.getSoMemberFeeProgressFormItems()
//		       {
//			xtype:'panel',
//			region:'center',
//			border:false,
//			items: Tine.Membership.getSoMemberFeeProgressFormItems()
//		}
		       ]};
}

Tine.Membership.getSoMemberFeeProgressFormItems = function(){
	return [{
		xtype:'panel',
		/*defaults: {
		    disabledClass: 'x-item-disabled-view'
		},*/
		items:[
		   {xtype:'columnform',border:false,items:[[
		   {xtype: 'hidden',id:'feeprogress_id',name:'id'}
		   ],[
		      new Tine.Tinebase.widgets.form.RecordPickerComboBox({
		        fieldLabel: 'Mitglied',
		        disabledClass: 'x-item-disabled-view',
		        id:'feeprogress_member_id',
		        name: 'member_id',
		        blurOnSelect: true,
		        recordClass: Tine.Membership.Model.SoMember,
		        width: 300,
		        allowBlank:false
		    })
		  ],[  
			{
				    fieldLabel: 'Mitglied-Nummer',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_member_nr',
				    name:'member_nr',
				    value:null,
				    width: 150
				},
			    {
				    fieldLabel: 'Nummer in Beitragsperiode',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    disabled: true,
				    id:'feeprogress_progress_nr',
				    name:'progress_nr',
				    value:null,
				    width: 200
				}
			],[
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'feeprogress_is_calculation_approved',
					name: 'is_calculation_approved',
					hideLabel:true,
					boxLabel: 'Freigabe Beitragsberechnung',
				    width:200
				},
				Tine.Membership.Custom.getRecordPicker('FeeGroup','feeprogress_fee_group_id',{
					//disabledClass: 'x-item-disabled-view',
					width: 200,
					fieldLabel: 'Beitragsgruppe',
				    name:'fee_group_id',
				    onAddEditable: true,
				    onEditEditable: false,
				    blurOnSelect: true,
				    allowBlank:true
				})
//			],[
//				Tine.Membership.Custom.getRecordPicker('SoMember','feeprogress_parent_member_id',{
//					//disabledClass: 'x-item-disabled-view',
//					width: 300,
//					fieldLabel: 'Übergeordnete Mitgliedschaft',
//				    name:'parent_member_id',
//				    disabled: true,
//				   // displayField: 'org_name',
//				    onAddEditable: true,
//				    onEditEditable: false,
//				    blurOnSelect: true,
//				    allowBlank:true,
//				    ddConfig:{
//				    	ddGroup: 'ddGroupContact'
//				    }
//				})
			],[
				{
				   	xtype: 'datefield',
				    disabledClass: 'x-item-disabled-view',
				    fieldLabel: 'Beitrag von', 
				    id:'feeprogress_fee_from_datetime',
				    name:'fee_from_datetime',
				    width: 150
				},{
					xtype:'datefield',
					disabledClass: 'x-item-disabled-view',
				    fieldLabel: 'Beitrag bis',
				    id:'feeprogress_fee_to_datetime',
				    name:'fee_to_datetime',
				    width: 150
				},{
					fieldLabel: 'Beitragsjahr',
					disabledClass: 'x-item-disabled-view',
					id:'feeprogress_fee_year',
					name:'fee_year',
					width: 150
				}
			],[
				{
					xtype: 'sopencurrencyfield',
			    	fieldLabel: 'indiv. Jahresbeitr.', 
			    	id:'feeprogress_individual_yearly_fee',
					name:'individual_yearly_fee',
					width: 150,
					disabledClass: 'x-item-disabled-view',
			    	blurOnSelect: true
				},{
					xtype:'extuxclearabledatefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Beitr.berechn. berücksicht.',
					id:'feeprogress_fee_calc_datetime',
					name:'fee_calc_datetime',
					width: 150
				},{
					fieldLabel: 'Alter am Stichtag (Jahre)',
					disabledClass: 'x-item-disabled-view',
					id:'feeprogress_age',
					name:'age',
					width: 150
				}
			],[
				{
					fieldLabel: 'Bemerkung zur Beitragsperiode',
					disabledClass: 'x-item-disabled-view',
					id:'feeprogress_fee_period_notes',
					name:'fee_period_notes',
					width: 300,
					height:100
				}
			],[
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'feeprogress_is_first',
					name: 'is_first',
					hideLabel:true,
				    boxLabel: 'ist Erster',
				    width:150
				},{
					fieldLabel: 'Faktor(anteilig)',
					disabledClass: 'x-item-disabled-view',
					id:'feeprogress_fee_units',
					name:'fee_units',
					width: 150
				}
			],[	
				{
					xtype: 'sopencurrencyfield',
					fieldLabel: 'Aufnahmegebühr', 
				    id:'feeprogress_amount_admission_fee',
				    name:'amount_admission_fee',
					disabledClass: 'x-item-disabled-view',
					blurOnSelect: true,
					width:150
				},{
					xtype: 'sopencurrencyfield',
					fieldLabel: 'Zahlsaldo vor Sollst.', 
				    id:'feeprogress_deb_summation',
				    name:'deb_summation',
					disabledClass: 'x-item-disabled-view',
					blurOnSelect: true,
					disabled:true,
					width:150
				}
				],[	
					{
						xtype: 'sopencurrencyfield',
						fieldLabel: 'Vorschau Beitrag', 
					    id:'feeprogress_fee_to_calculate',
					    name:'fee_to_calculate',
						disabledClass: 'x-item-disabled-view',
						blurOnSelect: true,
						disabled:true,
						width:150
					},{
						xtype: 'sopencurrencyfield',
						fieldLabel: 'Sollgest. Beitrag', 
					    id:'feeprogress_sum_brutto',
					    name:'sum_brutto',
						disabledClass: 'x-item-disabled-view',
						blurOnSelect: true,
						disabled:true,
						width:150
					}
				],[	
					{
					    fieldLabel: 'Zahlungsstatus',
					    disabledClass: 'x-item-disabled-view',
					    id:'feeprogress_payment_state',
					    name:'payment_state',
					    width: 200,
					    xtype:'combo',
					    store:[['NOTDUE','noch nicht fällig'],['TOBEPAYED','unbezahlt'],['PARTLYPAYED','teilbezahlt'],['PAYED','bezahlt']],
					    value: 'NOTDUE',
						mode: 'local',
						displayField: 'name',
					    valueField: 'id',
					    triggerAction: 'all'
					}
			]]} 
	]}];
}