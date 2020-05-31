<?php /* Template_ 2.2.3 2016/05/30 22:18:23 /home/hosting_users/ochae/www/_template/content/admin/goods/in_stock_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
</style>

<script>
	function addStock(){
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		document.location = "/admin/goods/inStockDetail?"+retUrl;
	}
	
	function detail(artistIdx, regDay){
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		document.location = "/admin/goods/inStockDetail?"+retUrl+"&artist_idx="+artistIdx+"&reg_day="+regDay;
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>상품관리</a></li>
  <li class="current"><a>입고관리</a></li>
</ul>

<button type="button" class="tiny" onclick="addStock();">입고등록</button>
<table>
	<thead>
		<tr>
			<th>입고일</th>
			<th>작가명</th>
			<th>입고품목</th>
		</tr>
	</thead>
	<tbody id="goods_list">
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
		<tr onclick="detail('<?php echo $TPL_V1["artist_idx"]?>', '<?php echo $TPL_V1["reg_day"]?>')">
			<td><?php echo $TPL_V1["reg_day"]?></td>
			<td><?php echo $TPL_V1["artist_name"]?></td>
			<td><?php echo $TPL_V1["title"]?><?php if($TPL_V1["cnt"]>1){?> 외 <?php echo $TPL_V1["cnt"]-1?>개<?php }?></td>
		</tr>
<?php }}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<form name="searchForm" id="searchForm" action="/admin/goods/list" method="get">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select name="search_type" id="search_type">
								<option value="artist_name">작가명</option>
								<option value="goods_name">상품명</option>
							</select>
						</div>
						<div class="small-6 large-7 columns">
							<input type="text" name="search_keyword" id="search_keyword">
						</div>
						<button type="submit" class="tiny small-2 large-2 columns">검색</button>
					</div>
				</form>
			</td>
		</tr>
	</tfoot>
</table>