<?php /* Template_ 2.2.3 2016/07/28 17:47:30 D:\Work\JeongWeb\www\_template\content\living\living_detail.html */?>
<script>
	$(document).ready(function(){
		$("#num_plus, #num_minus").click(function(){
			var t = $(this);
			var qty = $("#goods_qty").val();
			
			if(t.attr("id") == "num_minus"){
				if(qty < 2){
					qty = 1;
				}
				else{
					qty--;
				}
			}
			else{
				qty++;
			}
			
			$("#goods_qty").val(qty);
		});
		
		$(".content_image .thumb_list ul li").click(function(){
			var imgName = $(this).children("img").attr("src");
			var preview = $(".thumb_preview");
			
			var imgOld = preview.children("img");
			var imgNew = $("<image src="+imgName+"/>");
			
			imgOld.animate({opacity: 0}, 600, function() {
				imgOld.remove();
			});
			
			imgNew.css("opacity", 0);
			preview.append(imgNew);
			imgNew.animate({opacity: 1}, 600, function() {
			});
		});
	});
</script>

<article class="default_contents">
	<div class="detail_content under_line">
		<section class="content_image">
			<div class="thumb_list">
				<ul>
					<li><img src="/image/test1.jpg" /></li>
					<li><img src="/image/test2.jpg" /></li>
					<li><img src="/image/test3.jpg" /></li>
					<li><img src="/image/test4.jpg" /></li>
					<li><img src="/image/test1.jpg" /></li>
					<li><img src="/image/test2.jpg" /></li>
				</ul>
			</div>
			<div class="thumb_preview">
				<img src="/image/test4.jpg" />
			</div>
		</section>
		<section class="content_info">
			<div class="title">상품명</div>
			<div class="sale_price"><span>PRICE</span>￦ 999,999,999</div>
			<div class="discount_price"><span>DISCOUNT</span>￦ 999,999,999</div>
			<ul>
				<li><span>CODE</span>001001001</li>
				<li><span>DELIVERY</span>￦ 2,500</li>
				<li class="qty">
					<span>QTY</span>
					<input type="text" id="goods_qty" value="1" onkeydown="return numberCheck(event)"/><button id="num_plus">+</button><button id="num_minus">-</button>
				</li>
			</ul>
			<div class="button_group">
				<button class="btn_buy">BUY</button>
				<button class="btn_cart">CART</button>
			</div>
		</section>
	</div>
	
	<div class="detail_view under_line">
		<img src="/image/main/about_01.jpg"/>
		<img src="/image/main/about_02.jpg"/>
		<img src="/image/main/about_03.jpg"/>
	</div>
	
	<div class="others_list under_line">
		<h2>OTHERS</h2>
		<section class="default_list">
			<ul>
<?php if(is_array($TPL_R1=range(1,8))&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
				<li>
					<div class="list_content">
						<img src="/image/test4.jpg" onclick="detail('<?php echo $TPL_I1?>')" />
						<div class="info">
							<div class="bg"></div>
							
							<div class="description">
								<p class="title">상품명 Title 상품명 Product Goods 상품명</p>
								<p class="artist">아티스트 Artist</p>
								<p class="price">999,999,999</p>
							</div>
						</div>
					</div>
				</li>
<?php }}?>
			</ul>
		</section>
	</div>
</article>