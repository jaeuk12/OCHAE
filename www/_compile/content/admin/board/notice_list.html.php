<?php /* Template_ 2.2.3 2016/08/04 15:30:02 D:\Work\OCHAE\www\_template\content\admin\board\notice_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
    $(document).ready(function(){
    	$("#goods_list tr").click(function(){
    		var idx = $(this).attr("oc_idx");
    		addNotice(idx);
    	});
    	
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
    	
    });
    
    function addNotice(idx){
    	if(typeof idx == "undefined"){
			idx = "";
		}
		
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		if(idx == ""){
			document.location.href = "/admin/board/add?"+retUrl+"&kind=notice";
		}
		else{
			document.location.href = "/admin/board/add?"+retUrl+"&kind=notice&board_idx="+idx;
		}
    }

</script>

<ul class="breadcrumbs">
	<li class="unavailable"><a>게시판관리</a></li>
	<li class="current"><a>공지사항</a></li>
</ul>

<button type="button" class="tiny" onclick="addNotice();">공지사항 등록</button>
<table>
	<thead>
		<tr>
			<th>No.</th>
			<th>제목</th>
			<th>조회수</th>
			<th>변경일</th>
			<th>등록일</th>
		</tr>
	</thead>
	<tbody id="goods_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
		<tr class="<?php echo $TPL_V1["delete_yn"]?>" oc_idx="<?php echo $TPL_V1["idx"]?>">
			<td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td><?php echo $TPL_V1["hit"]?></td>
			<td><?php echo $TPL_V1["modify_date"]?></td>
			<td><?php echo $TPL_V1["reg_date"]?></td>
		</tr>
<?php }}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
		</tr>
		<tr>
			<td colspan="8" align="center">
				<form name="searchForm" id="searchForm" action="/admin/board/list" method="get">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select name="search_type" id="search_type">
								<option value="title">제목</option>
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