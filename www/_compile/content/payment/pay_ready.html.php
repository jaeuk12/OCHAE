<?php /* Template_ 2.2.3 2016/08/31 20:06:54 D:\Work\OCHAE\www\_template\content\payment\pay_ready.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);
$TPL_addressRows_1=empty($TPL_VAR["addressRows"])||!is_array($TPL_VAR["addressRows"])?0:count($TPL_VAR["addressRows"]);?>
<script>
	$(document).ready(function(){
		$(".options > ul li").click(function(){
			var t = $(this);
			var ul = t.parent();
			
			if(ul.hasClass("open")){
				ul.removeClass("open");
				$(".options > ul li").removeClass("active");
				t.addClass("active");
				$("#option_choose").val(t.attr("idx"));
				
				$("input[name='post_code']").val(t.attr("post_code"));
				$("input[name='address1']").val(t.attr("address1"));
				$("input[name='address1_old']").val(t.attr("address1_old"));
				$("input[name='address2']").val(t.attr("address2"));
				$("input[name='contact1']").val(t.attr("contact1"));
				$("input[name='contact2']").val(t.attr("contact2"));
			}
			else{
				ul.addClass("open");
			}
		});
		
		$(".options ul li:first-child").click().click();
		
		if($(document).width() > 766){
			$(document).scroll(function(){
				var tt = $(this).scrollTop()+$(".nav_menu_bar").height();
				var cp = $(".cart_pay");
				var pr = $(".pay_ready").get(0).offsetTop;
				
				if(tt >= pr){
					if(cp.css("position") != "absolute"){
						cp.css({"position":"fixed", "top":"150px", "right":"0"});
					}
				}
				else{
					console.log(tt + " , " + pr + " , " + cp.css("position"));
					if(cp.css("position") == "fixed"){
						cp.css("position", "static");
						cp.removeProp("padding-left");
					}
				}
			});
		}
		else{
			$(".footer").css("");
		}
	});
	
	function postResult(zipNo, roadAddr, jibunAddr){
		$("input[name='post_code']").val(zipNo);
		$("input[name='address1']").val(roadAddr);
		$("input[name='address1_old']").val(jibunAddr);
		
		postModalPop.close();
	}
</script>

<article class="pay_contents">
	<section class="cart_content">
		<h2>Payment</h2>
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
							<h5><a href="javascript:detail('<?php echo $TPL_V1["oc_code"]?>');"><?php echo $TPL_V1["title"]?></a></h5>
							<div class="price">
								<span class="<?php echo $TPL_V1["discount_yn"]?>">￦ <?php echo number_format($TPL_V1["sale_price"])?></span>
<?php if($TPL_V1["discount_yn"]=="Y"){?><span class="discount">￦ <?php echo number_format($TPL_V1["discount_price"])?></span><?php }?>
								<span class="shipping"><?php if($TPL_V1["tot_shipping_price"]>0){?>￦ <?php echo number_format($TPL_V1["shipping_price"])?><?php if($TPL_V1["shipping_count"]!=''&&$TPL_V1["shipping_count"]>1){?> X <?php echo $TPL_V1["shipping_count"]?><?php }?><?php }else{?>FREE<?php }?></span>
							</div>
							<div class="option">
								<ul>
<?php if(is_array($TPL_R2=$TPL_V1["list"])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
									<li>
<?php if($TPL_V2["idx"]!=0){?>
										<?php echo $TPL_V2["option_name"]?><?php if($TPL_V2["option_price"]!=0){?><div><span class="price">￦ <?php echo number_format($TPL_V2["option_price"])?></span><?php }?><span class="qty"><?php echo $TPL_V2["qty"]?></span></div>
<?php }?>
									</li>
<?php }}?>
								</ul>
							</div>
							<div class="total">
								<span class="price">￦ <?php echo number_format($TPL_V1["tot_price"])?></span><span class="qty"><?php echo $TPL_V1["total_qty"]?></span>
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
	</section>
	<div class="pay_ready">
		<div class="order_info">
			<div class="address_list options">
				<h3>배송지 주소록</h3>
				<input type="hidden" name="option_choose" id="option_choose"/>
				<ul>
<?php if($TPL_addressRows_1){foreach($TPL_VAR["addressRows"] as $TPL_V1){?>
					<li post_code="<?php echo $TPL_V1["post_code"]?>" contact1="<?php echo $TPL_V1["contact1"]?>" contact2="<?php echo $TPL_V1["contact2"]?>" address1="<?php echo $TPL_V1["address1"]?>" address1_old="<?php echo $TPL_V1["address1_old"]?>" address2="<?php echo $TPL_V1["address2"]?>"><span class="op_title"><?php echo $TPL_V1["shipping_name"]?></span></li>
<?php }}?>
				</ul>
			</div>
			<div class="shipping_address">
				<h3>배송지 주소</h3>
				<label>
					<span>받으실 분</span>
					<input type="text" name="shipping_from" placeholder="받으실 분"/>
				</label>
				<label>
					<span>우편번호</span>
					<input type="text" name="post_code" placeholder="우편번호 찾기를 눌러주세요"/><button class="search_post" onclick="postPop()"></button>
				</label>
				<label>
					<span>주소</span>
					<input type="text" name="address1" placeholder="도로명 주소" readonly />
					<input type="text" name="address1_old" placeholder="지번 주소" readonly/>
				</label>
				<label>
					<span>주소 상세</span>
					<input type="text" name="address2" placeholder="상세 주소"/>
				</label>
				<label>
					<span>연락처1</span>
					<input type="text" name="contact1" placeholder="필수 연락처"/><br/>
				</label>
				<label>
					<span>연락처2</span>
					<input type="text" name="contact2" placeholder="보조 연락처"/><br/>
				</label>
				<label>
					<span>배송 메모</span>
					<input type="text" name="shipping_memo"/>
				</label>
			</div>
		</div>
		
		<div class="pay_method">
			<h3>결제 정보</h3>
			<div class="bank_info">
				<p><b>은행명</b>우리은행</p>
				<p><b>계좌번호</b>123456-123456-123456</p>
				<p><b>예금주</b>갤러리 오채</p>
				<p><b>입금주</b><input type="text" name="send_name"/></p>
				<p>▷ 1주일이 지나면 주문내역은 자동으로 취소됩니다.</p>
				<p>▷ 입금주가 다를시 확인이 어려우니 정확히 기입해주세요.</p>
				<p>▷ 입금 확인은 AM 10:00 ~ PM 6:00 매시 정각에 합니다</p>
			</div>
		</div>
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
</article>