<?php /* Template_ 2.2.3 2016/02/29 22:26:08 /home/hosting_users/ochae/www/_template/content/news/main.html */
$TPL_nt_1=empty($TPL_VAR["nt"])||!is_array($TPL_VAR["nt"])?0:count($TPL_VAR["nt"]);
$TPL_ns_1=empty($TPL_VAR["ns"])||!is_array($TPL_VAR["ns"])?0:count($TPL_VAR["ns"]);
$TPL_eh_1=empty($TPL_VAR["eh"])||!is_array($TPL_VAR["eh"])?0:count($TPL_VAR["eh"]);?>
<script>
	$(document).ready(function(){
		$(".btn_link").click(function(){
			var kind = $(this).attr("kind");
			document.location.href = "/news/more?kind="+kind;
		});
		
		$(".board_list li").click(function(){
			document.location.href = "/news/view?kind=<?php echo $TPL_VAR["kind"]?>&idx="+$(this).attr("idx");
		});
	});
	
</script>

<div class="news_area">
	<div class="news_board notice">
		<h5>공지사항</h5>
		<button type="button" class="btn_link tiny" kind="notice">더보기</button>
		<ul class="board_list">
<?php if($TPL_nt_1){foreach($TPL_VAR["nt"] as $TPL_V1){?>
			<li idx="<?php echo $TPL_V1["idx"]?>">
				<span class="news_tag"></span>
				<div class="news_item"><?php echo $TPL_V1["title"]?></div>
			</li>
<?php }}else{?>
			<li>
				<div class="news_item" style="text-align:center;">게시글이 없습니다</div>
			</li>
<?php }?>
		</ul>
	</div>
	
	<div class="news_board news">
		<h5>오채소식</h5>
		<button type="button" class="btn_link tiny" kind="news">더보기</button>
		<ul class="board_list">
<?php if($TPL_ns_1){foreach($TPL_VAR["ns"] as $TPL_V1){?>
			<li idx="<?php echo $TPL_V1["idx"]?>">
				<span class="news_tag"></span>
				<div class="news_item"><?php echo $TPL_V1["title"]?></div>
			</li>
<?php }}else{?>
			<li>
				<div class="news_item" style="text-align:center;">게시글이 없습니다</div>
			</li>
<?php }?>
		</ul>
	</div>
	
	<div class="news_board exhibit">
		<h5>전시소식</h5>
		<button type="button" class="btn_link tiny" kind="exhibit">더보기</button>
		<ul class="board_list">
<?php if($TPL_eh_1){foreach($TPL_VAR["eh"] as $TPL_V1){?>
			<li idx="<?php echo $TPL_V1["idx"]?>">
				<span class="news_tag"></span>
				<div class="news_item"><?php echo $TPL_V1["title"]?></div>
			</li>
<?php }}else{?>
			<li>
				<div class="news_item" style="text-align:center;">게시글이 없습니다</div>
			</li>
<?php }?>
		</ul>
	</div>
</div>