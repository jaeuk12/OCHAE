<?php /* Template_ 2.2.3 2016/08/21 18:16:13 D:\Work\OCHAE\www\_template\content\admin\board\notice_edit.html */?>
<script>
	$(document).ready(function(){
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		$("#board_idx").val("<?php echo $TPL_VAR["row"]["idx"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
		$("#board_title").val("<?php echo $TPL_VAR["row"]["title"]?>");
		$("#board_view_<?php if($TPL_VAR["row"]["view_yn"]=='Y'){?>Y<?php }else{?>N<?php }?>").attr("checked", true);
<?php }else{?>
		$("#board_view_Y").attr("checked", true);
<?php }?>
		
		CKEDITOR.replace("board_content").on('instanceReady', function()
		{
			var writer = this.dataProcessor.writer;
			writer.indentationChars = '';
			writer.lineBreakChars = '';
		});
	});
	
	function checkForm(){
		document.boardForm.submit();
	}
	
	function cencel(){
		document.boardForm.reset();
		history.back();
	}
	
	function deleteIdx(){
		if(confirm("삭제하시겠습니까?")){
			document.location.href = "/admin/board/delete?board_idx=<?php echo $TPL_VAR["row"]["idx"]?>";
		}
	}
		
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>게시판관리</a></li>
  <li class="unavailable"><a>공지사항</a></li>
  <li class="current"><a>공지사항등록</a></li>
</ul>

<form name="boardForm" action="/admin/board/noticeprocess" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="board_idx" id="board_idx"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	
	<label for="board_title">제목
		<input type="text" name="board_title" id="board_title"/>
		<span class="label_info"></span>
	</label>
	
	<label for="board_content">내용
		<textarea name="board_content" id="board_content"><?php echo $TPL_VAR["row"]["content"]?></textarea>
	</label>
	
	<label for="board_attach">첨부파일
		<input type="file" name="board_attach" id="board_attach"/>
<?php if($TPL_VAR["row"]["attach_path"]!=""){?><a href="<?php echo $TPL_VAR["row"]["attach_path"]?>"><?php echo $TPL_VAR["row"]["attach_name"]?></a><?php }?>
	</label>
	
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>표시여부</label>
	     	<input type="radio" name="board_view" id="board_view_Y" value="Y"/><label for="board_view_Y">표시</label>
			<input type="radio" name="board_view" id="board_view_N" value="N"/><label for="board_view_N">숨김</label>
		</div>
	</div>
	
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns" onclick="deleteIdx();">삭제</button><?php }?>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>