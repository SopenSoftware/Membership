Ext.namespace('Tine.Membership');

Tine.Membership.FeeVarConfigEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'FeeVarConfigEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.FeeVarConfig,
	recordProxy: Tine.Membership.feeDefBackend,
	loadRecord: false,
	evalGrants: false,
	feeDefinitionRecord: null,
	initComponent: function(){
		Tine.Membership.FeeVarConfigEditDialog.superclass.initComponent.call(this);
		this.on('afterrender', this.onAfterRender, this);
	},
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	onAfterRender: function(){
		Ext.getCmp('feedef_dfilters_id').setFeeDefinition(this.feeDefinitionRecord);
	},
	onRecordLoad: function(){
		if(this.feeDefinitionRecord){
			this.record.set('fee_definition_id', this.feeDefinitionRecord.get('id'));
		}
		this.supr().onRecordLoad.call(this);
	},
	getFormItems: function() {
		var fields = Tine.Membership.FeeVarConfigFormFields.get();
	    return {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	              	fields.id
	             ],[
					fields.name, fields.label
				],[
					fields.type, fields.vartype
				],[
				   	fields.feedef_dfilters_id
			   	],[
				   	fields.dataobject
			   	],[
				   	fields.compare1, fields.compare_value1, fields.result_value1 
				],[
				   	fields.compare2, fields.compare_value2, fields.result_value2
				],[
			   		fields.compare3, fields.compare_value3, fields.result_value3  
				],[
				   	fields.compare4, fields.compare_value4, fields.result_value4 
			   	],[
			   	   fields.compare5, fields.compare_value5, fields.result_value5 
				],[
				   fields.compare6, fields.compare_value6, fields.result_value6 
				],[
				   	fields.compare7, fields.compare_value7, fields.result_value7 
			   	],[
				   	fields.transform1, fields.transform2
				]
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.FeeVarConfigEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 650,
        height: 700,
        name: Tine.Membership.FeeVarConfigEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.FeeVarConfigEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};


Ext.ns('Tine.Membership.FeeVarConfigFormFields');

Tine.Membership.FeeVarConfigFormFields.get = function(){
	return{
		// hidden fields
		id: 
			{xtype: 'hidden',id:'id',name:'id'},
		name:	
			{
			    fieldLabel: 'Bezeichnung',
			    id:'name',
			    name:'name',
			    value:null,
			    allowBlank:false,
			    width: 400
			},
		feedef_dfilters_id:
			Tine.Membership.Custom.getRecordPicker('FeeDefFilter', 'feedef_dfilters_id',{
				name: 'feedef_dfilters_id',
				width:400,
				allowBlank:true
			}),
		label:	
			{
			    fieldLabel: 'Dialog-Text',
			    id:'label',
			    name:'label',
			    value:null,
			    allowBlank:false,
			    width: 400
			},
		type:
			{
			    fieldLabel: 'Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'type',
			    name:'type',
			    width: 200,
			    xtype:'combo',
			    store:[['FIX','Fix'],['DATAOBJECT','Datenobjekt'],['VARIABLE','Variabel']],
			    value: 'VARIABLE',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},		
		vartype:
			{
			    fieldLabel: 'Var-Typ',
			    disabledClass: 'x-item-disabled-view',
			    id:'vartype',
			    name:'vartype',
			    width: 200,
			    xtype:'combo',
			    store:[['INTEGER','Ganzzahl'],['FLOAT','Fliesskomma'],['TEXT','Text']],
			    value: 'FLOAT',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		dataobject:
			{
			    fieldLabel: 'Attribut Datenobjekt',
			    disabledClass: 'x-item-disabled-view',
			    id:'dataobject',
			    name:'dataobject',
			    width: 200,
			    xtype:'combo',
			    store:
			    [
			     ['','...keine Auswahl...'],
			     ['MB_ADMFEE_PAYED','Mitglied: Aufnahmegeb. ist bezahlt'],
			     ['MB_PAYS_ADMFEE','Mitglied: bezahlt Aufnahmegeb.'],
			     ['FPROG_FIRST', 'Beitragsverlauf: ist erster'],
			     ['FPROG_FEE_UNITS', 'Beitragsverlauf: Faktor ant. Beitrag'],
			     ['MB_SPEC_FEE_ARTICLE', 'spez. Beitrag Artikel'],
			     ['MB_INDIV_FEE', 'Mitglied: individueller Beitrag'],
			     ['MB_ADDITIONAL_FEE', 'Mitglied: Zusatzbeitrag'],
			     ['MB_DONATION', 'Mitglied: Spende(Beitrag)'],
			     ['FG_SUM_I', 'BG: Teilsumme(I)']
			     
			     //,
			     //['MB_SPEC_FEE_GROUP', 'spez. Beitrag Beitragsgruppe']
			    ],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare1:
			{
				fieldLabel: 'Vergleich 1',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare1',
			    name:'compare1',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value1:
			{
				fieldLabel: 'Vergleichswert 1',
			    id:'compare_value1',
			    name:'compare_value1',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value1:
			{
				fieldLabel: 'Ergebniswert 1',
			    id:'result_value1',
			    name:'result_value1',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		compare2:
			{
				fieldLabel: 'Vergleich 2',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare2',
			    name:'compare2',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value2:
			{
				fieldLabel: 'Vergleichswert 2',
			    id:'compare_value2',
			    name:'compare_value2',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value2:
			{
				fieldLabel: 'Ergebniswert 2',
			    id:'result_value2',
			    name:'result_value2',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		compare3:
			{
				fieldLabel: 'Vergleich 1',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare3',
			    name:'compare3',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value3:
			{
				fieldLabel: 'Vergleichswert 3',
			    id:'compare_value3',
			    name:'compare_value3',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value3:
			{
				fieldLabel: 'Ergebniswert 3',
			    id:'result_value3',
			    name:'result_value3',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		compare4:
			{
				fieldLabel: 'Vergleich 4',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare4',
			    name:'compare4',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value4:
			{
				fieldLabel: 'Vergleichswert 4',
			    id:'compare_value4',
			    name:'compare_value4',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value4:
			{
				fieldLabel: 'Ergebniswert 4',
			    id:'result_value4',
			    name:'result_value4',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		compare5:
			{
				fieldLabel: 'Vergleich 5',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare5',
			    name:'compare5',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value5:
			{
				fieldLabel: 'Vergleichswert 5',
			    id:'compare_value5',
			    name:'compare_value5',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value5:
			{
				fieldLabel: 'Ergebniswert 5',
			    id:'result_value5',
			    name:'result_value5',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
			compare6:
			{
				fieldLabel: 'Vergleich 6',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare6',
			    name:'compare6',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value6:
			{
				fieldLabel: 'Vergleichswert 6',
			    id:'compare_value6',
			    name:'compare_value6',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value6:
			{
				fieldLabel: 'Ergebniswert 6',
			    id:'result_value6',
			    name:'result_value6',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
			compare7:
			{
				fieldLabel: 'Vergleich 7',
			    disabledClass: 'x-item-disabled-view',
			    id:'compare7',
			    name:'compare7',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['EQUALS','='],['GREATER','>'],['GREATEROREQUALS','>='],['LESS','<'],['LESSOREQUALS','<=']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		compare_value7:
			{
				fieldLabel: 'Vergleichswert 7',
			    id:'compare_value7',
			    name:'compare_value7',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
		result_value7:
			{
				fieldLabel: 'Ergebniswert 7',
			    id:'result_value7',
			    name:'result_value7',
			    value:null,
			    allowBlank:true,
			    width: 200
			},
			
		transform1:
			{
			    fieldLabel: 'Transform 1',
			    disabledClass: 'x-item-disabled-view',
			    id:'transform1',
			    name:'transform1',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['ABS','abs'],['SGN','sgn'],['NOT','not'],['IFDEF','wenn definiert']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
		transform2:
			{
			    fieldLabel: 'Transform 2',
			    disabledClass: 'x-item-disabled-view',
			    id:'transform2',
			    name:'transform2',
			    width: 200,
			    xtype:'combo',
			    store:[['','...keine Auswahl...'],['ABS','abs'],['SGN','sgn'],['NOT','not'],['IFDEF','wenn definiert']],
			    value: '',
				mode: 'local',
				displayField: 'name',
			    valueField: 'id',
			    triggerAction: 'all'
			},
			
	};
}