<?php
class PaymentModel extends Lemon_Model{
	function getGoodsCodes($ocCodes){
		$sql = "SELECT *
				FROM oc_goods
				WHERE oc_code in ('".implode("', '", $ocCodes)."')";
	
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	function getOptionList($optionIdx){
		$sql = "SELECT *
				FROM oc_option
				WHERE idx in (".implode(", ", $optionIdx).")
				ORDER BY oc_code, sort_no";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
} 
?>