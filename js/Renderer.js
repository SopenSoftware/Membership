Ext.namespace('Tine.Membership');
Ext.namespace('Tine.Membership.renderer');

Tine.Membership.renderer.contactRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Addressbook.Model.Contact(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.getTitle(true);
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.associationRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	var _record = new Tine.Membership.Model.Association(_recordData,_recordData.id);
	var contact = _record.getContact();
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			return _record.getTitle();
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.committeeRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	var _record = new Tine.Membership.Model.Committee(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			return _record.get('committee_nr')+' ' +_record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.feeDefinitionRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	var _record = new Tine.Membership.Model.FeeDefinition(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};
Tine.Membership.renderer.membershipRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.SoMember(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.getTitle(true);
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.feeVarConfigRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.FeeVarConfig(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.committeeKindRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.CommitteeKind(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.committeeLevelRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.CommitteeLevel(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.committeeFunctionRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.CommitteeFunction(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.awardListRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.AwardList(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.feeVarConfigLabelRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.FeeVarConfig(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('label');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.feeVarValueRenderer =  function(v) {
		return v + '';
};

Tine.Membership.renderer.feeGroupRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.FeeGroup(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.actionRenderer =  function(_recordData) {
	if(!_recordData){
		return null;
	}
	_record = new Tine.Membership.Model.Action(_recordData,_recordData.id);
	if(typeof(_record) === 'object' && !Ext.isEmpty(_record)){
		try{
			// focus organisation -> true
			return _record.get('name');
		}catch(e){
			return "";
		}
	}
};

Tine.Membership.renderer.memshipType = function(v){
	if(Tine.Membership.renderer.memshipTypeCollection === undefined){
		Tine.Membership.renderer.memshipTypeCollection = new Ext.util.MixedCollection();
		var array = Tine.Membership.getStore('MembershipKind');
		for(var i = 0; i<array.length; i++){
			Tine.Membership.renderer.memshipTypeCollection.add(array[i][0],array[i][1]);
		}
	}
	return Tine.Membership.renderer.memshipTypeCollection.get(v);
};

Tine.Membership.renderer.memshipStatus = function(v){
	switch(v){
	case 'ACTIVE':
		return 'aktiv';
	case 'PASSIVE':
		return 'passiv';
	case 'TERMINATED':
		return 'ausgetreten';
	case 'DISCHARGED':
		return 'gekündigt';
	}
}

Tine.Membership.renderer.actionCategoryRenderer = function(v){
	switch(v){
	case 'DATA':
		return 'Daten';
	case 'EXPORT':
		return 'Export';	
	case 'PRINT':
		return 'Druck';
	case 'BILLING':
		return 'Abrechnung';		
	}
}

Tine.Membership.renderer.actionTypeRenderer = function(v){
	switch(v){
	case 'MANUAL':
		return 'manuell';
	case 'AUTO':
		return 'automatisch';	
	}
}

Tine.Membership.renderer.actionStateRenderer = function(v){
	switch(v){
	case 'OPEN':
		return 'offen';
	case 'DONE':
		return 'erledigt';	
	case 'ERROR':
		return 'Fehler';
	}
}

Tine.Membership.renderer.feePaymentInterval = function(v){
	switch(v){
	case 'NOVALUE':
		return '';
	case 'YEAR':
		return 'jährlich';	
	case 'HALF':
		return 'halbjährlich';	
	case 'QUARTER':
		return 'quartalsweise';
	case 'MONTH':
		return 'monatlich';		
	}
}

Tine.Membership.renderer.feePaymentMethod = function(v){
	switch(v){
	case 'NOVALUE':
		return '';
	case 'BANKTRANSFER':
		return 'Überweisung';	
	case 'DEBIT':
		return 'Lastschrift';
	}
}

Tine.Membership.renderer.messageReceiverType = function(v){
	switch(v){
	case 'GROUP':
		return 'Gruppe';
	case 'USER':
		return 'Benutzer';	
	case 'PARENTMEMBER':
		return 'Verein';
	case 'MEMBER':
		return 'Mitglied';
	}
}

Tine.Membership.renderer.messageType = function(v){
	switch(v){
	case 'OUT':
		return 'Ausgang';
	case 'IN':
		return 'Eingang';	
	default: 
		return '';
	}
}

