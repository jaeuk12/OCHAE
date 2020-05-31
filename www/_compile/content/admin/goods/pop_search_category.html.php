<?php /* Template_ 2.2.3 2016/08/04 10:31:06 D:\Work\JeongWeb\www\_template\content\admin\goods\pop_search_category.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		$(".category_list li").click(function(){
			var t = $(this);
        	var title = t.attr("title");
        	var code = t.attr("code");
        	
			parent.popSelectCategoryResult(title, code);
		});
	});

</script>

<article class="goods_category">
	<section class="category_list">
		<ul>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
			<li code="<?php echo $TPL_V1["code"]?>" title="<?php echo $TPL_V1["code_name"]?>" class="<?php if($TPL_V1["list"]!=''){?>child<?php }?>"><?php echo $TPL_V1["code_name"]?>

				<ul>
<?php if(is_array($TPL_R2=$TPL_V1["list"])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
					<li code="<?php echo $TPL_V2["code"]?>" title="<?php echo $TPL_V2["code_name"]?>"><?php echo $TPL_V2["code_name"]?></li>
<?php }}?>
				</ul>
			</li>
<?php }}?>
		</ul>
	</section>
</article>