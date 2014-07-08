Ext.namespace('Tine.Membership');
Ext.namespace('Tine.Membership.Listeners');

/**
 * change listener: debitor_ext_id
 * triggers unique check on server
 */
Tine.Membership.Listeners.debitorNumberChangeListener = function(field, value){
	if((field.getValue() == null) || (field.getValue() == '') || (field.getValue()<=0)){
		Ext.Msg.alert(
			'Fehler', 
            'Bitte geben Sie zuerst einen Wert für die Debitor-Nr ein.'
        );
		return;
	}
	try{
		var contactId = Ext.getCmp('contact_id').getValue();
		Ext.Ajax.request({
            scope: this,
            params: {
                method: 'Membership.debitorCheckUnique',
                debitorExtId:  field.getValue(),
                contactId: contactId
            },
            success: function(response){
            	var result = Ext.util.JSON.decode(response.responseText);
            	if(!result.success){
            		var adressNr = result.data.contact_id;
            		var debitorExtId = result.data.debitor_ext_id;
            		var name = result.data.n_fileas;
            		var org_name = result.data.org_name;
            		var company2 = result.data.company2;
    				Ext.Msg.alert(
						'Hinweis', 
			            'Die von Ihnen eingegebene Debitor-Nr: ' + debitorExtId + 
			            ' existiert bereits im Zusammenhang mit <br />Adressnummer: ' + adressNr +
			            '<br />Name: ' + name +
			            '<br />Firma: ' + org_name + ', ' + company2
			        );
            	}

        	},
        	failure: function(response){
        		Ext.Msg.alert(
					'Fehler', 
		            'Die Plausibilitätsprüfung konnte nicht durchgeführt werden.'
		        );
        	}
        });
	}catch(e){
		alert(e.message);
	}
};

/**
 * validate listener: debitor_ext_id
 * on successfull form validation trigger the unique check, if the field is filled
 */
Tine.Membership.Listeners.debitorNumberValidateListener = function(field, value){
	if((field.getValue() == null) || (field.getValue() == '') || (field.getValue()<=0)){
		return true;
	}
	Tine.Membership.Listeners.debitorNumberChangeListener(field,value);
	return true;
};

// tab member

// etc.
