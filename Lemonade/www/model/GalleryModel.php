<?
class GalleryModel extends Lemon_Model{
	/**
	 * 갤러리 목록 가져오기
	 * @param where - 조건
	 * @param first - db 시작 순번
	 * @param limit - 가져올 갯수
	 */
	function getGalleryList($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = " count(*) as cnt ";
		}
		else{
			$select = " * ";
		}
	
		$sql = "SELECT ".$select.
		"FROM view_oc_gallery"
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
	function getGalleryIdx($idx){
		$sql = "SELECT *
				FROM view_oc_gallery
				WHERE idx = ".$idx;
	
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	/**
	 * 특정 작가 작품목록 가져오기
	 */
	function getGalleryArtist($artistIdx){
		$sql = "SELECT *
				FROM view_oc_gallery
				WHERE artist_idx = ".$artistIdx
				." AND display_yn = 'Y'";
		
		$rs = $this->db->exeSql($sql);
		return $rs;
	}
}
?>