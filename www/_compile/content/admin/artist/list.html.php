<?php /* Template_ 2.2.3 2016/08/04 15:03:52 D:\Work\OCHAE\www\_template\content\admin\artist\list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
    $(document).ready(function(){
    	$("#artist_list tr").click(function(){
    		var idx = $(this).attr("idx");
    		if(idx != "" && typeof idx != "undefined"){
    			addArtist(idx);
    		}
    	});
    	
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
    	
    });

	function addArtist(idx){
		if(typeof idx == "undefined"){
			idx = "";
		}
		
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		if(idx == ""){
			document.location.href = "/admin/artist/add?"+retUrl;
		}
		else{
			document.location.href = "/admin/artist/add?"+retUrl+"&idx="+idx;
		}
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a href="#">작가관리</a></li>
  <li class="current"><a href="#">작가목록</a></li>
</ul>

<button type="button" class="tiny" onclick="addArtist();">작가추가</button>
<table>
	<thead>
		<tr>
			<th>No.</th>
			<th>작가명</th>
			<th>등록일</th>
		</tr>
	</thead>
	<tbody id="artist_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
		<tr class="<?php echo $TPL_V1["delete_yn"]?>" idx="<?php echo $TPL_V1["idx"]?>">
			<td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
			<td><?php echo $TPL_V1["name"]?></td>
			<td><?php echo $TPL_V1["reg_date"]?></td>
		</tr>
<?php }}else{?>
		<tr>
			<td colspan="3" style="width:100%;">결과가 없습니다</td>
		</tr>
<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<form name="searchForm" id="searchForm" action="/admin/artist/list" method="get">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select name="search_type" id="search_type">
								<option value="name">저자명</option>
								<option value="id">아이디</option>
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