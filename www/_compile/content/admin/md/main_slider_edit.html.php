<?php /* Template_ 2.2.3 2016/07/27 10:09:17 D:\Work\JeongWeb\www\_template\content\admin\md\main_slider_edit.html */?>
<style>
</style>

<script>
	$(document).ready(function(){
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
			$("#idx").val("<?php echo $TPL_VAR["row"]["idx"]?>");
			$("#md_main_title").val("<?php echo $TPL_VAR["row"]["title"]?>");
			$("#md_main_link").val("<?php echo $TPL_VAR["row"]["link"]?>");
<?php }?>
		
		CKEDITOR.replace("md_main_content").on('instanceReady', function()
		{
			var writer = this.dataProcessor.writer;
			writer.indentationChars = '';
			writer.lineBreakChars = '';
		});
		
		$("#md_main_view_<?php if($TPL_VAR["row"]["view_yn"]=='N'){?>N<?php }else{?>Y<?php }?>").attr("checked", true);
	});
	
	function checkForm(){
		document.mdMainForm.submit();
	}
	
	function cencel(){
		document.mdMainForm.reset();
		history.back();
	}
	
	function deleteMainSlider(){
		document.location.href = "/admin/md/deleteMainSlider?idx=<?php echo $TPL_VAR["row"]["idx"]?>&gubun=main_slider";
	}
</script>

<ul class="breadcrumbs">
 	<li class="unavailable"><a>게시판관리</a></li>
 	<li class="current"><a>메인 등록</a></li>
</ul>

<form name="mdMainForm" id="mdMainForm" action="/admin/md/mainsliderprocess" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="idx" id="idx"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	
	<label for="md_main_bg">배경 이미지
		<input type="file" name="md_main_bg" id="md_main_bg"/>
		<span class="label_info"></span>
	</label>
	<label for="md_main_title">제목
		<input type="text" name="md_main_title" id="md_main_title"/>
		<span class="label_info"></span>
	</label>
	<label for="md_main_content">간략 설명
		<textarea type="text" name="md_main_content" id="md_main_content"><?php echo $TPL_VAR["row"]["content"]?></textarea>
	</label>
	<label for="md_main_link">링크
		<input type="text" name="md_main_link" id="md_main_link"/>
		<span class="label_info"></span>
	</label>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>표시여부</label>
	     	<input type="radio" name="md_main_view" id="md_main_view_Y" value="Y"/><label for="md_main_view_Y">표시</label>
			<input type="radio" name="md_main_view" id="md_main_view_N" value="N"/><label for="md_main_view_N">숨김</label>
		</div>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm()"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns" onclick="deleteMainSlider()">삭제</button><?php }?>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel()">취소</button>
	</div>
</form>