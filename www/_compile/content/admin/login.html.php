<?php /* Template_ 2.2.3 2016/08/19 15:45:48 D:\Work\OCHAE\www\_template\content\admin\login.html */?>
<script>
	var isProcess = false;
	
	$(document).ready(function(){
		$("#id").focus();
	});
	
	function login(){
		var id = $("#id").val();
		var pw = $("#pw").val();
		
		if(id == ""){
			alert("아이디를 입력하세요");
		}
		else if(pw == ""){
			alert("비밀번호를 입력하세요");
		}
		else{
			if(!isProcess){
				isProcess = true;
				
				$.ajax({
					type: 'POST',
					url: '/loginAjax',
					data: { "id" : id,
							 "pw" : pw },
					async: true,
					dataType: 'json',
					success: function(data) {
						isProcess = false;
						if(data.code == 100){
							document.location = "/admin";
						}
						else{
							alert(data.message);
						}
					},
					error: function(xhr, status) {
						isProcess = false;
						alert('[' + status + ']\n\n' + xhr.responseText);
					}
				});
			}
		}
	}
	
	function passwordEnter(e){
		if(enterCheck(e)){
			login();
		}
	}
</script>

<div class="row centered login">
	<div class="medium-6 medium-centered columns">
		<h2>OCHAE <small><b>관리자 로그인</b></small></h2>
		<div>
			<input type="text" id="id" placeholder="ID"/>
			<input type="password" id="pw" placeholder="Password" onkeyup="passwordEnter(event)"/>
		</div>
		<button type="button" class="columns" onclick="login();">로그인</button>
	</div>
</div>