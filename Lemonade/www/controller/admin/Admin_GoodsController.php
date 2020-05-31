<?
class Admin_GoodsController extends WebServiceController{
	var $listNum = 10;
	
	/**
	 * 상품 목록
	 */
	function listAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods/list.html");
		
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
		
		if($page == "")
			$page = 1;
		
		$where = "";
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$total = $gModel->getGoodsList($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
		
		$rows = $gModel->getGoodsList($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
		
		$this->display();
	}
	
	/**
	 * 상품 등록/변경 페이지
	 */
	function addAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods/edit.html");
		
		$ocCode = $this->req->get("oc_code");
		$retUrl = $this->req->get("ret_url");
		$mode = "new";
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		if($ocCode != ""){
			$mode = "modify";
			$row = $gModel->getGoodsCode($ocCode);
			$cRow = $gModel->getGoodsCategoryInfo($ocCode);
			$iRow = $gModel->getGoodsImages($ocCode);
			$oRow = $gModel->getRows("*", "oc_option", "oc_code = '".$ocCode."'", "sort_no");
			
			$this->view->assign("row", $row);
			$this->view->assign("cRow", $cRow);
			$this->view->assign("iRow", $iRow);
			$this->view->assign("oRow", $oRow);
		}
		
		$this->view->assign("mode", $mode);
		$this->view->assign("ret_url", $retUrl);
		$this->view->assign("image_path", $this->path['goods_thumb_path']);
		
		$this->display();
	}
	
	/**
	 * 상품 등록/변경 처리
	 */
	function processAction(){
		$mode = $this->req->post("mode");
		$retUrl = $this->req->post("ret_url");
		$ocCode = $this->req->post("oc_code");
		
		$goodsThumb = $this->req->post("goods_thumb");
		$goodsTitle = $this->req->post("goods_title");
		$goodsCostPrice = $this->req->post("goods_cost_price");
		$goodsSalePrice = $this->req->post("goods_sale_price");
		$goodsStock = $this->req->post("goods_stock");
		$goodsCategory = $this->req->post("goods_category");
		$goodsArtist = $this->req->post("goods_artist");
		$goodsArtistName = $this->req->post("goods_artist_name");
		$goodsTax = $this->req->post("goods_tax");
		$goodsDisplay = $this->req->post("goods_display");
		$goodsPos = $this->req->post("goods_pos");
		$goodsDiscount = $this->req->post("goods_discount");
		$goodsDiscountPrice = $this->req->post("goods_discount_price");
		$goodsShippingPrice = $this->req->post("goods_shipping_price");
		$goodsShippingFree = $this->req->post("goods_shipping_free");
		$goodsShippingQty = $this->req->post("goods_shipping_qty");
		
		$goodsImages = $this->req->post("goods_images");
		$goodsOptionIdx = $this->req->post("option_idx");
		$goodsOptionName = $this->req->post("option_name");
		$goodsOptionPrice = $this->req->post("option_price");
		
		if($goodsDiscount == "Y"){
			$goodsDiscount = "Y";
		}
		else{
			$goodsDiscount = "N";
			$goodsDiscountPrice = 0;
		}
		
		if($retUrl == ""){
			$retUrl = "/admin/goods/list";
		}
		else{
			$retUrl = htmlspecialchars_decode(urldecode($retUrl));
		}
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		if($mode == "new"){
			$ocCode = $gModel->getNextCode();
		}
		else if($ocCode == ""){
			throw new Lemon_ScriptException("상품코드가 비어있습니다", "", "go", $retUrl);
			exit;
		}
		
		$data["title"] = $goodsTitle;
		$data["cost_price"] = $goodsCostPrice;
		$data["sale_price"] = $goodsSalePrice;
		$data['discount_price'] = $goodsDiscountPrice;
		$data['shipping_price'] = $goodsShippingPrice;
		$data['shipping_free'] = $goodsShippingFree;
		$data['shipping_qty'] = $goodsShippingQty;
		$data["stock"] = $goodsStock;
		$data['thumb'] = $goodsThumb;
		$data["artist_idx"] = $goodsArtist;
		$data["artist_name"] = $goodsArtistName;
		$data['discount_yn'] = $goodsDiscount;
		$data["tax_yn"] = $goodsTax; 
		$data["display_yn"] = $goodsDisplay;
		$data["pos_yn"] = $goodsPos;
		
		if($mode == "new"){
			$data['oc_code'] = $ocCode;
			$data["reg_date"] = "now()";
			
			$rs = $gModel->insertData("oc_goods", $data);
		}
		else{
			$data["modify_date"] = "now()";
			
			$rs = $gModel->updateData("oc_goods", $data, "oc_code = '".$ocCode."'");
		}
		
		if($rs){
			if($mode == "new"){
				$isData['title'] = $goodsTitle;
				$isData['oc_code'] = $ocCode;
				$isData['qty'] = $goodsStock;
				$isData['cost_price'] = $goodsCostPrice;
				$isData['sale_price'] = $goodsSalePrice;
				$isData['tax_yn'] = $goodsTax;
				$isData['reg_day'] = "curdate()";
				$isData['reg_date'] = "now()";
				
				$gModel->insertData("oc_goods_in", $isData);
			}
			
			$imgPath = $_SERVER['DOCUMENT_ROOT'].$this->path['goods_thumb_path'];
			
			if(!is_dir($imgPath)){
				mkdir($imgPath);
			}
			
			$path = $imgPath."/".date("Ym");
			if(!is_dir($path)){
				mkdir($path);
			}
			
			$path .= "/".$ocCode;
			if(!is_dir($path)){
				mkdir($path);
			}
			
			$iRs = $gModel->getRows("*", "oc_goods_img", "oc_code = '".$ocCode."'");
			
			if($goodsImages != ""){
				$inData['oc_code'] = array();
				$inData['image_path'] = array();
				$inData['image_name'] = array();
				$inData['image_ext'] = array();
				$inData['reg_date'] = array();
				
				for($i=0; $i<sizeof($goodsImages); $i++){
					$targetPath = $_SERVER['DOCUMENT_ROOT'].substr($goodsImages[$i], 0, strripos($goodsImages[$i], "/")+1);
					$fullName = basename($goodsImages[$i]);
					$fileName = substr($fullName, 0, strripos($fullName, "."));
					$ext = strrchr($fullName, ".");
					
					$isEquals = false;
					for($j=0; $j<sizeof($iRs); $j++){
						if($iRs[$j]['image_name'] == $fileName){
							$isEquals = true;
							array_splice($iRs, $j, 1);
							break;
						}
					}
					
					if(!$isEquals){
						$smallName = $fileName."_small".$ext;
						$mediumName = $fileName."_medium".$ext;
						$largeName = $fileName."_large".$ext;
					
						rename($_SERVER['DOCUMENT_ROOT'].$goodsImages[$i], $path."/".$fullName);
						rename($targetPath.$smallName, $path."/".$smallName);
						rename($targetPath.$mediumName, $path."/".$mediumName);
						rename($targetPath.$largeName, $path."/".$largeName);
					
						array_push($inData['oc_code'], $ocCode);
						array_push($inData['image_path'], str_replace($imgPath, "", $path));
						array_push($inData['image_name'], $fileName);
						array_push($inData['image_ext'], $ext);
						array_push($inData['reg_date'], "NOW()");
					}
				}
				
				$delCode = array();
				for($i=0; $i<sizeof($iRs); $i++){
					array_push($delCode, "'".$iRs[$i]['image_name']."'");
						
					$imagePath = $_SERVER['DOCUMENT_ROOT'].$this->path['goods_thumb_path'];
						
					unlink($imagePath.$iRs[$i]['image_path']."/".$iRs[$i]['image_name'].$iRs[$i]['image_ext']);
					unlink($imagePath.$iRs[$i]['image_path']."/".$iRs[$i]['image_name']."_small".$iRs[$i]['image_ext']);
					unlink($imagePath.$iRs[$i]['image_path']."/".$iRs[$i]['image_name']."_medium".$iRs[$i]['image_ext']);
					unlink($imagePath.$iRs[$i]['image_path']."/".$iRs[$i]['image_name']."_large".$iRs[$i]['image_ext']);
				}
				
				if(sizeof($inData['oc_code']) > 0){
					$gModel->insertArraysOnDuplicateUpdate("oc_goods_img", $inData);
				}
				
				if(sizeof($delCode)>0){
					$gModel->deleteData("oc_goods_img", "oc_code = '".$ocCode."' AND image_name in ('".implode("', '", $delCode)."')");
				}
			}
			
			$oRs = $gModel->getRows("*", "oc_option", "oc_code = '".$ocCode."'", "sort_no");
			
			if($goodsOptionIdx != ""){
				$oiData['oc_code'] = array();
				$oiData['option_name'] = array();
				$oiData['option_price'] = array();
				$oiData['sort_no'] = array();
				
				for($i=0; $i<sizeof($goodsOptionIdx); $i++){
					$isEquals = false;
					
					for($j=0; $j<sizeof($oRs); $j++){
						if($oRs[$j]['idx'] == $goodsOptionIdx[$i]){
							$isEquals = true;
							array_splice($oRs, $j, 1);
							
							if($oRs[$j]['option_name'] != $goodsOptionName[$i]
								|| $oRs[$j]['option_price'] != $goodsOptionPrice[$i]
								|| $oRs[$j]['sort_no'] != ($i+1)){
								$ouData['option_name'] = $goodsOptionName[$i];
								$ouData['option_price'] = $goodsOptionPrice[$i];
								$ouData['sort_no'] = ($i+1);
								
								$gModel->updateData("oc_option", $ouData, "idx = ".$goodsOptionIdx[$i]);
							}
							break;
						}
					}
					
					if(!$isEquals){
						array_push($oiData['oc_code'], $ocCode);
						array_push($oiData['option_name'], $goodsOptionName[$i]);
						array_push($oiData['option_price'], $goodsOptionPrice[$i]);
						array_push($oiData['sort_no'], ($i+1));
					}
				}
				
				$delCode = array();
				for($i=0; $i<sizeof($oRs); $i++){
					array_push($delCode, "'".$oRs[$i]['idx']."'");
				}
				
				if(sizeof($delCode)>0){
					$gModel->deleteData("oc_option", "idx in (".implode($delCode).")");
				}
					
				if(sizeof($oiData['option_name']) > 0){
					$gModel->insertArraysOnDuplicateUpdate("oc_option", $oiData);
				}
			}
			
			$cRs = $gModel->getRows("code", "oc_goods_category", "oc_code = '".$ocCode."'");
			
			if($goodsCategory != ""){
				$insertDatas = array();
				$insertDatas['oc_code'] = array();
				$insertDatas['code'] = array();
				
				for($i=0; $i<sizeof($goodsCategory); $i++){
					$isEquals = false;
					
					for($j=0; $j<sizeof($cRs); $j++){
						if($goodsCategory[$i] == $cRs[$j]['code']){
							$isEquals = true;
							array_splice($cRs, $j, 1);
							break;
						}
					}
					
					if(!$isEquals){
						array_push($insertDatas['oc_code'], $ocCode);
						array_push($insertDatas['code'], $goodsCategory[$i]);
					}
				}
				
				$delCode = array();
				for($i=0; $i<sizeof($cRs); $i++){
					array_push($delCode, $cRs[$i]['code']);
				}
				
				if(sizeof($delCode)>0){
					$gModel->deleteData("oc_goods_category", "oc_code = '".$ocCode."' AND code in ('".implode("', '", $delCode)."')");
				}
				
				$gModel->insertArraysOnDuplicateUpdate("oc_goods_category", $insertDatas);
			}
			
			throw new Lemon_ScriptException($mode=="new"?"등록되었습니다":"수정되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 상품 상세정보 MD
	 */
	function mdAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods/md.html");
		
		$retUrl = $this->req->get("ret_url");
		$ocCode = $this->req->get("oc_code");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$row = $gModel->getGoodsCode($ocCode);
		
		$this->view->assign("ret_url", $retUrl);
		$this->view->assign("row", $row);
		
		$this->display();
	}
	
	/**
	 * 상품 상세정보 MD 등록/수정
	 */
	function mdprocessAction(){
		$retUrl = $this->req->post("ret_url");
		$ocCode = $this->req->post("oc_code");
		$content = $this->req->post("goods_md_content");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$data['oc_code'] = $ocCode;
		$data['content'] = addslashes($content);
		
		$rs = $gModel->insertOnDuplicateUpdate("oc_goods_md", $data, "oc_code = '".$ocCode."'");
		
		if($rs){
			throw new Lemon_ScriptException("등록되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 상품 삭제
	 */
	function deleteAction(){
		$ocCode = $this->req->get("oc_code");
		$retUrl = "/admin/goods/list";
	
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$gRs = $gModel->getRow("delete_yn", "oc_goods", "oc_code = '".$ocCode."'");
		
		if($gRs['delete_yn'] == "Y"){
			$rs = $gModel->deleteData("oc_goods", "oc_code = '".$ocCode."'");
			if($rs){
				$gModel->deleteData("oc_goods_md", "oc_code = '".$ocCode."'");
					
				if($rs['thumb'] != ""){
					$filePath = $_SERVER['DOCUMENT_ROOT'].$this->path['goods_thumb_path'].$rs['thumb'];
					if(is_file($filePath)){
						unlink($filePath);
					}
				}
			}
		}
		else{
			$data['display_yn'] = "N";
			$data['delete_yn'] = "Y";
			
			$rs = $gModel->updateData("oc_goods", $data, "oc_code = '".$ocCode."'");
		}
		
		if($rs){
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	
	/**
	 * 카테고리 관리
	 */
	function categoryAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods/category.html");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$rs = $gModel->getCategoryList();
		
		for($i=0; $i<sizeof($rs); $i++){
			$row = $rs[$i];
			
			if($row['deep'] == 1){
				$rows[$row['code']] = $row;
			}
			else if($row['deep'] == 2){
				$rows[$row['parent_code']]['list'][$row['code']] = $row;
			}
			else{
				$rows[substr($row['code'], 0 ,3)."000000"]['list'][$row['parent_code']]['list'][$row['code']] = $row;
			}
		}
		
		$this->view->assign("rows", $rows);
		
		$this->display();
	}
	
	/**
	 * 카테고리 추가
	 */
	function addCategoryAction(){
		$parentCode = $this->req->request("parent_code");
		$categoryName = $this->req->request("category_name");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$row = $gModel->getRow("*", "oc_category", "code = '".$parentCode."'");
		$lastRs = $gModel->getRow("code", "oc_category", "parent_code = '".$parentCode."'", "code DESC LIMIT 1");
		
		if($row['deep'] == 1){
			if($lastRs['code'] == ""){
				$code = substr($parentCode, 0, 3)."001000";
			}
			else{
				$code = substr($lastRs['code'], 0, 3).str_pad(substr($lastRs['code'], -1, 3)+1,"3","0",STR_PAD_LEFT)."000";
			}
		}
		else{
			if($lastRs['code'] == ""){
				$code = substr($parentCode, 0, 6)."001";
			}
			else{
				$code = substr($lastRs['code'], 0, 6).str_pad(substr($lastRs['code'], -1, 3)+1,"3","0",STR_PAD_LEFT);
			}
		}
		
		$data['code'] = $code;
		$data['code_name'] = $categoryName;
		$data['parent_code'] = $parentCode;
		$data['deep'] = $row['deep']+1;
		
		$rs = $gModel->insertData("oc_category", $data, true);
		
		if($rs){
			throw new Lemon_ScriptException("등록되었습니다", "", "go", "/admin/goods/category");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/admin/goods/category");
		}
	}
	
	/**
	 * 카테고리명 변경
	 */
	function modifyCategoryAction(){
		$code = $this->req->request("code");
		$categoryName = $this->req->request("category_name");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$data['code_name'] = $categoryName;
		
		$rs = $gModel->updateData("oc_category", $data, "code = '".$code."'");
		
		if($rs){
			throw new Lemon_ScriptException("변경되었습니다", "", "go", "/admin/goods/category");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/admin/goods/category");
		}
	}
	
	/**
	 * 카테고리 삭제
	 */
	function deleteCategoryAction(){
		$code = $this->req->request("code");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);

		$gModel->db->tranBegin();
		$rs = $gModel->deleteData("oc_category", "code = '".$code."'", false);
		$rs2 = $gModel->deleteData("oc_goods_category", "code = '".$code."'", false);
		
		if($rs && $rs2){
			$gModel->db->tranEnd(true);
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", "/admin/goods/category");
		}
		else{
			$gModel->db->tranEnd(false);
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/admin/goods/category");
		}
	}
	
	/**
	 * 입고관리 리스트
	 */
	function inStockListAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods_in/in_stock_list.html");
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
		
		if($page == "")
			$page = 1;
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$where = "";
		
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$total = $gModel->getInStockList($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
		
		$rows = $gModel->getInStockList($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
		
		
		$this->display();
	}
	
	/**
	 * 입고관리 상세
	 */
	function inStockDetailAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods_in/in_stock_detail.html");
		
		$artistIdx = $this->req->get("artist_idx");
		$regDay = $this->req->get("reg_day");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$artistRow = $gModel->getRow("name", "oc_artist", "idx = ".$artistIdx);
		
		$rows = $gModel->getInStockDetail($artistIdx, $regDay);
			
		$this->view->assign("rows", $rows);
		$this->view->assign("artist", $artistRow);
		$this->view->assign("artist_idx", $artistIdx);
		$this->view->assign("reg_day", $regDay);
	
		$this->display();
	}
	
	/**
	 * 입고관리 추가
	 */
	function inStockAddAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/goods_in/in_stock_edit.html");
		
		$mode = $this->req->get("mode");
		$idx = $this->req->get("idx");
		$artistIdx = $this->req->get("artist_idx");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		if($idx != ""){
			$row = $gModel->getInStockIdx($idx);
			$this->view->assign("row", $row);
		}
		
		if($artistIdx != ""){
			$artistRow = $gModel->getRow("*", "oc_artist", "idx = ".$artistIdx);
			$this->view->assign("artist", $artistRow);
		}
		
		$this->view->assign("mode", $mode);
		
		$this->display();
	}
	
	/**
	 * 상품 찾기 팝업
	 */
	function popSearchGoodsAction(){
		$this->adminIframeDefine();
		$this->view->define("content", "content/admin/goods/pop_search_goods.html");
		$artistIdx = $this->req->get("artist_idx");
		
		$this->view->assign("artist_idx", $artistIdx);
	
		$this->display();
	}
	
	/**
	 * 간단 상품등록
	 */
	function simpleAddAction(){
		$this->adminIframeDefine();
		$this->view->define("content", "content/admin/goods/pop_simple_add.html");
		$artistIdx = $this->req->get("artist_idx");
		$artistName = $this->req->get("artist_name");
		
		$this->view->assign("artist_idx", $artistIdx);
		$this->view->assign("artist_name", $artistName);
		
		$this->display();
	}
	
	/**
	 * 간단 상품등록 처리
	 */
	function simpleProcessAction(){
		$goodsTitle = $this->req->post("goods_title");
		$goodsCostPrice = $this->req->post("goods_cost_price");
		$goodsSalePrice = $this->req->post("goods_sale_price");
		$goodsStock = $this->req->post("goods_stock");
		$goodsArtist = $this->req->post("goods_artist_idx");
		$goodsTax = $this->req->post("goods_tax");
		$goodsDisplay = $this->req->post("goods_display");
		$goodsPos = $this->req->post("goods_pos");
		
		if($goodsStock == ""){
			$goodsStock = 0;
		}
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		$aModel = Lemon_Instance::getObject("ArtistModel", true);
		
		if($goodsArtist != ""){
			$goodsArtistName = $aModel->getArtistName($goodsArtist);
		}
		else{
			$goodsArtistName = $this->req->post("goods_artist_name");
		}
		
		$ocCode = $gModel->getNextCode();
		
		$data["title"] = $goodsTitle;
		$data["cost_price"] = $goodsCostPrice;
		$data["sale_price"] = $goodsSalePrice;
		$data["artist_idx"] = $goodsArtist;
		$data["artist_name"] = $goodsArtistName;
		$data["tax_yn"] = $goodsTax;
		$data["display_yn"] = $goodsDisplay;
		$data["pos_yn"] = $goodsPos;
		$data["modify_date"] = "now()";
		$data['oc_code'] = $ocCode;
		$data["reg_date"] = "now()";
				
		$rs = $gModel->insertData("oc_goods", $data);
		
		if($rs){
			throw new Lemon_ScriptException("등록되었습니다", "", "script", "parent.popAddGoodsResult(true, '".$ocCode."', '".$goodsTitle."', ".$goodsStock.", ".$goodsCostPrice.", ".$goodsSalePrice.", '".$goodsTax."');");
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "script", "parent.result(false);");
		}
	}
	
	function inStockProcessAction(){
		$mode = $this->req->post("mode");
		$idx = $this->req->post("idx");
		
		$artistIdx = $this->req->post("artist_idx");
		$isOccode = $this->req->post("in_stock_oc_code");
		$isRegday = $this->req->post("in_stock_reg_day");
		$isTitle = $this->req->post("in_stock_title");
		$isQty = $this->req->post("in_stock_qty");
		$isCostprice = $this->req->post("in_stock_cost_price");
		$isSaleprice = $this->req->post("in_stock_sale_price");
		$isTax = $this->req->post("in_stock_tax");
		$apply = $this->req->post("in_stock_apply");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$data['title'] = $isTitle;
		$data['oc_code'] = $isOccode;
		$data['artist_idx'] = $artistIdx;
		$data['qty'] = $isQty;
		$data['cost_price'] = $isCostprice;
		$data['sale_price'] = $isSaleprice;
		$data['tax_yn'] = $isTax;
		$data['reg_day'] = $isRegday;
		
		$retUrl = "/admin/goods/inStockDetail?artist_idx=".$artistIdx."&reg_day=".$isRegday;
		
		$gModel->db->tranBegin();
		
		$updateQty = 0;
		
		if($mode == "new"){
			$data['reg_date'] = "NOW()";
			$rs = $gModel->insertData("oc_goods_in", $data, false);
			$updateQty = $data['qty'];
		}
		else{
			$oldRs = $gModel->getRow("qty", "oc_goods_in", "idx = ".$idx);
			$rs = $gModel->updateData("oc_goods_in", $data, "idx = ".$idx, false);
			$updateQty = $data['qty'] - $oldRs['qty'];
		}
		
		$aRs = true;
		if($apply == "Y"){
			$aData['cost_price'] = $data['cost_price'];
			$aData['sale_price'] = $data['sale_price'];
				
			$aRs = $gModel->updateData("oc_goods", $aData, "oc_code = '".$data['oc_code']."'", false);
		}
		
		$sRs = $gModel->setGoodsStock($isOccode, $updateQty, false);
		
		if($rs && $aRs && $sRs){
			$gModel->db->tranEnd(true);
			throw new Lemon_ScriptException("등록되었습니다", "", "go", $retUrl);
		}
		else{
			$gModel->db->tranEnd(false);
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	function inStockDeleteAction(){
		$idx = $this->req->get("idx");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		$gModel->db->tranBegin();
		
		$row = $gModel->getInStockIdx($idx);
		$listCnt = $gModel->getInStockDetail($row['artist_idx'], $row['reg_day'], true);
		
		if($listCnt > 1){
			$retUrl = "/admin/goods/inStockDetail?artist_idx=".$row['artist_idx']."&reg_day=".$row['reg_day'];
		}
		else{
			$retUrl = "/admin/goods/inStockList";
		}
		
		$updateQty = -$row['qty'];
		
		$sRs = $gModel->setGoodsStock($row['oc_code'], $updateQty, false);
		
		$dRs = $gModel->deleteData("oc_goods_in", "idx = ".$idx, false);
		
		if($dRs && $sRs){
			$gModel->db->tranEnd(true);
			throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
		}
		else{
			$gModel->db->tranEnd(false);
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	function popSearchCategoryAction(){
		$this->adminIframeDefine();
		$this->view->define("content", "content/admin/goods/pop_search_category.html");
		
		$gModel = Lemon_Instance::getObject("GoodsModel", true);
		
		$rs = $gModel->getCategoryList();
		
		for($i=0; $i<sizeof($rs); $i++){
			$row = $rs[$i];
				
			if($row['deep'] == 1){
				$rows[$row['code']] = $row;
			}
			else{
				$rows[$row['parent_code']]['list'][$row['code']] = $row;
			}
		}
		
		$this->view->assign("rows", $rows);
		
		$this->display();
	}
	
	function imageUploadAjaxAction(){
		$this->setAjaxHeader();

		if($_FILES['thumb_upload']['name'] != ""){
			if(preg_match("/.(png|jpg|jpeg|gif)$/", $_FILES['thumb_upload']['name'])){
				$imgPath = $_SERVER['DOCUMENT_ROOT'].$this->path['temp_path'];
		
				if(!is_dir($imgPath)){
					mkdir($imgPath);
				}
		
				$path = $imgPath."/".date("Ym");
				if(!is_dir($path)){
					mkdir($path);
				}
		
				$fileName = uniqid();
		
				$fileUpload = Lemon_Instance::getObject("Lemon_FileUpload");
				$fileUpload->setFile($_FILES['thumb_upload'], $path."/".$fileName);
				$fileUpload->setOverwrite();
				$fileUpload->upload();
				$updateFileName = $fileUpload->getSaveFile();
		
				$th = new Lemon_Thumbnail($updateFileName, $path."/".$fileName."_small");
				$th->setMode('ratio');		// 비율유지, fix - 지정크기로 무조건 리사이즈
				$th->setSize($this->config['goods_thumb_small']['width'], $this->config['goods_thumb_small']['height']);	// 사이즈 지정. ratio - 해당 사이즈를 넘지 않는 선에서 비율유지하여 리사이즈
				$th->make();
				
				$th = new Lemon_Thumbnail($updateFileName, $path."/".$fileName."_medium");
				$th->setMode('ratio');		// 비율유지, fix - 지정크기로 무조건 리사이즈
				$th->setSize($this->config['goods_thumb_medium']['width'], $this->config['goods_thumb_medium']['height']);	// 사이즈 지정. ratio - 해당 사이즈를 넘지 않는 선에서 비율유지하여 리사이즈
				$th->make();
				
				$th = new Lemon_Thumbnail($updateFileName, $path."/".$fileName."_large");
				$th->setMode('ratio');		// 비율유지, fix - 지정크기로 무조건 리사이즈
				$th->setJpegRate(90);
				$th->setSize($this->config['goods_thumb_large']['width'], $this->config['goods_thumb_large']['height']);	// 사이즈 지정. ratio - 해당 사이즈를 넘지 않는 선에서 비율유지하여 리사이즈
				$th->make();
		
				$data['code'] = $this->message['success']['code'];
				$data['image_path'] = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);
				$data['image_name'] = str_replace($path, "", $updateFileName);
			}
			else{
				$data['code'] = $this->message['fail']['code'];
				$data['message'] = "이미지(png, jpg, jpeg, gif) 파일을 선택해주세요";
			}
		}
		else{
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "파일을 전송받지 못했습니다";
		}
		
		echo json_encode($data);
	}
}
?>