<?php /* Template_ 2.2.3 2016/08/19 16:54:18 D:\Work\OCHAE\www\_template\content\member\my_profile.html */?>
<script>

	function emailEdit(){
		$(".email").prev().find("a").remove();
		var email = $(".profile_content .email li:first-child");
		var password = $(".profile_content .email li:last-child");
		
		email.find("span").html("<input type='email' name='email' placeholder='Enter Change Email' value='"+email.find("span").text()+"'/>");
		email.after("<li><b>CONFIRM EMAIL</b><input type='email' name='confirm_email' placeholder='Enter Again Email'/></li>");
		
		password.find("span").html("<input type='password' name='password' placeholder='Enter Change Password'/>");
		password.after("<li><button onclick='checkEmailForm()'>SAVE</button><br/>▷ 이메일과 비밀번호는 동시에 변경이 불가능합니다</li>");
		password.after("<li><b>CONFIRM PASSWORD</b><input type='password' name='confirm_password' placeholder='Enter Again Password'/></li>");
	}
	
	function profileEdit(){
		$(".profile").prev().find("a").remove();
		var firstName = $(".profile_content .profile li:nth-child(1)");
		var lastName = $(".profile_content .profile li:nth-child(2)");
		var gender = $(".profile_content .profile li:nth-child(3)");
		var birthday = $(".profile_content .profile li:nth-child(4)");
		var contact = $(".profile_content .profile li:nth-child(5)");
		
		firstName.find("span").html("<input type='text' name='first_name' placeholder='Enter Change First Name'/>");
		lastName.find("span").html("<input type='text' name='last_name' placeholder='Enter Change Last Name'/>");
		gender.find("span").html("<label for='gender_m'><input type='radio' name='gender' id='gender_m' value='M'/>남</label><label for='gender_w'><input type='radio' name='gender' id='gender_w' value='W'/>여</label>");
		birthday.find("span").html("<input type='text' name='birthday' placeholder='Enter Change Birthday'/>");
		contact.find("span").html("<input type='text' name='contact' placeholder='Enter Change Contact'/>");
		contact.after("<li><button onclick='checkProfileForm()'>SAVE</button></li>");
	}
	
	function checkEmailForm(){
		var email = $(".email form input[name=email]").val();
		var confirmEmail = $(".email form input[name=confirm_email]").val();
		var password = $(".email form input[name=password]").val();
		var confirmPassword = $(".email form input[name=confirm_password]").val();
		
		if($.trim(email)== ""){
			alert("이메일을 입력해주세요");
			email.focus();
		}
		else if($.trim(confirmEmail) == ""){
			alert("이메일 확인을 입력해주세요");
			confirmEmail.focus();
		}
		else if(email != confirmEmail){
			alert("이메일 주소가 일치하지 않습니다");
			confirmEmail.focus();
		}
		else if($.trim(password) == ""){
			alert("비밀번호를 입력해주세요");
			password.focus();
		}
		else if(password.length < 6 || password.length > 26){
			alert("비밀번호는 6자~26자 이내로 입력해주세요");
			password.focus();
		}
		else if($.trim(confirmPassword) == ""){
			alert("비밀번호 확인을 입력해주세요");
			confirmPassword.focus();
		}
		else if(password != confirmPassword){
			alert("비밀번호가 일치하지 않습니다");
			confirmPassword.focus();
		}
		else {
			document.emailForm.submit();
		}
	}
	
	function checkProfileForm(){
		document.profileForm.submit();
	}
</script>

<article class="my_account">
	<?php echo $TPL_VAR["my_account_left"]?>

	<div class="right_container">
		<div class="profile_content">
			<h3>EMAIL<a href="javascript:emailEdit();">EDIT</a></h3>
			<ul class="email">
				<form name="emailForm" action="/member/editemail" method="post" onsubmit="return false;">
					<li><b>EMAIL</b><span><?php echo $TPL_VAR["row"]["id"]?></span></li>
					<li><b>PASSWORD</b><span>＊＊＊＊＊＊</span></li>
				</form>
			</ul>
			<h3>PROFILE<a href="javascript:profileEdit();">EDIT</a></h3>
			<ul class="profile">
				<form name="profileForm" action="/member/editprofile" method="post" onsubmit="return false;">
					<li><b>FIRST NAME</b><span><?php if($TPL_VAR["row"]["first_name"]==''){?>-<?php }else{?><?php echo $TPL_VAR["row"]["first_name"]?><?php }?></span></li>
					<li><b>LAST NAME</b><span><?php if($TPL_VAR["row"]["last_name"]==''){?>-<?php }else{?><?php echo $TPL_VAR["row"]["last_name"]?><?php }?></span></li>
					<li><b>GENDER</b><span><?php if($TPL_VAR["row"]["gender"]=='W'){?>WOMAN<?php }elseif($TPL_VAR["row"]["gender"]=='M'){?>MAN<?php }else{?>-<?php }?></span></li>
					<li><b>BIRTHDAY</b><span><?php if($TPL_VAR["row"]["birthday"]=='0000-00-00'){?>-<?php }else{?><?php echo $TPL_VAR["row"]["birthday"]?><?php }?></span></li>
					<li><b>CONTACT</b><span><?php if($TPL_VAR["row"]["contact"]==''){?>-<?php }else{?><?php echo $TPL_VAR["row"]["contact"]?><?php }?></span></li>
				</form>
			</ul>
		</div>
	</div>
</article>