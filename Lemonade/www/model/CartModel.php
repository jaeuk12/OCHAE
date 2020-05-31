<?php
class CartModel extends Lemon_Model{
	
	function getCartGoodsInfo($memberIdx){
		$sql = "SELECT c.oc_code, SUM(c.qty) as total_qty, g.title, g.sale_price, g.discount_price, g.discount_yn, g.shipping_price, g.shipping_free, g.shipping_qty, g.thumb
				FROM oc_cart c LEFT JOIN oc_goods g ON c.oc_code = g.oc_code
				WHERE member_idx = '".$memberIdx."' 
				GROUP BY c.oc_code 
				ORDER BY c.reg_date DESC";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	function getCartList($memberIdx, $ocCode=""){
		$sql = "SELECT c.oc_code, c.qty, c.option_idx, o.option_name, o.option_price
				FROM oc_cart c LEFT JOIN oc_option o ON o.idx = c.option_idx AND o.oc_code = c.oc_code
				WHERE c.member_idx = '".$memberIdx."' ".
				($ocCode!=""?" AND c.oc_code = '".$ocCode."' ":"").
				" ORDER BY c.reg_date";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
} 
?>