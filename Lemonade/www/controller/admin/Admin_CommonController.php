<?
class Admin_CommonController extends WebServiceController{
	var $listNum = 8;
	/**
	 * 저자 찾기 Ajax
	 */
	function searchArtistAjaxAction(){
		$this->setAjaxHeader();
	
		$searchType = $this->req->post("search_type");
		$searchKeyword = $this->req->post("search_keyword");
		$page = floor($this->req->post("page"));
		
		if($page < 1){
			$page = 1;
		}
	
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
	
		$total = $aModel->getArtistList($searchType." like '".$searchKeyword."%'");
	
		$totalPage = ceil($total/$this->listNum);
	
		if($page > $totalPage){
			$page = $totalPage;
		}
	
		$rows = $aModel->getArtistList($searchType." like '".$searchKeyword."%'", ($page-1)*$this->listNum, $this->listNum);
	
		$data['list'] = $rows;
		$data['page'] = $page;
		$data['total_num'] = $total - (($page-1)*$this->listNum);
		$data['total_page'] = $totalPage;
	
		echo json_encode($data);
	}
	
	/**
	 * 상품 찾기
	 */
	function searchGoodsAjaxAction(){
		$this->setAjaxHeader();
		
		$searchType = $this->req->post("search_type");
		$searchKeyword = $this->req->post("search_keyword");
		$page = $this->req->post("page");
		$artistIdx = $this->req->post("artist_idx");
		
		if($page < 1){
			$page = 1;
		}
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$where = "artist_idx = ".$artistIdx;
		
		if($searchKeyword != ""){
			$where .= " AND ".$searchType." like '".$searchKeyword."%'";
		}
		
		$total = $gModel->getGoodsList($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page != 1 && $page > $totalPage){
			$page = $totalPage;
		}
		
		$rows = $gModel->getGoodsList($where, ($page-1)*$this->listNum, $this->listNum);
		
		$data['list'] = $rows;
		$data['page'] = $page;
		$data['total_num'] = $total - (($page-1)*$this->listNum);
		$data['total_page'] = $totalPage;
		
		echo json_encode($data);
	}
}
?>