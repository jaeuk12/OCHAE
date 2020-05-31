<?php /* Template_ 2.2.3 2016/02/29 22:26:09 /home/hosting_users/ochae/www/_template/content/news/view.html */?>
<style>
	.news_board h5 {padding-right: 8rem; font-size:1.1rem; font-weight:bold;}
</style>

<script>
	
</script>

<div class="news_board view">
	<h5><?php echo $TPL_VAR["row"]["title"]?></h5>
	<span class="board_day"><?php echo substr($TPL_VAR["row"]["reg_date"],0,10)?></span>
	<div class="board_content">
		<?php echo $TPL_VAR["row"]["content"]?>

	</div>
</div>