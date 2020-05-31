<?php /* Template_ 2.2.3 2016/04/02 12:43:02 /home/hosting_users/ochae/www/_template/content/admin/author/edit.html */
$TPL_bank_rows_1=empty($TPL_VAR["bank_rows"])||!is_array($TPL_VAR["bank_rows"])?0:count($TPL_VAR["bank_rows"]);?>
<script>
	$(document).ready(function(){
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
		
		$("#idx").val("<?php echo $TPL_VAR["row"]["idx"]?>");
		$("#author_name").val("<?php echo $TPL_VAR["row"]["name"]?>");
		$("#author_contact").val("<?php echo $TPL_VAR["row"]["contact"]?>");
		$("#author_address1").val("<?php echo $TPL_VAR["row"]["address1"]?>");
		$("#author_address2").val("<?php echo $TPL_VAR["row"]["address2"]?>");
		$("#author_name_account").val("<?php echo $TPL_VAR["row"]["name_account"]?>");
		$("#author_bank").val("<?php echo $TPL_VAR["row"]["bank"]?>");
		$("#author_account_number").val("<?php echo $TPL_VAR["row"]["account_number"]?>");
<?php }?>
		
	});
	
	function checkForm(){
		var name = $("#author_name");
		var contact = $("#author_contact");
		
		if($.trim(name.val()) == ""){
			name.next().removeClass("s").addClass("e").html("이름을 입력해주세요.");
		}
		else{
			document.authorForm.submit();
		}
	}
	
	function cencel(){
		document.authorForm.reset();
		history.back();
	}
	
	function deleteAuthor(){
		document.location.href = "/admin/author/delete?idx=<?php echo $TPL_VAR["row"]["idx"]?>";
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>작가관리</a></li>
  <li class="current"><a>작가추가</a></li>
</ul>

<form name="authorForm" id="authorForm" action="/admin/author/process" method="post">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="idx" id="idx"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	<div>
		<label for="author_name">작가명
			<input type="text" name="author_name" id="author_name"/>
			<span class="label_info"></span>
		</label>
	</div>
	<div>
		<label for="author_name">연락처
			<input type="text" name="author_contact" id="author_contact"/>
			<span class="label_info"></span>
		</label>
	</div>
	<div>
		<label for="author_name">주소
			<input type="text" name="author_address1" id="author_address1"/>
		</label>
	</div>
	<div>
		<label for="author_name" class="col-lg-1 control-label">상세주소
			<input type="text" name="author_address2" id="author_address2"/>
		</label>
	</div>
	<div>
		<label for="author_name">거래은행
			<select class="form-control" name="author_bank" id="author_bank">
<?php if($TPL_bank_rows_1){foreach($TPL_VAR["bank_rows"] as $TPL_V1){?>
				<option value="<?php echo $TPL_V1["code"]?>"><?php echo $TPL_V1["name"]?></option>
<?php }}?>
			</select>
		</label>
	</div>
	<div>
		<label for="author_name">예금주명
			<input type="text" name="author_name_account" id="author_name_account"/>
		</label>
	</div>
	<div>
		<label for="author_name">계좌번호
			<input type="text" name="author_account_number" id="author_account_number"/>
		</label>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns"onclick="deleteAuthor();">삭제</button><?php }?>
	</div>
</form>