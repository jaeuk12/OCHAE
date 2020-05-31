<?
/**
* MySQL을 사용하기 위한 Class
*/
class Lemon_Mysql {

	private $dbInfo			= '';
	private $isDebug		= false;
	private $dmlType		= '';
	private $funcName		= '';
	private $transaction	= '';

	public $sql				= '';
	public $errorMsg		= '';

	public $conn = '';

	public function __construct($db,$isPConnect=''){
		$this->getConnection($db,$isPConnect);
		if($db['db']!='')
			$this->changeDB($db['db']);

		$this->funcName = "^(PASSWORD|VERSION|QUOTE|ABS|CHAR|CURDATE|CURRENT_DATE|CURTIME|CURRENT_TIME|NEW|SYSDATE|NOW|DAYOFMONTH|DAYOFWEEK|WEEKDAY|DAYOFYEAR|ENCRYPT|MD5|SHA|SHA1|BENCHMARK|IF|LOWER|UPPER|MID|MOD|QUARTER|REPLACE|REVERSE|ROUND|RTRIM|TRIM|LOAD_FILE|SIGN|FLOOR|CEILING|EXP|LOG|LOG10|POW|SQRT|PI|COS|SIN|TAN|ACOS|ASIN|ATAN|RAND|LEAST|GREATEST|CONCAT|LENGTH|LPAD|RPAD|LEFT|RIGHT|SUBSTRING|LTRIM|SPACE|REPLACE|REVERSE|LCASE|UCASE|COUNT)\(.*\)$";
	}

	public function getConnection($db,$isPConnect=''){
		try {
			if($isPConnect===true)
				$con = mysql_pconnect($db['host'],$db['user'],$db['password']);
			else {
				if($db['port']!="")
					$con = mysql_connect($db['host'].":".$db['port'],$db['user'],$db['password']);
				else
					$con = mysql_connect($db['host'],$db['user'],$db['password']);
			}

			if(!$con)
				throw new Exception("[MySQL접속 Error] Mysql에 접속이 불가능 합니다. 호스트, 유저명, 페스워드를 확인하세요\n");

			$this->conn = $con;

			if($db['encoding']!=''){
				if(mysql_query("set names ".$db['encoding'])===false){
					throw new Exception("[Mysql 인코딩 설정 오류] - ".mysql_error());
				}
			}

			$this->dbInfo = $db;

		}catch(Exception $e){
			echo $e;
			exit;
		}

		return $this->conn;
	}

	public function setDebugMode($mode='true'){
		$this->isDebug = $mode;
	}

	public function getDbInfo(){
		return $this->dbInfo;
	}

	public function changeDB($db){
		try {
			if(!mysql_select_db($db,$this->conn))
				throw new Exception("[ MySQL DataBase 선택 Error ] ".mysql_errno($this->conn)." : ".mysql_error($this->conn)."\n");
		}catch(Exception $e){
			echo $e->getMessage();
			exit;
		}
	}

	public function getDMLType($sql){
		$types = array('select','update','insert','delete');

		$sql = trim($sql);
		if(in_array(strtolower(substr($sql,0,6)),$types)){
			$this->dmlType = strtolower(substr($sql,0,6));
		}
	}

	public function exeSql($sql=''){

		if($sql==''){
			if($this->sql=='')
				return false;
			else
				$sql = $this->sql;
		}
		else {
			$this->dmlType = '';
		}

		$this->getDMLType($sql);
		$error = false;

		try {
			if(!$result=mysql_query($sql,$this->conn)){
				$error = true;
				//echo "[MySQL Query Error] ".mysql_errno($this->conn)." : ".mysql_error($this->conn)."\n".$sql."\n";
				//exit;

				if($this->isDebug)
					throw new Exception("[MySQL Query Error] ".mysql_errno($this->conn)." : ".mysql_error($this->conn)."<br>".$sql);
				else
					throw new Exception("잘못된 접근 또는 오류가 발생했습니다");
			}

			if($this->dmlType=='select'){
				for($i=0;$row = mysql_fetch_array($result,MYSQL_ASSOC);$i++){
					$rows[$i]=$row;
				}
				mysql_free_result($result);
				return $rows;
			} else if($this->dmlType=='insert'){
				$rs = mysql_insert_id($this->conn);
				@mysql_free_result($result);
				return $rs;
			} else if($this->dmlType=='update' || $this->dmlType=='delete'){
				$rs = mysql_affected_rows($this->conn);
				@mysql_free_result($result);
				return $rs;
			}

		}
		catch(Exception $e){
			if($this->transaction===true){
				$this->tranEnd(false);
			}

			if($this->isDebug){
				echo $e->getMessage();
				exit;
			}
			else {
				$this->errorMsg = $e->getMessage();
				return false;
			}
		}
	}

	public function setInsert($table,$value){
		$values = $this->valuesMakeAsInsert($value);
		$this->sql = "insert into " . $table . " " . $values;
	}

	public function setUpdate($table,$value,$where){
		$values = $this->valuesMakeAsUpdate($value);
		$this->sql = "update " . $table . " set " . $values . " where ".$where;
	}

	public function setDelete($table,$where){
		$this->sql = "delete from " . $table . " where ".$where;
	}

	public function valuesMakeAsInsert($value){
		$dml = '';

		foreach($value as $k => $v ){
			if($v!=''){
				$dmlField .= $k.",";

				if(preg_match("/".$this->funcName."/",strtoupper($v),$match)){
					$dmlValue .= $v.",";
				}
				else
					$dmlValue .= "'".$v."',";
			}
		}

		$dmlField = $dmlField==''?'':substr($dmlField,0,-1);
		$dmlValue = $dmlValue==''?'':substr($dmlValue,0,-1);
		$dml = "(" . $dmlField . ") values (" . $dmlValue . ")";
		return $dml;
	}

	public function valuesMakeAsUpdate($value){
		$dml = '';

		foreach($value as $k => $v ){
			if($v!=''){
				if(preg_match("/".$this->funcName."/",strtoupper($v),$match)){
					$dml .= $k."=".$v.",";
				}
				else
					$dml .= $k."="."'".$v."',";
			}
			else
				$dml .= $k."='',";
		}

		$dml = $dml==''?'':substr($dml,0,-1);
		return $dml;
	}

	// 트랙잭션 시작 함수
	// innodb 일때만 정상동작
	public function tranBegin(){
		try{
			if(!mysql_query("START TRANSACTION"))
				throw new Exception("START TRANSACTION Error : ".mysql_errno($this->conn)." : ".mysql_error($this->conn));
		}
		catch(HtmlException $e){
			echo $e;
			exit;
		}

		$this->transaction = true;
	}

	// 트랙잭션 끝내는  함수 ( Commit ) ;
	public function tranEnd($result=true){
		try{
			if($this->transaction === true){
				if(!mysql_query(($result===true?'COMMIT':'ROLLBACK')))
					throw new Exception(($result===true?'COMMIT':'ROLLBACK')." Error : ".mysql_errno($this->conn)." : ".mysql_error($this->conn));
			}
		}
		catch(HtmlException $e){
			echo $e;
			exit;
		}

		$this->transaction = false;
	}

	public function close(){
		$this->__destruct();
	}

	/**
	* 소멸자로 DB의 커넥션이 열려있을 경우 접속을 중지하고 메모리를 반환한다.
	* @return void
	*/
	public function __destruct(){
		if($this->conn!='')
			@mysql_close($this->conn);
	}
}
?>