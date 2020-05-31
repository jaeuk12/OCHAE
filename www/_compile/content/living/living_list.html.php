<?php /* Template_ 2.2.3 2016/07/28 11:56:31 D:\Work\JeongWeb\www\_template\content\living\living_list.html */?>
<script>

	function detail(ocCode){
		location.href = "/living/detail?oc_code="+ocCode;
	}
	
</script>

<article class="default_contents">
	<section >
	</section>
	
	<section class="default_list">
		<ul>
<?php if(is_array($TPL_R1=range(1,8))&&!empty($TPL_R1)){$TPL_I1=-1;foreach($TPL_R1 as $TPL_V1){$TPL_I1++;?>
			<li>
				<div class="list_content">
					<img src="/image/test4.jpg" onclick="detail('<?php echo $TPL_I1?>')" />
					<div class="info">
						<div class="bg"></div>
						
						<div class="description">
							<p class="title">상품명 Title 상품명 Product Goods 상품명</p>
							<p class="artist">아티스트 Artist</p>
							<p class="price">999,999,999</p>
						</div>
					</div>
				</div>
			</li>
<?php }}?>
		</ul>
	</section>
</article>