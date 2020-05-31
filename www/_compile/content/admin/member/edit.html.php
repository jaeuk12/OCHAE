<?php /* Template_ 2.2.3 2016/04/25 17:13:10 /home/hosting_users/ochae/www/_template/content/admin/member/edit.html */?>
<style>
	div.row .key {font-weight:bold; padding-left:0.5rem; margin-bottom:0.75rem; border-left:#333 0.7rem solid;}
	div.row .value {padding:0.75rem; margin-bottom:1.5rem; -webkit-box-shadow: inset 0px 5px 20px -5px rgba(0,0,0,0.5); -moz-box-shadow: inset 0px 5px 20px -5px rgba(0,0,0,0.5); box-shadow: inset 0px 5px 20px -5px rgba(0,0,0,0.5); border-radius:0.25rem;}
</style>

<script>
    $(document).ready(function(){
    	
    });
    
    function cencel(){
        history.back();
    }
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>회원관리</a></li>
  <li class="unavailable"><a>회원목록</a></li>
  <li class="current"><a>회원정보</a></li>
</ul>

<div class="row">
	<div class="key columns">프로필 이미지</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["profile_image"]?> <?php echo $TPL_VAR["row"]["profile_date"]?></div>
	
	<div class="key columns">ID</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["id"]?></div>
	
	<div class="key columns">PW 초기화</div>
	<button type="button" class="tiny medium-3 columns round" onclick="">핸드폰 번호로 초기화</button>
	
	<div class="key columns">성별</div>
	<div class="value medium-6 columns"><?php if($TPL_VAR["row"]["gender"]=="M"){?>남자<?php }elseif($TPL_VAR["row"]["gender"]=="W"){?>여자<?php }else{?>알수없음<?php }?></div>
	
	<div class="key columns">이름</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["name"]?></div>
	
	<div class="key columns">보유 머니</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["ocmoney"]?></div>
	
	<div class="key columns">연락처</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["tel"]?></div>
	
	<div class="key columns">휴대폰 번호</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["phone"]?></div>
	
	<div class="key columns">생년월일</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["birthday"]?></div>
	
	<div class="key columns">주소</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["address1"]?></div>
	
	<div class="key columns">상세주소</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["address2"]?></div>
	
	<div class="key columns">이메일 수신여부</div>
	<div class="switch medium-6 columns" style="margin-bottom:1.5rem;">
		<input id="receive_email" type="checkbox">
		<label for="receive_email"></label>
	</div>
	
	<div class="key columns">SMS 수신여부</div>
	<div class="switch medium-6 columns" style="margin-bottom:1.5rem;">
		<input id="receive_sms" type="checkbox">
		<label for="receive_sms"></label>
	</div>
	
	<div class="key columns">PUSH 수신여부</div>
	<div class="switch medium-6 columns" style="margin-bottom:1.5rem;">
		<input id="receive_push" type="checkbox">
		<label for="receive_push"></label>
	</div>
	
	<div class="key columns">탈퇴여부</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["out_yn"]?> / <?php echo $TPL_VAR["row"]["out_date"]?> / <?php echo $TPL_VAR["row"]["out_comment"]?></div>
	
	<div class="key columns">계정 정지 여부</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["block_yn"]?> / <?php echo $TPL_VAR["row"]["block_reason"]?></div>
	
	<div class="key columns">정보 수정일</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["modify_date"]?></div>
	
	<div class="key columns">가입일</div>
	<div class="value medium-6 columns"><?php echo $TPL_VAR["row"]["reg_date"]?></div>
	<div class="medium-6 columns"></div>
</div>

<div class="row">
	<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">뒤로</button>
</div>