<?php /* Template_ 2.2.3 2016/07/27 10:09:17 D:\Work\JeongWeb\www\_template\content\admin\md\main_slider_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
</style>

<script>

	$(document).ready(function(){
		$("#main_slider_list tr").click(function(){
    		var idx = $(this).attr("idx");
    		addMainContent(idx);
    	});
    	
    	$( "#main_slider_list" ).sortable({stop: checkSort});
    	$( "#main_slider_list" ).disableSelection();
	});

	function addMainContent(idx){
		if(typeof idx == "undefined"){
			idx = "";
		}
		
		var retUrl = "ret_url="+encodeURIComponent(document.location);
		
		if(idx == ""){
			document.location.href = "/admin/md/addmainslider?"+retUrl;
		}
		else{
			document.location.href = "/admin/md/addmainslider?"+retUrl+"&idx="+idx;
		}
	}
	
	function checkSort(e, ui){
		var item = $(ui.item);
		var idx = item.attr("idx")
		var oNo = item.index()+1;
		
		if(item.attr("order_no") != oNo){
			document.location.href = "/admin/md/sort?idx="+idx+"&order_no="+oNo+"&gubun=<?php echo $TPL_VAR["gubun"]?>";
		}
	}
	
</script>

<ul class="breadcrumbs">
 	<li class="unavailable"><a>게시판관리</a></li>
 	<li class="current"><a>메인</a></li>
</ul>

<button type="button" class="tiny" onclick="addMainContent();">등록</button>

<table>
	<thead>
		<tr>
			<th>순번</th>
			<th>제목</th>
			<th>변경일</th>
			<th>등록일</th>
		</tr>
	</thead>
	<tbody id="main_slider_list">
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
		<tr idx="<?php echo $TPL_V1["idx"]?>" order_no="<?php echo $TPL_V1["order_no"]?>">
			<td><?php echo $TPL_V1["order_no"]?></td>
			<td><?php echo $TPL_V1["title"]?></td>
			<td><?php echo $TPL_V1["modify_date"]?></td>
			<td><?php echo $TPL_V1["reg_date"]?></td>
		</tr>
<?php }}?>
	</tbody>
</table>