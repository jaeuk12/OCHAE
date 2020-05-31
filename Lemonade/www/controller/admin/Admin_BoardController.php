<?
class Admin_BoardController extends WebServiceController{
	var $listNum = 10;
	
	/**
	 * 공지사항
	 */
	function noticeAction(){
		$this->boardList("notice");
	}
	
	/**
	 * 공지사항
	 */
	function questionAction(){
		$this->boardList("question");
	}
	
	/**
	 * 리스트 만들기
	 * @param boardKind - 게시판 종류
	 */
	function boardList($boardKind){
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
		
		if($page == "")
			$page = 1;
		
		$this->adminDefine();
		$this->view->define("content", "content/admin/board/".$boardKind."_list.html");
		
		$where = "kind = '".$boardKind."'";
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		$total = $bModel->getBoardList($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
		
		$rows = $bModel->getBoardList($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
		$this->view->assign("board_kind", $boardKind);
		
		$this->display();
	}
	
	/**
	 * 게시글 등록/변경
	 */
	function addAction(){
		$idx = $this->req->get("board_idx");
		$kind = $this->req->get("kind");
		$retUrl = $this->req->get("ret_url");
		$mode = "new";
		
		if(!in_array($kind, array("notice"))){
			$this->redirect($retUrl);
			exit;
		}
		
		$this->adminDefine();
		$this->view->define("content", "content/admin/board/".$kind."_edit.html");
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		if($idx != ""){
			$row = $bModel->getBoardIdx($idx);
			
			$mode = "modify";
			$this->view->assign("row", $row);
		}
		
		$this->view->assign("board_idx", $idx);
		$this->view->assign("mode", $mode);
		$this->view->assign("ret_url", $retUrl);
		
		$this->display();
	}
	
	/**
	 * 공지사항 등록/변경 처리
	 */
	function noticeprocessAction(){
		$retUrl = $this->req->post("ret_url");
		$mode = $this->req->post("mode");
		
		$idx = $this->req->post("board_idx");
		$title = $this->req->post("board_title");
		$content = $this->req->post("board_content");
		$viewYn = $this->req->post("board_view");
		
		if($retUrl == ""){
			$retUrl = "/admin/board/notice";
		}
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		$data['kind'] = "notice";
		$data['title'] = addslashes($title);
		$data['content'] = addslashes($content);
		$data['view_yn'] = $viewYn;
		
		if($_FILES["board_attach"]["name"] != ""){
				$date = date("Ymd");
		
				$path = $_SERVER['DOCUMENT_ROOT'].$this->path['attach_path'];
		
				if(!is_dir($path)){
					mkdir($path);
				}
		
				$path .= $date;
		
				if(!is_dir($path)){
					mkdir($path);
				}
		
				$fileName = $date."_".uniqid();
		
				echo $path."/".$fileName."<br/>";
				$fileUpload = Lemon_Instance::getObject("Lemon_FileUpload");
				$fileUpload->setFile($_FILES['board_attach'], $path."/".$fileName);
				$fileUpload->setOverwrite();
				$fileUpload->upload();
				$updateFileName = $fileUpload->getSaveFile();
		
				$data['attach_name'] = addslashes($_FILES["board_attach"]["name"]);
				$data["attach_path"] = str_replace($_SERVER['DOCUMENT_ROOT'].$this->path['attach_path'], "", $updateFileName);
		}
		
		if($mode == "modify"){
			$data['modify_date'] = "NOW()";
			
			$rs = $bModel->updateData("oc_board", $data, "idx = ".$idx);
		}
		else{
			$data['member_idx'] = $this->auth->getUID();
			$data['reg_date'] = "NOW()";
			
			$rs = $bModel->insertData("oc_board", $data);
		}
		
		if($rs){
			throw new Lemon_ScriptException($mode=="new"?"등록되었습니다":"수정되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 게시글 삭제
	 */
	function deleteAction(){
		$idx = $this->req->get("board_idx");
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		$row = $bModel->getBoardIdx($idx);
		
		$retUrl = "/admin";
		
		if($row['delete_yn'] == "Y"){
			if(is_array($row)){
				if($row['attach_path'] != ""){
					unlink($_SERVER['DOCUMENT_ROOT'].$this->path['attach_path'].$row['attach_path']);
				}
					
				$rs = $bModel->deleteData("oc_board", "idx = ".$idx);
					
				$retUrl = "/admin/board/".$row['kind'];
			}
		}
		else{
			$data['delete_yn'] = "Y";
			
			$rs = $bModel->updateData("oc_board", $data, "idx = ".$idx);
		}
		
		if($rs){
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 고객질문 답변
	 */
	function answerAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/board/question_edit.html");
		
		$idx = $this->req->get("board_idx");
		$kind = $this->req->get("kind");
		$retUrl = $this->req->get("ret_url");
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		$row = $bModel->getBoardIdx($idx);
		
		$uData['hit'] = 1;
		$bModel->updateData("oc_board", $uData, "idx = ".$idx);
		
		$this->view->assign("row", $row);
		$this->view->assign("ret_url", $retUrl);
		$this->view->assign("kind", $kind);
		
		$this->display();
	}
	
	/**
	 * 고객질문 답변 처리
	 */
	function answerprocessAction(){
		$boardIdx = $this->req->post("board_idx");
		$boardAnswer = $this->req->post("board_answer");
		$retUrl = $this->req->post("ret_url");
		
		$bModel = Lemon_Instance::getObject("BoardModel", true);
		
		$uData['answer'] = $boardAnswer;
		$rs = $bModel->updateData("oc_board", $uData, "idx = ".$boardIdx);
		
		if($rs){
			throw new Lemon_ScriptException("답변이 등록되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
}
?>