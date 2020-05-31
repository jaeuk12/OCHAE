<?php
class IndexController extends WebServiceController {
	
	/**
	 * 오채 메인
	 */
	function indexAction(){
		$this->commonDefine();
 		$this->view->define("content", "content/main.html");
 		
 		$mModel = Lemon_Instance::getObject("MainModel", true);
 		
 		$row = $mModel->getMainSlider();
 		
 		$this->view->assign("row", $row);
 		
		$this->display();
	}
	
	/**
	 * 관리자 메인
	 */
	function adminAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/main.html");
		$this->display();
	}
	
	/**
	 * 관리자 로그인 화면
	 */
	function adminloginAction(){
		if($this->auth->isLogin()){
			$this->redirect("/admin");
			exit;
		}
		
		$this->adminDefine("login");
		$this->view->define("content", "content/admin/login.html");
		$this->display();
	}
	
	/**
	 * 관리자 로그인 처리
	 */
	function loginAjaxAction(){
		$this->setAjaxHeader();
	
		$id = $this->req->post("id");
		$pw = $this->req->post("pw");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
	
		if(!preg_match("/^([a-z0-9_]+)$/", $id)){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "아이디를 정확하게 입력해주세요";
		}
		else if($pw == ""){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "비밀번호를 입력해주세요";
		}
		else{
			$arUri = parse_url($_SERVER['HTTP_REFERER']);
				
			if(!in_array($arUri['path'], array("/login", "/adminlogin"))){
				$data['code'] = $this->message['fail']['code'];
				$data['message'] = "잘못된 접근입니다";
			}
			else if($arUri['path'] == "/adminlogin"){
				$kind = " AND kind = 'admin'";
			}
				
			$rs = $mModel->getRow("*", "oc_member", "id = '".addslashes($id)."' ".$kind);
			
			if(is_array($rs)){
				$upw = md5($pw);
				if($rs['pwd'] != $upw){
					$data['code'] = $this->message['member_pw']['code'];
					$data['message'] = $this->message['member_pw']['message'];
				}
				else{
					$_SESSION['member']['uid'] = $rs['idx'];
					$_SESSION['member']['kind'] = $rs['kind'];
					$_SESSION['member']['level'] = $rs['level'];
					$_SESSION['member']['id'] = $rs['id'];
					$_SESSION['member']['name'] = $rs['name'];
						
					$data['code'] = $this->message['success']['code'];
					$data['message'] = "로그인되었습니다";
				}
			}
			else{
				$data['code'] = $this->message['member_non']['code'];
				$data['message'] = $this->message['member_non']['message'];
			}
		}
	
		echo json_encode($data);
	}
	
	/**
	 * 로그아웃 공통
	 */
	function logoutAction(){
		unset($_SESSION['member']);
		$this->redirect("/");
	}
	
	/**
	 * 오채 위치안내
	 */
	function locationAction(){
		$this->commonDefine();
		$this->view->define("content", "content/location.html");
		
		$this->display();
	}
}
?>