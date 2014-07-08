Ext.namespace('Tine.Membership');


/**
 * Timeaccount grid panel
 */
Tine.Membership.OpenItemGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	id: 'tine-membership-openItem-gridpanel',
	recordClass: Tine.Membership.Model.OpenItem,
    evalGrants: false,
    // grid specific
    defaultSortInfo: {field: 'id', direction: 'DESC'},
    gridConfig: {
        loadMask: true
    },
    initComponent: function() {
        this.recordProxy = Tine.Membership.openItemBackend;
        this.action_payInvoice = new Ext.Action({
            text: 'Bezahlung erfassen',
            disabled: true,
            actionType: 'edit',
            handler: this.payInvoice,
            actionUpdater: this.updatePayInvoiceAction,
            iconCls: 'action_edit',
            scope: this
        });
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);        
        
        Tine.Membership.OpenItemGridPanel.superclass.initComponent.call(this);
        this.actionUpdater.addActions([this.action_payInvoice]);
    },
    updatePayInvoiceAction: function(action, grants, records) {
    	action.setDisabled(true);
        if (records.length == 1) {
            var invoice = records[0];
            if (! invoice) {
                return false;
            }
            var isPayed = invoice.get('payment_state')=='PAYED';

            if(!isPayed){
            	action.setDisabled(false);
            }
        }
    },
    payInvoice: function(){
    	var selectedRows = this.grid.getSelectionModel().getSelections();
        var record = selectedRows[0];
        var receiptRecord = record.getForeignRecord(Tine.Billing.Model.Receipt,'receipt_id');
        var orderRecord = record.getForeignRecord(Tine.Billing.Model.Order,'order_id');
        var debitorRecord = record.getForeignRecord(Tine.Billing.Model.Debitor,'debitor_id');
        receiptRecord.set('order_id', orderRecord);
        receiptRecord.set('debitor_id', debitorRecord);
    	
        var win = Tine.Billing.PaymentEditDialog.openWindow({
    		record: null,
    		receiptRecord: receiptRecord,
    		debitorRecord: debitorRecord,
			listeners: {
                scope: this,
                'update': function(record) {
                    this.grid.getStore().reload();
                }
            }
		});
    },
    getContextMenuItems: function(){
    	var contextMenuItems = Tine.Membership.OpenItemGridPanel.superclass.getContextMenuItems.call(this);
    	return contextMenuItems.concat(
    	[
    	    this.action_payInvoice
    	]);
    },
    initFilterToolbar: function() {
		var quickFilter = [new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()];	
		this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Membership.Model.OpenItem.getFilterModel(),
            defaultFilter: 'state',
            filters: [{field:'state',operator:'equals',value:'OPEN'}],
            plugins: quickFilter
        });
    },
	getColumns: function() {
		var columns = Tine.Membership.OpenItem.GridPanelConfig.getColumns();
		return [
		   columns.order_id,
		   columns.op_nr,
		   columns.receipt_id,
		   columns.debitor_id,
		   columns.receipt_nr,
		   columns.receipt_date,
		   columns.type,
		   columns.due_date,
		   columns.fibu_exp_date,
		   columns.total_netto,
		   columns.total_brutto,
		   columns.banking_exp_date,
		   columns.state,
		   columns.payment_method_id
	    ];
	}
});

Ext.namespace('Tine.Membership.OpenItem.GridPanelConfig');

Tine.Membership.OpenItem.GridPanelConfig.getColumns = function(){
	var app = Tine.Tinebase.appMgr.get('Membership');
	return {
	id:
		{ id: 'id', header: app.i18n._('ID'), dataIndex: 'id', sortable:true },
	order_id:
		{ id: 'order_id', header: app.i18n._('Auftrags-Nr'), dataIndex: 'order_id', sortable:true, renderer: Tine.Billing.renderer.orderRenderer },			
	op_nr:
		{ id: 'op_nr', header: app.i18n._('OP-Nr'), dataIndex: 'op_nr', sortable:true },
	receipt_id:
		{ id: 'receipt_id', header: app.i18n._('Beleg'), dataIndex: 'receipt_id', sortable:true, renderer: Tine.Billing.renderer.receiptRenderer },			
	debitor_id:
		{ id: 'debitor_id', header: app.i18n._('Kunde'), dataIndex: 'debitor_id', sortable:true, renderer: Tine.Billing.renderer.debitorRenderer },			
	receipt_nr:
		{ id: 'receipt_nr', header: app.i18n._('Beleg-Nr'), dataIndex: 'receipt_nr', sortable:true },
	receipt_date:
		{ id: 'receipt_date', header: app.i18n._('Beleg-Datum'), dataIndex: 'receipt_date', renderer: Tine.Tinebase.common.dateRenderer },
	type:
		{ id: 'type', header: app.i18n._('Typ'), dataIndex: 'type', sortable:true },
	due_date:
		{ id: 'due_date', header: app.i18n._('Datum Fälligkeit'), dataIndex: 'due_date', renderer: Tine.Tinebase.common.dateRenderer },
	fibu_exp_date:
		{ id: 'fibu_exp_date', header: app.i18n._('Datum FIBU Exp.'), dataIndex: 'fibu_exp_date', renderer: Tine.Tinebase.common.dateRenderer },
	total_netto:
	{ 
		   id: 'total_netto', header: 'Gesamt netto', dataIndex: 'total_netto', sortable:false,
		   renderer: Sopen.Renderer.MonetaryNumFieldRenderer
     },
	total_brutto:{
		   id: 'total_brutto', header: 'Gesamt brutto', dataIndex: 'total_brutto', sortable:false,
		   renderer: Sopen.Renderer.MonetaryNumFieldRenderer
	},
    banking_exp_date:
		{ id: 'banking_exp_date', header: app.i18n._('Datum DTA Exp.'), dataIndex: 'banking_exp_date', renderer: Tine.Tinebase.common.dateRenderer },
	state:
		{ id: 'state', header: app.i18n._('Status'), dataIndex: 'state', sortable:false, renderer:Tine.Membership.getOpenItemStateIcon  },
	payment_method_id:	   
		{ id: 'payment_method_id', header: app.i18n._('Zahlungsart'), dataIndex: 'payment_method_id', sortable:false, renderer:Tine.Billing.renderer.paymentMethodRenderer  }
		   
	};
}