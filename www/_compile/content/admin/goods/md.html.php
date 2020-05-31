<?php /* Template_ 2.2.3 2016/08/21 19:09:43 D:\Work\OCHAE\www\_template\content\admin\goods\md.html */
$TPL_iRow_1=empty($TPL_VAR["iRow"])||!is_array($TPL_VAR["iRow"])?0:count($TPL_VAR["iRow"]);?>
<script>
	$(document).ready(function(){
		$("#oc_code").val("<?php echo $TPL_VAR["row"]["oc_code"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		
<?php if($TPL_VAR["row"]){?>
			$("#goods_md_name").val("<?php echo $TPL_VAR["row"]["title"]?>");
<?php }?>
		
		CKEDITOR.replace("goods_md_content", {
			filebrowserImageUploadUrl:"/"
		});
		
	});
	
	function checkForm(){
		document.goodsForm.submit();
	}
	
	function cencel(){
		document.goodsForm.reset();
		history.back();
	}
	
	function uploadImage(){
		var fd = new FormData(document.goodsImageForm);
		
		if(!isProcess){
			isProcess = true;
			
			$.ajax({
				type: 'POST',
				url: "/admin/goods/imageUploadAjax",
				data: fd,
				async: true,
				dataType: 'json',
				processData: false,
	        	contentType: false,
				cache: false,
				success: function(data) {
					isProcess = false;
					
					var lastLi = $(".goods_images ul li:last-child");
					var li = $("<li></li>");
					var img = $("<img src='"+data.image_path+"/"+data.image_name+"'/>");
					var close = $("<button class='del'>X</button>");
					var input = $("<input type='hidden' name='goods_images[]'/>").val(data.image_path+data.image_name);
					
					li.append(img);
					li.append(input);
					li.append(close);
					lastLi.before(li);
					
					$("#thumb_upload").val("");
				},
				error: function(xhr, status) {
					// alert('[' + status + ']\n\n' + xhr.responseText);
					isProcess = false;
				}
			});
		}
	}
	
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>상품관리</a></li>
  <li class="current"><a>상품 상세정보 MD</a></li>
</ul>

<form name="goodsForm" id="goodsForm" action="/admin/goods/mdprocess" method="post">
	<input type="hidden" name="oc_code" id="oc_code"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	
	<label for="">상품명
		<input type="text" id="goods_md_name" readonly/>
	</label>
	
	<div class="goods_images">
		<form name="goodsImageForm" id="goodsImageForm" enctype="multipart/form-data" onsubmit="return false;">
			<label class="thumb_upload">MD 사용 이미지<br/>
				<input type="file" name="thumb_upload" id="thumb_upload" onchange="uploadImage()"/>
			</label>
		</form>
		<ul>
<?php if($TPL_iRow_1){foreach($TPL_VAR["iRow"] as $TPL_V1){?>
			<li><img src="<?php echo $TPL_VAR["image_path"]?><?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?>_medium<?php echo $TPL_V1["image_ext"]?>"><input type="hidden" name="goods_images[]" value="<?php echo $TPL_V1["image_path"]?>/<?php echo $TPL_V1["image_name"]?><?php echo $TPL_V1["image_ext"]?>"><button class="del">X</button></li>
<?php }}?>
			<li><label class="goods_image_plus" for="thumb_upload"><div>+</div></label></li>
		</ul>
	</div>
	
	<label for="goods_md_content">MD정보
		<textarea name="goods_md_content" id="goods_md_content"><?php echo $TPL_VAR["row"]["content"]?></textarea>
	</label>
	
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();">저장</button>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>