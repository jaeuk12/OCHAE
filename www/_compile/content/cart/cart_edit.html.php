<?php /* Template_ 2.2.3 2016/08/24 14:39:10 D:\Work\OCHAE\www\_template\content\cart\cart_edit.html */
$TPL_cartlist_1=empty($TPL_VAR["cartlist"])||!is_array($TPL_VAR["cartlist"])?0:count($TPL_VAR["cartlist"]);?>
<script>
	var ocCode = "<?php echo $TPL_VAR["goods"]["oc_code"]?>";
	var salePrice = <?php if($TPL_VAR["goods"]!=""){?><?php if($TPL_VAR["goods"]["discount_yn"]=='Y'){?><?php echo $TPL_VAR["goods"]["discount_price"]?><?php }else{?><?php echo $TPL_VAR["goods"]["sale_price"]?><?php }?><?php }else{?>""<?php }?>;
	
	$(document).ready(function(){
		$("#num_plus, #num_minus").click(function(){
			var t = $(this);
			var input = t.parent().children("input[type=text]");
			var qty = input.val();
			
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
			
			input.val(qty);
			calculTotalPrice();
		});
		
		$(".goods .qty_box .del").click(function(){
			$(this).parent().parent().remove();
			calculTotalPrice();
		});
		
		calculTotalPrice();
	});
	
	function applyCart(){
		var fd = new FormData(document.editCartForm);
		
		if(!isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: "/cart/updateCartAjax",
				data: fd,
				async: true,
				dataType: 'json',
				processData: false,
	        	contentType: false,
				cache: false,
				success: function(data) {
					modalProgress.close();
					
					alert(data.message);
					cartEditResult(true);
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
	
	function calculTotalPrice(){
		var goods = $(".goods");
		var total = 0;
		
		if(goods.length>0){
			$.each(goods, function(k, v){
				var t = $(this);
				
				var optionPrice = Number(t.find(".qty_box").attr("option_price"));
				var qty = Number(t.find("input[name='option_qty[]']").val());
				
				total += (salePrice+optionPrice)*qty;
			});
		}
		else{
			var qty = Number($(".qty_box input[name='option_qty[]']").val());
			total = salePrice * qty;
		}

		$(".total_price b").html(number_format(isNaN(total)?0:total));
	}
</script>

<article class="default_contents">
	<div class="cart_edit">
		<h2>EDIT GOODS</h2>
		<div class="thumb"><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_VAR["goods"]["thumb"]?>"/></div>
		<div class="info">
			<h3><?php echo $TPL_VAR["goods"]["title"]?></h3>
			<div class="sale_price <?php echo $TPL_VAR["goods"]["discount_yn"]?>"><span>PRICE</span>￦ <?php echo $TPL_VAR["goods"]["sale_price"]?></div>
<?php if($TPL_VAR["goods"]["discount_yn"]=="Y"){?><div class="discount_price"><span>DISCOUNT</span>￦ <?php echo $TPL_VAR["goods"]["discount_price"]?></div><?php }?>
			<form name="editCartForm" onsubmit="return false;">
				<input type="hidden" name="oc_code" value="<?php echo $TPL_VAR["goods"]["oc_code"]?>"/>
				<ul>
<?php if($TPL_cartlist_1){foreach($TPL_VAR["cartlist"] as $TPL_V1){?>
<?php if($TPL_V1["option_idx"]!=0){?>
				<li class="goods">
					<span class="option_name"><?php echo $TPL_V1["option_name"]?></span>
					<div class="qty_box" option_price="<?php echo $TPL_V1["option_price"]?>">
						<span class="option_price"><?php echo $TPL_V1["option_price"]?></span>
						<input type="hidden" name="option_idx[]" value="<?php echo $TPL_V1["option_idx"]?>"/>
						<input type="text" name="option_qty[]" value="<?php echo $TPL_V1["qty"]?>" onkeydown="return numberCheck(event)"/><button id="num_plus">+</button><button id="num_minus">-</button><button class="del">X</button>
					</div>
				</li>
<?php }else{?>
				<li>
					<span>QTY</span>
					<div class="qty_box">
						<input type="text" name="option_qty" value="<?php echo $TPL_V1["qty"]?>" onkeydown="return numberCheck(event)"/><button id="num_plus">+</button><button id="num_minus">-</button>
					</div>
				</li>
<?php }?>
<?php }}?>
				</ul>
			</form>
			<div class="total_price"><span>TOTAL PRICE</span>￦ <b></b></div>
			<div class="button_group">
				<button class="btn_update" onclick="applyCart()"><span>UPDATE</span></button>
				<button onclick="cartEditResult(false);"><span>CANCEL</span></button>
			</div>
		</div>
	</div>
</article>