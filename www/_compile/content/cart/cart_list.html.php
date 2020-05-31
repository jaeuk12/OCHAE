<?php /* Template_ 2.2.3 2016/08/31 21:33:57 D:\Work\OCHAE\www\_template\content\cart\cart_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
	});
	
	function cartEdit(ocCode){
		if(!isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/cart/getCartInfoAjax',
				data: {"oc_code" : ocCode},
				async: true,
				dataType: 'html',
				success: function(data) {
					modalProgress.close();
					modalPop.show(data);
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}

	function removeGoods(ocCode){
		if(confirm("카트에서 삭제하시겠습니까?")){
			if(!isProcess){
				modalProgress.show();
				
				$.ajax({
					type: 'POST',
					url: '/cart/removeCartAjax',
					data: {"oc_code" : ocCode},
					async: true,
					dataType: 'json',
					success: function(data) {
						modalProgress.close();
						
						alert(data.message);
						if(data.code == 100){
							location.reload();
						}
					},
					error: function(xhr, status) {
						//alert('[' + status + ']\n\n' + xhr.responseText);
						modalProgress.close();
					}
				});
			}
		}
	}
	
	function cartEditResult(b){
		modalPop.close();
		if(b){
			location.reload();
		}
	}
</script>

<article class="default_contents">
	<section class="cart_content">
		<form name="cartForm" action="/payment/ready" method="post" onsubmit="return false;">
			<h2>My Cart</h2>
			<div class="cart_list">
				<table>
					<thead>
						<th class="thumb">이미지</th>
						<th class="info">상품명</th>
						<th class="qty">수량</th>
						<th class="price">가격</th>
						<th class="total">합계</th>
					</thead>
					<tbody>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
						<tr>
							<td class="thumb"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["thumb"]?>"/></td>
							<td class="info">
								<h5><a href="javascript:detail('<?php echo $TPL_V1["oc_code"]?>');"><?php echo $TPL_V1["title"]?> <?php echo $TPL_V1["shipping_count"]?></a></h5>
								<div class="price">
									<span class="<?php echo $TPL_V1["discount_yn"]?>">￦ <?php echo number_format($TPL_V1["sale_price"])?></span>
<?php if($TPL_V1["discount_yn"]=="Y"){?><span class="discount">￦ <?php echo number_format($TPL_V1["discount_price"])?></span><?php }?>
									<span class="shipping"><?php if($TPL_V1["tot_shipping_price"]>0){?>￦ <?php echo number_format($TPL_V1["shipping_price"])?><?php if($TPL_V1["shipping_count"]!=''&&$TPL_V1["shipping_count"]>1){?> X <?php echo $TPL_V1["shipping_count"]?><?php }?><?php }else{?>FREE<?php }?></span>
								</div>
								<div class="option">
									<ul>
<?php if(is_array($TPL_R2=$TPL_V1["list"])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
										<li>
											<input type="hidden" name="oc_code[]" value="<?php echo $TPL_V2["oc_code"]?>"/>
											<input type="hidden" name="oc_qty[]" value="<?php echo $TPL_V2["qty"]?>"/>
											<input type="hidden" name="oc_option_idx[]" value="<?php echo $TPL_V2["option_idx"]?>"/>
<?php if($TPL_V2["option_idx"]!=0){?>
												<?php echo $TPL_V2["option_name"]?><?php if($TPL_V2["option_price"]!=0){?><div><span class="price">￦ <?php echo number_format($TPL_V2["option_price"])?></span><?php }?><span class="qty"><?php echo $TPL_V2["qty"]?></span></div>
<?php }?>
										</li>
<?php }}?>
									</ul>
								</div>
								<div class="total">
									<span class="price">￦ <?php echo number_format($TPL_V1["tot_price"])?></span><span class="qty"><?php echo $TPL_V1["total_qty"]?></span>
								</div>
								<div class="btn_group">
									<button onclick="removeGoods('<?php echo $TPL_V1["oc_code"]?>')">REMOVE</button>
									<button onclick="cartEdit('<?php echo $TPL_V1["oc_code"]?>')">EDIT</button>
								</div>
							</td>
							<td class="qty">
								<span class="qty"><?php echo $TPL_V1["total_qty"]?></span>
							</td>
							<td class="price">
								<span class="<?php echo $TPL_V1["discount_yn"]?>">￦ <?php echo number_format($TPL_V1["sale_price"])?></span><br/>
<?php if($TPL_V1["discount_yn"]=="Y"){?><span class="discount">￦ <?php echo number_format($TPL_V1["discount_price"])?></span><br/><?php }?>
								<span class="shipping"><?php if($TPL_V1["tot_shipping_price"]>0){?>￦ <?php echo number_format($TPL_V1["shipping_price"])?><?php if($TPL_V1["shipping_count"]!=''&&$TPL_V1["shipping_count"]>1){?> X <?php echo $TPL_V1["shipping_count"]?><?php }?><?php }else{?>FREE<?php }?></span>
							</td>
							<td class="total">
								￦ <?php echo number_format($TPL_V1["tot_price"])?>

							</td>
						</tr>
<?php }}?>
					</tbody>
				</table>
			</div>
			<div class="cart_pay">
				<div class="content">
					<ul>
						<li><span>SUBTOTAL PRICE</span>￦ <?php echo number_format($TPL_VAR["subtotal"])?></li>
						<li><span>SHIPPING PRICE</span>￦ <?php echo number_format($TPL_VAR["tot_shipping_price"])?></li>
						<li class="est"><span>ESTIMATED PRICE</span>￦ <?php echo number_format($TPL_VAR["subtotal"]+$TPL_VAR["tot_shipping_price"])?></li>
					</ul>
					<button class="btn_payment" onclick="document.cartForm.submit()">PAYMENT</button>
				</div>
			</div>
		</form>
	</section>
</article>