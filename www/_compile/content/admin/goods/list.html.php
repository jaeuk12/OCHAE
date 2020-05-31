<?php /* Template_ 2.2.3 2016/08/04 14:46:00 D:\Work\OCHAE\www\_template\content\admin\goods\list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
	
</style>

<script>
    $(document).ready(function(){
    	$("#goods_list tr").click(function(){
    		var ocCode = $(this).attr("oc_code");
    		addGoods(ocCode);
    	});
    	
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
    	
    });
    
    function addGoods(ocCode){
    	if(typeof ocCode == "undefined"){
			ocCode = "";
		}
		
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		if(ocCode == ""){
			document.location.href = "/admin/goods/add?"+retUrl;
		}
		else{
			document.location.href = "/admin/goods/add?"+retUrl+"&oc_code="+ocCode;
		}
    }
    
    function goodsMD(event, ocCode){
    	offEventBubble(event);
    	
    	var retUrl = "ret_url="+encodeURIComponent(document.location);
    	
    	document.location.href = "/admin/goods/md?"+retUrl+"&oc_code="+ocCode;
    }

</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>상품관리</a></li>
  <li class="current"><a>상품목록</a></li>
</ul>

<button type="button" class="tiny" onclick="addGoods();">상품등록</button>
<table>
	<thead>
		<tr>
			<th>No.</th>
			<th>오채코드</th>
			<th>상품명</th>
			<th>작가명</th>
			<th>표시 여부</th>
			<th>변경일</th>
			<th>등록일</th>
			<th>MD</th>
		</tr>
	</thead>
	<tbody class="goods_list" id="goods_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
		<tr class="<?php echo $TPL_V1["delete_yn"]?>" oc_code="<?php echo $TPL_V1["oc_code"]?>">
			<td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
			<td><?php echo $TPL_V1["oc_code"]?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td><?php echo $TPL_V1["author_name"]?></td>
			<td><?php echo $TPL_V1["display_yn"]?></td>
			<td><?php echo $TPL_V1["modify_date"]?></td>
			<td><?php echo $TPL_V1["reg_date"]?></td>
			<td><button type="button" class="tiny" onclick="goodsMD(event, '<?php echo $TPL_V1["oc_code"]?>');">MD</button></td>
		</tr>
<?php }}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
		</tr>
		<tr>
			<td colspan="8" align="center">
				<form name="searchForm" id="searchForm" action="/admin/goods/list" method="get">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select name="search_type" id="search_type">
								<option value="title">상품명</option>
								<option value="pcode">업체 상품코드</option>
								<option value="author_name">저자명</option>
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