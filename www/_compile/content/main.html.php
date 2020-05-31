<?php /* Template_ 2.2.3 2016/08/04 13:19:17 D:\Work\OCHAE\www\_template\content\main.html */
$TPL_row_1=empty($TPL_VAR["row"])||!is_array($TPL_VAR["row"])?0:count($TPL_VAR["row"]);?>
<style>
	
</style>

<script>
	$(document).ready(function(){
		ocSlider();
	});
</script>

<article class="oc_full_image_slider">
	<div class="oc_slider_container">
		<section class="oc_slider_box">
<?php if($TPL_row_1){$TPL_I1=-1;foreach($TPL_VAR["row"] as $TPL_V1){$TPL_I1++;?>
			<div class="oc_slider_content <?php if($TPL_I1==0){?>active<?php }?>" title="<?php echo $TPL_V1["title"]?>" txt="<?php echo $TPL_V1["content"]?>" link="<?php echo $TPL_V1["link"]?>">
				<img class="oc_slider_img" src="/image/test<?php echo $TPL_I1+1?>.jpg" />
			</div>
<?php }}?>
		</section>
		
		<section class="oc_slider_txt">
			<div class="bg"></div>
			<div class="oc_slider_nav">
				<div class="nav_content">
					<div class="nav_bg"></div>
					<ul>
					</ul>
					<!--
					<div class="nav_num">
						<span class="num_first"></span>/<span class="num_limit"></span>
					</div>
					-->
				</div>
			</div>
			<div class="txt_box">
				<h4></h4>
				<div></div>
				<button class="link"><a href="/">LINK</a></button>
			</div>
		</section>
		
		<div class="oc_slider_arrow l"></div>
		<div class="oc_slider_arrow r"></div>
	</div>
</article>