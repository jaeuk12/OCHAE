<?php
class GoodsController extends WebServiceController{
	var $listNum = 15;
	
	/**
	 * 리스트 공통
	 */
	function viewDefaultList($code){
		$this->commonDefine();
		$this->view->define("content", "content/default_list/default_list.html");
	
		$keyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
	
		if($page == "")
			$page = 1;
	
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
	
		$where = "";
	
		if($keyword != ""){
			$where .= "(title like '%".addslashes($keyword)."%' OR artist_name like '%".addslashes($keyword)."%')";
		}
	
		$total = $gModel->getGoodsCategoryList($code, $where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
	
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setIcon("icon_default");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(8);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_keyword=".$keyword);
	
		$rows = $gModel->getGoodsCategoryList($code, $where, $pageMaker->first, $this->listNum);
	
		$lastNum = $pageMaker->first+sizeof($rows);
	
		$this->view->assign("category_code", $code);
		$this->view->assign("rows", $rows);
		$this->view->assign("image_path", $this->path['goods_thumb_path']);
		$this->view->assign("keyword", $keyword);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("first", $pageMaker->first+1);
		$this->view->assign("last", $total>$lastNum?$lastNum:$total);
		$this->view->assign("list_num", $this->listNum);
	
		$this->display();
	}
	
	/**
	 * 상품 상세 공통
	 */
	function detailAction(){
		$this->commonDefine();
		$this->view->define("content", "content/default_list/default_detail.html");
	
		$ocCode = $this->req->get("oc_code");
	
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
	
		$row = $gModel->getGoodsCode($ocCode);
	
		if($row == ""){
			throw new Lemon_ScriptException("존재하지 않는 상품입니다", "", "back");
		}
		else if($row['display_yn'] == "N"){
			throw new Lemon_ScriptException("판매 중지된 상품입니다", "", "back");
		}
		else{
			$images = $gModel->getGoodsImages($ocCode);
			$others = $gModel->getOthers($row['artist_idx']);
			$options = $gModel->getRows("*", "oc_option", "oc_code='".$ocCode."'", "sort_no");
				
			$this->view->assign("row", $row);
			$this->view->assign("images", $images);
			$this->view->assign("image_path", $this->path['goods_thumb_path']);
			$this->view->assign("others", $others);
			$this->view->assign("options", $options);
		}
	
		$this->display();
	}
	
	/**
	 * 리빙 리스트
	 */
	function livingAction(){
		$this->viewDefaultList("001000000");
	}
	
	/**
	 * 상품 더불러오기
	 */
	function moreGoodsListAjaxAction(){
		$this->setAjaxHeader();
	
		$categoryCode = $this->req->post("category_code");
		$keyword = $this->req->get("keyword");
		$listSize = $this->req->post("size");
	
		$where = "";
	
		if($keyword != ""){
			$where .= "(title like '%".addslashes($keyword)."%' OR artist_name like '%".addslashes($keyword)."%')";
		}
	
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
	
		$rows = $gModel->getGoodsCategoryList($categoryCode, $where, $listSize, $this->listNum);
	
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