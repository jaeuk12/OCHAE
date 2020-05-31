<?
class Admin_MemberController extends WebServiceController{
	var $listNum = 10;
	
	/**
	 * 회원 목록
	 */
	function listAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/member/list.html");
		
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
		
		if($page == "")
			$page = 1;
		
		$where = "";
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		
		$total = $mModel->getMembers($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
		
		$rows = $mModel->getMembers($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
		
		$this->display();
	}
	
	function infoAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/member/edit.html");

		$idx = $this->req->get("idx");
		$retUrl = $this->req->get("ret_url");
		
		$mModel = Lemon_Instance::getObject("MemberModel", true);
		$row = $mModel->getMemberByIdx($idx);
		
		$this->view->assign("row", $row);
		$this->view->assign("ret_url", $retUrl);
		
		$this->display();
	}
	
	
}
?>