<?php /* Template_ 2.2.3 2016/08/19 10:57:11 D:\Work\OCHAE\www\_template\layout\layout.html */?>
<?php $this->print_("header",$TPL_SCP,1);?>

		<script>
			$(document).ready(function(){
				
			});
		</script>
		<div class="layout_contents">
			<div class="oc_nav_menu">
				<div class="nav_bg"></div>
				
				<a href="/"><img class="logo_ochae" src="/image/ochae_logo.png"/></a>
				<a href="/"><img class="logo_ochae2" src="/image/ochae_logo2.png"/></a>
				
				<div class="nav_mobile_icon">
					<img src="/image/icon/icon_list.png">
				</div>
				
				<div class="nav_menu_bar">
					<div class="nav_bar">
						<div class="nav_bar_bg"></div>
						<ul class="menu">
							<li><a href="/goods/living" title="Living">Living</a></li>
							<li><a title="Dining">Dining</a></li>
							<li><a title="Workspace">Workspace</a></li>
							<li><a title="Artists">Artists</a></li>
							<li><a title="Jewelry">Jewelry</a></li>
							<li><a title="Kids">Kids</a></li>
							<li><a title="Sale">Sale</a></li>
						</ul>
						<ul class="my">
							<li class="login"><a href="javascript:<?php if($TPL_VAR["sess_member_login"]){?>myShow();<?php }else{?>loginShow();<?php }?>"><?php if($TPL_VAR["sess_member_login"]){?><?php echo $TPL_VAR["sess_member_id"]?><?php }else{?>LOGIN<?php }?></a>
<?php if($TPL_VAR["sess_member_login"]){?>
								<ul class="my_pop">
									<li class="account"><a href="/member/profile" title="My Account">My Account</a></li>
									<li class="order"><a href="/member/order" title="My Order">My Order</a></li>
									<li class="logout"><a href="/logout" title="Logout">Logout</a></li>
								</ul>
<?php }?>
							</li>
							<li class="cart"><a href=<?php if($TPL_VAR["sess_member_login"]){?>"/cart/"<?php }else{?>javascript:loginShow();<?php }?>>CART</a></li>
						</ul>
						
					</div>
				</div>
			</div>
			
			<div class="main_contents">
<?php $this->print_("content",$TPL_SCP,1);?>

				
<?php $this->print_("footer",$TPL_SCP,1);?>

			</div>
		</div>
		
		<div class="login_pop">
			<div class="bg"></div>
			<div class="login_layout">
				<div class="login_content">
					<button class="close" onclick="loginClose()">X</button>
					<input type="email" placeholder="Enter Email"/><br/>
					<input type="password" class="pw" placeholder="Enter Password"/><br/>
					<input type="password" class="re" placeholder="Enter Again Password"/><br/>
					<button class="login" onclick="login()">LOGIN</button>
					<button class="join" onclick="join()">JOIN</button>
					<ul class="others">
						<li><a href="javascript:forgotPassword();">Forgot password?</a></li>
					</ul>
					<div class="join_info">
						<span>▷&nbsp;&nbsp;본인의 이메일 주소를 사용해주세요.</span><br/>
						<span>▷&nbsp;&nbsp;이메일은 비밀번호 찾기, 본인 확인을 위해 사용됩니다.</span>
						<span>▷&nbsp;&nbsp;본인의 이메일을 사용하지 않을 시 피해가 발생할 수 있습니다.</span><br/>
					</div>
				</div>
			</div>
		</div>
		
		<div class="find_pop">
			<div class="bg"></div>
			<div class="find_layout">
				<div class="find_content">
					<button class="close" onclick="findClose()">X</button>
					<input type="email" placeholder="Enter Email"/><br/>
					<button class="find" onclick="forgotPassword()">SEND</button>
				</div>
			</div>
		</div>
	</body>
</html>