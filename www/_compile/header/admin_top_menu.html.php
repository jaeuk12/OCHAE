<?php /* Template_ 2.2.3 2016/08/21 18:10:51 D:\Work\OCHAE\www\_template\header\admin_top_menu.html */?>
<div class="fixed">
	<nav class="top-bar" data-topbar role="navigation" data-options="is_hover: false">
		<ul class="title-area">
			<li class="name">
				<h1>
					<a href="/admin">OCHAE Admin</a>
				</h1>
			</li>
			<li class="toggle-topbar menu-icon">
				<a href="#"><span></span></a>
			</li>
		</ul>
	
		<section class="top-bar-section">
			<ul class="left">
				<li class="has-dropdown">
					<a>회원관리</a>
					<ul class="dropdown">
						<li><a href="/admin/member/list">회원목록</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
					<a>상품관리</a>
					<ul class="dropdown">
						<li><a href="/admin/goods/category">카테고리관리</a></li>
						<li><a href="/admin/goods/list">상품목록</a></li>
						<li><a href="/admin/goods/inStockList">입고관리</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
					<a>작가관리</a>
					<ul class="dropdown">
						<li><a href="/admin/artist/list">작가목록</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
					<a>게시판관리</a>
					<ul class="dropdown">
						<li><a href="/admin/gallery/list">갤러리</a></li>
						<li><a href="/admin/board/notice">공지사항</a></li>
						<li><a href="/admin/board/question">고객문의</a></li>
					</ul>
				</li>
				<li class="has-dropdown">
					<a>MD관리</a>
					<ul class="dropdown">
						<li><a href="/admin/md/mainslider">메인 슬라이더</a></li>
					</ul>
				</li>
			</ul>
			<ul class="right">
				<li class="has-dropdown">
					<a>Login : <?php echo $TPL_VAR["sess_member_name"]?></a>
					<ul class="dropdown">
						<li>
							<a href="/">사이트 홈</a>
							<a href="/logout">로그아웃</a>
						</li>
					</ul>
				</li>
			</ul>
		</section>
	</nav>
</div>