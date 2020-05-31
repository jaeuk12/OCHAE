<?php /* Template_ 2.2.3 2016/05/05 14:35:59 /home/hosting_users/ochae/www/_template/content/gallery/detail.html */
$TPL_works_1=empty($TPL_VAR["works"])||!is_array($TPL_VAR["works"])?0:count($TPL_VAR["works"]);?>
<script>
	$(document).ready(function(){
		$(".item_box .thumb").click(function(){
			var idx = $(this).attr("idx");
			document.location = "/gallery/detail?idx="+idx;
		});
		
		$(".artist_info .artist_content .box h5").click(function(){
			var next = $(this).next();
			if(next.hasClass("active")){
				next.removeClass("active");
			}
			else{
				next.addClass("active");
			}
		});
	});
	
</script>

<article class="content_gallery">
	<section class="preview">
		<div class="content">
			<img src="<?php echo $TPL_VAR["img_path"]?><?php echo $TPL_VAR["row"]["image"]?>"/>
		</div>
	</section>
	<section class="caption">
		<h5><?php echo $TPL_VAR["row"]["title"]?></h5>
		<?php echo $TPL_VAR["row"]["content"]?>

	</section>
	<section class="artist_info">
		<div class="artist_name">
			<h5><?php echo $TPL_VAR["row"]["artist_name"]?></h5>
		</div>
		<div class="artist_content">
			<div class="box">
				<h5>Introduction</h5>
				<div><?php echo $TPL_VAR["row"]["introduction"]?></div>
			</div>
			<div class="box">
				<h5>Timeline</h5>
				<div><?php echo $TPL_VAR["row"]["timeline"]?></div>
			</div>
		</div>
	</section>
	<section class="item_list">
		<h3><?php echo number_format($TPL_works_1)?> Works</h3>
<?php if($TPL_works_1){foreach($TPL_VAR["works"] as $TPL_V1){?>
		<div class="item_box">
			<div class="item_content">
				<div class="thumb" idx="<?php echo $TPL_V1["idx"]?>"><img src="<?php echo $TPL_VAR["img_path"]?><?php echo $TPL_V1["image"]?>"/></div>
				<div class="txt">
					<h6><?php echo $TPL_V1["title"]?></h6>
				</div>
			</div>
		</div>
<?php }}?>
	</section>
</article>