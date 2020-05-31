<?php /* Template_ 2.2.3 2016/06/05 17:17:31 /home/hosting_users/ochae/www/_template/content/admin/goods_in/in_stock_detail.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
.in_stock_thumb {width:37px; height:37px; border:#ccc 0.2px solid;}
.row label:nth-child(6), .row label:nth-child(7) {text-align: center;}
.row label input[type=checkbox] {margin:11px 0 0 0;}
button {margin:0;}

</style>

<script>
	$(document).ready(function(){
		$("#artist_idx").val(<?php echo $TPL_VAR["artist_idx"]?>);
		$("#reg_day").val("<?php echo $TPL_VAR["reg_day"]?>");
	});
	
	function inStockEdit(idx){
		document.location = "/admin/goods/inStockAdd?mode=modify&idx="+idx;
	}
	
	function inStockNew(){
		document.location = "/admin/goods/inStockAdd?mode=new&artist_idx=<?php echo $TPL_VAR["artist_idx"]?>";
	}
	
</script>

<ul class="breadcrumbs">
	<li class="unavailable"><a>상품관리</a></li>
	<li class="unavailable"><a>입고관리</a></li>
	<li class="current"><a>입고 등록/변경</a></li>
</ul>

<button type="button" class="tiny success" id="btn_add_goods" onclick="inStockNew();">신규 입고</button>

<h5><?php echo $TPL_VAR["artist"]["name"]?> _ <?php echo $TPL_VAR["reg_day"]?> 입고내역</h5>
<table>
	<thead>
		<tr>
			<th>입고일</th>
			<th>상품명</th>
			<th>수량</th>
			<th>단가</th>
			<th>판매가</th>
			<th>부가세</th>
		</tr>
	</thead>
	<tbody id="goods_list">
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
		<tr onclick="inStockEdit(<?php echo $TPL_V1["idx"]?>)">
			<td><?php echo $TPL_V1["reg_day"]?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td><?php echo $TPL_V1["qty"]?></td>
			<td><?php echo $TPL_V1["cost_price"]?></td>
			<td><?php echo $TPL_V1["sale_price"]?></td>
			<td><?php echo $TPL_V1["tax_yn"]?></td>
		</tr>
<?php }}?>
	</tbody>
</table>

<div class="row" style="margin-top:55px;">
	<button type="button" class="tiny secondary columns" onclick="history.back();">뒤로</button>
</div>