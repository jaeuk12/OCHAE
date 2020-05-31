<?
class BoardModel extends Lemon_Model{
	/**
	 * 게시글 목록 가져오기
	 * @param where - 조건
	 * @param first - db 시작 순번
	 * @param limit - 가져올 갯수
	 */
	function getBoardList($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = " count(*) as cnt ";
		}
		else{
			$select = " * ";
		}
	
		$sql = "SELECT ".$select.
		"FROM oc_board "
				.($where!=""?" WHERE ".$where:"")
				." ORDER BY reg_date DESC"
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
	 * 게시글 가져오기
	 * @param idx - 게시글 번호
	 * @return 배열
	 */
	function getBoardIdx($idx){
		$sql = "SELECT *
				FROM oc_board
				WHERE idx = ".$idx;
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
}
?>