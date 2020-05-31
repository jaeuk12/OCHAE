<?php /* Template_ 2.2.3 2016/08/29 11:58:14 D:\Work\OCHAE\www\_template\content\default_list\default_detail.html */
$TPL_images_1=empty($TPL_VAR["images"])||!is_array($TPL_VAR["images"])?0:count($TPL_VAR["images"]);
$TPL_options_1=empty($TPL_VAR["options"])||!is_array($TPL_VAR["options"])?0:count($TPL_VAR["options"]);?>
<script>
	var ocCode = "<?php echo $TPL_VAR["row"]["oc_code"]?>";
	var salePrice = <?php if($TPL_VAR["row"]!=""){?><?php if($TPL_VAR["row"]["discount_yn"]=='Y'){?><?php echo $TPL_VAR["row"]["discount_price"]?><?php }else{?><?php echo $TPL_VAR["row"]["sale_price"]?><?php }?><?php }else{?>""<?php }?>;
	
	$(document).ready(function(){
		$(".thumb_preview").height($(".thumb_preview").width());
		$(window).resize(function(){
			$(".thumb_preview").height($(".thumb_preview").width());
		});
		
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
			calculTotalPrice();
		});
		
		changePreview();
		othersMobileTouch();
		othersNumber();
		
		$(".button_group .btn_cart").click(function(){
			addCart(ocCode, $("#goods_qty").val(), $("#option_choose").val());
		});
		
<?php if($TPL_VAR["options"]!=""){?>
		$(".content_info .options ul li").click(function(){
			var t = $(this);
			var ul = t.parent();
			
			if(ul.hasClass("open")){
				ul.removeClass("open");
				$(".content_info .options ul li").removeClass("active");
				t.addClass("active");
				$("#option_choose").val(t.attr("idx"));
				calculTotalPrice();
			}
			else{
				ul.addClass("open");
			}
		});
		
		$(".content_info .options ul li:first-child").click().click();
<?php }?>
		
		calculTotalPrice();
	});
	
	function calculTotalPrice(){
		var optionPrice = Number($("li.options ul li[idx="+$("#option_choose").val()+"]").attr("price"));
		var qty = Number($("#goods_qty").val());
		
		if(isNaN(optionPrice)){
			optionPrice = 0;
		}
		
		$(".content_info .total_price b").html(number_format((salePrice+optionPrice) * qty));
	}
	
	function changePreview(){
		var thumbList = $(".content_image .thumb_list ul li");
		thumbList.click(function(){
			var imgName = $(this).children("img").attr("src");
			var preview = $(".thumb_preview");
			var imgOld = preview.children("img");
			var imgNew = $("<image src="+imgName.replace("_small", "_large")+"/>");
			
			thumbList.removeClass("active");
			$(this).addClass("active");
			
			imgNew.click(function(){
				imgModalPop.show(imgName.replace("_small", ""));
			});
			
			imgOld.animate({opacity: 0}, 600, function() {
				imgOld.remove();
			});
			
			imgNew.css("opacity", 0);
			preview.append(imgNew);
			imgNew.animate({opacity: 1}, 600);

			var scrollLeft = $(this).get(0).offsetLeft;
			var lw = $(this).width()/2;
			var bw = $("body").width()/2;
			var left = (scrollLeft-bw)+lw;
			
			$(".content_image .thumb_list").scrollLeft(left);
		});
		
		thumbList[0].click();
	}
	
	function othersMobileTouch(){
		$(".others_list .default_list").bind("touchstart", function(e){
			var sTouch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
			var sx = sTouch.pageX;
			
			$(this).bind("touchend", function(e){
				var eTouch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
				var ex = eTouch.pageX;
				
				var mx = sx - ex;
				
				if(mx > 50){
					othersMove(true);
				}
				else if(mx < -50){
					othersMove(false);
				}
				
				alert($(this).offsetLeft());
				
				$(this).unbind("touchend");
			});
		});
	}
	
	function othersNumber(){
		var navCircle = $(".others_list .default_list .others_number ul");
		for(var i=0; i<$(".others_list .default_list > ul").children().length; i++){
			navCircle.append("<li></li>");
		}
		
		navCircle.children(":first-child").addClass("active");
	}
	
	function othersMove(isNext){
		var obj = $(".others_list .default_list > ul li.active");
		var nextObj = "";
	
		if(!isAnimate && $(".default_list > ul li").length > 1){
			isAnimate = true;
			obj.animate({opacity:0}, 400, function(){
				isAnimate=false;
				$(this).removeClass("active");
			});
			
			if(isNext){
				nextObj = obj.next();
				if(nextObj.index() == -1){
					nextObj = obj.parent().children(":first-child");
				}
	
				nextObj.css({"left":nextObj.width(), "display":"inline-block", "opacity":1}).stop().animate({left:0}, 400, function(){$(this).addClass("active");});
			}
			else{
				nextObj = obj.prev();
				if(nextObj.index() == -1){
					nextObj = obj.parent().children(":last-child");
				}
				
				nextObj.css({"left":-nextObj.width(), "display":"inline-block", "opacity":1}).stop().animate({left:0}, 400, function(){$(this).addClass("active");});
			}
			
			var navCircle = $(".others_list .default_list .others_number ul li.active");
			navCircle.removeClass("active");
			
			if(isNext){
				if(navCircle.next().index() == -1){
					navCircle.parent().children(":first-child").addClass("active");
				}
				else{
					navCircle.next().addClass("active");
				}
			}
			else{
				if(navCircle.prev().index() == -1){
					navCircle.parent().children(":last-child").addClass("active");
				}
				else{
					navCircle.prev().addClass("active");
				}
			}
		}
	}
</script>

<article class="default_contents">
	<div class="detail_content under_line">
		<section class="content_image">
			<div class="thumb_preview">
				<img/>
			</div>
			<div class="thumb_list">
				<ul>
<?php if($TPL_images_1){$TPL_I1=-1;foreach($TPL_VAR["images"] as $TPL_V1){$TPL_I1++;?>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
					<li class="<?php if($TPL_I1==0){?>active<?php }?>"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_small<?php echo $TPL_V1["image_ext"]?>" /></li>
<?php }}?>
				</ul>
			</div>
		</section>
		<section class="content_info">
			<div class="title"><?php echo $TPL_VAR["row"]["title"]?></div>
			<div class="sale_price <?php echo $TPL_VAR["row"]["discount_yn"]?>"><span>PRICE</span>￦ <?php echo number_format($TPL_VAR["row"]["sale_price"])?></div>
<?php if($TPL_VAR["row"]["discount_yn"]=="Y"){?>
				<div class="discount_price"><span>DISCOUNT</span>￦ <?php echo number_format($TPL_VAR["row"]["discount_price"])?></div>			
<?php }?>
			<ul>
				<li><span>CODE</span><?php echo $TPL_VAR["row"]["oc_code"]?></li>
				<li><span>PRODUCT BY</span><?php echo $TPL_VAR["row"]["artist_name"]?></li>
				<li><span>SHIPPING PRICE</span>￦ <?php echo number_format($TPL_VAR["row"]["shipping_price"])?></li>
				<li class="qty">
					<span>QTY</span>
					<input type="text" id="goods_qty" value="1" onkeydown="return numberCheck(event)"/><button id="num_plus">+</button><button id="num_minus">-</button>
				</li>
<?php if($TPL_VAR["options"]!=""){?>
				<li class="options">
					<span>CHOOSE</span>
					<input type="hidden" name="option_choose" id="option_choose"/>
					<ul>
<?php if($TPL_options_1){foreach($TPL_VAR["options"] as $TPL_V1){?>
						<li idx="<?php echo $TPL_V1["idx"]?>" price="<?php echo $TPL_V1["option_price"]?>"><span class="op_title"><?php echo $TPL_V1["option_name"]?></span><?php if($TPL_V1["option_price"]!=0){?><span class="op_price">￦ <?php echo number_format($TPL_V1["option_price"])?></span><?php }?></li>
<?php }}?>
					</ul>
				</li>
<?php }?>
			</ul>
			<div class="total_price"><span>TOTAL PRICE</span>￦ <b></b></div>
			<div class="button_group">
				<button class="btn_cart"><span>CART</span></button>
			</div>
		</section>
	</div>
	
	<div class="detail_view<?php if($TPL_VAR["others"]!=""){?> under_line<?php }?>">
		<?php echo $TPL_VAR["row"]["content"]?>

	</div>
	
<?php if($TPL_VAR["others"]!=""){?>
	<div class="others_list">
		<h2>OTHERS</h2>
		<section class="default_list">
			<ul>
<?php if(is_array($TPL_R1=range(1,8))&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
				<li class="<?php if($TPL_I1==0){?>active<?php }?>">
					<div class="list_content">
						<img src="/image/test4.jpg" onclick="detail('<?php echo $TPL_I1?>')"/>
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
			
			<div class="others_number">
				<div class="content">
					<ul></ul>
				</div>
				<div class="oc_slider_arrow l2"></div>
				<div class="oc_slider_arrow r2"></div>
			</div>
		</section>
	</div>
<?php }?>
</article>