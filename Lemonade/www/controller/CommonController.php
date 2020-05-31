<?php
class CommonController extends WebServiceController{
	var $listNum = 10;
	
	function searchPostAjaxAction(){
		$this->setAjaxHeader();
		
		$url = "http://www.juso.go.kr/addrlink/addrLinkApi.do";
		$confmKey = "U01TX0FVVEgyMDE2MDgyNDAxMTIxNDE0NzQ2";
		
		$keyword = $this->req->post("keyword");
		$page = $this->req->post("page"); 
		
		$param = "confmKey=".$confmKey."&currentPage=".$page."&countPerPage=".$this->listNum."&keyword=".$keyword;
		
		if($page =="")
			$page = 1;
		
		$common = "";
		$juso = "";
		
		if($keyword != ""){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		    curl_setopt ($ch, CURLOPT_SSLVERSION,1); 
		    curl_setopt ($ch, CURLOPT_HEADER, 0); 
		    curl_setopt ($ch, CURLOPT_POST, 1);
		    curl_setopt ($ch, CURLOPT_POSTFIELDS, $param);  
		    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt ($ch, CURLOPT_TIMEOUT, 30); 
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			
			curl_close($ch);
	
			$strXml = simplexml_load_string($result);
			$xmlArray = Func::xmlToArray($strXml);
			
			$common = $xmlArray['results']['common'];
			
			if($common['totalCount'] < 2){
				$juso = array();
				array_push($juso, $xmlArray['results']['juso']);
			}
			else{
				$juso = $xmlArray['results']['juso'];
			}
		}
		
		$totalPage = ceil($common['totalCount']/$this->listNum);
		
		$pageCount = 9;
		$halfPage = ($pageCount-1)/2;
		
		if($page > $totalPage)
			$page = $totalPage;
		
		$startPage = $page-$halfPage;
		$endPage = $page+$halfPage;
		
		if($startPage < 1){
			$startPage = 1;
			$endPage = $pageCount;
		}
		else if($endPage > $totalPage){
			$startPage = $totalPage-$pageCount;
			$endPage = $totalPage;
		}
		
		$tpl = new Template_();
		$tpl->define("index", "content/postcode.html");
		$tpl->assign("page", $page);
		$tpl->assign("start_page", $startPage);
		$tpl->assign("end_page", $endPage);
		$tpl->assign("list_num", $this->listNum);
		$tpl->assign("keyword", $keyword);
		$tpl->assign("common", $common);
		$tpl->assign("juso", $juso);
		$content = $tpl->fetch("index");
		
		$data['code'] = $common['errorCode'];
		$data['message'] =$common['errorMessage'];
		$data['content'] = $content;
		
		echo json_encode($data);
	}
} 
?>
