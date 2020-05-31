<?php /* Template_ 2.2.3 2016/06/03 22:21:12 /home/hosting_users/ochae/www/_template/content/admin/goods/in_stock_detail.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
.in_stock_thumb {width:37px; height:37px; border:#ccc 0.2px solid;}
.row label:nth-child(6), .row label:nth-child(7) {text-align: center;}
.row label input[type=checkbox] {margin:11px 0 0 0;}

</style>

<script>
	$(document).ready(function(){
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		$("input[name='in_stock_reg_day[]']").datepicker({"dateFormat":"yy-mm-dd"});
		
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		
<?php if($TPL_VAR["mode"]=="modify"){?>
			$("#artist_idx").val(<?php echo $TPL_VAR["artist_idx"]?>);
			$("#reg_day").val("<?php echo $TPL_VAR["reg_day"]?>");
<?php }?>
	});
	
	/**
	 * 입고등록 폼 체크 
	 */
	function checkForm(){
		document.inStockForm.submit();
	}
	
	/**
	 * 취소 
	 */
	function cencel(){
		history.back();
	}
	
	/**
	 * 팝업 - 심플 상품추가 
	 */
	function popAddGoods(artistIdx, artistName){
		var iframeAddGoods = $("#popModal");
		iframeAddGoods.attr("src", "/admin/goods/simpleAdd?artist_idx="+artistIdx+"&artist_name="+artistName);
	}
	
	/**
	 * 팝업 - 작가 선택 
	 */
	function popSearchArtist(){
		var iframeSearchArtist = $("#popModal");
		iframeSearchArtist.attr("src", "/admin/artist/popSearchArtist");
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
		btnSelectArtist.html(artistName);
		btnAddGoods.on("click", function(){popAddGoods(artistIdx, artistName);}).css("display", "inline-block");
		btnSelectGoods.on("click", function(){popSearchGoods(artistIdx);}).css("display", "inline-block");
		
		popClose();
	}
	
	/**
	 * 팝업 리턴 - 심플 상품추가 리턴값 
	 */
	function popAddGoodsResult(rs, ocCode, title, qty, costPrice, salePrice, taxYn){
		if(rs){
			var inStockForm = $("#inStockForm");
			var l = makeLine(ocCode, title, qty, costPrice, salePrice, taxYn)
			inStockForm.append(l);
		}
		
		popClose();
	}
	
	/**
	 * 팝업 리턴 - 상품 선택 리턴값
	 */
	function popSelectGoodsResult(ocCode, title, qty, costPrice, salePrice, taxYn){
		if(!checkGoods(ocCode)){
			var line = makeLine(ocCode, title, qty, costPrice, salePrice, taxYn);
			$("#inStockForm").append(line);
			$("#inStockForm div:last-child").find("input[name='in_stock_reg_day[]']").datepicker({"dateFormat":"yy-mm-dd"});
			popClose();
		}
	}
	
	/**
	 * 입고라인 html 만들기 
	 */
	function makeLine(ocCode, title, qty, costPrice, salePrice, taxYn){
		return "<div class='row'><input type='hidden' name='in_stock_oc_code[]' value='"+ocCode+"'/><label class='small-2 large-2 columns'><input type='text' name='in_stock_reg_day[]' value=''/></label><label class='small-3 large-3 columns'><input type='text' name='in_stock_title[]' value='"+title+"'/></label></label><label class='small-1 large-1 columns'><input type='text' name='in_stock_qty[]' value='"+qty+"'/></label><label class='small-2 large-2 columns'><input type='text' name='in_stock_cost_price[]' value='"+costPrice+"'/></label><label class='small-2 large-2 columns'><input type='text' name='in_stock_sale_price[]' value='"+salePrice+"'/></label><label class='small-1 large-1 columns'><input type='checkbox' name='in_stock_tax[]' value='"+ocCode+"' "+(taxYn?"checked":"")+"/></label><label class='small-1 large-1 columns'><button type='button' class='tiny' onclick='delStockLine();'>X</button></label></div>";
	}
	
	/**
	 * 입고라인 삭제 
	 */
	function delStockLine(){
		
	}
	
	/**
	 * 당일 입고상품 중복체크 
	 */
	function checkGoods(ocCode){
		var arryInput = $("input[name='in_stock_oc_code[]']");
		var ocCodes = new Array();
		
		$.each(arryInput, function(k, v){
			ocCodes.push(v.value);
		});

		if($.inArray(ocCode, ocCodes) != -1){
			alert("중복된 입고상품입니다.");
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * 팝업 모달 닫기 
	 */
	function popClose(){
		$("#popModal").foundation('reveal', 'close').attr("src", "");
	}
	
</script>

<ul class="breadcrumbs">
	<li class="unavailable"><a>상품관리</a></li>
	<li class="unavailable"><a>입고관리</a></li>
	<li class="current"><a>입고 등록/변경</a></li>
</ul>

<!-- modal -->
<iframe id="popModal" class="reveal-modal" data-reveal width="100%" height="75%">
</iframe><!-- /.modal -->

<button type="button" data-reveal-id="popModal" class="tiny warning" id="btn_select_artist" onclick="popSearchArtist();">입고 작가 선택</button><br/>
<button type="button" data-reveal-id="popModal" class="tiny" id="btn_select_goods" style="display:none;">기존상품 입고</button>
<button type="button" data-reveal-id="popModal" class="tiny success" id="btn_add_goods" style="display:none;">신규상품 입고</button>

<form name="inStockForm" id="inStockForm" action="/admin/goods/inStockProcess" method="post">
	<input type="hidden" name="ret_url" id="ret_url"/>
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="artist_idx" id="artist_idx"/>
<?php if($TPL_VAR["mode"]=="modify"){?>
	<input type="hidden" name="reg_day" id="reg_day"/>
<?php }?>
	
	<div class="row" style="margin-bottom:15px;">
		<label class="small-2 large-2 columns">입고일</label>
		<label class="small-3 large-3 columns">상품명</label>
		<label class="small-1 large-1 columns">수량</label>
		<label class="small-2 large-2 columns">단가</label>
		<label class="small-2 large-2 columns">판매가</label>
		<label class="small-1 large-1 columns">부가세</label>
		<label class="small-1 large-1 columns">삭제</label>
	</div>
	
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>	
	<div class='row'>
		<input type='hidden' name='in_stock_oc_code[]' value='<?php echo $TPL_V1["oc_code"]?>'/>
		
		<label class='small-2 large-2 columns'>
			<input type='text' name='in_stock_reg_day[]' value='<?php echo $TPL_V1["reg_day"]?>'/>
		</label>
		<label class='small-3 large-3 columns'>
			<input type='text' name='in_stock_title[]' value='<?php echo $TPL_V1["title"]?>'/>
		</label>
		<label class='small-1 large-1 columns'>
			<input type='text' name='in_stock_qty[]' value='<?php echo $TPL_V1["qty"]?>'/>
		</label>
		<label class='small-2 large-2 columns'>
			<input type='text' name='in_stock_cost_price[]' value='<?php echo $TPL_V1["cost_price"]?>'/>
		</label>
		<label class='small-2 large-2 columns'>
			<input type='text' name='in_stock_sale_price[]' value='<?php echo $TPL_V1["sale_price"]?>'/>
		</label>
		<label class='small-1 large-1 columns'>
			<input type='checkbox' name='in_stock_tax[]' value='<?php echo $TPL_V1["oc_code"]?>' <?php if($TPL_V1["tax_yn"]=="Y"){?>checked<?php }?> />
		</label>
		<label class='small-1 large-1 columns'>
			<button type='button' class='tiny' onclick='delStockLine();'>X</button>
		</label>
	</div>
<?php }}?>
</form>

<div class="row" style="margin-top:55px;">
	<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();">등록</button>
	<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
</div>