<?php /* Template_ 2.2.3 2016/07/20 10:28:39 D:\Work\JeongWeb\www\_template\content\gallery\list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	var sScroll = false;
	var osScroll = false;
	
	$(document).ready(function(){
		$(".item_box .thumb").click(function(){
			var idx = $(this).attr("idx");
			document.location = "/gallery/detail?idx="+idx;
		});
		
		$(".tool_nav .tool_box .search button").click(function(){
			document.searchForm.submit();
		});
	});
</script>

<article class="content_gallery">
	<section class="tool_nav">
		<div class="tool_box">
			<form class="search" name="searchForm" action="/gallery" method="get">
				<input type="text" placeholder="Search Works and Artists" name="search_keyword"/>
				<button></button>
			</form>
		</div>
	</section>
	<section class="item_list">
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
		<div class="item_box">
			<div class="item_content">
				<div class="thumb" idx="<?php echo $TPL_V1["idx"]?>"><img src="<?php echo $TPL_VAR["img_path"]?><?php echo $TPL_V1["image"]?>"/></div>
				<div class="txt">
					<h6><?php echo $TPL_V1["title"]?></h6>
					<p><?php echo $TPL_V1["artist_name"]?></p>
				</div>
			</div>
		</div>
<?php }}?>
	</section>
	<section class="page_list">
		<div class="bg"></div>
		<div class="content">
			<?php echo $TPL_VAR["pagelist"]?>

		</div>
	</section>
</article>