<?
class MainModel extends Lemon_Model{
	function getMainSlider(){
		$sql = "SELECT *
				FROM oc_link_md
				WHERE gubun = 'main_slider'
					AND view_yn = 'Y'
				ORDER BY order_no ASC";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
}
?>