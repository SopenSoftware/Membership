<?php 
class Membership_Calculator_FeeGroupResult{
	private $results = array();
	
	public function __construct(){}
	
	public function addResult($feeGroup, array $results){
		$this->results[$feeGroup->__get('key')] = $results;
	}
	
}
?>