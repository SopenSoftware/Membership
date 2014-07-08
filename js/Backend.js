Ext.ns('Tine.Membership');

/**
* sopen member backend
*/
Tine.Membership.soMemberBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'SoMember',
   recordClass: Tine.Membership.Model.SoMember
});

Tine.Membership.soMemberEconomicBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'SoMemberEconomic',
   recordClass: Tine.Membership.Model.SoMemberEconomic
});

Tine.Membership.soMemberFeeProgressBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'SoMemberFeeProgress',
   recordClass: Tine.Membership.Model.SoMemberFeeProgress
});

Tine.Membership.feeDefinitionBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeDefinition',
   recordClass: Tine.Membership.Model.FeeDefinition
});

Tine.Membership.openItemBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'OpenItem',
   recordClass: Tine.Membership.Model.OpenItem
});

Tine.Membership.filterSetBackend = new Tine.Tinebase.data.RecordProxy({
	   appName: 'Membership',
	   modelName: 'FilterSet',
	   recordClass: Tine.Membership.Model.FilterSet
	});

Tine.Membership.filterResultBackend = new Tine.Tinebase.data.RecordProxy({
	   appName: 'Membership',
	   modelName: 'FilterResult',
	   recordClass: Tine.Membership.Model.FilterResult
	});

Tine.Membership.feeDefFilterBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeDefFilter',
   recordClass: Tine.Membership.Model.FeeDefFilter
});

Tine.Membership.feeVarConfigBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeVarConfig',
   recordClass: Tine.Membership.Model.FeeVarConfig
});

Tine.Membership.feeVarBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeVar',
   recordClass: Tine.Membership.Model.FeeVar
});

Tine.Membership.feeVarOrderPosBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeVarOrderPos',
   recordClass: Tine.Membership.Model.FeeVarOrderPos
});

Tine.Membership.awardListBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'AwardList',
   recordClass: Tine.Membership.Model.AwardList
});

Tine.Membership.membershipAwardBackend = new Tine.Tinebase.data.RecordProxy({
	   appName: 'Membership',
	   modelName: 'MembershipAward',
	   recordClass: Tine.Membership.Model.MembershipAward
	});

Tine.Membership.committeeBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'Committee',
   recordClass: Tine.Membership.Model.Committee
});

Tine.Membership.committeeFuncBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'CommitteeFunc',
   recordClass: Tine.Membership.Model.CommitteeFunc
});

Tine.Membership.committeeFunctionBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'CommitteeFunction',
   recordClass: Tine.Membership.Model.CommitteeFunction
});

Tine.Membership.committeeKindBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'CommitteeKind',
   recordClass: Tine.Membership.Model.CommitteeKind
});

Tine.Membership.committeeLevelBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'CommitteeLevel',
   recordClass: Tine.Membership.Model.CommitteeLevel
});

Tine.Membership.feeGroupBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'FeeGroup',
   recordClass: Tine.Membership.Model.FeeGroup
});

Tine.Membership.membershipFeeGroupBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'MembershipFeeGroup',
   recordClass: Tine.Membership.Model.MembershipFeeGroup
});

Tine.Membership.membershipKindBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'MembershipKind',
   recordClass: Tine.Membership.Model.MembershipKind
});

Tine.Membership.membershipAccountBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'MembershipAccount',
   recordClass: Tine.Membership.Model.MembershipAccount
});

Tine.Membership.associationBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'Association',
   recordClass: Tine.Membership.Model.Association
});

Tine.Membership.actionBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'Action',
   recordClass: Tine.Membership.Model.Action
});

Tine.Membership.entryReasonBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'EntryReason',
   recordClass: Tine.Membership.Model.EntryReason
});

Tine.Membership.terminationReasonBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'TerminationReason',
   recordClass: Tine.Membership.Model.TerminationReason
});

Tine.Membership.jobBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'Job',
   recordClass: Tine.Membership.Model.Job
});

Tine.Membership.actionHistoryBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'ActionHistory',
   recordClass: Tine.Membership.Model.ActionHistory
});

Tine.Membership.messageBackend = new Tine.Tinebase.data.RecordProxy({
   appName: 'Membership',
   modelName: 'Message',
   recordClass: Tine.Membership.Model.Message
});

Tine.Membership.getStoreFromRegistry = function(modelName, registryKey, searchMethod){
	var storeName = modelName + 'Store';
	var store = Ext.StoreMgr.get(storeName);
    if (!store) {
        store = new Ext.data.JsonStore({
            fields: Tine.Membership.Model[modelName],
            baseParams: {
            	method: 'Membership.' + searchMethod
            },
            root: 'results',
            totalProperty: 'totalcount',
            id: 'id',
            remoteSort: false
        });
        
        if (Tine.Membership.registry.get(registryKey)) {
            store.loadData(Tine.Membership.registry.get(registryKey));
        }
        Ext.StoreMgr.add(storeName, store);
    }
    
    return store;
};

Tine.Membership.getArrayFromRegistry = function(registryKey){
	if(registryKey.indexOf('.')>-1){
		var keys = registryKey.split('.');
		var array = Tine.Membership.registry.get(keys[0]);
		var strIndex = '';
		var prefix = '';
		for(var i = 1; i<keys.length;i++){
			strIndex +=  prefix + keys[i];
			if(prefix==''){
				prefix = '.';
			}
		}
		return array[strIndex];
	}else if (Tine.Membership.registry.get(registryKey)) {
		return Tine.Membership.registry.get(registryKey);
	}
	return [];
};

/**
 * get attender role store
 * if available, load data from initial data
 * 
 * @return Ext.data.JsonStore with salutations
 */
Tine.Membership.getStore = function(modelName) {
	switch(modelName){
    case 'MembershipKind':
    	return Tine.Membership.getArrayFromRegistry('MembershipKinds.simple');
    case 'MembershipExport':
    	return Tine.Membership.getArrayFromRegistry('MembershipExports');
    case 'FeeGroup':
    	return Tine.Membership.getArrayFromRegistry('FeeGroups');
    case 'Association':
    	return Tine.Membership.getStoreFromRegistry('Association', 'Associations', 'getAssociations');
    case 'Action':
    	return Tine.Membership.getArrayFromRegistry('Actions');
    case 'Committee':
    	return Tine.Membership.getArrayFromRegistry('Committees');
    case 'CommitteeKind':
    	return Tine.Membership.getArrayFromRegistry('CommitteeKinds');
    case 'CommitteeFunction':
    	return Tine.Membership.getArrayFromRegistry('CommitteeFunctions');
    case 'CommitteeLevel':
    	return Tine.Membership.getArrayFromRegistry('CommitteeLevels');
    case 'AwardList':
    	return Tine.Membership.getArrayFromRegistry('AwardLists');
    case 'EntryReason':
    	return Tine.Membership.getArrayFromRegistry('EntryReasons');
    case 'TerminationReason':
    	return Tine.Membership.getArrayFromRegistry('TerminationReasons');
    default:
    	throw 'Unknown model for store';
    }
};

Ext.ns('Tine.Membership.MembershipKind');
Tine.Membership.MembershipKind.getMap = function(){
	return new Ext.util.MixedCollection(Tine.Membership.getArrayFromRegistry('MembershipKinds.simple'));
};