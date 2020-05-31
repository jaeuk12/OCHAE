<?
class Admin_GalleryController extends WebServiceController{
	var $listNum = 10; 
	
	/**
	 * 갤러리 리스트
	 */
	function listAction(){
		$searchType = $this->req->get("search_type");
		$searchKeyword = $this->req->get("search_keyword");
		$page = $this->req->get("page");
	
		if($page == "")
			$page = 1;
		
		$this->adminDefine();
		$this->view->define("content", "content/admin/gallery/list.html");
		
		if($searchKeyword != ""){
			$where .= $searchType." like '".$searchKeyword."%' ";
		}
	
		$gModel = Lemon_Instance::getObject("GalleryModel", true);
	
		$total = $gModel->getGalleryList($where);
		
		$totalPage = ceil($total/$this->listNum);
		
		if($page > $totalPage)
			$page = $totalPage;
	
		$pageMaker = Lemon_Instance::getObject("Lemon_Page");
		$pageMaker->setListNum($this->listNum);	// 한 페이지에 표시할 리스트 개수
		$pageMaker->setPageNum(10);	// 총 노출시킬 페이지 번호 수
		$pageMaker->setPage($page, $total);	// $page - 현재페이지, $total - 총 페이지수
		$pagelist = $pageMaker->pageList("search_type=".$searchType."&search_keyword=".$searchKeyword);
	
		$rows = $gModel->getGalleryList($where, $pageMaker->first, $this->listNum);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("pagelist", $pagelist);
		$this->view->assign("total", $total);
		$this->view->assign("total_num", $total - (($page-1)*$this->listNum));
		$this->view->assign("search_type", $searchType);
		$this->view->assign("search_keyword", $searchKeyword);
	
		$this->display();
	}
	
	/**
	 * 갤러리 등록/변경 페이지
	 */
	function addAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/gallery/edit.html");
	
		$idx = $this->req->get("idx");
		$retUrl = $this->req->get("ret_url");
		$mode = "new";
	
		$gModel = Lemon_Instance::getObject("GalleryModel", true);
	
		if($idx != ""){
			$mode = "modify";
			$row = $gModel->getGalleryIdx($idx);
			$this->view->assign("row", $row);
		}
	
		$this->view->assign("mode", $mode);
		$this->view->assign("ret_url", $retUrl);
	
		$this->display();
	}
	
	/**
	 * 갤러리 등록/변경 처리
	 */
	function processAction(){
		$mode = $this->req->post("mode");
		$retUrl = $this->req->post("ret_url");
		$idx = $this->req->post("idx"); 
	
		$galleryTitle = $this->req->post("gallery_title");
		$galleryContent = $this->req->post("gallery_content");
		$galleryArtist = $this->req->post("gallery_artist");
		$galleryDisplay = $this->req->post("gallery_display");
	
		if($retUrl == ""){
			$retUrl = "/admin/gallery/list";
		}
		else{
			$retUrl = htmlspecialchars_decode(urldecode($retUrl));
		}
	
		$gModel = Lemon_Instance::getObject("GalleryModel", true);
	
		if($_FILES["gallery_image"]["name"] != ""){
			if(preg_match("/.(png|jpg|jpeg|gif)$/", $_FILES['gallery_image']['name'])){
				$imgPath = $_SERVER['DOCUMENT_ROOT'].$this->path['gallery_image_path'];
	
				if(!is_dir($imgPath)){
					mkdir($imgPath);
				}
	
				$path = $imgPath."/".date("Ym");
				if(!is_dir($path)){
					mkdir($path);
				}
	
				$fileName = uniqid();
	
				$fileUpload = Lemon_Instance::getObject("Lemon_FileUpload");
				$fileUpload->setFile($_FILES['gallery_image'], $path."/".$fileName);
				$fileUpload->setOverwrite();
				$fileUpload->upload();
				$updateFileName = $fileUpload->getSaveFile();
				
				$th = new Lemon_Thumbnail($updateFileName, $path."/".$fileName."_thumb");
				$th->setMode('ratio');		// 비율유지, fix - 지정크기로 무조건 리사이즈
				$th->setSize($this->config['gallery_thumb']['width'], $this->config['gallery_thumb']['height']);	// 사이즈 지정. ratio - 해당 사이즈를 넘지 않는 선에서 비율유지하여 리사이즈
				$th->make();
				
				$dImagePath = $gModel->getRow("image", "oc_gallery", "idx = ".$idx);
				if($dImagePath['image'] != "" && is_file($imgPath.$dImagePath['image'])){
					unlink($imgPath.$dImagePath['image']);
					unlink($imgPath.str_replace(".", "_thumb.", $dImagePath['image']));
				}
	
				$data["image"] = str_replace($imgPath, "", $updateFileName);
			}
		}
		
		$data["title"] = $galleryTitle;
		$data["content"] = $galleryContent;
		$data["artist_idx"] = $galleryArtist;
		$data["display_yn"] = $galleryDisplay;
		$data["modify_date"] = "now()";
	
		if($mode == "new"){
			$data["reg_date"] = "now()";
			
			$rs = $gModel->insertData("oc_gallery", $data);
		}
		else{
			$rs = $gModel->updateData("oc_gallery", $data, "idx = ".$idx);
		}
		
		if($rs){
			throw new Lemon_ScriptException($mode=="new"?"등록되었습니다":"수정되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	function deleteAction(){
		$idx = $this->req->get("idx");
		$retUrl = "/admin/gallery/list";
		
		$gModel = Lemon_Instance::getObject("GalleryModel", true);
		
		$rs = $gModel->getRow("*", "oc_gallery", "idx = ".$idx);
		
		if(is_array($rs)){
			$dRs = $gModel->deleteData("oc_gallery", "idx = ".$idx);
		
			if($dRs){
				if($rs['image'] != ""){
					$filePath = $_SERVER['DOCUMENT_ROOT'].$this->path['gallery_image_path'].$rs['image'];
					if(is_file($filePath)){
						unlink($filePath);
						unlink(str_replace(".", "_thumb.", $filePath));
					}
				}
		
				throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
			}
			else{
				throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
			}
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
}
?>