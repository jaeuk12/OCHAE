<?
class rtistModel extends Lemon_Model{
	/**
	 * 저자 목록 가져오기
	 * @param where - 조건
	 * @param first - db 시작 순번
	 * @param limit - 가져올 갯수
	 */
	function getArtistList($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = " count(*) as cnt ";
		}
		else{
			$select = " * ";
		}
		
		$sql = "SELECT ".$select.
				"FROM oc_artist "
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
	 * 저자 아이디 중복체크
	 * @param id - 찾을 id
	 */
	function getArtistId($id){
		$sql = "SELECT count(*) as cnt
				FROM oc_artist
				WHERE id = '".$id."'";
		
		$rs = $this->db->exeSql($sql);
		
		return $rs[0]['cnt'];
	}
	
	/**
	 * 저자 번호로 가져오기
	 * @param idx - 찾을 idx
	 */
	function getArtistIdx($idx){
		$sql = "SELECT *
				FROM oc_artist
				WHERE idx = ".$idx;
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
}
?>