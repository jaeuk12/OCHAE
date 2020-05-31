<?
class Admin_ArtistController extends WebServiceController{
	var $listNum = 10;
	
	/**
	 * 작가 목록
	 */
	function listAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/artist/list.html");
		
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
		
		if($page=="")
			$page = 1;
		
		$where = "";
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
		
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		
		$total = $aModel->getArtistList($where);
		
		$totalPage = ceil(($total / $this->listNum));
		
		if($page >= $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
		
		$rows = $aModel->getArtistList($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
		
		$this->display();
	}
	
	/**
	 * 작가 추가
	 */
	function addAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/artist/edit.html");
		
		$idx = $this->req->get("idx");
		$retUrl = $this->req->get("ret_url");
		$mode = "new";
		
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		$bankRows = $aModel->getRows("*", "bank_code", "", "name asc");
		$kindRows = $aModel->getKinds();
		
		if($idx != ""){
			$row = $aModel->getArtist($idx);
			
			$mode = "modify";
			$this->view->assign("row", $row);
		}
		
		if($mode == "new"){
			$retUrl = "/admin/artist/list";
		}
		
		$this->view->assign("mode", $mode);
		$this->view->assign("bank_rows", $bankRows);
		$this->view->assign("kind_rows", $kindRows);
		$this->view->assign("ret_url", $retUrl);
		
		$this->display();
	}
	
	/**
	 * 등록/수정 처리
	 * @param artist_id - 작가 아이디
	 * @param artist_name - 저자명
	 * * @param artist_kind - 작업분유
	 * @param artist_content 작가 소개/연혁
	 * @param artist_contact - 저자 연락처
	 * @param artist_address1 - 주소
	 * @param artist_address2 - 상세주소
	 * @param artist_bank - 거래은행
	 * @param artist_account_number - 계좌번호
	 */
	function processAction(){
		$mode = $this->req->post("mode");
		$retUrl = $this->req->post("ret_url");
		$idx = $this->req->post("idx");
		
		$name = $this->req->post("artist_name");
		$kind = $this->req->post("artist_kind");
		$introduction = $this->req->post("artist_introduction");
		$timeline = $this->req->post("artist_timeline");
		$contact = $this->req->post("artist_contact");
		$address1 = $this->req->post("artist_address1");
		$address2 = $this->req->post("artist_address2");
		$nameAccount = $this->req->post("artist_name_account");
		$bank = $this->req->post("artist_bank");
		$accountNumber = $this->req->post("artist_account_number");
		
		if($retUrl == ""){
			$retUrl = "/admin/artist/list";
		}
		else{
			$retUrl = htmlspecialchars_decode(urldecode($retUrl));
		}
		
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		
		$data['name'] = $name;
		$data['kind'] = $kind;
		$data['introduction'] = $introduction;
		$data['timeline'] = $timeline;
		$data['contact'] = $contact;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['name_account'] = $nameAccount;
		$data['bank'] = $bank;
		$data['account_number'] = $accountNumber;
		
		if($mode == "new"){
			$data['reg_date'] = "now()";
			$rs = $aModel->insertData("oc_artist", $data);
		}
		else{
			$data['modify_date'] = "now()";
			$rs = $aModel->updateData("oc_artist", $data, "idx = ".$idx);
		}
		
		if($rs){
			throw new Lemon_ScriptException($mode=="new"?"등록되었습니다":"수정되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 저자아이디 중복체크
	 */
	function checkIDAjaxAction(){
		$id = $this->req->post("id");
		
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		$rs = $aModel->getArtistId($id);
		
		if($rs > 0){
			$data['code'] = 200;
			$data['message'] = "중복되는 아이디입니다.";
		}
		else{
			$data['code'] = 100;
			$data['message'] = "사용 가능한 아이디입니다.";
		}
		
		echo json_encode($data);
	}
	
	/**
	 * 저자 삭제
	 */
	function deleteAction(){
		$idx = $this->req->get("idx");
		$retUrl = "/admin/artist/list";
		
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		
		$aRs = $aModel->getRow("delete_yn", "oc_artist", "idx = ".$idx);
		
		if($aRs['delete_yn'] == "Y"){
			$rs = $aModel->deleteData("oc_artist", "idx = ".$idx);
		}
		else{
			$data['delete_yn'] = "Y";
			$rs = $aModel->updateData("oc_artist", $data, "idx = ".$idx);
		}
		
		if($rs){
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 저자 찾기 팝업
	 */
	function popSearchArtistAction(){
		$this->adminIframeDefine();
		$this->view->define("content", "content/admin/artist/pop_artist.html");
		
		$this->display();
	}
}
?>