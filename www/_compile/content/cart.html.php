<?php /* Template_ 2.2.3 2016/08/13 19:02:26 D:\Work\JeongWeb\www\_template\content\cart.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		
	});

	function removeGoods(ocCode){
		if(confirm("카트에서 삭제하시겠습니까?")){
			if(!isProcess){
				isProcess = true;
				$.ajax({
					type: 'POST',
					url: '/cart/removeCartAjax',
					data: {"oc_code" : ocCode},
					async: true,
					dataType: 'json',
					success: function(data) {
						isProcess = false;
						
						alert(data.message);
						if(data.code == 100){
							location.reload();
						}
					},
					error: function(xhr, status) {
						//alert('[' + status + ']\n\n' + xhr.responseText);
						isProcess = false;
					}
				});
			}
		}
	}
</script>

<article class="default_contents">
	<section class="cart_content">
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
							<h4><a href="javascript:detail('<?php echo $TPL_V1["oc_code"]?>');"><?php echo $TPL_V1["title"]?></a></h4>
							<div class="option">
								<ul>
<?php if(is_array($TPL_R2=$TPL_V1["list"])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
									<li><?php echo $TPL_V2["option_name"]?><?php if($TPL_V2["option_price"]!=0){?><div><span class="price"><?php echo number_format($TPL_V2["option_price"])?></span><?php }?><span class="qty"><?php echo $TPL_V2["qty"]?></span></div></li>
<?php }}?>
								</ul>
							</div>
							<div class="btn_group">
								<button onclick="removeGoods('<?php echo $TPL_V1["oc_code"]?>')">REMOVE</button>
								<button onclick="">EDIT</button>
							</div>
						</td>
						<td>
							<?php echo $TPL_V1["total_qty"]?>

						</td>
						<td>
							<?php echo $TPL_V1["sale_price"]?>

<?php if($TPL_V1["discount_yn"]=="Y"){?><?php echo $TPL_V1["discount_price"]?><?php }?>
						</td>
						<td>
							999,999
						</td>
					</tr>
<?php }}?>
				</tbody>
			</table>
		</div>
	</section>
</article>