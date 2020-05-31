<?php /* Template_ 2.2.3 2016/07/27 10:09:16 D:\Work\JeongWeb\www\_template\content\admin\goods_in\in_stock_edit.html */?>
<script>
	$(document).ready(function(){
		var btnSelectArtist = $("#btn_select_artist");
		var btnAddGoods = $("#btn_add_goods");
		var btnSelectGoods = $("#btn_select_goods");
		
		$("#in_stock_reg_day").datepicker({"dateFormat":"yy-mm-dd"});
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
			$("#idx").val(<?php echo $TPL_VAR["row"]["idx"]?>);
			$("#artist_idx").val(<?php echo $TPL_VAR["row"]["artist_idx"]?>);
			$("#in_stock_oc_code").val("<?php echo $TPL_VAR["row"]["oc_code"]?>");
			$("#in_stock_reg_day").val("<?php echo $TPL_VAR["row"]["reg_day"]?>");
			$("#in_stock_title").val("<?php echo $TPL_VAR["row"]["title"]?>");
			$("#in_stock_qty").val(<?php echo $TPL_VAR["row"]["qty"]?>);
			$("#in_stock_cost_price").val(<?php echo $TPL_VAR["row"]["cost_price"]?>);
			$("#in_stock_sale_price").val(<?php echo $TPL_VAR["row"]["sale_price"]?>);
			$("#in_stock_tax_<?php echo $TPL_VAR["row"]["tax_yn"]?>").attr("checked", true);
<?php }elseif($TPL_VAR["artist"]!=""){?>
			popSearchArtistResult(<?php echo $TPL_VAR["artist"]["idx"]?>, "<?php echo $TPL_VAR["artist"]["name"]?>");
<?php }else{?>
			btnSelectArtist.css("display", "inline-block");
			btnSelectArtist.on("click", function(){popSearchArtist();});
<?php }?>
	});

	function checkForm(){
		document.inStockForm.submit();
	}
	
	function cencel(){
		document.inStockForm.reset();
		history.back();
	}
	
	/**
	 * 팝업 모달 닫기 
	 */
	function popClose(){
		$("#popModal").foundation('reveal', 'close').attr("src", "");
	}
	
	/**
	 * 팝업 - 작가 선택 
	 */
	function popSearchArtist(){
		var iframeSearchArtist = $("#popModal");
		iframeSearchArtist.attr("src", "/admin/artist/popSearchArtist");
	}
	
	/**
	 * 팝업 - 심플 상품추가 
	 */
	function popAddGoods(artistIdx, artistName){
		var iframeAddGoods = $("#popModal");
		iframeAddGoods.attr("src", "/admin/goods/simpleAdd?artist_idx="+artistIdx+"&artist_name="+artistName);
	}
	
	/**
	 * 팝업 - 상품 선택 
	 */
	function popSearchGoods(artistIdx){
		var iframeSearchGoods = $("#popModal");
		iframeSearchGoods.attr("src", "/admin/goods/popSearchGoods?artist_idx="+artistIdx);
	}
	
	/**
	 * 팝업 리턴 - 작가선택 리턴값 
	 */
	function popSearchArtistResult(artistIdx, artistName){
		var btnSelectArtist = $("#btn_select_artist");
		var btnAddGoods = $("#btn_add_goods");
		var btnSelectGoods = $("#btn_select_goods");
		
		$("#artist_idx").val(artistIdx);
		btnSelectArtist.html(artistName).css("display", "inline-block");
		btnAddGoods.on("click", function(){popAddGoods(artistIdx, artistName);}).css("display", "inline-block");
		btnSelectGoods.on("click", function(){popSearchGoods(artistIdx);}).css("display", "inline-block");
		
		popClose();
	}
	
	/**
	 * 팝업 리턴 - 심플 상품추가 리턴값 
	 */
	function popAddGoodsResult(rs, ocCode, title, qty, costPrice, salePrice, taxYn){
		if(rs){
			$("#in_stock_oc_code").val(ocCode);
			$("#in_stock_title").val(title);
			$("#in_stock_qty").val(qty);
			$("#in_stock_cost_price").val(costPrice);
			$("#in_stock_sale_price").val(salePrice);
			$("#in_stock_tax_"+taxYn).attr("checked", true);
		}
		
		popClose();
	}
	
	/**
	 * 팝업 리턴 - 상품 선택 리턴값
	 */
	function popSelectGoodsResult(ocCode, title, qty, costPrice, salePrice, taxYn){
		$("#in_stock_oc_code").val(ocCode);
		$("#in_stock_title").val(title);
		$("#in_stock_qty").val(qty);
		$("#in_stock_cost_price").val(costPrice);
		$("#in_stock_sale_price").val(salePrice);
		$("#in_stock_tax_"+taxYn).attr("checked", true);
		
		popClose();
	}
	
	/**
	 * 입고내역 삭제 
	 */
	function inStockDelete(){
		if(confirm("삭제하시겠습니까?")){
			document.location = "/admin/goods/inStockDelete?idx=<?php echo $TPL_VAR["row"]["idx"]?>";
		}
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>입고관리</a></li>
  <li class="current"><a>입고<?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></a></li>
</ul>

<!-- modal -->
<iframe id="popModal" class="reveal-modal" data-reveal width="100%" height="75%">
</iframe><!-- /.modal -->

<button type="button" data-reveal-id="popModal" class="tiny warning" id="btn_select_artist" style="display:none;">입고 작가 선택</button><br/>
<button type="button" data-reveal-id="popModal" class="tiny" id="btn_select_goods" style="display:none;">기존상품 입고</button>
<button type="button" data-reveal-id="popModal" class="tiny success" id="btn_add_goods" style="display:none;">신규상품 입고</button>

<form name="inStockForm" id="inStockForm" action="/admin/goods/inStockProcess" method="post">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="idx" id="idx"/>
	<input type="hidden" name="artist_idx" id="artist_idx"/>
	<input type='hidden' name='in_stock_oc_code' id='in_stock_oc_code'/>
	
	<label for="in_stock_reg_day">입고일
		<input type="text" name="in_stock_reg_day" id="in_stock_reg_day"/>
		<span class="label_info"></span>
	</label>
	<label for="in_stock_title">상품명
		<input type="text" name="in_stock_title" id="in_stock_title"/>
		<span class="label_info"></span>
	</label>
	<label for="in_stock_qty">수량
		<input type="text" name="in_stock_qty" id="in_stock_qty"/>
		<span class="label_info"></span>
	</label>
	<label for="in_stock_cost_price">단가
		<input type="text" name="in_stock_cost_price" id="in_stock_cost_price"/>
		<span class="label_info"></span>
	</label>
	<label for="in_stock_sale_price">판매가
		<input type="text" name="in_stock_sale_price" id="in_stock_sale_price"/>
		<span class="label_info"></span>
	</label>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>상품가격을 입고가격으로 변경</label>
	     	<input type="checkbox" name="in_stock_apply" id="in_stock_apply" value="Y"/><label for="in_stock_apply">변경하기</label>
		</div>
	</div>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>공급가 부가세</label>
	     	<input type="radio" name="in_stock_tax" id="in_stock_tax_Y" value="Y"/><label for="in_stock_tax_Y">별도</label>
			<input type="radio" name="in_stock_tax" id="in_stock_tax_N" value="N"/><label for="in_stock_tax_N">포함</label>
		</div>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns" onclick="inStockDelete();">삭제</button><?php }?>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>