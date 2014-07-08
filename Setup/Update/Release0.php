<?php
/**
 *
 * Enter description here ...
 * @author hhartl
 *
 */

/**
 * Initial setup of membership, dropping table so_membership
 * move application tables from Addressbook -> to Membership
 * Reason for this non standard progress: artefacts hacked in Addressbook app, must be removed
 */

class Membership_Setup_Update_Release0 extends Setup_Update_Abstract{
	public function __construct(){
		parent::__construct(Setup_Backend_Factory::factory());
	}

	public static function create(){
		return new self();
	}

	/**
	 * Remove keys and tables (artefacts from membership headed in Addressbook app for demo purposes)
	 */
	private function removeMembershipArtefacts(){
		if($this->_backend->tableExists('so_membership')){
			$this->dropTable('so_membership');
		}
		if($this->_backend->tableExists('so_entry_reason')){
			$this->dropTable('so_entry_reason');
		}
		if($this->_backend->tableExists('so_termination_reason')){
			$this->dropTable('so_termination_reason');
		}
		if($this->_backend->tableExists('so_member_affiliate')){
			$this->dropTable('so_member_affiliate');
		}
		if($this->_backend->tableExists('so_committee_func')){
			$this->dropTable('so_committee_func');
		}
		if($this->_backend->tableExists('so_fee_category')){
			$this->dropTable('so_fee_category');
		}
		if($this->_backend->tableExists('so_fee_payment_interval')){
			$this->dropTable('so_fee_payment_interval');
		}
		if($this->_backend->tableExists('so_fee_payment_method')){
			$this->dropTable('so_fee_payment_method');
		}
		// remove tables from Addressbook app
		$appAddressbook = Tinebase_Application::getInstance()->getApplicationByName('Addressbook');
		$addressbookId = $appAddressbook->getId();
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_membership');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_entry_reason');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_termination_reason');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_member_affiliate');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_committee_func');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_fee_category');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_fee_payment_interval');
		Tinebase_Application::getInstance()->removeApplicationTable($addressbookId, 'so_fee_payment_method');
	}

	public function update_0(){
		$this->removeMembershipArtefacts();

		$tableDefinition = '
    	<table>
			<name>so_membership</name>	
			<version>2</version>
			<engine>InnoDB</engine>
         	<charset>utf8</charset>
			<declaration>
				<field>
                    <name>contact_id</name>
                    <type>integer</type>
                    <notnull>true</notnull>
                </field>
				<field>
					<name>begin_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
				</field>
				<field>
					<name>discharge_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
				</field>
				<field>
					<name>termination_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
				</field>
			  	<field>
					<name>so_entry_reason_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>so_member_affiliate_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>so_termination_reason_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>so_fee_category_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>yearly_fee</name>
					<type>float</type>
				</field>
				<field>
					<name>so_fee_payment_interval_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>so_fee_payment_method_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>total_items_balance</name>
					<type>float</type>
				</field>

				<field>
					<name>directorate</name>
					<type>boolean</type>
					<notnull>true</notnull>
					<default>false</default>
				</field>
				<field>
					<name>func_directorate_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>management_board</name>
					<type>boolean</type>
					<notnull>true</notnull>
					<default>false</default>
				</field>
				<field>
					<name>func_management_board_id</name>
					<type>integer</type>
				</field>
					
				<field>
					<name>advisory_board</name>
					<type>boolean</type>
					<notnull>true</notnull>
					<default>false</default>
				</field>
				<field>
					<name>func_advisory_board_id</name>
					<type>integer</type>
				</field>
				<field>
					<name>main_tec_committee</name>
					<type>boolean</type>
					<notnull>true</notnull>
					<default>false</default>
				</field>
				<field>
					<name>func_main_tec_committee_id</name>
					<type>integer</type>
				</field>
				<index>
                    <name>contact_id</name>
                    <primary>true</primary>
                    <field>
                        <name>contact_id</name>
                    </field>
                </index>
                <index>
                    <name>so_membership::contact_id-addressbook::id</name>
                    <field>
                        <name>contact_id</name>
                    </field>
                    <foreign>true</foreign>
                    <reference>
                        <table>addressbook</table>
                        <field>id</field>
                        <ondelete>CASCADE</ondelete>
                    </reference>
                    <ondelete>cascade</ondelete>
                </index>
			</declaration>
		</table>	
    	';
		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
		$this->_backend->createTable($table);
		Tinebase_Application::getInstance()->addApplicationTable(
		Tinebase_Application::getInstance()->getApplicationByName('Membership'),
            'so_membership', 
		2
		);
		$tableDefinition = '
        <table>
			<name>so_fee_category</name>
			<version>2</version>
			<engine>InnoDB</engine>
         	<charset>utf8</charset>
			<declaration>
				<field>
					<name>id</name>
					<type>integer</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>name</name>
					<type>text</type>
					<length>128</length>	
				</field>
				<field>
					<name>fee</name>
					<type>float</type>	
				</field>
				<index>
	                <name>so_fee_category_pkey</name>
					<primary>true</primary>
					<field>
						<name>id</name>
					</field>	            	
				</index>
			</declaration>
		</table>
		';
		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
		$this->_backend->createTable($table);
		Tinebase_Application::getInstance()->addApplicationTable(
		Tinebase_Application::getInstance()->getApplicationByName('Membership'),
            'so_fee_category', 
		2
		);
		$tableDefinition = '
        <table>
			<name>so_fee_payment_interval</name>
			<version>2</version>
			<engine>InnoDB</engine>
         	<charset>utf8</charset>
			<declaration>
				<field>
					<name>id</name>
					<type>integer</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>name</name>
					<type>text</type>
					<length>128</length>	
				</field>
				<field>
					<name>interval</name>
					<type>enum</type>
					<value>1</value>
					<value>3</value>
					<value>6</value>
					<value>12</value>	
				</field>
				<index>
	                <name>so_fee_payment_interval_pkey</name>
					<primary>true</primary>
					<field>
						<name>id</name>
					</field>	            	
				</index>
			</declaration>
		</table>
		';
		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
		$this->_backend->createTable($table);
		Tinebase_Application::getInstance()->addApplicationTable(
			Tinebase_Application::getInstance()->getApplicationByName('Membership'),
	            'so_fee_payment_interval', 
			2
		);
		$tableDefinition = '
        <table>
			<name>so_fee_payment_method</name>
			<version>2</version>
			<engine>InnoDB</engine>
         	<charset>utf8</charset>
			<declaration>
				<field>
					<name>id</name>
					<type>integer</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>name</name>
					<type>text</type>
					<length>128</length>	
				</field>
				<field>
					<name>method</name>
					<type>text</type>	
					<length>255</length>
				</field>
				<index>
	                <name>so_fee_payment_method_pkey</name>
					<primary>true</primary>
					<field>
						<name>id</name>
					</field>	            	
				</index>
			</declaration>
		</table>
        ';
		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
		$this->_backend->createTable($table);
		Tinebase_Application::getInstance()->addApplicationTable(
			Tinebase_Application::getInstance()->getApplicationByName('Membership'),
	            'so_fee_payment_method', 
			2
		);
		$this->setApplicationVersion('Membership', '1.1');
	}
}
?>