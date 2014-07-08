Ext.ns('Tine.Membership');

Tine.Membership.FeeProgressEditRecord = Ext.extend(Ext.Panel, {
	id: 'sopen-membership-feeprogress-edit-record-form',
	className: 'Tine.Membership.FeeProgressEditRecord',
	recordArray: Tine.Membership.Model.SoMemberFeeProgressArray,
	recordCollection: null,
	recordClass: Tine.Membership.Model.SoMemberFeeProgress,
    recordProxy: Tine.Membership.memberFeeProgressBackend,
	record: null,
	memberRecord:null,
	record: null,
	editing: false,
	formFieldPrefix: 'feeprogress_',
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
    initComponent: function(){
    	this.addEvents(
    		'addfeeprogress',
    		'editfeeprogress',
    		'showfeeprogress',
			'beforesavefeeprogress',
			'savefeeprogress',
			'feeprogressviewmode'
		);
    	this.record = new this.recordClass(this.recordClass.getDefaultData(), 0);
    	this.feeProgressGrid = new Tine.Membership.FeeProgressGridPanel(
		{
			formId: this.id,
			recordClass: this.recordClass,
			recordProxy: this.recordProxy
		});
		this.initFormItems();
		
		this.feeProgressGrid.on('addfeeprogress',this.onAddFeeProgress,this);
    	this.feeProgressGrid.on('editfeeprogress',this.onEditFeeProgress,this);
    	this.feeProgressGrid.on('showfeeprogress',this.onShowFeeProgress,this);
    	
    	this.feeProgressExtPanel = Tine.Membership.getFeeProgressExtEditRecordPanel();
    	this.feeProgressExtPanel.exchangeEvents(this);
    	
    	this.initButtons();
    	this.items = this.getFormContents();
    	this.on('afterrender',this.onAfterRender,this);
		Tine.Membership.FeeProgressEditRecord.superclass.initComponent.call(this);
	},
	initFormItems: function(){
		this.recordCollection = new Ext.util.MixedCollection();
		this.recordCollection.addAll(this.recordArray);
	},
	onAfterRender: function(){
		this.setViewMode();
	},
	onMemberViewMode: function(){
		this.setViewMode();
	},
	setViewMode: function(){
		this.editing = false;
		this.recordCollection.each(
			function(item){
				Ext.getCmp(this.formFieldPrefix+item.name).disable();
			},this
		);
		this.fireEvent('feeprogressviewmode');
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
	exchangeEvents: function(observable){
		if((typeof(observable)!='object') || (observable.className === undefined)){
			return false;
		}
		switch(observable.className){
		case 'Tine.Membership.EditRecord':
			observable.on('savemember',this.onSaveMember, this);
			observable.on('cancelmember',this.onCancelMember, this);
			observable.on('memberviewmode',this.onMemberViewMode, this);
			observable.on('loadmember',this.load, this);
			observable.exchangeEvents(this);
			return true;
		}
		return false;
	},
	onSaveMember: function( memberRecord ){
		this.handlerApplyChanges();
	},
	onCancelMember: function(){
		this.feeProgressGrid.enable();
		this.clearForm();
		this.setViewMode();
	},
	initButtons: function(){
		this.buttons = [];
	},
	
	handlerApplyChanges: function(){
		if(this.editing){
			this.updateRecord();
			this.saveRecord();
		}
		this.feeProgressGrid.enable();
	},
	
	handlerCancel: function(){
		this.clearForm();
		this.setViewMode();
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
                        this.loadRecord(record);
                       // this.fireEvent('update', Ext.util.JSON.encode(this.record.data));
                    },
                    failure: this.onRequestFailed,
                    timeout: 150000 // 3 minutes
                });
            } else {
                this.loadRecord(this.record);
                this.fireEvent('update', Ext.util.JSON.encode(this.record.data));
            }
        } else {
            Ext.MessageBox.alert(_('Errors'), _('Please fix the errors noted.'));
        }
    },
	
	load: function(member){
		this.memberRecord = new Tine.Membership.Model.SoMember(member.data,member.data.id);
		this.feeProgressGrid.loadStore(this.memberRecord);
	},
	
	/**
	 * on add handler (event fired by this.memberGrid)
	 * new record 
	 * @param: {Tine.Membership.Model.SoMember} new record 
	 */
	onAddFeeProgress: function(record){
		record.data.fee_progress_ext_id = new Tine.Membership.Model.SoMemberFeeProgressExt({},0);
		this.feeProgressGrid.disable();
		this.setEditMode();
		//this.historyPanel.disable();
		this.loadRecord(record);
		this.fireEvent('addfeeprogress',this.record.data);
	},
	onShowFeeProgress: function(record){
		this.setViewMode();
		this.loadRecord(record);
		this.fireEvent('showfeeprogress',this.record.data);
	},
	/**
	 * on edit handler (event fired by this.memberGrid)
	 * selected record
	 * @param: {Tine.Membership.Model.SoMember} selected record
	 */
	onEditFeeProgress: function(record){
		this.feeProgressGrid.disable();
		this.loadRecord(record);
		this.setEditMode();
		this.fireEvent('editfeeprogress',record);
	},
	
	loadRecord: function(record){
		this.record = record;
		this.loadForm();
	},
	
	loadForm: function(){
		var att;
		var value;
		var field;
		for(var i in Tine.Membership.Model.SoMemberFeeProgressArray){
			att = Tine.Membership.Model.SoMemberFeeProgressArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = this.record.data[att.name];
				
				if(att.type == 'date'){
					value = (value ? Date.parseDate(value, att.dateFormat): value);
				}
				if(value){
					this.loadField('feeprogress_'+att.name,value);
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
		for(var i in Tine.Membership.Model.SoMemberFeeProgressArray){
			att = Tine.Membership.Model.SoMemberFeeProgressArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = Ext.getCmp('feeprogress_'+att.name).getValue();
				if(value){
					this.record.data[att.name] = value;
				}
			}
		}
		this.record.set('member_id',this.memberRecord.get('id'));
	},
	
	getFormContents: function(){
		return Tine.Membership.getFeeProgressEditDialogPanel(this.feeProgressGrid, this.feeProgressExtPanel);
	},
	
    isValid: function() {
    	this._isValid = true;
    	this.recordCollection.each(
			function(item){
				if(Ext.getCmp(this.formFieldPrefix+item.name).validate()==false){
					this._isValid = false;
				}
			},this
		);
		return this._isValid;
	},
    
    /**
     * generic request exception handler
     * 
     * @param {Object} exception
     */
    onRequestFailed: function(exception) {
        Tine.Tinebase.ExceptionHandler.handleRequestException(exception);
    }
});

Tine.Membership.getFeeProgressEditRecordAsTab = function(closable){
	return new Tine.Membership.FeeProgressEditRecord (
		{
			title: 'Beitragsverlauf'
		}
	);
};

Tine.Membership.getFeeProgressEditDialogPanel = function(feeProgressGrid, feeProgressExtPanel){
	var editPanel = {
		xtype: 'panel',
		id: 'membership-feeprogress-edit-dialog-panel',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		layout:'border',
		defferedRender:true,
		items:[{
			xtype:'panel',
			title:'Zusatzdaten',
			width:480,
			border:false,
			region:'east',
			layout:'fit',
			split:true,
			autoScroll: true,
			collapsible:true,
			collapsed:false,
			items:[
			       feeProgressExtPanel
			]
		},{
			xtype:'panel',
			region:'center',
			layout:'fit',
			autoScroll: true,
			border:false,
			width: 400,
			defaults: {
		        xtype: 'fieldset',
		        autoHeight: 'auto',
		        layout:'fit',
		        defaultType: 'textfield'
		    },
		    items:[
		       {title:'',checkboxToggle:false,border:false,items:[{xtype:'columnform',border:false,items:[[
   		       {xtype: 'hidden',id:'feeprogress_id',name:'id'},
   		       {xtype: 'hidden',id:'feeprogress_member_id',name:'member_id'},
   		       {xtype: 'hidden',id:'feeprogress_fee_progress_ext_id',name:'member_id'},
               {
				    fieldLabel: 'Mitglied-Nummer',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_member_nr',
				    name:'member_nr',
				    value:null,
				    width: 150
				},
				Tine.Membership.Custom.getRecordPicker('FeeGroup','membership_fee_group_id',{
					//disabledClass: 'x-item-disabled-view',
					width: 200,
					fieldLabel: 'Beitragsgruppe',
				    name:'fee_group_id',
				    onAddEditable: true,
				    onEditEditable: false,
				    blurOnSelect: true,
				    allowBlank:true
				})
			 ],[
				{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'feeprogress_is_calculation_approved',
					name: 'is_calculation_approved',
					hideLabel:true,
					disabled:true,
				    boxLabel: 'Freigabe Beitragsberechnung',
				    width:200
				}
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
				}
			],[
				{
					fieldLabel: 'Beitragsjahr',
					disabledClass: 'x-item-disabled-view',
					id:'feeprogress_fee_year',
					name:'fee_year',
					width: 150
				},{
					xtype:'datefield',
					disabledClass: 'x-item-disabled-view',
					fieldLabel: 'Beitr.berechn. berücksicht.',
					id:'feeprogress_fee_calc_datetime',
					name:'fee_calc_datetime',
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
			 ]       
	]}]}]}]};

	
	return [ 
		{
			xtype:'panel',
			layout:'fit',
			id: 'member-feeprogress-main-content-panel',
			frame:true,
			border:false,
			items: [
			{
  			    xtype: 'tabpanel',
			    border: false,
			    plain:true,
			    layoutOnTabChange: true,
			    activeTab: 0,
			    items:[
		           {
			    	   xtype:'panel',
			    	   title:'Verlaufsdaten',
			    	   border:false,
			    	   frame:true,
			    	   layout:'border',
			    	   items:[{
				    	   xtype:'panel',
				    	   region:'center',
				    	   header:false,
				    	   //title:'Mitgliedschaft',
				    	   border:false,
				    	   frame:true,
				    	   layout:'fit',
				    	   items:[editPanel]
				       },{
				    	   xtype:'panel',
				    	   region:'north',
				    	   //title:'Alle Mitgliedschaften',
				    	   height:140,
				    	   collapsible:true,
				    	   collapseMode:'mini',
				    	   split:true,
				    	   layout:'fit',
				    	   items:[ feeProgressGrid ]
				       }]
			       },{
			    	   xtype:'panel',
			    	   title:'Historie',
			    	   border:false,
			    	   layout:'fit',
			    	   items:[]
			       }
			    ]
			}
		]
	}];

};

