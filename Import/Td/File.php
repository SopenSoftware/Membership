<?php 
class Membership_Import_Td_File{
	/*
NHDTDVClient   2000 24.07.2010                         
064199
17.12.2007 
01.03.1997          
TSC Laubach e.V.                                                                                    
Regina Frank                                                                                        
Im Katzengraben 16                                
11D     
35321 
Laubach                                           
regi.frank@t-online.de                                                                                                                                                    
06405-7042                                                                                                                                          
000207                    

064199000001
19.01.2008
 
02.12.1955
21.07.1996
          
1132
Becker                                            
Gerold                                                                                              
Oberpforte 11                                                                   
Laubach-Münster                                                                 
1
D     
35321 
3       
86      
06405-1676                                        
06405-950695                                      
0175-3219570                                      
Gerold-Becker@t-online.de                                                                                                                                                                                                                                 
1                    

	 */
	private static $def = array(
		'client' => array(
			'len' => 55
		),
		'club' => array(
			'len' => 695,
			'fields' => array(
				'club_nr' => array('len' => 6),
				'mod_date' => array('len' => 10),
				'change' => array('len' => 1),
				'foundation_date' => array('len' => 10),
				'termination_date' => array('len' => 10),
				'org_name' => array('len' => 50),
				'org_name2' => array('len' => 50),
				'org_addition' => array('len' => 50),
				'contact_name' => array('len' => 50),
				'street' => array('len' => 50),
				'sportdiver' => array('len' => 1),
				'affiliate' => array('len' => 1),
				'country' => array('len' => 6),
				'postalcode' => array('len' => 6),
				'location' => array('len' => 50),
				'email' => array('len' => 60),
				'www' => array('len' => 60),
				'fax' => array('len' => 50),
				'phone1' => array('len' => 50),
				'phone2' => array('len' => 50),
				'vorsitz1' => array('len' => 12),
				'vorsitz2' => array('len' => 12),
				'kassier' => array('len' => 12),
				'sachbearbeiter' => array('len' => 12),
				'countmembers' => array('len' => 6),
				'buffer' => array('len' => 20)
			)
		),
		'member' => array(
			'len' => 817,
			'fields' => array(
				'member_nr' => array('len' => 12),
				'mod_date' => array('len' => 10),
				'change' => array('len' => 1), // Wert 2 bei Austritt: 3
				'bday' => array('len' => 10),
				'begin_date' => array('len' => 10),
				'end_date' => array('len' => 10),
				'sex' => array('len' => 1),
				'sportdiver' => array('len' => 1),
				'qualification' => array('len' => 1), // 0:no, 1: *, 2: **, 3:***, 4: other
				'examiner' => array('len' => 1),	// 0:no, 1: TL1, 2: TL2, 3: TL3 4: TL4
				'n_family' => array('len' => 50),
				'n_given' => array('len' => 50),
				'title' => array('len' => 50),
				'street' => array('len' => 80),
				'location' => array('len' => 80),
				'affiliate' => array('len' => 1),
				'country' => array('len' => 6),
				'postalcode' => array('len' => 6),
				'classification' => array('len' => 8),
				'fee' => array('len' => 8),
				'fax' => array('len' => 50),
				'phone2' => array('len' => 50),
				'phone1' => array('len' => 50),
				'email' => array('len' => 100),
				'mobile' => array('len' => 50),
				'url' => array('len' => 100),
				'membership_status' => array('len' => 1),
				'buffer' => array('len' => 20)
			)
		)
	);
	private $clientHeader = null;
	private $club = null;
	private $members = array();
	private $memberStartPointer = 0;
	private $memberPointer = 0;
	
	
	public function __construct($fileName){
		$this->file = $fileName;
		$this->fileSize = filesize($fileName);
		$this->clubPointer = self::$def['client']['len'];
		$this->memberStartPointer = $this->clubPointer + self::$def['club']['len'];
		$this->memberPointer = $this->memberStartPointer;
		$this->currentMember = 1;
	}
	
	public static function createAndOpen($fileName){
		$obj = new self($fileName);
		$obj->open();
		return $obj;
	}
	
	public function open(){
		$this->fp = fopen($this->file,'r');
	}
	
	public function close(){
		fclose($this->fp);
	}
	
	public function getClub(){
		fseek($this->fp, $this->clubPointer);
		return $this->parseClub(fread($this->fp, self::$def['club']['len']));
	}
	
	public function getNextMember(){
		$member = $this->readMember();
		$this->moveMemberPointer(++$this->currentMember);
		return $member;
	}
	
	public function hasMembers(){
		return ($this->fileSize >$this->memberStartPointer);
	}
	
	public function hasNextMember(){
		return ($this->fileSize >$this->memberPointer);
	}
	
	private function moveMemberPointer($offset){
		$this->memberPointer = $this->memberStartPointer + $offset * self::$def['member']['len'];
	}
	
	private function parseClub($strClub){
		$aDef = self::$def['club']['fields'];
		return $this->parseRecord($strClub, $aDef);
	}
	
	private function readMember(){
		fseek($this->fp, $this->memberPointer);
		return $this->parseMember(fread($this->fp, self::$def['member']['len']));
	}
	
	private function parseMember($strMember){
		$aDef = self::$def['member']['fields'];
		return $this->parseRecord($strMember, $aDef);
	}
	
	private function parseRecord($strRec, array $aDef){
		$pointer = 0;
		$result = array();
		foreach($aDef as $fieldName => $fieldDef){
			$result[$fieldName] = trim(utf8_encode(substr($strRec, $pointer, $fieldDef['len'])));
			$pointer += $fieldDef['len'];	
		}
		return $result;
	}	
}
?>