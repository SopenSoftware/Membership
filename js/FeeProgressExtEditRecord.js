Ext.ns('Tine.Membership');

Tine.Membership.FeeProgressExtEditRecord = Ext.extend(Ext.Panel, {
	id: 'sopen-membership-feeprogress-ext-edit-record-form',
	className: 'Tine.Membership.FeeProgressExtEditRecord',
	recordArray: Tine.Membership.Model.SoMemberFeeProgressExtArray,
	recordCollection: null,
	recordClass: Tine.Membership.Model.SoMemberFeeProgressExt,
    recordProxy: Tine.Membership.memberFeeProgressExtBackend,
	record: null,
	feeProgressRecord:null,
	record: null,
	editing: false,
	formFieldPrefix: 'feeprogress_ext_',
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
    	this.record = new this.recordClass(this.recordClass.getDefaultData(), 0);
    	this.initFormItems();
		this.items = this.getFormContents();
    	this.on('afterrender',this.onAfterRender,this);
		Tine.Membership.FeeProgressExtEditRecord.superclass.initComponent.call(this);
	},
	initFormItems: function(){
		this.recordCollection = new Ext.util.MixedCollection();
		this.recordCollection.addAll(this.recordArray);
	},
	onAfterRender: function(){
		this.setViewMode();
	},
	onFeeProgressViewMode: function(){
		this.setViewMode();
	},
	setViewMode: function(){
		this.editing = false;
		this.recordCollection.each(
			function(item){
				Ext.getCmp(this.formFieldPrefix+item.name).disable();
			},this
		);
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
		case 'Tine.Membership.FeeProgressEditRecord':
			observable.on('savefeeprogress',this.onSaveFeeProgress, this);
			observable.on('cancelfeeprogress',this.onCancelFeeProgress, this);
			observable.on('addfeeprogress',this.onAddFeeProgress, this);
			observable.on('editfeeprogress',this.onEditFeeProgress, this);
			observable.on('showfeeprogress',this.onShowFeeProgress, this);
			return true;
		}
		return false;
	},
	onSaveFeeProgress: function( feeProgressRecord ){
		this.handlerApplyChanges();
	},
	onCancelFeeProgress: function(){
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
	
	load: function(feeProgress){
		this.feeProgressRecord = new Tine.Membership.Model.SoMemberFeeProgress(feeProgress.data,feeProgress.data.id);
	},
	
	/**
	 * on add handler (event fired by this.memberGrid)
	 * new record 
	 * @param: {Tine.Membership.Model.SoMember} new record 
	 */
	onAddFeeProgress: function(record){
		
		this.setEditMode();
		//this.historyPanel.disable();
		this.loadRecord(record);
	},
	onShowFeeProgress: function(record){
		this.setViewMode();
		this.loadRecord(record);
	},
	/**
	 * on edit handler (event fired by this.memberGrid)
	 * selected record
	 * @param: {Tine.Membership.Model.SoMember} selected record
	 */
	onEditFeeProgress: function(record){
		this.loadRecord(record);
		this.setEditMode();
	},
	
	loadRecord: function(record){
		var id = 0;
		var obj = {};
		if((record.fee_progress_ext_id != null) && (typeof(record.fee_progress_ext_id) == 'object') &&(record.fee_progress_ext_id.id !== undefined)){
			id = record.fee_progress_ext_id.id;
			obj = record.fee_progress_ext_id;
		}
		this.record = new Tine.Membership.Model.SoMemberFeeProgressExt(obj,id);
		this.loadForm();
	},
	
	loadForm: function(){
		var att;
		var value;
		var field;
		for(var i in Tine.Membership.Model.SoMemberFeeProgressExtArray){
			att = Tine.Membership.Model.SoMemberFeeProgressExtArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = this.record.data[att.name];
				
				if(att.type == 'date'){
					value = (value ? Date.parseDate(value, att.dateFormat): value);
				}
				if(value){
					this.loadField('feeprogress_ext_'+att.name,value);
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
		for(var i in Tine.Membership.Model.SoMemberFeeProgressExtArray){
			att = Tine.Membership.Model.SoMemberFeeProgressExtArray[i];
			if(undefined !== att.name && (typeof(att)!='function')){
				value = Ext.getCmp('feeprogress_ext_'+att.name).getValue();
				if(value){
					this.record.data[att.name] = value;
				}
			}
		}
		this.record.set('member_id',this.feeProgressRecord.get('id'));
	},
	
	getFormContents: function(){
		return Tine.Membership.getFeeProgressExtEditDialogPanel(this.feeProgressGrid);
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

Tine.Membership.getFeeProgressExtEditRecordPanel = function(){
	return new Tine.Membership.FeeProgressExtEditRecord (
		{
		}
	);
};
Tine.Membership.getFeeProgressExtEditDialogPanel = function(){
	var editPanel = {
		xtype: 'panel',
		id: 'membership-feeprogress-ext-edit-dialog-panel',
		border: false,
		frame: true,
		cls: 'tw-editdialog',
		layout:'fit',
		defferedRender:true,
		defaults: {
	        xtype: 'fieldset',
	        layout:'fit',
	        defaultType: 'textfield'
	    },
		items: [
	        {title:'',checkboxToggle:false,border:false,items:[{xtype:'columnform',border:false,items:[[
		       {xtype: 'hidden',id:'feeprogress_ext_id',name:'id'},
	   		   {xtype: 'hidden',id:'feeprogress_ext_member_id',name:'member_id'},
	   		   {xtype: 'hidden',id:'feeprogress_ext_fee_ext_year',name:'fee_ext_year'},
	   		   {
				    xtype: 'memberselect',
                    width: 300,
				    fieldLabel: 'bei HV vertreten durch', 
				    disabledClass: 'x-item-disabled-view',
				    id:'feeprogress_ext_mc_procure_contact_id',
				    name:'mc_procure_contact_id'
				}],[{
					xtype: 'checkbox',
					disabledClass: 'x-item-disabled-view',
					id: 'feeprogress_ext_mc_attendance',
					name: 'mc_attendance',
					hideLabel:true,
				    boxLabel: 'Teilnahme an Hauptversammlung (HV)',
				    width:300
				}],[{
	   		        fieldLabel: 'Mitglieder gesamt',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_members_total',
				    name:'members_total',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Aktive Mitglieder',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_active_members_total',
				    name:'active_members_total',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Passive Mitglieder',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_passive_members_total',
				    name:'passive_members_total',
				    value:null,
				    width: 150
				}],[{
				    fieldLabel: 'Jugendliche Mitglieder',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_juvenile_members_total',
				    name:'juvenile_members_total',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Erwachsene Mitglieder',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_adult_members_total',
				    name:'adult_members_total',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Stimmberecht. Mitglieder',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_acclamative_members_total',
				    name:'acclamative_members_total',
				    value:null,
				    width: 150
				}],[{
				    fieldLabel: 'Stimmen HV',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_main_convention_votes',
				    name:'main_convention_votes',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Vertr. Stimmen HV',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_mc_procure_votes',
				    name:'mc_procure_votes',
				    value:null,
				    width: 150
				},{
				    fieldLabel: 'Stimmen HV gem. Vertr.',
				    disabledClass: 'x-item-disabled-view',
				    infoField:true,
				    id:'feeprogress_ext_mc_votes_acc_procure',
				    name:'mc_votes_acc_procure',
				    value:null,
				    width: 150
				}]        
		]
	}]}]};
	return editPanel;
};