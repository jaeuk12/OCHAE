<?php 
class MemberController extends WebServiceController{
	var $listNum = 10;
	
	/**
	 * 회원가입 Ajax
	 */
	function joinAjaxAction(){
		$this->setAjaxHeader();
		
		$id = $this->req->post("id");
		$pw = $this->req->post("pw");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$row = $mModel->getRow("count(*) as cnt", "oc_member", "id = '".$id."'");
		
		if($id == ""){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "이메일을 입력해주세요";
		}
		else if($pw == ""){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "비밀번호를 입력해주세요";
		}
		else if(!Func::isValidEmail($id)){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "올바른 이메일주소가 아닙니다";
		}
		else if($row['cnt'] > 0){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "이미 가입된 이메일입니다";
		}
		else{
			$data["kind"] = "normal";
			$data["level"] = "9";
			$data["id"] = $id;
			$data['pwd'] = md5($pw);
			$data['reg_date'] = "NOW()";
			
			$rs = $mModel->insertData("oc_member", $data, true);
			
			if($rs){
				$result['code'] = $this->message['success']['code'];
				$result['message'] = "가입되었습니다";
				
				$mModel->addLoginLog($id, 1);
				
				$row = $mModel->getMemberById($id);
				
				$_SESSION['member']['uid'] = $row['idx'];
				$_SESSION['member']['id'] = $row['id'];
				$_SESSION['member']['level'] = $row["level"];
				$_SESSION['member']['kind'] = $row["kind"];
			}
			else{
				$result['code'] = $this->message['fail']['code'];
				$result['message'] = "에러가 발생했습니다\n다시 시도해주세요";
			}
		}
		
		echo json_encode($result);
	}
	
	/**
	 * 로그인 처리 Ajax
	 */
	function loginAajxAction(){
		$this->setAjaxHeader();
		
		$id = $this->req->post("id");
		$pw = $this->req->post("pw");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		if($id == ""){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "이메일을 입력해주세요";
		}
		else if($pw == ""){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "비밀번호를 입력해주세요";
		}
		else if(!Func::isValidEmail($id)){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "올바른 이메일주소가 아닙니다";
		}
		else{
			$row = $mModel->getMemberById($id);
			
			if(!is_array($row)){
				$result['code'] = $this->message['fail']['code'];
				$result['message'] = "회원정보가 존재하지 않습니다";
			}
			else{
				$count = $mModel->getLoginCount($row['idx']);
				if($count > 4){
					$result['code'] = $this->message['fail']['code'];
					$result['message'] = "로그인 시도 5회를 초과했습니다\n비밀번호 찾기를 해주세요";
				}
				else if($row['pwd'] != md5($pw)){
					$result['code'] = $this->message['fail']['code'];
					$result['message'] = "비밀번호가 틀렸습니다 (".($count+1)."/5 회)";
					
					$mModel->addLoginLog($row['idx'], 0);
				}
				else{
					$result['code'] = $this->message['success']['code'];
					$result['message'] = "";
					
					$mModel->addLoginLog($row['idx'], 1);
					
					$_SESSION['member']['uid'] = $row['idx'];
					$_SESSION['member']['id'] = $row['id'];
					$_SESSION['member']['level'] = $row['level'];
					$_SESSION['member']['kind'] = $row['kind'];
				}
			}
		}
		
		echo json_encode($result);
	}
	
	/**
	 * 비밀번호 찾기 메일 보내기
	 */
	function findPasswordAction(){
		$this->setAjaxHeader();
		
		$id = $this->req->post("id");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		if($id == ""){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "이메일을 입력해주세요";
		}
		else if(!Func::isValidEmail($id)){
			$result['code'] = $this->message['fail']['code'];
			$result['message'] = "올바른 이메일주소가 아닙니다";
		}
		else{
			$row = $mModel->getMemberById($id);
			
			if(!is_array($row)){
				$result['code'] = $this->message['fail']['code'];
				$result['message'] = "회원정보가 존재하지 않습니다";
			}
			else{
				$uniq = uniqid();
				$url = "http://www.ochae.com/member/resetPassword?id=".$id."&value=".md5($uniq);
				
				$insertData['id'] = $id;
				$insertData['uniqid'] =$uniq;
				$insertData['reg_date'] = "NOW()";
				
				$mModel->insertData("oc_password_log", $insertData);
				
				$tpl = new Template_();
				$tpl->define("index", "content/mailform/form_password.html");
				$tpl->assign("url", $url);
				$mailContent = $tpl->fetch("index");
				
				$mailFrom = $this->config['adminmail'];
				$userName = "갤러리 오채";
				$mailTo = $id;
				$mailTitle = "[오채] 비밀번호 찾기";
				
				$boundary = "----" . uniqid("part"); // 구분자 생성
				
				// --- 헤더작성 --- //
				$header = "Return-Path: ".$mailFrom."\r\n"; // 반송 이메일 주소
				$header .= "from: ".iconv("utf-8", "euc-kr", $userName)." <".$mailFrom.">\r\n"; // 송신자명, 송신자 이메일 주소
				
				// --- 헤더작성 --- //
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-Type: Multipart/alternative; boundary = \"$boundary\"";
					
				// --- 이메일 본문 생성 --- //
				$mailbody = "--$boundary\r\n";
				$mailbody .= "Content-Type: text/html; charset=UTF-8\r\n";
				$mailbody .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
				$mailbody .= nl2br($mailContent) . "\r\n";
					
				$mailbody .= "--$boundary--\r\n\r\n";
				
				$email = Lemon_Instance::getObject("Lemon_Email");
				
				$eRs = $email->send($mailFrom, $mailTo, $mailTitle, $mailbody, $header);
				
				if($eRs){
					$result['code'] = $this->message['success']['code'];
					$result['message'] = "메일이 발송되었습니다\n잠시 후 확인해주세요";
				}
				else{
					$result['code'] = $this->message['fail']['code'];
					$result['message'] = "에러가 발생했습니다\n잠시후 다시 시도해주세요";
				}
			}
		}
		
		echo json_encode($result);
	}
	/**
	 * 비밀번호 찾기 - 비밀번호 병경
	 */
	function resetPasswordAction(){
		$uid = $this->req->get("uid");
		$uniq = $this->req->get("value");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$toDay = date("Y-m-d H:i:s", mktime(date("H")-1, date("i"), date("s"), date("m"), date("j"), date("Y")));

		$row = $mModel->getRow("*", "oc_password_log", "join_count < 5 AND result = 'N' AND reg_date >= '".$toDay."'", "reg_date DESC LIMIT 1");
		
		if(!is_array($row)){
			throw new Lemon_ScriptException("유효 시간 또는 횟수가 넘었습니다", "처음부터 다시 시도해주세요", "go", "/");
			exit;
		}
		else if(md5($row['uniqid']) != $uniq){
			$mModel->incrPasswordCount($uid, $row['uniqid']);
			throw new Lemon_ScriptException("정상적인 값이 아닙니다", "다시 시도해주세요", "go", "/");
			exit;
		}
		
		$this->commonDefine();
		$this->view->define("content", "content/member/reset_password.html");
		
		$this->view->assign("uid", $uid);
		$this->view->assign("value", $uniq);
		
		$this->display();
	}
	
	/**
	 * 비밀번호 찾기 - 비밀번호 변경 처리
	 */
	function resetProcessAction(){
		$uid = $this->req->post("uid");
		$uniq = $this->req->post("value");
		$pw = $this->req->post("new_password");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$toDay = date("Y-m-d H:i:s", mktime(date("H")-1, date("i"), date("s"), date("m"), date("j"), date("Y")));
		
		$row = $mModel->getRow("*", "oc_password_log", "join_count < 5 AND result = 'N' AND reg_date >= '".$toDay."'", "reg_date DESC LIMIT 1");
		
		$uniq_md5 = md5($row['uniqid']);
		
		if(!is_array($row)){
			throw new Lemon_ScriptException("유효 시간 또는 횟수가 넘었습니다", "처음부터 다시 시도해주세요", "go", "/");
			exit;
		}
		else if($uniq_md5 != $uniq){
			$mModel->incrPasswordCount($uid, $row['uniqid']);
			throw new Lemon_ScriptException("정상적인 값이 아닙니다", "다시 시도해주세요", "go", "/");
			exit;
		}
		
		$mModel->db->tranBegin();
		
		$updateData['result'] = "Y";
		$mModel->updateData("oc_password_log", $updateData, "member_idx = '".$uid."' AND uniqid = '".$row['uniqid']."'", false);
		
		$pwData['pwd'] = md5($pw);
		$rs = $mModel->updateData("oc_member", $pwData, "idx = '".$uid."'", false);
		
		if($rs){
			$mModel->db->tranEnd(true);
			throw new Lemon_ScriptException("비밀번호가 변경되었습니다", "", "go", "/");
			exit;
		}
		else{
			$mModel->db->tranEnd(false);
			throw new Lemon_ScriptException("에러가 발생했습니다", "다시 시도해주세요", "go", "/");
			exit;
		}
	}
	
	/**
	 * 계정정보
	 */
	function profileAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/member/my_profile.html");
		
		$tpl = new Template_();
		$tpl->define("index", "content/member/my_menu.html");
		$tpl->assign("left_menu", "profile");
		$leftMenu = $tpl->fetch("index");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$row = $mModel->getMemberByIdx($this->auth->getUID());
		
		$this->view->assign("my_account_left", $leftMenu);
		$this->view->assign("row", $row);
		
		$this->display();
	}
	
	/**
	 * 주소정보
	 */
	function addressAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/member/my_address.html");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
	
		$tpl = new Template_();
		$tpl->define("index", "content/member/my_menu.html");
		$tpl->assign("left_menu", "address");
		$leftMenu = $tpl->fetch("index");
		
		$rows = $mModel->getAddress($this->auth->getUID());
	
		$this->view->assign("my_account_left", $leftMenu);
		$this->view->assign("rows", $rows);
	
		$this->display();
	}
	
	/**
	 * 회원 질문
	 */
	function questionAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/member/my_question.html");
		
		$page = floor($this->req->get("page"));
		
		if($page=="")
			$page = 1;
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
	
		$tpl = new Template_();
		$tpl->define("index", "content/member/my_menu.html");
		$tpl->assign("left_menu", "question");
		$leftMenu = $tpl->fetch("index");
		
		$memberIdx = $this->auth->getUID();
		
		$total = $mModel->getQuestionBoard($memberIdx);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setIcon("icon_default");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(5);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList();
		
		$rows = $mModel->getQuestionBoard($memberIdx, $pageMaker->first, $this->listNum);
	
		$lastNum = $pageMaker->first+sizeof($rows);
		
		$this->view->assign("my_account_left", $leftMenu);
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("first", $pageMaker->first+1);
		$this->view->assign("last", $total>$lastNum?$lastNum:$total);
		$this->view->assign("list_num", $this->listNum);
	
		$this->display();
	}
	
	/**
	 * 주문정보
	 */
	function orderAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/member/my_order.html");
	
		$tpl = new Template_();
		$tpl->define("index", "content/member/my_menu.html");
		$tpl->assign("left_menu", "order");
		$leftMenu = $tpl->fetch("index");
	
		$this->view->assign("my_account_left", $leftMenu);
	
		$this->display();
	}
	
	/**
	 * 회원 계정 변경
	 */
	function editemailAction(){
		$email = $this->req->post("email");
		$confirmEmail = $this->req->post("confirm_email");
		$pw = $this->req->post("password");
		$confirmPw = $this->req->post("confirm_password");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		if(!$this->auth->isLogin()){
			$data['code'] = $this->message['need_login']['code'];
			$data['message'] = $this->message['need_login']['message'];
		}
		else if($email == ""){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "이메일을 입력해주세요";
		}
		else if(!Func::isValidEmail($email)){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "올바른 이메일주소가 아닙니다";
		}
		else if($confirmEmail == ""){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "이메일 확인을 입력해주세요";
		}
		else if($email != $confirmEmail){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "두개의 이메일 주소가 일치하지 않습니다";
		}
		else if($pw == ""){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "비밀번호를 입력해주세요";
		}
		else if(strlen($pw) < 6 || strlen($pw) > 26){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "비밀번호는 6자~26자 이내로 입력해주세요";
		}
		else if($confirmPw == ""){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "비밀번호 확인을 입력해주세요";
		}
		else if($pw != $confirmPw){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "두개의 비밀번호가 일치하지 않습니다";
		}
		else{
			$row = $mModel->getMemberByIdx($this->auth->getUID());
			$pwd = md5($pw);
			
			if($row['id'] == $email){
				if($pwd != $row['pwd']){
					$uData['pwd'] = $pwd;
					$uData['modify_date'] = "NOW()";
					$mModel->updateData("oc_member", $uData, "idx=".$this->auth->getUID());
					$data['code'] = $this->message['success']['code'];
					$data['message'] = "비밀번호가 변경되었습니다";
				}
			}
			else if($pwd == $row['pwd']){
				$uData['id'] = $email;
				$uData['modify_date'] = "NOW()";
				$mModel->updateData("oc_member", $uData, "idx=".$this->auth->getUID());
				$data['code'] = $this->message['success']['code'];
				$data['message'] = "아이디가 변경되었습니다";
				
				$_SESSION['member']['id'] = $email;
			}
			else{
				$data['code'] = $this->message['fail']['code'];
				$data['message'] = "비밀번호가 틀렸습니다";
			}
		}
		
		throw new Lemon_ScriptException($data['message'], "", "go", "/member/profile");
	}
	
	/**
	 * 회원 정보 변경
	 */
	function editprofileAction(){
		$firstName = $this->req->post("first_name");
		$lastName = $this->req->post("last_name");
		$gender = $this->req->post("gender");
		$birthday = $this->req->post("birthday");
		$contact = $this->req->post("contact");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		if(!$this->auth->isLogin()){
			$data['code'] = $this->message['need_login']['code'];
			$data['message'] = $this->message['need_login']['message'];
		}
		
		$uData['first_name'] = addslashes($firstName);
		$uData['last_name'] = addslashes($lastName);
		$uData['gender'] = addslashes($gender);
		$uData['birthday'] = addslashes($birthday);
		$uData['contact'] = addslashes($contact);
		
		$rs = $mModel->updateData("oc_member", $uData, "idx=".$this->auth->getUID());
		
		if($rs){
			throw new Lemon_ScriptException("정보가 변경되었습니다", "", "go", "/member/profile");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/member/profile");
		}
	}
	
	/**
	 * 회원 주소록 등록/변경 폼
	 */
	function editAddressFromAjaxAction(){
		$this->setAjaxHeader("login");
		
		$idx = $this->req->post("idx");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$row = $mModel->getAddress($this->auth->getUID(), $idx);
		
		$tpl = new Template_();
		$tpl->define("index", "content/member/edit_address.html");
		$tpl->assign("row", $row);
		$content = $tpl->fetch("index");
		
		$data['code'] = $this->message['success']['code'];
		$data['message'] = $this->message['success']['message'];
		$data['content'] = $content;
		
		echo json_encode($data);
	}
	
	/**
	 * 회원 주소록 등록/변경 처리
	 */
	function editAddressAction(){
		$idx = $this->req->post("idx");
		$shippingName = $this->req->post("shipping_name");
		$postCode = $this->req->post("post_code");
		$address1 = $this->req->post("address1");
		$address1_old = $this->req->post("address1_old");
		$address2 = $this->req->post("address2");
		$contact1 = $this->req->post("contact1");
		$contact2 = $this->req->post("contact2");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		if(!$this->auth->isLogin()){
			throw new Lemon_ScriptException($this->message['need_login']['message'], "", "go", "/");
		}
		
		$memberIdx = $this->auth->getUID();
		
		$uData['shipping_name'] = addslashes($shippingName);
		$uData['post_code'] = addslashes($postCode);
		$uData['address1'] = addslashes($address1);
		$uData['address1_old'] = addslashes($address1_old);
		$uData['address2'] = addslashes($address2);
		$uData['contact1'] = addslashes($contact1);
		$uData['contact2'] = addslashes($contact2);
		
		if($idx == ""){
			$uData['member_idx'] = $memberIdx;
			
			$rs = $mModel->insertData("oc_member_address", $uData);
		}
		else{
			$rs = $mModel->updateData("oc_member_address", $uData, "idx = ".$idx." AND member_idx = '".$memberIdx."'");
		}
		
		if($rs){
			throw new Lemon_ScriptException($idx==""?"등록되었습니다":"정보가 변경되었습니다", "", "go", "/member/address");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/member/address");
		}
	}
	
	/**
	 * 회원 주소록 삭제
	 */
	function deleteAddressAction(){
		$idx = $this->req->get("idx");
		
		if(!$this->auth->isLogin()){
			throw new Lemon_ScriptException($this->message['need_login']['message'], "", "go", "/");
		}
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$rs = $mModel->deleteData("oc_member_address", "idx = ".$idx." AND member_idx = '".$this->auth->getUID()."'");
		
		if($rs){
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", "/member/address");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/member/address");
		}
	}
	
	/**
	 * 회원 문의 폼
	 */
	function editQuestionFromAjaxAction(){
		$this->setAjaxHeader("login");
		
		$idx = $this->req->post("idx");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$row = $mModel->getQuestionBoardByIdx($this->auth->getUID(), $idx);
		
		$tpl = new Template_();
		$tpl->define("index", "content/member/edit_question.html");
		$tpl->assign("row", $row);
		$content = $tpl->fetch("index");
		
		$data['code'] = $this->message['success']['code'];
		$data['message'] = $this->message['success']['message'];
		$data['content'] = $content;
		
		echo json_encode($data);
	}
	
	/**
	 * 회원 문의 등록/변경 처리 
	 */
	function editQuestionAction(){
		$idx = $this->req->post("idx");
		$title = $this->req->post("title");
		$content = $this->req->post("content");
		
		if(!$this->auth->isLogin()){
			throw new Lemon_ScriptException($this->message['need_login']['message'], "", "go", "/");
		}
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$memberIdx = $this->auth->getUID();
		
		$uData['title'] = $title;
		$uData['content'] = $content;
		
		if($idx == ""){
			$uData['kind'] = "question";
			$uData['secret_yn'] = "Y";
			$uData['reg_date'] = "NOW()";
			$uData['member_idx'] = $memberIdx;
			
			$rs = $mModel->insertData("oc_board", $uData);
		}
		else{
			$uData['modify_date'] = "NOW()";
			
			$rs = $mModel->updateData("oc_board", $uData, "idx = ".$idx." AND member_idx = '".$memberIdx."' AND delete_yn = 'N' AND hit = 0");
		}
		
		if($rs){
			throw new Lemon_ScriptException($idx==""?"등록되었습니다":"정보가 변경되었습니다", "", "go", "/member/question");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/member/question");
		}
	}
	
	/**
	 * 회원 질문 삭제
	 */
	function deleteQuestionAction(){
		$idx = $this->req->get("idx");
		
		if(!$this->auth->isLogin()){
			throw new Lemon_ScriptException($this->message['need_login']['message'], "", "go", "/");
		}
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$uData['delete_yn'] = "Y";
		$rs = $mModel->updateData("oc_board", $uData, "idx = ".$idx." AND member_idx = '".$this->auth->getUID()."'");
		
		if($rs){
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", "/member/question");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/member/question");
		}
	}
	
	/**
	 * 회원 질문 더 가져오기
	 */
	function moreQuestionListAjaxAction(){
		$this->setAjaxHeader("login");
		
		$listSize = $this->req->post("size");
		
		$memberIdx = $this->auth->getUID();
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$rows = $mModel->getQuestionBoard($memberIdx, $listSize, $this->listNum);
		
		if(sizeof($rows) > 0){
			$data['code'] = $this->message['success']['code'];
			$data['message'] = "";
			$data['list'] = $rows;
		}
		else{
			$data['code'] = $this->message['no_more']['code'];
			$data['message'] = $this->message['no_more']['message'];
		}
		
		echo json_encode($data);
	}
}
?>