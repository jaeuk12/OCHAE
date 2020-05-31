<?php /* Template_ 2.2.3 2016/08/24 14:10:59 D:\Work\OCHAE\www\_template\content\postcode.html */
$TPL_juso_1=empty($TPL_VAR["juso"])||!is_array($TPL_VAR["juso"])?0:count($TPL_VAR["juso"]);?>
<script>
	$(document).ready(function(){
		$(".post_keyword input").keydown(function(e){
			if (e.keyCode == 13){
				postPop($(this).val(), 1);
			}
		});
		
		$(".post_list > ul li").click(function(){
			var t = $(this);
			postResult(t.attr("zipNo"), t.attr("roadAddr"), t.attr("jibunAddr"));
		});
	});
</script>

<div class="post_code">
	<h3>SEARCH ADDRESS</h3>
	<div>
		<p><b>도로명 + 건물번호, 건물명, 읍/면/동/리 + 지번</b></p>
	</div>
	<div class="post_keyword">
		<input type="text" value="<?php echo $TPL_VAR["keyword"]?>"/><button onclick="postPop($(this).prev().val(), 1)">SEARCH</button>
	</div>
<?php if($TPL_VAR["keyword"]!=""){?>
	<div class="post_list">
		<h5><?php echo $TPL_juso_1+(($TPL_VAR["page"]-1)*$TPL_VAR["list_num"])?> of <?php echo $TPL_VAR["common"]["totalCount"]?> <span>Results found for</span> "<?php echo $TPL_VAR["keyword"]?>"</h5>
		<ul>
<?php if($TPL_juso_1){foreach($TPL_VAR["juso"] as $TPL_V1){?>
			<li zipNo="<?php echo $TPL_V1["zipNo"]?>" roadAddr="<?php echo $TPL_V1["roadAddr"]?>" jibunAddr="<?php echo $TPL_V1["jibunAddr"]?>">
				<span><?php echo $TPL_V1["zipNo"]?></span><?php echo $TPL_V1["roadAddr"]?>

			</li>
<?php }}?>
		</ul>
<?php if($TPL_VAR["common"]["totalCount"]>=$TPL_VAR["list_num"]){?>
		<div class="pagelist">
			<ul class="pagination">
<?php if(is_array($TPL_R1=range($TPL_VAR["start_page"],$TPL_VAR["end_page"]))&&!empty($TPL_R1)){foreach($TPL_R1 as $TPL_V1){?>
				<li <?php if($TPL_VAR["page"]==$TPL_V1){?>class="current"<?php }?>><a <?php if($TPL_VAR["page"]!=$TPL_V1){?>href="javascript:postPop('<?php echo $TPL_VAR["keyword"]?>', <?php echo $TPL_V1?>);"<?php }?>><?php echo $TPL_V1?></a></li>
<?php }}?>
			</ul>
		</div>
<?php }?>
	</div>
<?php }?>
</div>