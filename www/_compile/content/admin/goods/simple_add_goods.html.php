<?php /* Template_ 2.2.3 2016/05/31 13:16:32 /home/hosting_users/ochae/www/_template/content/admin/goods/simple_add_goods.html */?>
<script>
	var isProcess = false;
	var page = 1;
	var totalPage = 1;

	$(document).ready(function(){
	});
	
	function checkForm(){
		document.goodsForm.submit();
	}
</script>

<form name="goodsForm" id="goodsForm" action="/admin/goods/simpleProcess" method="post">
	<label for="goods_title">상품명
		<input type="text" name="goods_title" id="goods_title"/>
		<span class="label_info"></span>
	</label>
	<label for="goods_pcode">업체 상품코드
		<input type="text" name="goods_pcode" id="goods_pcode"/>
		<span class="label_info"></span>
	</label>
	<label for="goods_cost_price">상품 정가
		<input type="text" name="goods_cost_price" id="goods_cost_price"/>
		<span class="label_info"></span>
	</label>
	<label for="goods_sale_price">상품 판매가
		<input type="text" name="goods_sale_price" id="goods_sale_price"/>
		<span class="label_info"></span>
	</label>
	<label for="goods_stock">재고 수량
		<input type="text" name="goods_stock" id="goods_stock"/>
		<span class="label_info"></span>
	</label>
<?php if($TPL_VAR["artist_idx"]==""){?>
	<label for="goods_artist_name">작가명
		<input type="text" name="goods_artist_name" id="goods_artist_name"/>
		<span class="label_info"></span>
	</label>
<?php }?>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>공급가 부가세</label>
	     	<input type="radio" name="goods_tax" id="goods_tax_Y" value="Y"/><label for="goods_tax_Y">별도</label>
			<input type="radio" name="goods_tax" id="goods_tax_N" value="N" checked/><label for="goods_tax_N">포함</label>
		</div>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();">등록</button>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>