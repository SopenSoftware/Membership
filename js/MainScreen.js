/**
 * Sopen
 * 
 * @package     Membership
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author     
 * @copyright   
 * @version     $Id:  $
 *
 */
Ext.ns('Tine.Membership');

Tine.Membership.Application = Ext.extend(Tine.Addressbook.Application, {
    addressbookPlugin: null,
    
    messageBroker: null,
    
	init: function(){
		Tine.Tinebase.appMgr.on('initall',this.onInitAll,this);
		if(window.isMainWindow){
			this.initMessageBroker();
		}
	},
	
	onInitAll: function(){
		this.addressbookPlugin = new Tine.Membership.AddressbookPlugin();
		Tine.Tinebase.appMgr.get('Addressbook').registerPlugin(new Tine.Membership.AddressbookPlugin());
		this.registerPlugin(this.addressbookPlugin);
	},
	
	initMessageBroker: function(){
		if(!this.messageBroker){
			this.messageBroker = new Tine.Membership.MessageBroker();
			this.messageBroker.initialize();
		}
	},
	
	getMessageBroker: function(){
		return this.messageBroker;
	},
    /**
     * Get translated application title of the calendar application
     * 
     * @return {String}
     */
    getTitle: function() {
        return this.i18n.ngettext('Mitglieder', 'Mitglieder', 1);
    }
});

Tine.Membership.MainScreen = Ext.extend(Tine.widgets.MainScreen, {
    activeContentType: 'SoMember',
    westPanelXType: 'tine.membership.treepanel'
});

Tine.Membership.TreePanel = Ext.extend(Tine.widgets.persistentfilter.PickerPanel, {
	rootVisible:false,
	useArrows:true,
	hasFavoritesPanel: true,
	filter: [{field: 'model', operator: 'equals', value: 'Membership_Model_SoMemberFilter'}],
	
    initComponent: function() {
    	this.filterMountId = 'SoMember';

		
	   this.root = {
            id: 'memberroot',
            leaf: false,
            expanded: true,
            children: [
				{
				    text: this.app.i18n._('Mitglieder'),
				    id: 'SoMember',
				    contentType: 'SoMember',
				    iconCls:'MainTreeItemMember',
					leaf:false,
					expanded:true,
				    children: [{
				        text: this.app.i18n._('Alle Mitglieder'),
				        id: 'allmembers',
				        iconCls:'MainTreeItemMember',
				        leaf: true
				    }]	
				},{
					text: this.app.i18n._('Beitragsverläufe'),
				    id: 'SoMemberFeeProgress',
				    contentType: 'SoMemberFeeProgress',
				    iconCls:'MainTreeItemFeeProgress',
				    leaf:true/*,
					expanded:true,
				    children: [{
				        text: this.app.i18n._('Alle Beitragsperioden'),
				        id: 'allfeeprogress',
				        iconCls:'MainTreeItemMember',
				        leaf: true
				    }]	*/
				},{
					text: this.app.i18n._('Gremien'),
				    id: 'Committee',
				    contentType: 'Committee',
				    leaf: true
				},{
					text: this.app.i18n._('Funktionsträger'),
				    id: 'CommitteeFunc',
				    contentType: 'CommitteeFunc',
				    leaf: true
				},{
					text: this.app.i18n._('Ehrungen'),
				    id: 'MembershipAward',
				    contentType: 'MembershipAward',
				    leaf: true
				},{
					text: this.app.i18n._('Jobs'),
				    id: 'Job',
				    contentType: 'Job',
				    leaf: true
				},{
					text: this.app.i18n._('Aktionshistorie'),
				    id: 'ActionHistory',
				    contentType: 'ActionHistory',
				    leaf: true
				},{
					text: this.app.i18n._('Nachrichten'),
				    id: 'Message',
				    contentType: 'Message',
				    leaf: true
				},{
				    text: this.app.i18n._('Administration'),
				    iconCls: 'BillingConfig',
				    id : 'membershipConfig',
				    contentType: 'FilterSet',
				    leaf:false,
				    children: [
						{
						    text: this.app.i18n._('Beitragsdefinitionen'),
						    id: 'FeeDefinition',
						    contentType: 'FeeDefinition',
						    leaf: true
						},{
						    text: this.app.i18n._('Mitgliedsarten'),
						    id: 'MembershipKind',
						    contentType: 'MembershipKind',
						    leaf: true
						},{
						    text: this.app.i18n._('Beitragsgruppen'),
						    id: 'FeeGroup',
						    contentType: 'FeeGroup',
						    leaf: true
						},{
							text: this.app.i18n._('Hauptorganisationen'),
						    id: 'Association',
						    contentType: 'Association',
						    leaf: true
						},{
							text: this.app.i18n._('Gremien-Arten'),
						    id: 'CommitteeKind',
						    contentType: 'CommitteeKind',
						    leaf: true
						},{
							text: this.app.i18n._('Gremien-Ebenen'),
						    id: 'CommitteeLevel',
						    contentType: 'CommitteeLevel',
						    leaf: true
						},{
							text: this.app.i18n._('Gremien-Funktionen'),
						    id: 'CommitteeFunction',
						    contentType: 'CommitteeFunction',
						    leaf: true
						},{
							text: this.app.i18n._('Auszeichnungen/Ehrungen'),
						    id: 'AwardList',
						    contentType: 'AwardList',
						    leaf: true
						},{
							text: this.app.i18n._('Filtergruppen'),
						    id: 'FilterSet',
						    contentType: 'FilterSet',
						    leaf: true
						},{
							text: this.app.i18n._('Eintrittsgründe'),
						    id: 'EntryReason',
						    contentType: 'EntryReason',
						    leaf: true
						},{
							text: this.app.i18n._('Austrittsgründe'),
						    id: 'TerminationReason',
						    contentType: 'TerminationReason',
						    leaf: true
						}
				 ]
				}           
            ]
        };
	   	this.on('click',this.onClickNode, this);
    	Tine.Membership.TreePanel.superclass.initComponent.call(this);
        
       
	},
	
	onClickNode: function(node){
		if(node.id=='SoMember' || node.id=='allmembers'){
    		this.getFilterPlugin().getGridPanel().filterToolbar.deleteAllFilters();
    		//this.filter = [{field: 'model', operator: 'equals', value: 'Membership_Model_SoMemberFilter'}];
    		//this.filterMountId = 'SoMember';
		}
		/*if(node.id=='SoMemberFeeProgress' || node.id=='allfeeprogress'){
    		this.getFilterPlugin().getGridPanel().filterToolbar.deleteAllFilters();
    		this.filter = [{field: 'model', operator: 'equals', value: 'Membership_Model_SoMemberFilter'}];
    		this.filterMountId = 'SoMemberFeeProgress';
		}*/
		
    	if (node.attributes.isPersistentFilter != true) {
            var contentType = node.getPath().split('/')[2];
            if(contentType=='membershipConfig'){
            	 contentType = node.getPath().split('/')[3];
            }

            this.app.getMainScreen().activeContentType = contentType;
            this.app.getMainScreen().show();
        }
	},
	
    /**
     * @private
     */
    afterRender: function() {
    	Tine.Membership.TreePanel.superclass.afterRender.call(this);
        var type = this.app.getMainScreen().activeContentType;

        this.expandPath('/memberroot/' + type + '/allmembers');
        this.selectPath('/memberroot/' + type + '/allmembers');
    },
	splitViewToggle: function(){
		alert('split it');
	},
    /**
     * load grid from saved filter
     */
    onFilterSelect: function() {
    	this.app.getMainScreen().activeContentType = 'SoMember';
        this.app.getMainScreen().show();
        this.supr().onFilterSelect.apply(this, arguments);
    },
    /**
     * returns a filter plugin to be used in a grid
     */
    getFilterPlugin: function() {
        if (!this.filterPlugin) {
            var scope = this;
            this.filterPlugin = new Tine.widgets.grid.FilterPlugin({});
        }
        
        return this.filterPlugin;
    },
    
    getFavoritesPanel: function() {
        return this;
    }
});
Ext.reg('tine.membership.treepanel',Tine.Membership.TreePanel);