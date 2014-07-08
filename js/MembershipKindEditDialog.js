Ext.namespace('Tine.Membership');

Tine.Membership.MembershipKindEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
	
	/**
	 * @private
	 */
	windowNamePrefix: 'MembershipKindEditWindow_',
	appName: 'Membership',
	recordClass: Tine.Membership.Model.MembershipKind,
	recordProxy: Tine.Membership.membershipKindBackend,
	loadRecord: false,
	evalGrants: false,
	
	/**
	 * returns dialog
	 * 
	 * NOTE: when this method gets called, all initalisation is done.
	 */
	getFormItems: function() {
	    return {
	        xtype: 'panel',
	        border: false,
	        frame:true,
	        items:[{xtype:'columnform',items:[
	             [
	                {
						fieldLabel: 'Schlüssel',
					    id:'id',
					    name:'id',
					    value:null,
					    width: 250
					},{
						xtype:'hidden',
						id:'is_default',
						name:'is_default',
						value: 0
					},
					Tine.Membership.Custom.getRecordPicker('MembershipKind','parent_kind_id',{
						disabledClass: 'x-item-disabled-view',
						width: 300,
						fieldLabel: 'Übergeordnete Mitgliedsart',
					    name:'parent_kind_id',
					    disabled: false,
					    onAddEditable: true,
					    onEditEditable: false,
					    blurOnSelect: true,
					    allowBlank:true
					})
				 ],[
					{
						fieldLabel: 'Bezeichnung (Singular)',
					    id:'name',
					    name:'name',
					    value:null,
					    width: 500
					} 
				],[
					{
						fieldLabel: 'Bezeichnung (plural)',
					    id:'subject_plural',
					    name:'subject_plural',
					    value:null,
					    width: 500
					}
	             ],[
					{
						fieldLabel: 'Dialog-Text',
					    id:'dialog_text',
					    name:'dialog_text',
					    value:null,
					    width: 500
					}
				 ],[
					{
						fieldLabel: 'Dialog-Text Verband',
					    id:'dialog_text_assoc',
					    name:'dialog_text_assoc',
					    value:null,
					    width: 500
					}
				 ],[
					{
						fieldLabel: 'Dialog-Text Mitgliedsnummer',
					    id:'dialog_text_member_nr',
					    name:'dialog_text_member_nr',
					    value:null,
					    width: 500
					}
				],[
					{
						fieldLabel: 'Dialog-Text Mitgliedsnummer 2',
					    id:'dialog_text_member_ext_nr',
					    name:'dialog_text_member_ext_nr',
					    value:null,
					    width: 500
					}
				 ],[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Spez. Druckvorlage Beitragsrechnung',
					    id: 'invoice_template_id',
					    name: 'invoice_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 500
					})   
				 ],[
					{
						xtype:'checkbox',
						id:'uses_fee_progress',
						name:'uses_fee_progress',
						value: 0,
						fieldLabel: 'generiert Beitragsverläufe'
					},{
						xtype:'checkbox',
						id:'uses_member_fee_groups',
						name:'uses_member_fee_groups',
						value: 0,
						fieldLabel: 'Pflegt spezifische Beiträge'
					},{
						xtype:'checkbox',
						id:'identical_contact',
						name:'identical_contact',
						value: 0,
						fieldLabel: 'Kontakt analog zu übergeord. Mitgliedschaft'
					}      
				 ],[
				    {
				    	xtype: 'combo',
				    	fieldLabel:'Addressbuch',
				    	id: 'membership_kind_addressbook_id',
				    	width: 300,
				    	name: 'addressbook_id',
				    	store:Tine.Addressbook.getArrayFromRegistry('Addressbooks'),
				    	allowBlank:false,
				    	mode: 'local',
						displayField: 'name',
					    valueField: 'id',
					    triggerAction: 'all'
				    }
				 ],[
					{
						xtype:'checkbox',
						id:'has_functionaries',
						name:'has_functionaries',
						value: 0,
						fieldLabel: 'hat Funktionäre'
					},{
						xtype:'checkbox',
						id:'has_functions',
						name:'has_functions',
						value: 0,
						fieldLabel: 'hat Funktionen'
					},{
						xtype:'checkbox',
						id:'default_tab',
						name:'default_tab',
						value: 0,
						fieldLabel: 'ist Default-Tab'
					}
				 ],[
					{
						xtype:'checkbox',
						id:'fee_group_is_duty',
						name:'fee_group_is_duty',
						value: 0,
						fieldLabel: 'Beitragsgruppe ist Pflichtfeld'
					}					
				 ],[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Vorlage Begrüßungsschreiben',
					    id: 'begin_letter_template_id',
					    name: 'begin_letter_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 500
					})
				
				 ],[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Vorlage Versicherungsbestätigung',
					    id: 'insurance_letter_template_id',
					    name: 'insurance_letter_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 500
					})	
				 ],[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Vorlage Kündigungsbestätigung',
					    id: 'termination_letter_template_id',
					    name: 'termination_letter_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 500
					})	
				],[
					new Tine.Tinebase.widgets.form.RecordPickerComboBox({
					    fieldLabel: 'Vorlage Mitgliedsausweis',
					    id: 'membercard_letter_template_id',
					    name: 'membercard_letter_template_id',
					    blurOnSelect: true,
					    allowBlank:true,
					    recordClass: Tine.DocManager.Model.Template,
					    width: 500
					})					
				]
		            
	        ]}]
	    };
	}
});

/**
 * Membership Edit Popup
 */
Tine.Membership.MembershipKindEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 450,
        name: Tine.Membership.MembershipKindEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Membership.MembershipKindEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};