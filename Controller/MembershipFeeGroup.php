<?php
class Membership_Controller_MembershipFeeGroup extends Tinebase_Controller_Record_Abstract
{
	/**
	 * config of courses
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;

	/**
	 * the constructor
	 *
	 * don't use the constructor. use the singleton
	 */
	private function __construct() {
		$this->_applicationName = 'Membership';
		$this->_backend = new Membership_Backend_MembershipFeeGroup();
		$this->_modelName = 'Membership_Model_MembershipFeeGroup';
		$this->_currentAccount = Tinebase_Core::getUser();
		$this->_purgeRecords = FALSE;
		$this->_doContainerACLChecks = FALSE;
		$this->_config = isset(Tinebase_Core::getConfig()->eventmanager) ? Tinebase_Core::getConfig()->eventmanager : new Zend_Config(array());
	}

	private static $_instance = NULL;

	/**
	 * the singleton pattern
	 *
	 * @return SoMembershipManager_Controller_SoMembership
	 */
	public static function getInstance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getEmptyMembershipFeeGroup(){
		$emptyObj = new Membership_Model_MembershipFeeGroup(null,true);
		return $emptyObj;
	}
	
	public function getArticlePriceForMember($memberId, $articleId){
		try{
			$result = $this->_backend->getByPropertySet(array(
				'article_id' => $articleId,
				'member_id' => $memberId
			));
		}catch(Exception $e){
			return null;	
		}
		if(!$result){
			return null;
		}
		return $result['price'];
	}
	
	public function getFeeGroupFeeByCategory($feeGroupId, $category = 'I'){
		try{
			
			$dueDate = Membership_Controller_SoMember::getInstance()->getDueDate();
	    	$dateFilter = $dueDate->toString('yyyy-MM-dd').' 00:00:00';

			$fgFilter = new Membership_Model_MembershipFeeGroupFilter(array(
	            array('field' => 'no_member',   'operator' => 'isnull', 'value' => null),
	            array('field' => 'category',   'operator' => 'equals', 'value' => $category),
	            array('field' => 'valid_from_datetime',   'operator' => 'beforeOrAt', 'value' => $dateFilter),
		        array('field' => 'valid_to_datetime',   'operator' => 'afterAtOrNull', 'value' => $dateFilter),
		        array('field' => 'fee_group_id', 'operator'=> 'AND', 'value' => array(array('field'=>'id','operator'=>'equals','value'=>$feeGroupId)))
	        ),'AND');
	        
	        $result = Membership_Controller_MembershipFeeGroup::getInstance()->search($fgFilter);
			
			/*$result = $this->_backend->getByPropertySet(array(
				'fee_group_id' => $feeGroupId,
				'category' => $category,
				'summarize' => 1//,
				//'member_id' => 'IS NULL'
				),
				false,
				false
			);
			$result = $result->filter('member_id', null);*/
			//print_r($result);
		}catch(Exception $e){
			//echo $e->__toString();
			//exit;
			return 0;
		}
		if(!$result){
			return 0;
		}
		$retVal = 0;
		foreach($result as $data){
			$retVal += $data['price'];
		}
		return $retVal;
	}
	/**
	 * 
	 * Get specific price for a member by feegroup and article!!
	 * The feegroup also has an article assigned! but here the admin can
	 * have have a price defined for a feegroup and the given article here!
	 * @param string $memberId
	 * @param string $feeGroupId
	 * @param string $articleId
	 */
	public function getFeeGroupArticlePriceForMember($memberId, $feeGroupId, $articleId){
		try{
			$result = $this->_backend->getByPropertySet(array(
				'article_id' => $articleId,
				'fee_group_id' => $feeGroupId,
				'member_id' => $memberId
			));
		}catch(Exception $e){
			return null;	
		}
		if(!$result){
			return null;
		}
		return $result['price'];
	}
	
	public function getForFeeGroupAndMember($memberId, $feeGroupId){
		try{
			return $this->_backend->getByPropertySet(array(
				'fee_group_id' => $feeGroupId,
				'member_id' => $memberId
			));
		}catch(Exception $e){
			return null;	
		}
	}
	public function getFeeGroupPriceSumByCategory($memberId, $feeGroupId, $category ){
		$dueDate = Membership_Controller_SoMember::getInstance()->getDueDate();
    	$dateFilter = $dueDate->toString('Y-M-d').' 00:00:00';
    	
		$fgFilter = new Membership_Model_MembershipFeeGroupFilter(array(
            array('field' => 'no_member',   'operator' => 'isnull', 'value' => null),
            array('field' => 'valid_from_datetime',   'operator' => 'beforeOrAt', 'value' => $dateFilter),
	        array('field' => 'valid_to_datetime',   'operator' => 'afterAtOrNull', 'value' => $dateFilter),
	        array('field' => 'fee_group_id', 'operator'=> 'AND', 'value' => array(array('field'=>'id','operator'=>'equals','value'=>$feeGroupId)))
        ),'AND');
        $memFeeGroups = Membership_Controller_MembershipFeeGroup::getInstance()->search($fgFilter);
        $catSum = 0;
        foreach($memFeeGroups as $mFeeGroup){
        	$value = $mFeeGroup->__get('price');
        	$cCategory = $mFeeGroup->__get('category');
        	$summarize = $mFeeGroup->__get('summarize');
        	
        	if(($cCategory == $category) && $summarize){
        		$catSum += $value;
        	}
        }
		
       	if($memFeeGroup = Membership_Controller_MembershipFeeGroup::getInstance()->getForFeeGroupAndMember($memberId, $feeGroupId)){
		    $value = $memFeeGroup->__get('price');
		    $cCategory = $memFeeGroup->__get('category');
	        $summarize = $memFeeGroup->__get('summarize');
       		if(($cCategory == $category) && $summarize){
        		$catSum += $value;
        	}
        }
	    
        return $catSum;
	}
	/**
	 * 
	 * Get specific price for a member by feegroup and article!!
	 * The feegroup also has an article assigned! but here the admin can
	 * have have a price defined for a feegroup and the given article here!
	 * @param string $memberId
	 * @param string $feeGroupId
	 * @param string $articleId
	 */
	public function getFeeGroupArticlePrice($feeGroupId, $articleId){
		try{
			$result = $this->_backend->getByPropertySet(array(
				'article_id' => $articleId,
				'fee_group_id' => $feeGroupId
			));
		}catch(Exception $e){
			return null;	
		}
		if(!$result){
			return null;
		}
		return $result['price'];
	}
	
	
    /**
     * get attender eventKinds
     *
     * @param string $_sort
     * @param string $_dir
     * @return Tinebase_Record_RecordSet of subtype Membership_Model_AttenderMembershipFeeGroup
     * 
     * @todo    use getAll from generic controller
     */
    public function getAllMembershipFeeGroups($_sort = 'name', $_dir = 'ASC')
    {
        $result = $this->_backend->getAll($_sort, $_dir);
        return $result;    
    }
}
?>