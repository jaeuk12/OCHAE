<?
class Admin_MdController extends WebServiceController{
	/**
	 * 메인 슬라이더 화면 관리
	 */
	function mainsliderAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/md/main_slider_list.html");
		
		$gubun = "main_slider";
		
		$mdModel = Lemon_Instance::getObject("MDModel", true);
		
		$rows = $mdModel->getLinkList($gubun);
		
		$this->view->assign("rows", $rows);
		$this->view->assign("gubun", $gubun);

		$this->display();
	}
	
	/**
	 * 메인 슬라이더 등록/변경
	 */
	function addmainsliderAction(){
		$this->adminDefine();
		$this->view->define("content", "content/admin/md/main_slider_edit.html");
		
		$idx = $this->req->get("idx");
		$retUrl = $this->req->get("ret_url");
		
		$mdModel = Lemon_Instance::getObject("MDModel", true);
		
		if(is_numeric($idx)){
			$mode = "modify";
			
			$row = $mdModel->getRow("*", "oc_link_md", "idx = ".$idx);
			
			$this->view->assign("row", $row);
		}
		else{
			$mode = "new";
		}
	
		$this->view->assign("mode", $mode);
		$this->view->assign("ret_url", $retUrl);
		
		$this->display();
	}
	
	/**
	 * 메인 슬라이더 등록/변경 처리
	 */
	function mainsliderprocessAction(){
		$gubun = "main_slider";
		$retUrl = $this->req->post("ret_url");
		$mode = $this->req->post("mode");
		$idx = $this->req->post("idx");
		
		if($retUrl == ""){
			$retUrl = "/admin/md/mainslider";
		}
		
		$mdModel = Lemon_Instance::getObject("MDModel", true);
		
		if($_FILES['md_main_bg']['name'] != ""){
			if(preg_match("/.(png|jpg|jpeg|gif)$/", $_FILES['md_main_bg']['name'])){
				$imgPath = $_SERVER['DOCUMENT_ROOT'].$this->path['main_slider_path'];
		
				if(!is_dir($imgPath)){
					mkdir($imgPath);
				}
				
				$path = $imgPath."/".date("Ym");
				if(!is_dir($path)){
					mkdir($path);
				}
				
				$fileName = uniqid();
		
				$fileUpload = Lemon_Instance::getObject("Lemon_FileUpload");
				$fileUpload->setFile($_FILES['md_main_bg'], $path."/".$fileName);
				$fileUpload->setOverwrite();
				$fileUpload->upload();
				$updateFileName = $fileUpload->getSaveFile();
				
				$dImagePath = $mdModel->getRow("bg_image", "oc_link_md", "idx = ".$idx);
				if($dImagePath['bg_image'] != "" && is_file($imgPath.$dImagePath['bg_image'])){
					unlink($imgPath.$dImagePath['bg_image']);
				}
		
				$data['bg_image'] = str_replace($imgPath, "", $updateFileName);
				
			}
		}
	
		$title = $this->req->post("md_main_title");
		$content = $this->req->post("md_main_content");
		$link = $this->req->post("md_main_link");
		$viewYn = $this->req->post("md_main_view");
		
		$data['gubun'] = $gubun;
		$data['title'] = addslashes($title);
		$data['content'] = addslashes($content);
		$data['link'] = addslashes($link);
		$data['view_yn'] = $viewYn=="N"?"N":"Y";
	
		if($idx == ""){
			$lRs = $mdModel->getRow("order_no", "oc_link_md", "gubun = '".$gubun."'", "order_no DESC");
			$data['order_no'] = is_numeric($lRs['order_no'])?$lRs['order_no']+1:1;
			$data['reg_date'] = "now()";
			$rs = $mdModel->insertData("oc_link_md", $data);
		}
		else{
			$data['modify_date'] = "now()";
			$rs = $mdModel->updateData("oc_link_md", $data, "idx=".$idx);
		}
		
		if($rs){
			throw new Lemon_ScriptException($mode=="new"?"등록되었습니다":"수정되었습니다", "", "go", $retUrl);
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
		}
	}
	
	/**
	 * 메인 슬라이더 삭제
	 */
	function deleteMainSliderAction(){
		$idx = $this->req->get("idx");
		$gubun = $this->req->get("gubun");
	
		$mdModel = Lemon_Instance::getObject("MDModel", true);
	
		$rs = $mdModel->getRow("*", "oc_link_md", "idx = ".$idx." AND gubun = '".$gubun."'");
	
		if(is_array($rs)){
			$retUrl = "/admin/md/".str_replace("_", "", $gubun);
			$dRs = $mdModel->deleteData("oc_link_md", "idx = ".$idx." AND gubun = '".$gubun."'");
			
			if($dRs){
				if($rs['bg_image'] != ""){
					$filePath = $_SERVER['DOCUMENT_ROOT'].$this->path['main_slider_path'].$rs['bg_image'];
					if(is_file($filePath)){
						unlink($filePath);
					}
				}
				
				$mdModel->updateOrderno($rs['order_no'], $gubun);
				
				throw new Lemon_ScriptException("삭제되었습니다", "", "go", $retUrl);
			}
			else{
				throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", $retUrl);
			}
		}
		else{
			throw new Lemon_ScriptException("에러가 발생했습니다", "", "go", "/admin");
		}
	}
	
	function sortAction(){
		$idx = $this->req->get("idx");
		$orderNo = $this->req->get("order_no");
		$gubun = $this->req->get("gubun");
		
		$mdModel = Lemon_Instance::getObject("MDModel", true);
		
		$row = $mdModel->getRow("*", "oc_link_md", "idx=".$idx);
		
		$mdModel->sortOrderno($gubun, $orderNo, $row['order_no']-1);
		
		$data['order_no'] = $orderNo;
		$mdModel->updateData("oc_link_md", $data, "idx=".$idx);
		
		throw new Lemon_ScriptException("정렬되었습니다", "", "go", $_SERVER['HTTP_REFERER']);
	}
}
?>