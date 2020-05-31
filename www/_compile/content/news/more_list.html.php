<?php /* Template_ 2.2.3 2016/02/29 22:26:09 /home/hosting_users/ochae/www/_template/content/news/more_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		$(".board_list li").click(function(){
			document.location.href = "/news/view?kind=<?php echo $TPL_VAR["kind"]?>&idx="+$(this).attr("idx");
		});
	});
</script>

<div class="news_board <?php echo $TPL_VAR["kind"]?>">
	<h5><?php if($TPL_VAR["kind"]=="notice"){?>공지사항<?php }elseif($TPL_VAR["kind"]=="news"){?>오채소식<?php }else{?>전시소식<?php }?></h5>
	<ul class="board_list">
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
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
	<div class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></div>
</div>