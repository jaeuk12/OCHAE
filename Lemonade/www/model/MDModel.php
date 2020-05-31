<?
class MDModel extends Lemon_Model{
	function getLinkList($gubun){
		$sql = "SELECT * 
				FROM oc_link_md
				WHERE gubun = '".$gubun."'
				ORDER BY order_no ASC";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	function updateOrderno($orderNo, $gubun){
		$sql = "UPDATE oc_link_md
				SET order_no = order_no - 1
				WHERE gubun = '".$gubun."' 
					AND order_no > ".$orderNo;
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	function sortOrderno($gubun, $fNo, $eNo){
		$sql = "UPDATE oc_link_md
				SET order_no = order_no + 1
				WHERE gubun = '".$gubun."'
					AND order_no between ".$fNo." AND ".$eNo;
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
}
?>