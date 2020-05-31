<?php /* Template_ 2.2.3 2016/08/22 14:52:58 D:\Work\OCHAE\www\_template\content\admin\board\question_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
    $(document).ready(function(){
    	$("#board_list tr").click(function(){
    		var idx = $(this).attr("idx");
    		addAnswer(idx);
    	});
    	
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
    	
    });
    
    function addAnswer(idx){
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		document.location.href = "/admin/board/answer?"+retUrl+"&kind=<?php echo $TPL_VAR["board_kind"]?>&board_idx="+idx;
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
			<th>답변</th>
			<th>변경일</th>
			<th>등록일</th>
		</tr>
	</thead>
	<tbody id="board_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
		<tr class="<?php echo $TPL_V1["delete_yn"]?>" idx="<?php echo $TPL_V1["idx"]?>">
			<td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td class="<?php if($TPL_V1["answer"]==""){?>N<?php }else{?>Y<?php }?>"><?php if($TPL_V1["answer"]==""){?>미답변<?php }else{?>답변완료<?php }?></td>
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