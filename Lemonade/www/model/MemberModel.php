<?
class MemberModel extends Lemon_Model{
	/**
	 * 회원 리스트 
	 */
	function getMembers($where="", $first="0", $limit=""){
		if($limit == ""){
			$select = " count(*) as cnt ";
		}
		else{
			$select = " * ";
		}
	
		$sql = "SELECT ".$select.
				"FROM oc_member "
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
	 * 회원 번호로 정보 가져오기 
	 */
	function getMemberByIdx($idx){
		$sql = "SELECT *
				FROM oc_member
				WHERE idx = ".$idx;
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	/**
	 * 회원 아이디로 정보 가져오기 
	 */
	function getMemberById($id){
		$sql = "SELECT idx, kind, level, id, pwd, out_yn, block_yn, block_reason
				FROM oc_member
				WHERE id = '".$id."'";
		
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	/**
	 * 회원 로그인 기록 
	 */
	function addLoginLog($memberIdx, $result){
		$logData['member_idx'] = $memberIdx;
		$logData['ip'] = $_SERVER['REMOTE_ADDR'];
		$logData['result'] = $result;
		$logData['reg_date'] = "NOW()";
		
		return $this->insertData("oc_login_log", $logData, true);
	}
	
	/**
	 * 로그인 실패 횟수
	 */
	function getLoginCount($memberIdx){
		$sql1 = "SELECT reg_date FROM oc_login_log WHERE member_idx = '".$memberIdx."' AND result = 1 ORDER BY reg_date DESC";
		$rs1 = $this->db->exeSql($sql1);
		
		$sql2 = "SELECT count(*) as cnt
				FROM oc_login_log
				WHERE member_idx = '".$memberIdx."'
					AND result = 0 
					AND reg_date > '".$rs1[0]['reg_date']."'";
		
		$rs2 = $this->db->exeSql($sql2);
		return $rs2[0]['cnt'];
	}
	
	/**
	 * 패스워드 변경 로그 카운트
	 */
	function incrPasswordCount($memberIdx, $uniq){
		$sql = "UPDATE oc_password_log
				SET join_count = join_count + 1
				WHERE member_idx = '".$memberIdx."'
					AND uniqid = '".$uniq."'";
		
		$rs = $this->db->exeSql($sql);
		if(is_numeric($rs)){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * 회원 주소록 전부 가져오기
	 */
	function getAddress($memberIdx, $idx=""){
		$sql = "SELECT *
				FROM oc_member_address
				WHERE member_idx = '".$memberIdx."'"
				.($idx != ""?" AND idx = ".$idx:"")
				." ORDER BY shipping_name";
		
		$rs = $this->db->exeSql($sql);
		
		if($idx == ""){
			return $rs;
		}
		else{
			return $rs[0];
		}
	}
	
	/**
	 * 회원 문의 가져오기
	 */
	function getQuestionBoard($memberIdx, $first=0, $limit=""){
		if($limit==""){
			$select = "count(*) as cnt";
		}
		else{
			$select = "*";
		}
		
		$sql = "SELECT ".$select.
				" FROM oc_board
				WHERE kind = 'question'
					AND member_idx = '".$memberIdx."'"
				.($idx != ""?" AND idx = ".$idx:"")
				." AND delete_yn = 'N' 
				ORDER BY reg_date DESC"
				.($limit!=""?" LIMIT ".$first.", ".$limit:"");
		
		$rs = $this->db->exeSql($sql);
		
		if($limit != ""){
			return $rs;
		}
		else{
			return $rs[0]['cnt'];
		}
	}
	
	/**
	 * 특정 회원 문의 가져오기
	 */
	function getQuestionBoardByIdx($memberIdx, $idx=""){
		$sql = "SELECT * 
				FROM oc_board
				WHERE kind = 'question'
					AND member_idx = '".$memberIdx."'"
				.($idx != ""?" AND idx = ".$idx:"")
				." AND delete_yn = 'N' 
				ORDER BY reg_date DESC";
		
		$rs = $this->db->exeSql($sql);
		
		if($idx == ""){
			return $rs;
		}
		else{
			return $rs[0];
		}
	}
}
?>