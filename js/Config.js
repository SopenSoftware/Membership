Ext.ns('Tine.Membership.Config');

Tine.Membership.Config = {
	Alerts:{
		// alert: if no birth date set in contact -> remind user for input
		//Tine.Membership.Config.Alerts.NoBirthdateGiven
		NoBirthdateGiven: {
			isActive: true,
			message: 'Bei diesem Kontakt ist kein Geburtsdatum erfasst.<br/>Sie können es in diesem Dialog eintragen, es wird dann in den Kontakt übernommen.'
		}
	},
	Jobs:{
		BatchFeeInvoice:{
			ActionDefault: 'FEEINVOICE',
			FeeYearDefault: new Date().getFullYear(),
			FeeYearMin: 2010,
			FeeYearMax: 2030
		}
	}
};