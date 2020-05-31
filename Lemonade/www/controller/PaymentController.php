<?php
class PaymentController extends WebServiceController{
	function readyAction(){
		$this->commonDefine("login");
		$this->view->define("content", "content/payment/pay_ready.html");
		
		$orderNo = "OC_".uniqid();
		
		$pModel = Lemon_Instance::getObject("PaymentModel", true);
		
		$referer = parse_url($_SERVER['HTTP_REFERER']);
		$memberIdx = $this->auth->getUID();
		$totShippingPrice = 0;
		$subTotal = 0;
		
		if($referer['host'] != "local.ochae.com"){
			throw new Lemon_ScriptException("잘못된 접근입니다", "", "back");
			exit;
		}
		
		$ocCode = $this->req->post("oc_code");
		$qty = $this->req->post("oc_qty");
		$optionIdx = $this->req->post("oc_option_idx");
		
		$goodsInfo = $pModel->getGoodsCodes($ocCode);
		$optionList = $pModel->getOptionList($optionIdx);
		
		$inputOrder['oc_code'] = array();
		$inputOrder['order_no'] = array();
		$inputOrder['sort_no'] = array();
		$inputOrder['cost_price'] = array();
		$inputOrder['sale_price'] = array();
		$inputOrder['discount_price'] = array();
		$inputOrder['discount_yn'] = array();
		$inputOrder['qty'] = array();
		$inputOrder['option_name'] = array();
		$inputOrder['option_price'] = array();
		$inputOrder['total_price'] = array();
		
		$pModel->db->tranBegin();
		
		for($i=0; $i<sizeof($goodsInfo); $i++){
			$price = ($goodsInfo[$i]['discount_yn'] == "Y"?$goodsInfo[$i]['discount_price']:$goodsInfo[$i]['sale_price']);
			
			for($j=0; $j<sizeof($ocCode); $j++){
				if($goodsInfo[$i]['oc_code'] == $ocCode[$j]){
					$goodsInfo[$i]['total_qty'] += $qty[$j];
					
					if($optionIdx[$j] != 0){
						for($k=0; $k<sizeof($optionList); $k++){
							if($goodsInfo[$i]['oc_code'] == $optionList[$k]['oc_code']
							&& $optionIdx[$j] == $optionList[$k]['idx']){
								if(!is_array($goodsInfo[$i]['list'])){
									$goodsInfo[$i]['list'] = array();
								}
									
								$optionList[$k]['qty'] = $qty[$j];
									
								$goodsInfo[$i]['tot_price'] += ($price + $optionList[$k]['option_price']) * $optionList[$k]['qty'];
					
								array_push($goodsInfo[$i]['list'], $optionList[$k]);
								
								array_push($inputOrder['option_name'], $optionList[$k]['option_name']);
								array_push($inputOrder['option_price'], $optionList[$k]['option_price']);
								
								array_splice($optionList, $k, 1);
								break;
							}
						}
					}
					else{
						$goodsInfo[$i]['tot_price'] = $price * $goodsInfo[$i]['total_qty'];
					}
					
					array_push($inputOrder['oc_code'], $goodsInfo[$i]['oc_code']);
					array_push($inputOrder['order_no'], $orderNo);
					array_push($inputOrder['sort_no'], sizeof($inputOrder['sort_no'])+1);
					array_push($inputOrder['cost_price'], $goodsInfo[$i]['cost_price']);
					array_push($inputOrder['sale_price'], $goodsInfo[$i]['sale_price']);
					array_push($inputOrder['discount_price'], $goodsInfo[$i]['discount_price']);
					array_push($inputOrder['discount_yn'], $goodsInfo[$i]['discount_yn']);
					array_push($inputOrder['qty'], $qty[$j]);
					array_push($inputOrder['total_price'], $goodsInfo[$i]['tot_price']);
				}
			}
			
			if($goodsInfo[$i]['shipping_free'] > 0){
				if($goodsInfo[$i]['tot_price'] >= $goodsInfo[$i]['shipping_free']){
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
				
			$subTotal += $goodsInfo[$i]['tot_price'];
			
			$insertShipping['order_no'] = array();
			$insertShipping['oc_code'] = array();
			$insertShipping['shipping_price'] = array();
			
			if($goodsInfo[$i]['shipping_count'] > 0){
				if($goodsInfo[$i]['shipping_count'] == 1){
					$insertShipping['order_no'] = $orderNo;
					$insertShipping['oc_code'] = $goodsInfo[$i]['oc_code'];
					$insertShipping['shipping_price'] = $goodsInfo[$i]['tot_shipping_price'];
					
					$shippingRs = $pModel->insertData("oc_order_shipping", $insertShipping, false);
				}
				else{
					$shippingPrice = floor($goodsInfo[$i]['tot_shipping_price']/$goodsInfo[$i]['shipping_count']);
					for($sc=0; $sc<sizeof($goodsInfo[$i]['shipping_count']); $sc++){
						array_push($insertShipping['order_no'], $orderNo);
						array_push($insertShipping['oc_code'], $goodsInfo[$i]['oc_code']);
						array_push($insertShipping['shipping_price'], $shippingPrice);
					}
					
					$shippingRs = $pModel->insertArraysOnDuplicateUpdate("oc_order_shipping", $insertShipping, false, false);
				}
			}
		}
		
		$payPrice = $subTotal+$totShippingPrice;

		$inputPay['member_idx'] = $memberIdx;
		$inputPay['order_no'] = $orderNo;
		$inputPay['total_price'] = $payPrice;
		$inputPay['shipping_price'] = $totShippingPrice;
		$inputPay['pay_price'] = $payPrice + $totShippingPrice;
		$inputPay['pay_gubun'] = "ready";
		$inputPay['reg_date'] = "NOW()";
		
		$orderRs = $pModel->insertArraysOnDuplicateUpdate("oc_order_info", $inputOrder, false, false);
		$payRs = $pModel->insertData("oc_pay_info", $inputPay, false);
		
		if($shippingRs && $orderRs && $payRs){
			$pModel->db->tranEnd(true);
		}
		else{
			$pModel->db->tranEnd(false);
			throw new Lemon_ScriptException("$shippingRs , $orderRs , $payRs 에러가 발생했습니다", "다시 시도해주세요", "back");
			exit;
		}
		
		$addressRows = $pModel->getRows("*", "oc_member_address", "member_idx='".$memberIdx."'", "shipping_name");
		
		$this->view->assign("order_no", $orderNo);
		$this->view->assign("addressRows", $addressRows);
		$this->view->assign("rows", $goodsInfo);
		$this->view->assign("subtotal", $subTotal);
		$this->view->assign("tot_shipping_price", $totShippingPrice);
		$this->view->assign("image_path", $this->path['goods_thumb_path']);
		
		$this->display();
	}
} 
?>