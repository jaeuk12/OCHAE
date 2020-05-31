<?php /* Template_ 2.2.3 2016/08/21 17:45:47 D:\Work\OCHAE\www\_template\content\default_list\default_list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		$("form[name=searchForm]").attr("action", document.location);
		
		if($(".default_list ul li").length >= <?php echo $TPL_VAR["list_num"]?>){
			$(".pagelist .more").click(function(){
				getMoreList();
			});
		}
		else{
			$(".pagelist .more").css("display", "none");
		}
	});
	
	function getMoreList(){
		var goodsSize = $(".default_list ul li").length;
		
		$.ajax({
			type: 'POST',
			url: '/goods/moreGoodsListAjax',
			data: {"category_code" : "<?php echo $TPL_VAR["category_code"]?>",
					"keyword" : "<?php echo $TPL_VAR["keyword"]?>",
					"size" : goodsSize },
			async: true,
			dataType: 'json',
			success: function(data) {
				if(data.code == 100){
					var ul = $(".default_list ul");
					$.each(data.list, function(k, v){
						var li = $("<li>"
								+"<div class='list_content'>"
								+"<img src='<?php echo $TPL_VAR["image_path"]?>"+v.thumb.replace(".", "_medium.")+"' onclick='detail(\""+v.oc_code+"\")' />"
								+"<div class='info'><div class='bg'></div>"
								+"<div class='description'><p class='title'>"+v.title+"</p>"
								+"<p class='artist'>"+v.artist_name+"</p>"
								+"<p class='price "+v.discount_yn+"'>"+(v.discount_yn=="Y"?number_format(v.discount_price):number_format(v.sale_price))+"</p>"
								+"</div></div></div></li>");
								
						ul.append(li);
					});
					
					$(".pagelist .more .more_num").html(number_format(goodsSize+data.list.length));
					$(".default_list .last_num").html(number_format(goodsSize+data.list.length));
					
					if(data.list.length < <?php echo $TPL_VAR["list_num"]?>){
						$(".pagelist .more").remove();
					}
				}
				else if(data.code == 200){
					$(".pagelist .more").remove();
				}
			},
			error: function(xhr, status) {
				//alert('[' + status + ']\n\n' + xhr.responseText);
			}
		});
	}
</script>

<article class="default_contents">
	<section class="search_keyword">
		<form name="searchForm" method="get">
			<input type="text" name="search_keyword" placeholder="Search Goods Name or Artist Name" value="<?php echo $TPL_VAR["keyword"]?>"/><button class="btn_search">SEARCH</button>
		</form>
	</section>
	
	<section class="default_list">
<?php if($TPL_VAR["keyword"]!=""){?><h3><span class="total_num"><?php echo number_format($TPL_VAR["total"])?></span> Results found for "<?php echo $TPL_VAR["keyword"]?>"</h3><?php }?>
		<h4>	<span class="first_num"><?php echo number_format($TPL_VAR["first"])?></span> - <span class="last_num"><?php echo number_format($TPL_VAR["last"])?></span> of <span class="total_num"><?php echo number_format($TPL_VAR["total"])?></span> Goods</h4>
		<ul>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
			<li>
				<div class="list_content">
					<img src="<?php echo $TPL_VAR["image_path"]?><?php echo str_replace('.','_medium.',$TPL_V1["thumb"])?>" onclick="detail('<?php echo $TPL_V1["oc_code"]?>')" />
					<div class="info">
						<div class="bg"></div>
						
						<div class="description">
							<p class="title"><?php echo $TPL_V1["title"]?></p>
							<p class="artist"><?php echo $TPL_V1["artist_name"]?></p>
							<p class="price <?php echo $TPL_V1["discount_yn"]?>"><?php if($TPL_V1["discount_yn"]=="Y"){?><?php echo number_format($TPL_V1["discount_price"])?><?php }else{?><?php echo number_format($TPL_V1["sale_price"])?><?php }?></p>
						</div>
					</div>
				</div>
			</li>
<?php }}?>
		</ul>
	</section>
	
	<div class="pagelist">
		<?php echo $TPL_VAR["pagelist"]?>

		<button class="more"><img src="/image/icon/icon_triangle_down.png"/>MORE<span class="more_num"><?php echo number_format($TPL_VAR["last"])?></span> of <?php echo number_format($TPL_VAR["total"])?></button>
	</div>
</article>