<?php /* Template_ 2.2.3 2016/07/28 10:39:56 D:\Work\JeongWeb\www\_template\content\admin\goods\category.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		// 오른쪽 클릭 메뉴 막기
        document.oncontextmenu = function() {return false;};
        
        // 마우스 다운 이벤트
        $(".category_list ul li").mousedown(function(e){
        	var t = $(this);
        	var title = t.attr("title");
        	var code = t.attr("code");
        	var deep = t.attr("deep");
        	
            // 오른쪽 클릭 여부
            if( e.button == 2 ) {
                var buttonPop = $(".button_pop");
                var bg = buttonPop.children(".bg");
                var content = buttonPop.children(".content");
                var li = $(".button_pop .content ul li:first-child");
                
                $("#code").val(code);
                content.children("ul").removeClass().addClass("deep"+deep);
                
                bg.click(function(){
                	buttonPop.hide();
                });
                li.html(title);
                content.css({"left":e.pageX, "top":e.pageY});
                buttonPop.show();
            }
            
            offEventBubble(e);
        });
	});
	
	function addCategory(){
		var parentCode = $("#code").val();
		var categoryName = $("#popAddModal #insert_name").val();
		
		location.href="/admin/goods/addCategory?parent_code="+parentCode+"&category_name="+categoryName;
	}
	
	function modifyCategory(){
		var code = $("#code").val();
		var categoryName = $("#popModifyModal #update_name").val();
		
		location.href="/admin/goods/modifyCategory?code="+code+"&category_name="+categoryName;
	}
	
	function deleteCategory(){
		var code = $("#code").val();
		location.href="/admin/goods/deleteCategory?code="+code;
	}
	
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>상품관리</a></li>
  <li class="current"><a>카테고리관리</a></li>
</ul>

<!-- 버튼 팝업-->
<div class="button_pop">
	<input type="hidden" id="code"/>
	<div class="bg"></div>
	<div class="content">
		<ul>
			<li></li>
			<li data-reveal-id="popModifyModal">카테고리명 변경</li>
			<li data-reveal-id="popAddModal">카테고리 추가</li>
			<li onclick="deleteCategory()">카테고리 삭제</li>
		</ul>
	</div>
</div>

<!-- modal -->
<div id="popAddModal" class="reveal-modal" data-reveal>
	<label for="category_name">
		카테고리 명
		<input type="text" name="insert_name" id="insert_name"/>
		<button class="tiny" style="float: right;" onclick="addCategory()">확인</button>
	</label>
</div><!-- /.modal -->

<!-- modal -->
<div id="popModifyModal" class="reveal-modal" data-reveal>
	<label for="category_name">
		카테고리 명
		<input type="text" name="update_name" id="update_name"/>
		<button class="tiny" style="float: right;" onclick="modifyCategory()">확인</button>
	</label>
</div><!-- /.modal -->

<article class="goods_category">
	<section class="category_list">
		<ul>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
			<li code="<?php echo $TPL_V1["code"]?>" deep="<?php echo $TPL_V1["deep"]?>" title="<?php echo $TPL_V1["code_name"]?>" class="<?php if($TPL_V1["list"]!=''){?>child<?php }?>"><?php echo $TPL_V1["code_name"]?>

				<ul>
<?php if(is_array($TPL_R2=$TPL_V1["list"])&&!empty($TPL_R2)){foreach($TPL_R2 as $TPL_V2){?>
					<li code="<?php echo $TPL_V2["code"]?>" deep="<?php echo $TPL_V2["deep"]?>" title="<?php echo $TPL_V2["code_name"]?>" class="<?php if($TPL_V1["list"]!=''){?>child<?php }?>"><?php echo $TPL_V2["code_name"]?>

						<ul>
<?php if(is_array($TPL_R3=$TPL_V2["list"])&&!empty($TPL_R3)){foreach($TPL_R3 as $TPL_V3){?>
							<li code="<?php echo $TPL_V3["code"]?>" deep="<?php echo $TPL_V3["deep"]?>" title="<?php echo $TPL_V3["code_name"]?>"><?php echo $TPL_V3["code_name"]?></li>
<?php }}?>
						</ul>
					</li>
<?php }}?>
				</ul>
			</li>
<?php }}?>
		</ul>
	</section>
</article>