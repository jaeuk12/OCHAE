<?
/*
* 세션에 들어있는 로긴 정보를 가져옴
*/

class Lemon_Auth {
	public $rSession = '';

	function __construct(){
		$this->rSession = $_SESSION;
	}

	function getId(){
		return $this->rSession['member']["id"];
	}
	
	function getUID(){
		return $this->rSession['member']["uid"];
	}
	
	function getLevel(){
		return $this->rSession['member']["level"];
	}
	
	function getKind(){
		return $this->rSession['member']["kind"];
	}
	
	function isLogin(){
		return $this->rSession['member']["id"]!=''?true:false;
	}
	
	public function isAdmin(){
		if($this->rSession['member']["kind"]=="admin"){
			return true;
		}
		else {
			return false;
		}
	}

}

?>