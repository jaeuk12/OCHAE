<?php /* Template_ 2.2.3 2016/07/27 10:09:16 D:\Work\JeongWeb\www\_template\content\admin\goods\pop_simple_add.html */?>
<script>
	var isProcess = false;
	var page = 1;
	var totalPage = 1;

	$(document).ready(function(){
		$("#goods_artist_idx").val("<?php echo $TPL_VAR["artist_idx"]?>");
		$("#goods_artist_name").val("<?php echo $TPL_VAR["artist_name"]?>");
	});
	
	function checkForm(){
		document.goodsForm.submit();
	}
</script>

<form name="goodsForm" id="goodsForm" action="/admin/goods/simpleProcess" method="post">
	<input type="hidden" name="goods_artist_idx" id="goods_artist_idx"/>
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
	<label for="goods_artist_name">작가명
		<input type="text" name="goods_artist_name" id="goods_artist_name"/>
		<span class="label_info"></span>
	</label>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>공급가 부가세</label>
	     	<input type="radio" name="goods_tax" id="goods_tax_Y" value="Y"/><label for="goods_tax_Y">별도</label>
			<input type="radio" name="goods_tax" id="goods_tax_N" value="N" checked/><label for="goods_tax_N">포함</label>
		</div>
	</div>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>사이트 표시여부</label>
	     	<input type="radio" name="goods_display" id="goods_display_Y" value="Y" checked/><label for="goods_display_Y">표시</label>
			<input type="radio" name="goods_display" id="goods_display_N" value="N"/><label for="goods_display_N">숨김</label>
		</div>
	</div>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>포스 표시여부</label>
	     	<input type="radio" name="goods_pos" id="goods_pos_Y" value="Y" checked/><label for="goods_pos_Y">표시</label>
			<input type="radio" name="goods_pos" id="goods_pos_N" value="N"/><label for="goods_pos_N">숨김</label>
		</div>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();">등록</button>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="parent.popClose();">취소</button>
	</div>
</form>