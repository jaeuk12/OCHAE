<?
class GoodsModel extends Lemon_Model{
	
	/**
	 * 상품 목록 가져오기
	 * @param where - 조건
	 * @param first - db 시작 순번
	 * @param limit - 가져올 갯수
	 */
	function getGoodsList($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = "count(*) as cnt";
		}
		else{
			$select = "*";
		}
	
		$sql = "SELECT ".$select.
				" FROM oc_goods"
				.($where!=""?" WHERE ".$where:"")
				.($limit!=""?" LIMIT ".$first.", ".$limit:"");

		$rs = $this->db->exeSql($sql);
	
		if($limit == ""){
			return $rs[0]['cnt'];
		}
		else{
			return $rs;
		}
	}
	
	/**
	 * 오채코드로 상품 가져오기
	 */
	function getGoodsCode($ocCode){
		$sql = "SELECT g.*, gm.content
				FROM oc_goods g LEFT JOIN oc_goods_md gm ON g.oc_code = gm.oc_code
				WHERE g.oc_code = '".$ocCode."'";
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	/**
	 * 입고 목록
	 */
	function getInStockList($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = " count(*) as cnt ";
		}
		else{
			$select = " * ";
		}
		
		$sql = "SELECT ".$select.
				"FROM view_oc_goods_in"
				.($where!=""?" WHERE ".$where:"")
				.($limit!=""?" LIMIT ".$first.", ".$limit:"");
		
		$rs = $this->db->exeSql($sql);
		
		if($limit == ""){
			return $rs[0]['cnt'];
		}
		else{
			return $rs;
		}
	}
	
	/**
	 * 입고 내역
	 */
	function getInStockDetail($artistIdx, $regDay, $cnt=false){
		$select = "*";
		if($cnt){
			$select = " count(*) as cnt ";
		}
		
		$sql = "SELECT ".$select. 
				" FROM oc_goods_in
				WHERE reg_day = '".$regDay."' 
					AND artist_idx = ".$artistIdx;
		
		$rs = $this->db->exeSql($sql);
		
		if($cnt){
			return $rs[0]['cnt'];
		}
		else{
			return $rs;
		}
	}
	
	/**
	 * 입고 상세
	 */
	function getInStockIdx($idx){
		$sql = "SELECT *
				FROM oc_goods_in
				WHERE idx = ".$idx;
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	/**
	 * 수량 변경
	 */
	function setGoodsStock($ocCode, $qty, $tran=true){
		if($tran){
			$this->db->tranBegin();
		}
		
		$sql = "UPDATE oc_goods
				SET stock = stock + ".$qty
				." WHERE oc_code = '".$ocCode."'";
		
		$rs = $this->db->exeSql($sql);
		
		if(is_numeric($rs)){
			if($tran){
				$this->db->tranEnd(true);
			}
			return true;
		}
		else{
			if($tran){
				$this->db->tranEnd(false);
			}
			return false;
		}
	}
	
	/**
	 * 전체 카테고리 가져오기
	 */
	function getCategoryList(){
		$sql = "SELECT *
				FROM oc_category
				ORDER BY deep, code";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	/**
	 * 상품 카테고리 가져오기
	 */
	function getGoodsCategoryInfo($ocCode){
		$sql = "SELECT gc.*, c.code_name
				FROM oc_goods_category gc LEFT JOIN oc_category c ON gc.code = c.code
				WHERE gc.oc_code = '".$ocCode."'";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	/**
	 * 카테고리 상품 리스트
	 */
	function getGoodsCategoryList($ocCode, $where="", $first="0", $limit=""){
		if($limit == ""){
			$select = "count(g.oc_code) as cnt";
		}
		else{
			$select = "gc.code as category_code, g.*";
		}
		
		$sql = "SELECT ".$select
				." FROM oc_goods_category gc LEFT JOIN oc_goods g ON gc.oc_code = g.oc_code
				WHERE gc.code = '".$ocCode."'
					AND g.display_yn ='Y'
					AND g.delete_yn = 'N' "
					.($where!=""?" AND ".$where:"").
				" ORDER BY g.reg_date DESC "
				.($limit!=""?" LIMIT ".$first.", ".$limit:"");
		
		$rs = $this->db->exeSql($sql);
	
		if($limit == ""){
			return $rs[0]['cnt'];
		}
		else{
			return $rs;
		}
	}
	
	/**
	 * 상품 이미지 가져오기
	 */
	function getGoodsImages($ocCode){
		$sql = "SELECT *
				FROM oc_goods_img
				WHERE oc_code = '".$ocCode."'
				ORDER BY reg_date";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
	
	/**
	 * 다른 상품
	 */
	function getOthers($artistIdx){
		$sql = "SELECT *
				FROM oc_goods g JOIN oc_artist a ON g.artist_idx a.idx
				WHERE g.artist_idx = ".$artistIdx;
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
}
?>