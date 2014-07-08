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

class Membership_Setup_Update_Release1 extends Setup_Update_Abstract{
	public function update_0(){
		$this->setApplicationVersion('Membership', '1.1');
	}
	public function update_1(){
		$this->setApplicationVersion('Membership', '1.2');
	}
	public function update_2(){
//		$tableDefinition = '
//		<table>
//			<name>membership_fee_definition</name>	
//			<version>1</version>
//			<engine>InnoDB</engine>
//	     	<charset>utf8</charset>
//			<declaration>
//                <field>
//                    <name>id</name>
//                    <type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//                </field>
//				<field>
//					<name>membership_type</name>
//					<type>enum</type>
//					<value>SINGLE</value>
//					<value>FAMILY</value>
//					<value>VIASOCIETY</value>
//					<value>SOCIETY</value>
//					<default>SOCIETY</default>
//				</field>
//				<field>
//					<name>fee_category</name>
//					<type>enum</type>
//					<value>NOVALUE</value>
//					<value>SOCIETY</value>
//					<value>FAMILY</value>
//					<value>CHILD</value>
//					<value>JUVENILE</value>
//					<value>ADULT</value>
//					<default>SOCIETY</default>
//				</field>
//                <field>
//                    <name>fee_class_name</name>
//                    <type>text</type>
//					<length>48</length>
//                    <notnull>true</notnull>
//                </field>
//                <field>
//                    <name>filter_class</name>
//                    <type>text</type>
//					<length>128</length>
//                    <notnull>false</notnull>
//                </field>
//				<field>
//                    <name>filters</name>
//                    <type>text</type>
//					<notnull>false</notnull>
//                </field>
//				<field>
//                    <name>fee_calculator_class</name>
//                    <type>text</type>
//					<length>128</length>
//                    <notnull>false</notnull>
//                </field>
//				<index>
//                    <name>id</name>
//                    <primary>true</primary>
//                    <field>
//                        <name>id</name>
//                    </field>
//                </index>
//				<index>
//                    <name>unique_type_category</name>
//					<unique>true</unique>
//                    <field>
//                        <name>membership_type</name>
//                    </field>
//                    <field>
//                        <name>fee_category</name>
//                    </field>
//				</index>		
//			</declaration>
//		</table>
//		';
//		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
//		$this->_backend->createTable($table);
//		
//		$tableDefinition = '
//		<table>
//			<name>membership_fee_article</name>	
//			<version>1</version>
//			<engine>InnoDB</engine>
//	     	<charset>utf8</charset>
//			<declaration>
//                <field>
//                    <name>id</name>
//                    <type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//                </field>
//				<field>
//					<name>membership_fee_def_id</name>
//                    <type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//				</field>
//				<field>
//	                <name>society_contact_id</name>
//	                <type>integer</type>
//	                <notnull>false</notnull>
//	            </field>
//				<field>
//					<name>article_id</name>
//                    <type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//				</field>
//				<field>
//					<name>price_group_id</name>
//                    <type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//				</field>
//				<field>
//					<name>fee_base_category</name>
//                    <type>text</type>
//					<length>64</length>
//                    <notnull>false</notnull>
//				</field>
//				<index>
//                    <name>id</name>
//                    <primary>true</primary>
//                    <field>
//                        <name>id</name>
//                    </field>
//                </index>
//			</declaration>
//		</table>
//		';
//		$table = Setup_Backend_Schema_Table_Factory::factory('String', $tableDefinition);
//		$this->_backend->createTable($table);
//		
		$this->setApplicationVersion('Membership', '1.3');
	}
	
	public function update_3(){
//		
//		$this->_backend->dropIndex('membership_fee_definition', 'unique_type_category');
//		
//		$dropColumns = array(
//			'membership_type',
//			'fee_category'
//		);
//		$this->dropColumns('membership_fee_definition',$dropColumns);
//		
//		$declaration = new Setup_Backend_Schema_Field_Xml('
//                <field>
//                    <name>iterator_filter_class</name>
//                    <type>text</type>
//					<length>128</length>
//                    <notnull>false</notnull>
//                </field>');
//        $this->_backend->addCol('membership_fee_definition', $declaration);
//		
//        $declaration = new Setup_Backend_Schema_Field_Xml('
//				<field>
//                    <name>iterator_filters</name>
//                    <type>text</type>
//					<notnull>false</notnull>
//                </field>');
//        $this->_backend->addCol('membership_fee_definition', $declaration);
		$this->setApplicationVersion('Membership', '1.4');
	}
	
	public function update_4(){
//        $declaration = new Setup_Backend_Schema_Field_Xml('
//				<field>
//	                <name>society_member_id</name>
//	                <type>integer</type>
//	                <notnull>false</notnull>
//	            </field>');
//        $this->_backend->addCol('membership', $declaration);
		$this->setApplicationVersion('Membership', '1.5');
	}
	
	public function update_5(){
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>valid_from_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>');
        $this->_backend->addCol('membership_member_fee_group', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>valid_to_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>');
        $this->_backend->addCol('membership_member_fee_group', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>invoice_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>');
        $this->_backend->addCol('membership_kind', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.6');
	}
	
	public function update_6(){
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>dialog_text_assoc</name>
	                <type>text</type>
					<length>128</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>');
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>dialog_text_member_nr</name>
	                <type>text</type>
					<length>128</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>');
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>uses_fee_progress</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>');
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>uses_member_fee_groups</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>');
        $this->_backend->addCol('membership_kind', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.61');
	}
	
	public function update_61(){
		$declaration = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_association</name>
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
	                <name>contact_id</name>
	                <type>integer</type>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
				<field>
					<name>association_nr</name>
					<type>text</type>
					<length>16</length>
					<default>null</default>
					<notnull>true</notnull>
				</field>
				<field>
					<name>association_name</name>
					<type>text</type>
					<length>64</length>
					<default>null</default>
					<notnull>true</notnull>
				</field>
				<field>
	                <name>short_name</name>
	                <type>text</type>
					<length>24</length>
					<notnull>false</notnull>
	            </field>
				<field>
	                <name>is_default</name>
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
		
		$this->_backend->createTable($declaration);

		$this->_backend->dropIndex('membership', 'membership::association_contact_id--addressbook::id');
		
		$declaration = new Setup_Backend_Schema_Field_Xml('
                <field>
	                <name>association_id</name>
	               	<type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
	            </field>');
	    $this->_backend->alterCol('membership', $declaration, 'association_contact_id');
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
	                <name>dialog_text_assoc_nr</name>
	                <type>text</type>
					<length>64</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_kind', $declaration);
	    
		$this->setApplicationVersion('Membership', '1.62');
	}
	
	public function update_62(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>fee_from_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>fee_to_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
	
	    $this->setApplicationVersion('Membership', '1.63');
	}
	
	public function update_63(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
                    <name>progress_nr</name>
                    <type>integer</type>
					<length>4</length>
                    <notnull>true</notnull>
					<default>1</default>
                </field>
	    ');
	    $this->_backend->addCol('membership_fee_progress', $declaration);
	
	    $this->setApplicationVersion('Membership', '1.64');
	}
	
	public function update_64(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
                    <name>begin_progress_nr</name>
                    <type>integer</type>
					<length>4</length>
                    <notnull>true</notnull>
					<default>1</default>
                </field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
	
	    $this->setApplicationVersion('Membership', '1.65');
	}
	
	public function update_65(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
	                <name>identical_contact</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_kind', $declaration);
	
	    $this->setApplicationVersion('Membership', '1.66');
	}
	
	public function update_66(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
	                <name>key</name>
	                <type>text</type>
					<length>16</length>
					<notnull>true</notnull>
	            </field>
	    ');
	    $this->_backend->addCol('membership_fee_group', $declaration);
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
	                <name>fee_class</name>
	                <type>text</type>
					<length>16</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_fee_group', $declaration);
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
	                <name>text1</name>
	                <type>text</type>
					<length>48</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_fee_group', $declaration);
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>text2</name>
	                <type>text</type>
					<length>48</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_fee_group', $declaration);
	    $this->setApplicationVersion('Membership', '1.67');
	}		

	public function update_67(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>valid_from_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership_member_fee_group', $declaration);
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>valid_to_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership_member_fee_group', $declaration);
	    $this->setApplicationVersion('Membership', '1.68');
	}		

	public function update_68(){
				$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>donation</name>
					<type>float</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
					<name>additional_fee</name>
					<type>float</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
	    $this->setApplicationVersion('Membership', '1.69');
	}
	
	public function update_69(){
		$dec = new Setup_Backend_Schema_Index_Xml('
			<index>
           		<name>membership_kind_id</name>
           		<field>
           			<name>membership_kind_id</name>
           		</field>
        	</index>
		');
		$this->_backend->addIndex('membership_fee_group', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			<index>
//                    <name>membership_fee_group::membership_kind_id-membership_kind::id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>membership_kind_id</name>
//                    </field>
//                    <reference>
//                        <table>membership_kind</table>
//                        <field>id</field>
//                    </reference>
//                </index> 
//		');
//		$this->_backend->addForeignKey('membership_fee_group', $dec);
//		
		$dec = new Setup_Backend_Schema_Index_Xml('
			<index>
                    <name>article_id</name>
                    <field>
                        <name>article_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_fee_group', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			<index>
//			        <name>membership_fee_group::article_id-billing_article::id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>article_id</name>
//                    </field>
//                    <reference>
//                        <table>billing_article</table>
//                        <field>id</field>
//                    </reference>
//                </index> 
//		');
//		$this->_backend->addForeignKey('membership_fee_group', $dec);
//
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>name</name>
                    <field>
                        <name>name</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_fee_group', $dec);	
		$dec = new Setup_Backend_Schema_Index_Xml('
			 <index>
                    <name>key</name>
                    <field>
                        <name>key</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_fee_group', $dec);
		

		// table membership_fee_group
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>fee_group_id</name>
                    <field>
                        <name>fee_group_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_member_fee_group', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			    <index>
//                    <name>fee_group_id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>fee_group_id</name>
//                    </field>
//                    <reference>
//                        <table>membership_fee_group</table>
//                        <field>id</field>
//                    </reference>
//                </index> 
//		');
//		$this->_backend->addForeignKey('membership_member_fee_group', $dec);
		
				$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>member_id</name>
                    <field>
                        <name>member_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_member_fee_group', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			    <index>
//                    <name>member_id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>member_id</name>
//                    </field>
//                    <reference>
//                        <table>membership</table>
//                        <field>id</field>
//                    </reference>
//                </index>
//		');
//		$this->_backend->addForeignKey('membership_member_fee_group', $dec);
		
		
		
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>article_id</name>
                    <field>
                        <name>article_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_member_fee_group', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			   <index>
//                    <name>article_id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>article_id</name>
//                    </field>
//                    <reference>
//                        <table>billing_article</table>
//                        <field>id</field>
//                    </reference>
//                </index> 
//		');
		
//		$this->_backend->addForeignKey('membership_member_fee_group', $dec);
		
		// 	table association
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>association_name</name>
                    <field>
                        <name>association_name</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_association', $dec);	
		$dec = new Setup_Backend_Schema_Index_Xml('
			   <index>
                    <name>association_nr</name>
                    <field>
                        <name>association_nr</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_association', $dec);
//
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>contact_id</name>
                    <field>
                        <name>contact_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_association', $dec);	
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			   <index>
//                    <name>mem_assoc_contact_id</name>
//                    <foreign>true</foreign>
//                    <field>
//                        <name>contact_id</name>
//                    </field>
//                    <reference>
//                        <table>addressbook</table>
//                        <field>id</field>
//                    </reference>
//                </index> 
//		');
//		$this->_backend->addForeignKey('membership_association', $dec);
		
		// table membership
//		$dec = new Setup_Backend_Schema_Index_Xml('
//				<index>
//                    <name>contact_id</name>
//                    <field>
//                        <name>contact_id</name>
//                    </field>
//                </index>
//		');
//		$this->_backend->addIndex('membership', $dec);	
		
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>parent_member_id</name>
                    <field>
                        <name>parent_member_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership', $dec);	
		
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			    <index>
//                    <name>membership::parent_member_id-membership::id</name>
//                    <field>
//                        <name>parent_member_id</name>
//                    </field>
//                    <foreign>true</foreign>
//                    <reference>
//                        <table>membership</table>
//                        <field>id</field>
//                        <ondelete>CASCADE</ondelete>
//                        <onupdate>CASCADE</onupdate>
//                    </reference>
//                    <ondelete>cascade</ondelete>
//                </index>
//		');
//		$this->_backend->addForeignKey('membership', $dec);
//		
				$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>association_id</name>
                    <field>
                        <name>association_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership', $dec);	
		
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			    <index>
//                    <name>membership::association_id-association::id</name>
//                    <field>
//                        <name>association_id</name>
//                    </field>
//                    <length>40</length>
//                    <foreign>true</foreign>
//                    <reference>
//                        <table>association</table>
//                        <field>id</field>
//                        <ondelete>CASCADE</ondelete>
//                        <onupdate>CASCADE</onupdate>
//                    </reference>
//                    <ondelete>cascade</ondelete>
//                </index>
//		');
//		$this->_backend->addForeignKey('membership', $dec);
		
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>fee_group_id</name>
                    <field>
                        <name>fee_group_id</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership', $dec);	
		
//		$dec = new Setup_Backend_Schema_Index_Xml('
//			    <index>
//                    <name>membership::fee_group_id-membership_fee_group::id</name>
//                    <field>
//                        <name>fee_group_id</name>
//                    </field>
//                    <foreign>true</foreign>
//                    <reference>
//                        <table>membership_fee_group</table>
//                        <field>id</field>
//                        <ondelete>CASCADE</ondelete>
//                        <onupdate>CASCADE</onupdate>
//                    </reference>
//                    <ondelete>cascade</ondelete>
//                </index>
//		');
//		$this->_backend->addForeignKey('membership', $dec);
		
				
		$dec = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>member_nr</name>
                    <field>
                        <name>member_nr</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership', $dec);	
		$this->setApplicationVersion('Membership', '1.70');
	}
	
	public function update_70(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>category</name>
				<type>enum</type>
				<value>I</value>
				<value>II</value>
				<value>III</value>
				<value>IV</value>
				<value>V</value>
				<default>I</default>
			</field>
		');
		$this->_backend->addCol('membership_member_fee_group', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>summarize</name>
				<type>boolean</type>
				<default>true</default>
			</field>
		');
		$this->_backend->addCol('membership_member_fee_group', $dec);		
		$this->setApplicationVersion('Membership', '1.71');
	}
	
	public function update_71(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>parent_member_id</name>
                <type>text</type>
				<length>40</length>
                <notnull>false</notnull>
				<default>null</default>
            </field>
		');
		$this->_backend->addCol('membership_fee_progress', $dec);
	
		$this->setApplicationVersion('Membership', '1.72');
	}
	
	public function update_72(){
		$declaration = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_action</name>
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
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>category</name>
	                <type>enum</type>
					<value>DATA</value>
					<value>PRINT</value>
					<value>EXPORT</value>
					<value>BILLING</value>
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
		
		$this->_backend->createTable($declaration);
		
		$declaration = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_action_history</name>
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
                    <notnull>true</notnull>
                </field>
				<field>
	                <name>member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>true</notnull>
	            </field>
	            <field>
	                <name>association_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
				<field>
	                <name>parent_member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
				<field>
					<name>old_data_id</name>
					<type>integer</type>
				    <notnull>false</notnull>
				</field>
				<field>
					<name>data_id</name>
					<type>integer</type>
				    <notnull>false</notnull>
				</field>
				<field>
                    <name>fee_progress_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>false</notnull>
					<default>null</default>
                </field>
				<field>
					<name>action_id</name>
					<type>text</type>
					<length>40</length>
	                <notnull>true</notnull>
				</field>
				<field>
					<name>action_text</name>
					<type>text</type>
					<length>128</length>
	                <notnull>false</notnull>
				</field>
				<field>
					<name>action_data</name>
					<type>text</type>
					<notnull>false</notnull>
				</field>
				<field>
					<name>action_category</name>
					<type>enum</type>
					<value>DATA</value>
					<value>PRINT</value>
					<value>EXPORT</value>
					<value>BILLING</value>
				</field>	
				<field>
					<name>action_type</name>
					<type>enum</type>
					<value>MANUAL</value>
					<value>AUTO</value>
					<default>AUTO</default>
				</field>
				<field>
					<name>action_state</name>
					<type>enum</type>
					<value>OPEN</value>
					<value>DONE</value>
					<value>ERROR</value>
					<default>DONE</default>
				</field>
				<field>
					<name>created_datetime</name>
					<type>datetime</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>valid_datetime</name>
					<type>datetime</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>to_process_datetime</name>
					<type>datetime</type>
					<notnull>true</notnull>
				</field>
				<field>
					<name>process_datetime</name>
					<type>datetime</type>
					<notnull>true</notnull>
				</field>
				<field>
                    <name>created_by_user</name>
                    <type>text</type>
                    <length>40</length>
                </field>
				<field>
                    <name>processed_by_user</name>
                    <type>text</type>
                    <length>40</length>
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
		
		$this->_backend->createTable($declaration);
		
		$declaration = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_data</name>	
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
					<notnull>true</notnull>
                </field>
				<field>
	                <name>member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>true</notnull>
	            </field>
				<field>
	                <name>parent_member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
				<field>
	                <name>association_id</name>
	               	<type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
	            </field>
				<field>
                    <name>fee_group_id</name>
                    <type>text</type>
					<length>40</length>
                   	<notnull>false</notnull>
					<default>null</default>
                </field>
				<field>
					<name>membership_type</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
				</field>
				<field>
					<name>membership_status</name>
					<type>enum</type>
					<value>ACTIVE</value>
					<value>PASSIVE</value>
					<default>ACTIVE</default>
				</field>
				<field>
					<name>fee_payment_interval</name>
					<type>enum</type>
					<value>NOVALUE</value>
					<value>YEAR</value>
					<value>QUARTER</value>
					<value>MONTH</value>
					<default>NOVALUE</default>
				</field>				
				<field>
					<name>fee_payment_method</name>
					<type>enum</type>
					<value>NOVALUE</value>
					<value>BANKTRANSFER</value>
					<value>DEBIT</value>
					<default>NOVALUE</default>
				</field>
				<field>
					<name>bank_code</name>
					<type>text</type>
					<length>12</length>
					<notnull>false</notnull>
				</field>	
				<field>
					<name>bank_name</name>
					<type>text</type>
					<length>64</length>
					<notnull>false</notnull>
				</field>
				<field>
					<name>bank_account_nr</name>
					<type>text</type>
					<length>64</length>
					<notnull>false</notnull>
				</field>
				<field>
					<name>account_holder</name>
					<type>text</type>
					<length>64</length>
					<notnull>false</notnull>
				</field>															
				<field>
					<name>individual_yearly_fee</name>
					<type>float</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>donation</name>
					<type>float</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>additional_fee</name>
					<type>float</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
				
				<index>
                    <name>member_id</name>
                    <field>
                        <name>member_id</name>
                    </field>
                </index>
				<index>
                    <name>parent_member_id</name>
                    <field>
                        <name>parent_member_id</name>
                    </field>
                </index>
				
				<index>
                    <name>fee_group_id</name>
                    <field>
                        <name>fee_group_id</name>
                    </field>
                </index>
     		</declaration>
		</table>
		');
		
		$this->_backend->createTable($declaration);
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>CREATE</value>
			</field>
			<field>
				<name>name</name>
				<value>Neuzugang</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>DISCHARGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Kündigung</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>TERMINATION</value>
			</field>
			<field>
				<name>name</name>
				<value>Austritt</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PARENTCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Verein</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>ASSOCIATIONCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Hauptorganisation</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>FEEGROUPCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Beitragsgruppe</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>MEMKINDCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Mitgliedsart</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>MEMSTATECHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Mitgliedsstatus</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PAYMENTMETHODCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Zahlungsart</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PAYMENTINTERVALCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Zahlungsintervall</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>BANKACCOUNTCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Bankdaten</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PRINTMEMCARD</value>
			</field>
			<field>
				<name>name</name>
				<value>Druck Mitgliedsausweis</value>
			</field>
			<field>
				<name>category</name>
				<value>PRINT</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>EXPORTMEMCARD</value>
			</field>
			<field>
				<name>name</name>
				<value>Export Mitgliedsausweis</value>
			</field>
			<field>
				<name>category</name>
				<value>EXPORT</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>BILLMEMBER</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsrechnung Mitglied</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>BILLPARENTMEMBER</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsrechnung Verein berücksichtigt</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		$this->setApplicationVersion('Membership', '1.73');
	}
	
	public function update_73(){
	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>is_invoice_component</name>
					<type>boolean</type>
					<default>false</default>
				</field>
	    ');
	    $this->_backend->addCol('membership_feedef_dfilters', $declaration);
	    
	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>valid_from</name>
					<type>datetime</type>
					<notnull>true</notnull>
				</field>
	    ');
	    $this->_backend->addCol('membership_data', $declaration);
	    
	    
	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>member_ext_nr</name>
	                <type>text</type>
					<length>64</length>
	                <notnull>false</notnull>
	                <default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership', $declaration);

	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>child_member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
	    ');
	    $this->_backend->addCol('membership_action_history', $declaration);
	    
	    $this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PARENTENTER</value>
			</field>
			<field>
				<name>name</name>
				<value>Zugang Mitglied</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
	    
	    $this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>PARENTLEAVE</value>
			</field>
			<field>
				<name>name</name>
				<value>Abgang Mitglied</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
	    
	    $this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>DTAEXPPARENT</value>
			</field>
			<field>
				<name>name</name>
				<value>DTA-Export</value>
			</field>
			<field>
				<name>category</name>
				<value>EXPORT</value>
			</field>
		</record>'));
	    	    
	    $this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>DTAEXPCHILD</value>
			</field>
			<field>
				<name>name</name>
				<value>In DTA-Export enthalten</value>
			</field>
			<field>
				<name>category</name>
				<value>EXPORT</value>
			</field>
		</record>'));
	    
	    $this->setApplicationVersion('Membership', '1.74');
	}	
	
	
	public function update_74(){
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_export</name>	
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
					<notnull>true</notnull>
                </field>
				<field>
                    <name>output_template_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>classify_main_orga</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>classify_society</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>classify_fee_group</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>classify_mem_kind</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
				<field>
					<name>result_source</name>
					<type>enum</type>
					<value>ASSET</value>
					<value>FLOW</value>
					<default>ASSET</default>
				</field>
				<field>
					<name>result_type</name>
					<type>enum</type>
					<value>COUNT</value>
					<value>DATA</value>
					<default>DATA</default>
				</field>
				<field>
					<name>output_type</name>
					<type>enum</type>
					<value>CSV</value>
					<value>ODT</value>
					<default>ODT</default>
				</field>
				<field>
					<name>begin_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>end_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
                    <name>filter_main_orga</name>
                    <type>text</type>
					<notnull>false</notnull>
                </field>
				<field>
                    <name>filter_society</name>
                    <type>text</type>
					<notnull>false</notnull>
                </field>
				<field>
                    <name>filter_membership</name>
                    <type>text</type>
					<notnull>false</notnull>
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
	
		$this->setApplicationVersion('Membership', '1.75');
	}
	
	
	public function update_75(){
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_committee_kind</name>
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
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>is_default</name>
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
			<name>membership_committee_level</name>
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
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>is_default</name>
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
			<name>membership_committee_function</name>
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
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>is_default</name>
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
			<name>membership_award_list</name>
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
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>is_default</name>
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
			<name>membership_award</name>
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
	                <name>award_list_id</name>
	               	<type>text</type>
					<length>40</length>
	                <notnull>true</notnull>
	            </field>
				<field>
					<name>award_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
				<index>
                    <name>member_id</name>
                    <field>
                        <name>member_id</name>
                    </field>
                </index>
			</declaration>
		</table>	
    	');
		$this->_backend->createTable($dec);
		
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_committee</name>
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
					<name>committee_nr</name>
					<type>text</type>
					<length>16</length>
					<default>null</default>
					<notnull>true</notnull>
				</field>
				<field>
					<name>committee_kind_id</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
				</field>
				<field>
					<name>committee_level_id</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
				</field>
				<field>
					<name>name</name>
					<type>text</type>
					<length>128</length>
					<default>null</default>
					<notnull>true</notnull>
				</field>
				<field>
	                <name>challenge</name>
	                <type>text</type>
					<length>512</length>
					<notnull>false</notnull>
	            </field>
				<field>
	                <name>description</name>
	                <type>text</type>
					<notnull>false</notnull>
	            </field>
				<field>
					<name>begin_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>end_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
	                <name>jur_committee</name>
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
				<index>
                    <name>name</name>
                    <field>
                        <name>name</name>
                    </field>
                </index>
				<index>
                    <name>committee_nr</name>
                    <field>
                        <name>committee_nr</name>
                    </field>
                </index>
			</declaration>
		</table>
		
		');
		$this->_backend->createTable($dec);
	
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_committee_func</name>
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
	                <name>committee_id</name>
	               	<type>text</type>
					<length>40</length>
	                <notnull>true</notnull>
	            </field>
				<field>
					<name>committee_function_id</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
				</field>
				<field>
	                <name>description</name>
	                <type>text</type>
					<notnull>false</notnull>
	            </field>
				<field>
					<name>begin_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>end_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
	                <name>management_mail</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>treasure_mail</name>
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
				<index>
                    <name>member_id</name>
                    <field>
                        <name>member_id</name>
                    </field>
                </index>
				<index>
                    <name>committee_id</name>
                    <field>
                        <name>committee_id</name>
                    </field>
                </index>
				<index>
                    <name>committee_function_id</name>
                    <field>
                        <name>committee_function_id</name>
                    </field>
                </index>
				
			</declaration>
		</table>
		
		');
		$this->_backend->createTable($dec);
		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_kind</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Leitungsgremium</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_kind</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Ausschuss</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_kind</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>temporäres Gremium</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_kind</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>externes Gremium</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_level</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Bund</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_level</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Land</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_level</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Kreis</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_level</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Ort</value>
//			</field>
//		</record>'));
//		
//		$this->_backend->execInsertStatement(new SimpleXMLElement('
//		<record>
//			<table>
//				<name>membership_committee_level</name>
//			</table>
//			<field>
//				<name>name</name>
//				<value>Verein</value>
//			</field>
//		</record>'));
		
		// create committee_nr counter
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>number_base</name>
			</table>
			<field>
				<name>key</name>
				<value>membership_committee_nr</value>
			</field>
			<field>
				<name>formula</name>
				<value>N1</value>
			</field>
			<field>
				<name>number1</name>
				<value>0</value>
			</field>
			<field>
				<name>number2</name>
				<value>0</value>
			</field>
			<field>
				<name>number3</name>
				<value>0</value>
			</field>
			<field>
				<name>last_generated</name>
				<value>0</value>
			</field>			
		</record>'));
		$this->setApplicationVersion('Membership', '1.76');
	}

	public function update_76(){
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>fee_payment_method</name>
                   	<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                    <default>NOVALUE</default>
                </field>
		');
		$this->_backend->alterCol('membership', $dec);
	
		$this->setApplicationVersion('Membership', '1.77');
	}
	
	public function update_77(){
//		$dec = new Setup_Backend_Schema_Field_Xml('
//				<field>
//                    <name>fee_payment_method</name>
//                   	<type>text</type>
//					<length>40</length>
//                    <notnull>true</notnull>
//                    <default>NOVALUE</default>
//                </field>
//		');
//		$this->_backend->alterCol('membership_data', $dec);
	
		$this->setApplicationVersion('Membership', '1.78');
	}
	
	public function update_78(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
                    <name>account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
	    ');
	    $this->_backend->addCol('membership', $declaration);
//		
//	    $declaration = new Setup_Backend_Schema_Index_Xml('
//	    		<index>
//                    <name>account_id</name>
//                    <field>
//                        <name>account_id</name>
//                    </field>
//                </index>
//	    ');
//	    $this->_backend->addIndex('membership', $_declaration);
//	    
//	    $declaration = new Setup_Backend_Schema_Index_Xml('
//	    		<index>
//                    <name>member_nr</name>
//                    <field>
//                        <name>member_nr</name>
//                    </field>
//                </index>
//	    ');
//	    $this->_backend->addIndex('membership', $_declaration);
	    
	    
	    $this->setApplicationVersion('Membership', '1.79');
	}
	
	public function update_79(){
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_job</name>	
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
	                <notnull>true</notnull>
                </field>
				<field>
                    <name>account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
				<field>
	                <name>job_nr</name>
	                <type>text</type>
					<length>16</length>
	                <notnull>false</notnull>
				</field>
				<field>
					<name>job_category</name>
					<type>enum</type>
					<value>FEEPROGRESS</value>
					<value>FEEINVOICE</value>
					<value>FEEINVOICECURRENT</value>
					<value>DTAEXPORT</value>
					<value>PAYMENT</value>
					<value>MANUALEXPORT</value>
					<value>PREDEFINEDEXPORT</value>
				</field>
				<field>
					<name>job_type</name>
					<type>enum</type>
					<value>RUNTIME</value>
					<value>SCHEDULER</value>
				</field>
				<field>
	                <name>job_data</name>
	                <type>text</type>
					<notnull>false</notnull>
		        </field>
				<field>
					<name>job_state</name>
					<type>enum</type>
					<value>TOBEPROCESSED</value>
					<value>RUNNING</value>
					<value>PROCESSED</value>
					<value>ABANDONED</value>
					<value>USERCANCELLED</value>
				</field>
				<field>
					<name>job_result_state</name>
					<type>enum</type>
					<value>UNDEFINED</value>
					<value>OK</value>
					<value>PARTLYERROR</value>
					<value>ERROR</value>
					<default>UNDEFINED</default>
				</field>
				<field>
					<name>on_error</name>
					<type>enum</type>
					<value>STOP</value>
					<value>PROCEED</value>
					<default>STOP</default>
				</field>
				<field>
	                <name>process_info</name>
	                <type>text</type>
					<notnull>false</notnull>
		        </field>
				<field>
	                <name>error_info</name>
	                <type>text</type>
					<notnull>false</notnull>
		        </field>
				<field>
	                <name>ok_count</name>
	                <type>integer</type>
					<default>0</default>
					<notnull>true</notnull>
		        </field>
				<field>
	                <name>error_count</name>
	                <type>integer</type>
					<default>0</default>
					<notnull>true</notnull>
		        </field>
				<field>
					<name>schedule_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>start_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>end_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
                <index>
                    <name>job_nr</name>
                    <field>
                        <name>job_nr</name>
                    </field>
                </index>
			</declaration>
		</table>
		
		');
		$this->_backend->createTable($dec);
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>number_base</name>
			</table>
			<field>
				<name>key</name>
				<value>membership_job_nr</value>
			</field>
			<field>
				<name>formula</name>
				<value>N1</value>
			</field>
			<field>
				<name>number1</name>
				<value>0</value>
			</field>
			<field>
				<name>number2</name>
				<value>0</value>
			</field>
			<field>
				<name>number3</name>
				<value>0</value>
			</field>
			<field>
				<name>last_generated</name>
				<value>0</value>
			</field>			
		</record>'));
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>job_id</name>
                <type>integer</type>
				<notnull>false</notnull>
				<default>null</default>
            </field>
		');
		$this->_backend->addCol('membership_action_history', $dec);
		
//		$dec = new Setup_Backend_Schema_Index_Xml('
//				<index>
//                    <name>job_nr</name>
//                    <field>
//                        <name>job_nr</name>
//                    </field>
//                </index>
//		');
//		$this->_backend->addIndex('membership_action_history', $dec);
		
		$this->setApplicationVersion('Membership', '1.80');
	}
	
	public function update_80(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>order_id</name>
                <type>integer</type>
				<notnull>false</notnull>
				<default>null</default>
            </field>
		');
		$this->_backend->addCol('membership_action_history', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>receipt_id</name>
               	<type>integer</type>
				<notnull>false</notnull>
				<default>null</default>
            </field>
		');
		$this->_backend->addCol('membership_action_history', $dec);
				
		$this->setApplicationVersion('Membership', '1.81');
	}
	
	public function update_81(){
		if(!$this->_backend->columnExists('account_id', 'membership')){
			$declaration = new Setup_Backend_Schema_Field_Xml('
	    		<field>
                    <name>account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
		    ');
			$this->_backend->addCol('membership', $declaration);
		}
		$this->setApplicationVersion('Membership', '1.82');
	}
	
	public function update_82(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>job_name1</name>
                <type>text</type>
				<length>64</length>
                <notnull>false</notnull>
			</field>
		');
		$this->_backend->addCol('membership_job', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>job_name2</name>
                <type>text</type>
				<length>128</length>
                <notnull>false</notnull>
			</field>
		');
		$this->_backend->addCol('membership_job', $dec);
		
		$this->setApplicationVersion('Membership', '1.83');
	}	
	
	public function update_83(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>create_datetime</name>
				<type>datetime</type>
				<notnull>false</notnull>
				<default>null</default>
			</field>
		');
		$this->_backend->addCol('membership_job', $dec);
		$this->setApplicationVersion('Membership', '1.84');
	}
	
	
	public function update_84(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>error_info</name>
				<type>text</type>
				<notnull>false</notnull>
			</field>
		');
		$this->_backend->addCol('membership_action_history', $dec);
		$this->setApplicationVersion('Membership', '1.85');
	}
	
	public function update_85(){
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>CREATEFEEPROGRESS</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsverlauf anlegen</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>UPDATEFEEPROGRESS</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsverlauf aktualisieren</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		
		$this->setApplicationVersion('Membership', '1.86');
	}
	
	public function update_86(){
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_entry_reason</name>
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
                </field>
				<field>
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>key</name>
	                <type>text</type>
					<length>128</length>
					<notnull>false</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>is_default</name>
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
			<name>membership_termination_reason</name>
			<version>1</version>
			<engine>InnoDB</engine>
	     	<charset>utf8</charset>
			<declaration>
                <field>
                    <name>id</name>
                    <type>integer</type>
					<autoincrement>true</autoincrement>
                </field>
				<field>
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>key</name>
	                <type>text</type>
					<length>128</length>
					<notnull>false</notnull>
					<default>false</default>
	            </field>
				<field>
	                <name>is_default</name>
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
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_entry_reason</name>
			</table>
			<field>
				<name>name</name>
				<value>keine Auswahl</value>
			</field>
			<field>
				<name>is_default</name>
				<value>1</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_termination_reason</name>
			</table>
			<field>
				<name>name</name>
				<value>keine Auswahl</value>
			</field>
			<field>
				<name>is_default</name>
				<value>1</value>
			</field>
		</record>'));
		
		$this->setApplicationVersion('Membership', '1.87');
	}
	
	public function update_87(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>job_id</name>
                <type>integer</type>
			    <notnull>true</notnull>
             </field>
		');
		$this->_backend->addCol('membership_job', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>job_category</name>
					<type>enum</type>
					<value>FEEPROGRESS</value>
					<value>FEEINVOICE</value>
					<value>FEEINVOICECURRENT</value>
					<value>DTAEXPORT</value>
					<value>PAYMENT</value>
					<value>MANUALEXPORT</value>
					<value>PREDEFINEDEXPORT</value>
					<value>PRINT</value>
				</field>
		');
		$this->_backend->alterCol('membership_job', $dec);
		
		$this->setApplicationVersion('Membership', '1.88');
	}	
	
	public function update_88(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>job_id</name>
                <type>integer</type>
			    <notnull>false</notnull>
			    <default>null</default>
             </field>
		');
		$this->_backend->alterCol('membership_job', $dec);
		
		$this->setApplicationVersion('Membership', '1.89');
	}	
	
	public function update_89(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>calculation_type</name>
				<type>enum</type>
				<value>UNSPECIFIED</value>
				<value>FEEGROUPOVERVIEW</value>
				<value>FEEOVERVIEW</value>
				<default>UNSPECIFIED</default>
			</field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
		$this->setApplicationVersion('Membership', '1.90');
	}
	
	public function update_90(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>member_id</name>
               	<type>text</type>
				<length>40</length>
                <notnull>false</notnull>
				<default>null</default>
            </field>
		');
		$this->_backend->addCol('membership_committee', $dec);
		
		$this->setApplicationVersion('Membership', '1.91');
	}
	
	public function update_91(){
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_filter_set</name>
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
					<name>conjunction</name>
					<type>enum</type>
					<value>AND</value>
					<value>OR</value>
					<value>XOR</value>
					<default>AND</default>
				</field>
				<field>
					<name>result_type</name>
					<type>enum</type>
					<value>SCALAR</value>
					<value>SCALARSET</value>
					<value>DATAOBJECT</value>
					<value>DATAOBJECTCOLLECTION</value>
					<default>SCALAR</default>
				</field>
				<field>
					<name>transform</name>
					<type>enum</type>
					<value>PERCENTAGE</value>
					<value>DISTRIBUTION</value>
					<value>UNDEFINED</value>
					<default>UNDEFINED</default>
				</field>
				<field>
	                <name>name</name>
	                <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
	            </field>
				<field>
	                <name>description</name>
	                <type>text</type>
					<length>1024</length>
					<notnull>true</notnull>
	            </field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
				<index>
                    <name>name</name>
                    <field>
                        <name>name</name>
                    </field>
                </index>
			</declaration>
		</table>
		');
		$this->_backend->createTable($dec);
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_filter_result</name>	
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
                    <name>filter_set_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				
                <field>
                    <name>name</name>
                    <type>text</type>
					<length>128</length>
					<notnull>true</notnull>
                </field>
				 <field>
                    <name>key</name>
                    <type>text</type>
					<length>48</length>
					<notnull>false</notnull>
                </field>
				<field>
					<name>type</name>
					<type>enum</type>
					<value>COUNT</value>
					<value>DATA</value>
					<value>TRANSFORM</value>
					<default>COUNT</default>
				</field>
				<field>
					<name>sub_type</name>
					<type>enum</type>
					<value>TOTAL</value>
					<value>PART</value>
					<value>UNDEFINED</value>
					<default>UNDEFINED</default>
				</field>
				<field>
                    <name>filters</name>
                    <type>text</type>
					<notnull>false</notnull>
                </field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
				<index>
                    <name>fk_filter_result_filter_set</name>
                    <foreign>true</foreign>
                    <field>
                        <name>filter_set_id</name>
                    </field>
                    <reference>
                        <table>membership_filter_set</table>
                        <field>id</field>
                    </reference>
                </index> 
				<index>
                    <name>name</name>
                    <field>
                        <name>name</name>
                    </field>
                </index>
			</declaration>
		</table>
		');
		$this->_backend->createTable($dec);
		$this->setApplicationVersion('Membership', '1.92');
	}
	
	public function update_92(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>birth_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>birth_day</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>birth_month</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>birth_year</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>entry_year</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                    <name>filter_set_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>false</notnull>
					<default>null</default>
                </field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
		
		/*
		 * 
		 * adding index failed: TODO
		$dec = new Setup_Backend_Schema_Index_Xml('
			<index>
                    <name>fk_mem_export_filter_set</name>
                    <foreign>true</foreign>
                    <field>
                        <name>filter_set_id</name>
                    </field>
                    <reference>
                        <table>membership_filter_set</table>
                        <field>id</field>
                    </reference>
                </index> 
		');
		$this->_backend->addIndex('membership_export', $dec);	
		
		*/
		$this->setApplicationVersion('Membership', '1.93');
	}
	
	public function update_93(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                    <name>sort_order</name>
                    <type>integer</type>
					<length>4</length>
					<notnull>true</notnull>
					<default>0</default>
                </field>
		');
		$this->_backend->addCol('membership_filter_result', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>assoc_sortfield1</name>
					<type>enum</type>
					<value>short_name</value>
					<value>assoc_nr</value>
					<value>association_name</value>
					<value>UNDEFINED</value>
					<default>assoc_nr</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			
				<field>
					<name>assoc_sortfield1_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
				
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>assoc_sortfield2</name>
					<type>enum</type>
					<value>short_name</value>
					<value>assoc_nr</value>
					<value>association_name</value>
					<value>UNDEFINED</value>
					<default>UNDEFINED</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
						
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>assoc_sortfield2_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>society_sortfield1</name>
					<type>enum</type>
					<value>member_nr</value>
					<value>n_given</value>
					<value>n_family</value>
					<value>n_fileas</value>
					<value>member_age</value>
					<value>person_age</value>
					<value>birth_date</value>
					<value>birth_year</value>
					<value>birth_month</value>
					<value>birth_day</value>
					<value>UNDEFINED</value>
					<default>member_nr</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
				
		$dec = new Setup_Backend_Schema_Field_Xml('
		<field>
					<name>society_sortfield1_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
					
		$dec = new Setup_Backend_Schema_Field_Xml('
		<field>
					<name>society_sortfield2</name>
					<type>enum</type>
					<value>member_nr</value>
					<value>n_given</value>
					<value>n_family</value>
					<value>n_fileas</value>
					<value>member_age</value>
					<value>person_age</value>
					<value>birth_date</value>
					<value>birth_year</value>
					<value>birth_month</value>
					<value>birth_day</value>
					<value>UNDEFINED</value>
					<default>UNDEFINED</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
					
		$dec = new Setup_Backend_Schema_Field_Xml('
		<field>
					<name>society_sortfield2_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
						
		$dec = new Setup_Backend_Schema_Field_Xml('
		<field>
					<name>member_sortfield1</name>
					<type>enum</type>
					<value>member_nr</value>
					<value>n_given</value>
					<value>n_family</value>
					<value>n_fileas</value>
					<value>member_age</value>
					<value>person_age</value>
					<value>birth_date</value>
					<value>birth_year</value>
					<value>birth_month</value>
					<value>birth_day</value>
					<value>UNDEFINED</value>
					<default>member_nr</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
				
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>member_sortfield1_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
						
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>member_sortfield2</name>
					<type>enum</type>
					<value>member_nr</value>
					<value>n_given</value>
					<value>n_family</value>
					<value>n_fileas</value>
					<value>member_age</value>
					<value>person_age</value>
					<value>birth_date</value>
					<value>birth_year</value>
					<value>birth_month</value>
					<value>birth_day</value>
					<value>UNDEFINED</value>
					<default>UNDEFINED</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
					
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>member_sortfield2_dir</name>
					<type>enum</type>
					<value>ASC</value>
					<value>DESC</value>
					<default>ASC</default>
				</field>
		');
		$this->_backend->addCol('membership_export', $dec);
				
				
		
		$this->setApplicationVersion('Membership', '1.94');
	}
	
	public function update_94(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>committee_kind_id</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
				</field>
		');
		$this->_backend->addCol('membership_committee_function', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
	                <name>parent_member_id</name>
	                <type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
		');
		$this->_backend->addCol('membership_committee_func', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>association_id</name>
	               	<type>text</type>
					<length>40</length>
	                <notnull>false</notnull>
	                <default>null</default>
	            </field>
		');
		$this->_backend->addCol('membership_committee_func', $dec);

		$this->_backend->dropCol('membership_committee', 'member_id');
				
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>has_functionaries</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
		');
		$this->_backend->addCol('membership_kind', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>has_functions</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>
		');
		$this->_backend->addCol('membership_kind', $dec);
				
		$this->setApplicationVersion('Membership', '1.95');
	}
	
	public function update_95(){
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                <name>key</name>
                <type>text</type>
				<length>48</length>
				<notnull>true</notnull>
            </field>
		');
		$this->_backend->addCol('membership_award_list', $dec);
		
		$dec = new Setup_Backend_Schema_Index_Xml('
			<index>
                    <name>key</name>
                    <field>
                        <name>key</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_award_list', $dec);	
		
		$dec = new Setup_Backend_Schema_Index_Xml('
			<index>
                    <name>name</name>
                    <field>
                        <name>name</name>
                    </field>
                </index>
		');
		$this->_backend->addIndex('membership_award_list', $dec);
		
		$this->setApplicationVersion('Membership', '1.96');
	}
	
	public function update_96(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>sex</name>
					<type>enum</type>
					<value>MALE</value>
					<value>FEMALE</value>
					<value>NEUTRAL</value>
					<default>MALE</default>
				</field>
		');
		$this->_backend->addCol('membership', $dec);
		$this->setApplicationVersion('Membership', '1.97');
	}
	
	public function update_97(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                    <name>addressbook_id</name>
                    <type>integer</type>
                    <notnull>false</notnull>
                </field>
		');
		$this->_backend->addCol('membership_kind', $dec);
		$this->setApplicationVersion('Membership', '1.971');
	}
	
	public function update_971(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
				<name>membership_status</name>
				<type>enum</type>
				<value>ACTIVE</value>
				<value>PASSIVE</value>
				<value>DISCHARGED</value>
				<value>TERMINATED</value>
				<default>ACTIVE</default>
			</field>
		');
		$this->_backend->alterCol('membership', $dec, 'membership_status');
		$this->setApplicationVersion('Membership', '1.972');
	}
	
	public function update_972(){
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_account</name>
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
                    <name>account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>true</notnull>
                </field>
                <field>
	                <name>contact_id</name>
	                <type>integer</type>
	                <notnull>false</notnull>
					<default>null</default>
	            </field>
				<field>
                    <name>related_member_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>member_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
					<default>null</default>
                </field>
				<field>
					<name>valid_from_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>valid_to_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<index>
                    <name>id</name>
                    <primary>true</primary>
                    <field>
                        <name>id</name>
                    </field>
                </index>
				<index>
                    <name>fk_mem_account_account</name>
                    <foreign>true</foreign>
                    <field>
                        <name>account_id</name>
                    </field>
                    <reference>
                        <table>accounts</table>
                        <field>id</field>
                    </reference>
                </index>
				<index>
                    <name>fk_mem_account_rel_member</name>
                    <foreign>true</foreign>
                    <field>
                        <name>related_member_id</name>
                    </field>
                    <reference>
                        <table>membership</table>
                        <field>id</field>
                    </reference>
                </index> 
			</declaration>
		</table>
		');
		$this->_backend->createTable($dec);
		$this->setApplicationVersion('Membership', '1.973');
	}
	
	public function update_973(){
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>CONTACTDATACHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Kontaktdaten</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>CONTACTCUSTOMCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Zusatzfelder Kontakt</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>ADDITIONALDATACHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Zusatzdaten</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>CUSTOMFIELDCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Zusatzfelder</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>SEXCHANGE</value>
			</field>
			<field>
				<name>name</name>
				<value>Änderung Geschlecht</value>
			</field>
			<field>
				<name>category</name>
				<value>DATA</value>
			</field>
		</record>'));
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
	            <name>custom_data</name>
	            <type>text</type>
				<notnull>false</notnull>
				<default>null</default>
	        </field>
		');
		$this->_backend->addCol('membership_data', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
	            <name>contact_data</name>
	            <type>text</type>
				<notnull>false</notnull>
				<default>null</default>
	        </field>
		');
		$this->_backend->addCol('membership_data', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
	            <name>contact_custom_data</name>
	            <type>text</type>
				<notnull>false</notnull>
				<default>null</default>
	        </field>
		');
		$this->_backend->addCol('membership_data', $dec);
		
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
	            <name>additional_data</name>
	            <type>text</type>
				<notnull>false</notnull>
				<default>null</default>
	        </field>
		');
		$this->_backend->addCol('membership_data', $dec);
		
		$this->setApplicationVersion('Membership', '1.974');
	}
	
	public function update_974(){
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                    <name>previous_data_id</name>
                    <type>integer</type>
					<notnull>false</notnull>
                </field>
		');
		$this->_backend->addCol('membership_data', $dec);
		
		$this->setApplicationVersion('Membership', '1.975');
	}
	
	public function update_975(){
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>BILLMEMBERRECALCULATION</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsnachberechnung</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		
		$this->_backend->execInsertStatement(new SimpleXMLElement('
		<record>
			<table>
				<name>membership_action</name>
			</table>
			<field>
				<name>id</name>
				<value>BILLMEMBERREVERT</value>
			</field>
			<field>
				<name>name</name>
				<value>Beitragsrechnung storniert</value>
			</field>
			<field>
				<name>category</name>
				<value>BILLING</value>
			</field>
		</record>'));
		
		$this->setApplicationVersion('Membership', '1.976');
	}	

	public function update_976(){
	
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
                    <name>deb_summation</name>
                    <type>float</type>
                    <notnull>false</notnull>
					<unsigned>false</unsigned>
			    </field>
		');
		$this->_backend->addCol('membership_fee_progress', $dec);
		
		$this->setApplicationVersion('Membership', '1.977');
	}
	
	public function update_977(){
	
		$dec = new Setup_Backend_Schema_Field_Xml('
			<field>
					<name>action_text</name>
					<type>text</type>
					<length>2048</length>
	                <notnull>false</notnull>
				</field>
		');
		$this->_backend->alterCol('membership_action_history', $dec);
		
		$this->setApplicationVersion('Membership', '1.978');
	}
	
	public function update_978(){
		 $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>default_tab</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.979');
	
	}
	
	public function update_979(){
		 $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>begin_letter_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>insurance_letter_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>termination_letter_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.980');
	}			
	
	public function update_980(){
		 $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>dialog_text_member_ext_nr</name>
	                <type>text</type>
					<length>128</length>
					<notnull>false</notnull>
					<default>null</default>
	            </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
	                <name>fee_group_is_duty</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>'
		 );
        $this->_backend->addCol('membership_kind', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.981');
	}
	
	public function update_981(){
 		$declaration = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>member_id</name>
                    <field>
                        <name>member_id</name>
                    </field>
                </index>'
		 );
        $this->_backend->addIndex('membership_action_history', $declaration);
        
        $declaration = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>parent_member_id</name>
                    <field>
                        <name>parent_member_id</name>
                    </field>
                </index>'
		 );
        $this->_backend->addIndex('membership_action_history', $declaration);
        
        $declaration = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>action_id</name>
                    <field>
                        <name>action_id</name>
                    </field>
                </index>'
		 );
        $this->_backend->addIndex('membership_action_history', $declaration);
        
        $declaration = new Setup_Backend_Schema_Index_Xml('
				<index>
                    <name>unbilled_members_action_history</name>
                    <field>
                        <name>action_id</name>
                    </field>
                    <field>
                        <name>member_id</name>
                    </field>
                    <field>
                        <name>valid_datetime</name>
                    </field>
                </index>'
		 );
        $this->_backend->addIndex('membership_action_history', $declaration);
        				
		$this->setApplicationVersion('Membership', '1.982');
	}
	
	public function update_982(){
		 $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>process_percentage</name>
                    <type>integer</type>
                    <length>4</length>
                    <default>0</default>
                </field>'
		 );
        $this->_backend->addCol('membership_job', $declaration);

        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>task_count</name>
                    <type>integer</type>
                    <length>11</length>
					<default>0</default>
                </field>'
		 );
        $this->_backend->addCol('membership_job', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>tasks_done</name>
                    <type>integer</type>
                    <length>11</length>
					<default>0</default>
                </field>'
		 );
        $this->_backend->addCol('membership_job', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>modified_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership_job', $declaration);
        
         $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>process_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->alterCol('membership_action_history', $declaration);
        
		$this->setApplicationVersion('Membership', '1.983');
	}
	
	public function update_983(){
		
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>skip_count</name>
                    <type>integer</type>
                    <length>11</length>
					<default>0</default>
                </field>'
		 );
        $this->_backend->addCol('membership_job', $declaration);
        
        $this->setApplicationVersion('Membership', '1.984');
	}
				
	public function update_984(){
		
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>ext_system_username</name>
					<type>text</type>
					<length>32</length>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>print_reception_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>skip_count</name>
                    <type>integer</type>
                    <length>11</length>
					<default>0</default>
                </field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>print_discharge_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>print_confirmation_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $this->setApplicationVersion('Membership', '1.985');
	}          
	
	public function update_985(){
		
		$this->_backend->dropCol('membership', 'skip_count');
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>ext_system_modified</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);
        
        $this->setApplicationVersion('Membership', '1.986');
	}     

	
	public function update_986(){
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>fee_payment_method</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
					<default>NOVALUE</default>
				</field>'
		 );
        $this->_backend->alterCol('membership', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>fee_payment_method</name>
					<type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
					<default>NOVALUE</default>
				</field>'
		 );
        $this->_backend->alterCol('membership_data', $declaration);
        
        $this->setApplicationVersion('Membership', '1.987');
	}     
	
	public function update_987(){
		
	    $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>job_category</name>
					<type>enum</type>
					<value>FEEPROGRESS</value>
					<value>FEEINVOICE</value>
					<value>FEEINVOICECURRENT</value>
					<value>DTAEXPORT</value>
					<value>PAYMENT</value>
					<value>MANUALEXPORT</value>
					<value>PREDEFINEDEXPORT</value>
					<value>PRINT</value>
					<value>DUETASKS</value>
				</field>'
		 );
        $this->_backend->alterCol('membership_job', $declaration);
        
        $declaration = new Setup_Backend_Schema_Field_Xml('
				 <field>
	                <name>phantom</name>
	                <type>boolean</type>
	                <notnull>true</notnull>
					<default>false</default>
	            </field>'
		 );
        $this->_backend->addCol('membership_data', $declaration);
       
        
        $this->setApplicationVersion('Membership', '1.988');
	} 
	
	public function update_988(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				 <field>
					<name>membership_status</name>
					<type>enum</type>
					<value>ACTIVE</value>
					<value>PASSIVE</value>
					<value>DISCHARGED</value>
					<value>TERMINATED</value>
					<default>ACTIVE</default>
				</field>
	'
		 );
        $this->_backend->alterCol('membership_data', $declaration);
       
        
        $this->setApplicationVersion('Membership', '1.989');
	}
	
	
	public function update_989(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>termination_letter_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>
	'
		 );
        $this->_backend->addCol('membership_data', $declaration);
       
        
        $this->setApplicationVersion('Membership', '1.990');
	}
	
	public function update_990(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>deb_summation</name>
                    <type>float</type>
                    <notnull>false</notnull>
					<unsigned>false</unsigned>
					<default>0</default>
			    </field>
	'
		 );
        $this->_backend->alterCol('membership_fee_progress', $declaration);
		
        $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>fee_to_calculate</name>
                    <type>float</type>
                    <notnull>false</notnull>
					<unsigned>false</unsigned>
					<default>0</default>
			    </field>
	'
		 );

		 $this->_backend->addCol('membership_fee_progress', $declaration);
               $declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>sum_brutto</name>
                    <type>float</type>
                    <notnull>false</notnull>
					<unsigned>false</unsigned>
					<default>0</default>
			    </field>
	'
		 );
        $this->_backend->addCol('membership_fee_progress', $declaration);
	
        $declaration = new Setup_Backend_Schema_Field_Xml('
				 <field>
                    <name>payment_state</name>
                    <type>enum</type>
					<value>NOTDUE</value>
                    <value>TOBEPAYED</value>
                    <value>PARTLYPAYED</value>
					<value>PAYED</value>
                    <default>NOTDUE</default>
                    <notnull>true</notnull>
                </field>
	'
		 );
        $this->_backend->addCol('membership_fee_progress', $declaration);        
        
        $this->setApplicationVersion('Membership', '1.991');
	}
	
	public function update_991(){
    	$declaration = new Setup_Backend_Schema_Field_Xml('
				 <field>
                    <name>membercard_letter_template_id</name>
                    <type>text</type>
					<length>40</length>
					<default>null</default>
                    <notnull>false</notnull>
                </field>
	'
		 );
        $this->_backend->addCol('membership_kind', $declaration);        
        
        $this->setApplicationVersion('Membership', '1.992');
	}
	
	public function update_992(){
    	$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>member_card_year</name>
					<type>integer</type>
					<notnull>false</notnull>
					<default>0</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);        
        
        $this->setApplicationVersion('Membership', '1.993');
	}
	
	public function update_993(){
    	$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>public_comment</name>
					<type>text</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>'
		 );
        $this->_backend->addCol('membership', $declaration);        
        
        $this->setApplicationVersion('Membership', '1.994');
	}
	
	public function update_994(){
   		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>valid_state</name>
					<type>enum</type>
					<value>PENDING</value>
					<value>DONE</value>
					<default>DONE</default>
				</field>'
		 );
        $this->_backend->addCol('membership_data', $declaration);       

       
        
        
        
        
        // migrate: set valid_state of pending actions to pending
        
		/*
		 * 
		 * 
		 
		 UPDATE sopen_membership_data SET valid_state='DONE';

UPDATE sopen_membership_data SET valid_state='PENDING'
WHERE id IN(
SELECT data_id from sopen_membership_action_history WHERE action_state='OPEN' AND data_id IS NOT NULL
);
		  
		 * 
		 * 
		 * 
		 * $select = $this->_db->select()
    	   ->distinct()
    	   ->from(array('action_history' => SQL_TABLE_PREFIX . 'membership_action_history'), 'data_id')
    	   ->where('action_state = "OPEN"');
    	
        $dataIds = $this->_db->fetchAssoc($select);
        
        foreach ($dataIds as $data) {
        	$dataId = $data['data_id'];
        	
        	$this->_db->update(SQL_TABLE_PREFIX . 'membership_data', array('valid_state' => 'PENDING'));
        }*/
        
        /*return;
        
        
        
        $pagination = new Tinebase_Model_Pagination();
		$filters = array();
		$filters[] = array(
    		'field' => 'action_state',
    		'operator' => 'equals',
    		'value' => 'OPEN'
	    );
	    
		$filter = new Membership_Model_ActionHistoryFilter($filters, 'AND');
		$actionHistoryIds = Membership_Controller_ActionHistory::getInstance()->search(
			$filter,
			$pagination,
			false,
			true
		);
		
		foreach($actionHistoryIds as $actionHistoryId){
			$ah = Membership_Controller_ActionHistory::getInstance()->get($actionHistoryId);
			$dataId = $ah->__get('data_id');
			if($dataId){
				$data = Membership_Controller_MembershipData::getInstance()->get($dataId);
				$data->__set('valid_state','PENDING');
				Membership_Controller_MembershipData::getInstance()->update($data);
			}
		}*/
        
        $this->setApplicationVersion('Membership', '1.995');
	}
	
	public function update_995(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>fee_payment_interval</name>
					<type>enum</type>
					<value>NOVALUE</value>
					<value>YEAR</value>
					<value>HALF</value>
					<value>QUARTER</value>
					<value>MONTH</value>
					<default>NOVALUE</default>
				</field>		
		    ');
       $this->_backend->alterCol('membership', $declaration);
       
       $this->setApplicationVersion('Membership', '1.996');
	    
	}
	
	public function update_996(){
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
					<name>debit_auth_date</name>
					<type>date</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>		
		    ');
       $this->_backend->addCol('membership', $declaration);
       
       $this->setApplicationVersion('Membership', '1.997');
	    
	}
	
	
	public function update_997(){
		
		$dec = new Setup_Backend_Schema_Table_Xml('
		<table>
			<name>membership_message</name>
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
                    <name>receiver_group_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
				<field>
                    <name>receiver_account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
				<field>
                    <name>sender_account_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>parent_member_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
					<default>null</default>
                </field>
				<field>
                    <name>member_id</name>
                    <type>text</type>
                    <length>40</length>
                    <notnull>false</notnull>
					<default>null</default>
                </field>
				<field>
					<name>receiver_type</name>
					<type>enum</type>
					<value>GROUP</value>
					<value>USER</value>
					<value>PARENTMEMBER</value>
					<value>MEMBER</value>
					<default>GROUP</default>
				</field>
				<field>
					<name>send_mail</name>
					<type>boolean</type>
					<value>EMAIL</value>
					<default>false</default>
				</field>
				<field>
					<name>direction</name>
					<type>enum</type>
					<value>IN</value>
					<value>OUT</value>
					<default>OUT</default>
				</field>
				<field>
                    <name>subject</name>
                    <type>text</type>
                    <length>512</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>message</name>
                    <type>text</type>
                    <length>4096</length>
                    <notnull>true</notnull>
                </field>
				<field>
                    <name>ticket</name>
                    <type>text</type>
                    <length>4096</length>
                    <notnull>false</notnull>
                    <default>null</default>
                </field>
				<field>
					<name>created_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
				</field>
				<field>
					<name>expiry_datetime</name>
					<type>datetime</type>
					<notnull>false</notnull>
					<default>null</default>
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
			<name>membership_message_read</name>
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
                    <name>account_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>
				<field>
					<name>read_datetime</name>
					<type>datetime</type>
					<notnull>true</notnull>
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
		
		$this->setApplicationVersion('Membership', '1.998');
		
	}
	
	public function update_998(){
		
		$declaration = new Setup_Backend_Schema_Field_Xml('
				<field>
                    <name>message_id</name>
                    <type>text</type>
					<length>40</length>
                    <notnull>true</notnull>
                </field>	
		    ');
       $this->_backend->addCol('membership_message_read', $declaration);
       
       $this->setApplicationVersion('Membership', '1.999');
		
	}
	
	public function update_999(){
		
		$this->setApplicationVersion('Membership', '2.0');
		
	}
	
	public function update_999(){
		
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
       
		$this->setApplicationVersion('Membership', '2.0');
		
	}
		
	
}
?>