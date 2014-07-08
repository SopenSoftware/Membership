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

class Membership_Setup_Update_Release2 extends Setup_Update_Abstract{
	
	public function update_0(){
		//$this->_backend->dropForeignKey('membership','membership::affiliate_contact_id--addressbook::id');
		/*$this->_backend->dropConstraint('membership','sopen_membership_ibfk_2');
 		$this->_backend->dropForeignKey('membership','affiliate_contact_id');
 		$this->_backend->dropCol('membership', 'affiliate_contact_id');*/
		//$this->_backend->dropIndex('membership','affiliate_contact_id');
 		
		//$this->_backend->dropForeignKey('membership','affiliate_contact_id');
		//$this->_backend->dropCol('membership', 'affiliate_contact_id');
 		$this->setApplicationVersion('Membership', '2.1');
		
	}
	/*public function update_0(){
		$this->_backend->dropCol('membership_filter_set', 'sum_category');
		$this->_backend->dropCol('membership_filter_set', 'scalar_formula1');
		$this->_backend->dropCol('membership_filter_set', 'scalar_formula2');
	
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>sum_category</name>
					<type>enum</type>
					<value>1</value>
					<value>2</value>
					<value>3</value>
					<value>4</value>
					<default>1</default>
				</field>
		    ');
       	$this->_backend->addCol('membership_filter_result', $declaration);
       
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>scalar_formula1</name>
					<type>text</type>
					<length>1024</length>
					<notnull>false</notnull>
					<default>null</default>
				</field>
		    ');
       	$this->_backend->addCol('membership_filter_result', $declaration);
       
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>scalar_formula2</name>
					<type>text</type>
					<length>1024</length>
					<notnull>false</notnull>
					<default>null</default>
				</field>
		    ');
       	$this->_backend->addCol('membership_filter_result', $declaration);
       
		$this->setApplicationVersion('Membership', '2.01');
		
	}*/
	
	public function update_1(){
		$this->_backend->dropCol('membership_fee_progress', 'payment_state');
		$this->_backend->dropCol('membership_fee_progress', 'sum_brutto');
 		$this->setApplicationVersion('Membership', '2.11');
		
	}
	public function update_11(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>bank_account_id</name>
					<type>integer</type>
					<notnull>false</notnull>
				</field>	
		    ');
       $this->_backend->addCol('membership', $declaration);
       
       $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>bank_account_usage_id</name>
					<type>integer</type>
					<notnull>false</notnull>
				</field>	
		    ');
       $this->_backend->addCol('membership', $declaration);
       
       $this->setApplicationVersion('Membership', '2.12');
	}
	
	
	public function update_12(){
		/*$this->_backend->dropCol('membership_data', 'bank_code');
		$this->_backend->dropCol('membership_data', 'bank_name');
		$this->_backend->dropCol('membership_data', 'bank_account_nr');
		$this->_backend->dropCol('membership_data', 'account_holder');*/
		// drop old bank cols from membership_data
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>bank_account_id</name>
                    <type>integer</type>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>	
		    ');
       $this->_backend->addCol('membership_data', $declaration);
       
       $this->setApplicationVersion('Membership', '2.13');
	}
	
	public function update_13(){
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_vote</name>
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>member_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
                <field>
                    <name>transfer_member_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
                <field>
                    <name>association_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
					<name>original_votes</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
				<field>
					<name>become_votes</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
				<field>
					<name>transferred_votes</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
				<field>
					<name>total_votes</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
				<field>
					<name>vote_permission</name>
					<type>enum</type>
					<value>OWN</value>
					<value>TRANSFERMAIN</value>
					<value>TRANSFERCLUB</value>
					<value>NOREACTION</value>
					<notnull>true</notnull>
					<default>NOREACTION</default>
				</field>
				<field>
					<name>on_site</name>
					<type>boolean</type>
					<notnull>true</notnull>
					<default>false</default>
				</field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
			</declaration>
		</table>
		');
		$this->_backend->createTable($dec);
		
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_vote_transfer</name>
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>member_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
                <field>
                    <name>from_member_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
                <field>
                    <name>transferred_votes</name>
                    <type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
                </field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
			</declaration>
		</table>
		');
		$this->_backend->createTable($dec);
		
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>sum_category</name>
					<type>enum</type>
					<value>1</value>
					<value>2</value>
					<value>3</value>
					<value>4</value>
					<default>1</default>
				</field>
		    ');
       $this->_backend->addCol('membership_filter_set', $declaration);
       
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>scalar_formula1</name>
					<type>text</type>
					<length>1024</length>
					<notnull>false</notnull>
					<default>null</default>
				</field>
		    ');
       $this->_backend->addCol('membership_filter_set', $declaration);
       
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>scalar_formula2</name>
					<type>text</type>
					<length>1024</length>
					<notnull>false</notnull>
					<default>null</default>
				</field>
		    ');
       $this->_backend->addCol('membership_filter_set', $declaration);
       // @todo: sync point necessary
		$this->setApplicationVersion('Membership', '2.132');
	}
	
	//@todo: sync point necessary
	
				
	
}
?>