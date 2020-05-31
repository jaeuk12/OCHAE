<?php /* Template_ 2.2.3 2016/08/17 16:37:53 D:\Work\OCHAE\www\_template\content\member\my_menu.html */?>
<script>
	$(document).ready(function(){
<?php if($TPL_VAR["left_menu"]!=""){?>
		$(".my_account_left #<?php echo $TPL_VAR["left_menu"]?>").addClass("active");
<?php }?>
	});
</script>
<div class="my_account_left">
	<ul>
		<li id="profile"><a href="/member/profile">PROFILE</a></li>
		<li id="address"><a href="/member/address">ADDRESS</a></li>
		<li id="question"><a href="/member/question">QUESTION</a></li>
	</ul>
</div>