<?php /* Template_ 2.2.3 2016/08/22 14:46:38 D:\Work\OCHAE\www\_template\content\admin\board\question_edit.html */?>
<style>
	input[type='text'][readonly], textarea[readonly] {background-color: #fff;}
</style>

<script>
	$(document).ready(function(){
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
  <li class="unavailable"><a>고객문의</a></li>
  <li class="current"><a>답변등록</a></li>
</ul>

<form name="boardForm" action="/admin/board/answerprocess" method="post">
	<input type="hidden" name="board_idx" id="board_idx" value="<?php echo $TPL_VAR["row"]["idx"]?>"/>
	<input type="hidden" name="ret_url" id="ret_url" value="<?php echo $TPL_VAR["ret_url"]?>"/>
	
	<label>제목
		<input type="text" value="<?php echo $TPL_VAR["row"]["title"]?>" readonly/>
		<span class="label_info"></span>
	</label>
	
	<label>내용
		<textarea rows="15" readonly><?php echo $TPL_VAR["row"]["content"]?></textarea>
	</label>
	<label for="board_answer">답변
		<textarea name="board_answer" id="board_answer" rows="15"><?php echo $TPL_VAR["row"]["answer"]?></textarea>
	</label>
	
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();">답변 등록</button>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>