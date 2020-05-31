<?php /* Template_ 2.2.3 2016/07/27 10:09:15 D:\Work\OCHAE\www\_template\content\admin\gallery\list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
	
</style>

<script>
    $(document).ready(function(){
    	$("#gallery_list tr").click(function(){
    		var idx = $(this).attr("idx");
    		addGallery(idx);
    	});
    	
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
    	
    });
    
    function addGallery(idx){
    	if(typeof idx == "undefined"){
			idx = "";
		}
		
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		if(idx == ""){
			document.location.href = "/admin/gallery/add?"+retUrl;
		}
		else{
			document.location.href = "/admin/gallery/add?"+retUrl+"&idx="+idx;
		}
    }

</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>게시판관리</a></li>
  <li class="current"><a>갤러리</a></li>
</ul>

<button type="button" class="tiny" onclick="addGallery();">갤러리 등록</button>
<table>
	<thead>
		<tr>
			<th>No.</th>
			<th>작품명</th>
			<th>작가명</th>
			<th>표시 여부</th>
			<th>변경일</th>
			<th>등록일</th>
		</tr>
	</thead>
	<tbody id="gallery_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
		<tr idx="<?php echo $TPL_V1["idx"]?>">
			<td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td><?php echo $TPL_V1["author_name"]?></td>
			<td><?php echo $TPL_V1["display_yn"]?></td>
			<td><?php echo $TPL_V1["modify_date"]?></td>
			<td><?php echo $TPL_V1["reg_date"]?></td>
		</tr>
<?php }}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="9" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
		</tr>
		<tr>
			<td colspan="9" align="center">
				<form name="searchForm" id="searchForm" action="/admin/gallery/list" method="get">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select name="search_type" id="search_type">
								<option value="title">작품명</option>
								<option value="name">저자명</option>
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