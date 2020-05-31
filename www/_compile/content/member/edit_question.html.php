<?php /* Template_ 2.2.3 2016/08/21 14:41:43 D:\Work\OCHAE\www\_template\content\member\edit_question.html */?>
<script>
	function submitQuestion(){
		document.questionFrom.submit();
	}
</script>

<form name="questionFrom" action="/member/editQuestion" method="post" onsubmit="return false;">
	<input type="hidden" name="idx" value="<?php echo $TPL_VAR["row"]["idx"]?>"/>
	<div class="edit_address">
		<div class="my_account">
			<div class="question_content">
				<h3>EDIT QUESTION</h3>
				<div class="form">
					<label>QUESTION TITLE
						<input type="text" name="title" value="<?php echo $TPL_VAR["row"]["title"]?>"/>
					</label>
					<label>QUESTION CONTENT
						<textarea name="content"><?php echo $TPL_VAR["row"]["content"]?></textarea>
					</label>
					<button onclick="submitQuestion()">SAVE</button><?php if($TPL_VAR["row"]["idx"]!=""){?><button class="delete" onclick="document.location='/member/deleteQuestion?idx=<?php echo $TPL_VAR["row"]["idx"]?>';">DELETE</button><?php }?>
				</div>
			</div>
		</div>
	</div>
</form>