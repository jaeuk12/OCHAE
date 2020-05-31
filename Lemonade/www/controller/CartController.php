<?php
class CartController extends WebServiceController{
	function indexAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/cart/cart_list.html");
		
		$cModel = Lemon_Instance::getObject("CartModel", true);
		
		$memberIdx = $this->auth->getUID();
		
		$goodsInfo = $cModel->getCartGoodsInfo($memberIdx);
		$cartList = $cModel->getCartList($memberIdx);
		$totShippingPrice = 0;
		$subTotal = 0;
		
		for($i=0; $i<sizeof($goodsInfo); $i++){
			for($j=0; $j<sizeof($cartList); $j++){
				if($goodsInfo[$i]['oc_code'] == $cartList[$j]['oc_code']){
					if(!is_array($goodsInfo[$i]['list'])){
						$goodsInfo[$i]['list'] = array();
					}
					
					$price = ($goodsInfo[$i]['discount_yn'] == "Y"?$goodsInfo[$i]['discount_price']:$goodsInfo[$i]['sale_price']);
					
					$goodsInfo[$i]['tot_price'] += ($price + $cartList[$j]['option_price']) * $cartList[$j]['qty'];
					
					array_push($goodsInfo[$i]['list'], $cartList[$j]);
					
					array_splice($cartList, $j, 1);
					$j--;
				}
			}
			
			$subTotal += $goodsInfo[$i]['tot_price'];
		}
		
		for($i=0; $i<sizeof($goodsInfo); $i++){
			if($goodsInfo[$i]['shipping_free'] > 0){
				if($subTotal >= $goodsInfo[$i]['shipping_free']){
					$goodsInfo[$i]['tot_shipping_price'] = 0;
				}
				else if($goodsInfo[$i]['shipping_qty']>0){
					$count = ceil($goodsInfo[$i]['total_qty']/$goodsInfo[$i]['shipping_qty']);
					$goodsInfo[$i]['shipping_count'] = $count;
					$goodsInfo[$i]['tot_shipping_price'] = $count * $goodsInfo[$i]['shipping_price'];
					$totShippingPrice += $goodsInfo[$i]['tot_shipping_price'];
				}
				else{
					$goodsInfo[$i]['shipping_count'] = 1;
					$goodsInfo[$i]['tot_shipping_price'] = $goodsInfo[$i]['shipping_price'];
					$totShippingPrice += $goodsInfo[$i]['tot_shipping_price'];
				}
			}
			else{
				if($goodsInfo[$i]['shipping_qty']>0){
					$count = ceil($goodsInfo[$i]['total_qty']/$goodsInfo[$i]['shipping_qty']);
					$goodsInfo[$i]['shipping_count'] = $count;
					$goodsInfo[$i]['tot_shipping_price'] = $count * $goodsInfo[$i]['shipping_price'];
					$totShippingPrice += $goodsInfo[$i]['tot_shipping_price'];
				}
				else{
					$goodsInfo[$i]['shipping_count'] = 1;
					$goodsInfo[$i]['tot_shipping_price'] = $goodsInfo[$i]['shipping_price'];
					$totShippingPrice += $goodsInfo[$i]['tot_shipping_price'];
				}
			}
		}
		
		$this->view->assign("rows", $goodsInfo);
		$this->view->assign("subtotal", $subTotal);
		$this->view->assign("tot_shipping_price", $totShippingPrice);
		$this->view->assign("image_path", $this->path['goods_thumb_path']);
		
		$this->display();
	}
	
	function addCartAjaxAction(){
		$this->setAjaxHeader("login");
	
		$ocCode = $this->req->post("oc_code");
		$qty = $this->req->post("qty");
		$optionIdx = $this->req->post("option_idx");
		$uid = $this->auth->getUID();
		
		$cModel = Lemon_Instance::getObject("CartModel", true);
		
		if($optionIdx == ""){
			$optionIdx = 0;
		}
	
		$oRs = $cModel->getRow("*", "oc_cart", "member_idx='".$uid."' AND oc_code='".$ocCode."' AND option_idx=".$optionIdx);
		
		if($oRs != ""){
			$upData['qty'] = $oRs['qty'] + $qty;
			$upData['modify_date'] = "NOW()";
			$rs = $cModel->updateData("oc_cart", $upData, "member_idx='".$uid."' AND oc_code='".$ocCode."' AND option_idx=".$optionIdx);
		
			$data['code'] = $this->message['success']['code'];
			$data['message'] = "수량이 추가되었습니다 [ ".$oRs['qty']."=>".$upData['qty']." ]";
		}
		else{
			$inData['member_idx'] = $uid;
			$inData['oc_code'] = $ocCode;
			$inData['qty'] = $qty;
			$inData['option_idx'] = $optionIdx;
			$inData['modify_date'] = "NOW()";
			$inData['reg_date'] = "NOW()";
		
			$rs = $cModel->insertData("oc_cart", $inData);
		
			$data['code'] = $this->message['success']['code'];
			$data['message'] = "카트에 담았습니다";
		}
		
		if(!$rs){
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "에러가 발생했습니다";
		}
	
		echo json_encode($data);
	}
	
	function removeCartAjaxAction(){
		$this->setAjaxHeader();
		
		$ocCode = $this->req->post("oc_code");
		$uid = $this->auth->getUID();
		
		if(!$this->auth->isLogin()){
			$data['code'] = $this->message['need_login']['code'];
			$data['message'] = $this->message['need_login']['message'];
				
			echo json_encode($data);
			exit;
		}
	
		$cModel = Lemon_Instance::getObject("CartModel", true);
		
		$rs = $cModel->deleteData("oc_cart", "member_idx='".$uid."' AND oc_code='".$ocCode."'");
		
		if($rs){
			$data['code'] = $this->message['success']['code'];
			$data['message'] = "카트에서 삭제되었습니다";
		}
		else{
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "카트에서 삭제중 에러가 발생했습니다";
		}
		
		echo json_encode($data);
	}
	
	function getCartInfoAjaxAction(){
		$this->setAjaxHeader();
		
		$ocCode = $this->req->post("oc_code");
		
		$cModel = Lemon_Instance::getObject("CartModel", true);
		
		$goods = $cModel->getRow("oc_code, title, sale_price, discount_price, discount_yn, shipping_price, shipping_free, shipping_qty, thumb", "oc_goods", "oc_code = '".$ocCode."'");
		$cartList = $cModel->getCartList($this->auth->getUID(), $ocCode);
		
		$tpl = new Template_();
		$tpl->define("index", "content/cart/cart_edit.html");
		$tpl->assign("goods", $goods);
		$tpl->assign("cartlist", $cartList);
		$tpl->assign("image_path", $this->path['goods_thumb_path']);
		$content = $tpl->fetch("index");
		
		echo $content;
	}
	
	function updateCartAjaxAction(){
		$this->setAjaxHeader("login");
		
		$ocCode = $this->req->post("oc_code");
		$optionIdx = $this->req->post("option_idx");
		$optionQty = $this->req->post("option_qty");

		$cModel = Lemon_Instance::getObject("CartModel", true);
		
		if($this->auth->isLogin()){
			$memberIdx = $this->auth->getUID();
			$cartList = $cModel->getCartList($memberIdx, $ocCode);
			
			if(sizeof($cartList)>0){
				if($optionIdx != ""){
					for($i=0; $i<sizeof($optionIdx); $i++){
						$isEquals = false;
				
						for($j=0; $j<sizeof($cartList); $j++){
							if($optionIdx[$i] == $cartList[$j]['option_idx']){
								if($optionQty[$i] != $cartList[$j]['qty']){
									$uData['qty'] = $optionQty[$i];
									$cModel->updateData("oc_cart", $uData, "member_idx='".$memberIdx."' AND oc_code='".$ocCode."' AND option_idx=".$optionIdx[$i]);
								}
				
								array_splice($cartList, $j, 1);
								break;
							}
						}
					}
					
					$delCode = array();
					for($i=0; $i<sizeof($cartList); $i++){
						array_push($delCode, $cartList[$i]['option_idx']);
					}
					
					if(sizeof($delCode)>0){
						$cModel->deleteData("oc_cart", "member_idx='".$memberIdx."' AND oc_code = '".$ocCode."' AND option_idx in (".implode(", ", $delCode).")");
					}
				}
				else{
					$uData['qty'] = $optionQty;
					$cModel->updateData("oc_cart", $uData, "member_idx='".$memberIdx."' AND oc_code='".$ocCode."' AND option_idx=0");
				}
				
				$data['code'] = $this->message['success']['code'];
				$data['message'] = "카트가 변경되었습니다";
			}
			else{
				$data['code'] = $this->message['fail']['code'];
				$data['message'] = "카트에 해당 상품이 없습니다";
			}
		}
		else{
			$data['code'] = $this->message['fail']['code'];
			$data['message'] = "로그인 후 이용해주세요";
		}
		
		echo json_encode($data);
	}
} 
?>