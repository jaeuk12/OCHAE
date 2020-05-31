<?php /* Template_ 2.2.3 2016/07/27 10:09:14 D:\Work\OCHAE\www\_template\content\admin\artist\edit.html */
$TPL_kind_rows_1=empty($TPL_VAR["kind_rows"])||!is_array($TPL_VAR["kind_rows"])?0:count($TPL_VAR["kind_rows"]);
$TPL_bank_rows_1=empty($TPL_VAR["bank_rows"])||!is_array($TPL_VAR["bank_rows"])?0:count($TPL_VAR["bank_rows"]);?>
<script>
	$(document).ready(function(){
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
		
		$("#idx").val("<?php echo $TPL_VAR["row"]["idx"]?>");
		$("#artist_name").val("<?php echo $TPL_VAR["row"]["name"]?>");
		$("#artist_kind").val("<?php echo $TPL_VAR["row"]["kind"]?>");
		$("#artist_contact").val("<?php echo $TPL_VAR["row"]["contact"]?>");
		$("#artist_address1").val("<?php echo $TPL_VAR["row"]["address1"]?>");
		$("#artist_address2").val("<?php echo $TPL_VAR["row"]["address2"]?>");
		$("#artist_name_account").val("<?php echo $TPL_VAR["row"]["name_account"]?>");
		$("#artist_bank").val("<?php echo $TPL_VAR["row"]["bank"]?>");
		$("#artist_account_number").val("<?php echo $TPL_VAR["row"]["account_number"]?>");
<?php }?>
		
		CKEDITOR.replace("artist_introduction").on('instanceReady', function()
		{
			var writer = this.dataProcessor.writer;
			writer.indentationChars = '';
			writer.lineBreakChars = '';
		});
		
		CKEDITOR.replace("artist_timeline").on('instanceReady', function()
		{
			var writer = this.dataProcessor.writer;
			writer.indentationChars = '';
			writer.lineBreakChars = '';
		});
	});
	
	function checkForm(){
		var name = $("#artist_name");
		var contact = $("#artist_contact");
		
		if($.trim(name.val()) == ""){
			name.next().removeClass("s").addClass("e").html("이름을 입력해주세요.");
		}
		else{
			document.artistForm.submit();
		}
	}
	
	function cencel(){
		document.artistForm.reset();
		history.back();
	}
	
	function deleteArtist(){
		if(confirm("삭제하시겠습니까?")){
			document.location.href = "/admin/artist/delete?idx=<?php echo $TPL_VAR["row"]["idx"]?>";
		}
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>작가관리</a></li>
  <li class="current"><a>작가추가</a></li>
</ul>

<form name="artistForm" id="artistForm" action="/admin/artist/process" method="post">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="idx" id="idx"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	<div>
		<label for="artist_name">작가명
			<input type="text" name="artist_name" id="artist_name"/>
			<span class="label_info"></span>
		</label>
		<label for="artist_kind">분야
			<input type="text" name="artist_kind" id="artist_kind" list="artist_kinds"/>
			<span class="label_info"></span>
		    <datalist id="artist_kinds">
<?php if($TPL_kind_rows_1){foreach($TPL_VAR["kind_rows"] as $TPL_V1){?>
		    	<option value="<?php echo $TPL_V1["kind"]?>"><?php echo $TPL_V1["kind"]?></option>
<?php }}?>
		    </datalist>
		</label>
		<label for="artist_introduction">작가 소개
			<textarea name="artist_introduction" id="artist_introduction"><?php echo $TPL_VAR["row"]["introduction"]?></textarea>
		</label>
		<label for="artist_timeline">작가 연혁
			<textarea name="artist_timeline" id="artist_timeline"><?php echo $TPL_VAR["row"]["timeline"]?></textarea>
		</label>
		<label for="artist_name">연락처
			<input type="text" name="artist_contact" id="artist_contact"/>
			<span class="label_info"></span>
		</label>
		<label for="artist_name">주소
			<input type="text" name="artist_address1" id="artist_address1"/>
		</label>
		<label for="artist_name" class="col-lg-1 control-label">상세주소
			<input type="text" name="artist_address2" id="artist_address2"/>
		</label>
		<label for="artist_name">거래은행
			<select class="form-control" name="artist_bank" id="artist_bank">
<?php if($TPL_bank_rows_1){foreach($TPL_VAR["bank_rows"] as $TPL_V1){?>
				<option value="<?php echo $TPL_V1["code"]?>"><?php echo $TPL_V1["name"]?></option>
<?php }}?>
			</select>
		</label>
		<label for="artist_name">예금주명
			<input type="text" name="artist_name_account" id="artist_name_account"/>
		</label>
		<label for="artist_name">계좌번호
			<input type="text" name="artist_account_number" id="artist_account_number"/>
		</label>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns" onclick="deleteArtist();">삭제</button><?php }?>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>