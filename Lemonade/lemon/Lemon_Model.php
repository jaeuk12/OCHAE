<?
/*
* 부모 모델. 모든 모델은 이 모델을 상속해야함.
*/

class Lemon_Model extends Lemon_Object {
	
	public $db = '';
	var $funcName = "^(PASSWORD|VERSION|QUOTE|ABS|CHAR|CURDATE|CURRENT_DATE|CURTIME|CURRENT_TIME|NEW|SYSDATE|NOW|DAYOFMONTH|DAYOFWEEK|WEEKDAY|DAYOFYEAR|ENCRYPT|MD5|SHA|SHA1|BENCHMARK|IF|LOWER|UPPER|MID|MOD|QUARTER|REPLACE|REVERSE|ROUND|RTRIM|TRIM|LOAD_FILE|SIGN|FLOOR|CEILING|EXP|LOG|LOG10|POW|SQRT|PI|COS|SIN|TAN|ACOS|ASIN|ATAN|RAND|LEAST|GREATEST|CONCAT|LENGTH|LPAD|RPAD|LEFT|RIGHT|SUBSTRING|LTRIM|SPACE|REPLACE|REVERSE|LCASE|UCASE|COUNT)\(.*\)$";
	
	function __construct(){
		global $t_req, $t_config, $t_path, $t_message, $t_auth;
		
		parent::setRequest($t_req);
		parent::setConfig($t_config);
		parent::setPath($t_path);
		parent::setMessage($t_message);
		parent::setAuth($t_auth);
		
	}
	
	public function setDB($db){
		$this->db = $db;
	}
	
	public function getRow($select='*', $table, $where='', $orderBy=''){
		$sql = "select " . $select . " from " . $table . ($where != ""?" where " . $where:"") . ($orderBy!=""?" order by ".$orderBy:"");
		$rs = $this->db->exeSql($sql);
		return $rs[0];
	}
	
	public function getRows($select='*', $table, $where='', $orderBy=''){
		$sql = "select " . $select . " from " . $table . ($where!=""?" where " . $where:"") . ($orderBy!=""?" order by ".$orderBy:"");
		$rs = $this->db->exeSql($sql);
		return $rs;
	}

	public function getCount($table,$where=''){
		$sql = "select count(1) as cnt from ".$table.($where!=''?" where ".$where:'');
		if(($rs = $this->db->exeSql($sql))===false)
			throw new Lemon_ScriptException("에러 : ",$this->db->errorMsg);

		return $rs[0]['cnt'];
	}
	
	function getNextCode(){
		$rs = $this->getRow("currval", "sequences", "name = 'oc_code'");
		$ochaeCode = $rs['currval'];
	
		if($ochaeCode==''){
			$nextCode = "OC10000000001";
		}
		else {
			$ochaeCode++;
			$nextCode = "OC" . $ochaeCode;
			$this->db->exeSql("UPDATE sequences SET currval = ".$ochaeCode." WHERE name = 'oc_code'");
		}
		return $nextCode;
	}
	
	/**
	 * 추가
	 * @param table - 테이블명
	 * @param data - 추가할 데이터(배열)
	 * @return boolean - 결과값
	 */
	function insertData($table, $data, $tran=true){
		$this->db->setInsert($table, $data);
		
		if($tran){
			$this->db->tranBegin();
		}
		
		$rs = $this->db->exeSql();
	
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
	 * 변경
	 * @param table - 테이블명
	 * @param data - 변경할 데이터(배열)
	 * @param where - 조건
	 * @return boolean - 결과값
	 */
	function updateData($table, $data, $where, $tran=true){
		$this->db->setUpdate($table, $data, $where);
		
		if($tran){
			$this->db->tranBegin();
		}
		
		$rs = $this->db->exeSql();
	
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
	 * 삭제
	 * @param table - 테이블명
	 * @param where - 조건
	 * @return boolean - 결과값
	 */
	function deleteData($table, $where, $tran=true){
		$this->db->setDelete($table, $where);
		
		if($tran){
			$this->db->tranBegin();
		}
		if(is_numeric($this->db->exeSql())){
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
	 * 추가/변경
	 * 있으면 업데이트 없으면 추가
	 * @param table - 테이블명
	 * @param data - 데이터(배열)
	 * @param where - 조건
	 * @return boolean - 결과값
	 */
	function insertOnDuplicateUpdate($table, $data, $where, $tran=true){
		
		$count = $this->getRow("COUNT(*) as cnt", $table, $where);
		
		if(is_numeric($count['cnt']) && $count['cnt'] > 0){
			$data['modify_date'] = "now()";
			$this->db->setUpdate($table, $data, $where);
		}
		else{
			$data['reg_date'] = "now()";
			$this->db->setInsert($table, $data);
		}
		
		if($tran){
			$this->db->tranBegin();
		}
		
		$rs = $this->db->exeSql();
		
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
	 * 다중 INSERT
	 */
	function insertArraysOnDuplicateUpdate($table, $datas, $isUpdate=false, $tran=true){
		$insertSql = "INSERT INTO ".$table;
		$insertData = "";
		
		$keys = array_keys($datas);
		$len = sizeof($datas[$keys[0]]);
		
		$updateValues = " ON DUPLICATE KEY UPDATE ";
		
		foreach($keys as $k){
			$insertField .= $k.",";
			$updateValues .= $k." = VALUES(".$k."),";
		}
		
		$updateValues = substr($updateValues,0,-1);
		
	 	$insertSql .= " (".substr($insertField,0,-1).") VALUES ";
	 	
		for($i=0; $i<$len; $i++){
			$insertData .= "(";
			for($j=0; $j<sizeof($keys); $j++){
				$value = $datas[$keys[$j]][$i];
				if(preg_match("/".$this->funcName."/",strtoupper($value),$match)){
					$insertData .= $value.",";
				}
				else{
					$insertData .= "'".$value."',";
				}
			}
			$insertData = substr($insertData,0,-1);
			$insertData .= "),";
		}
		$insertData = substr($insertData,0,-1);
		
		if($tran){
			$this->db->tranBegin();
		}

		$rs = $this->db->exeSql($insertSql.$insertData.($isUpdate?$updateValues:""));
		
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
}

?>