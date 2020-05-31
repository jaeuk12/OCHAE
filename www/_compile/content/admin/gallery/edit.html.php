<?php /* Template_ 2.2.3 2016/07/27 10:09:15 D:\Work\JeongWeb\www\_template\content\admin\gallery\edit.html */?>
<script>
	var isProcess = false;
	var page = 1;
	var totalPage = 1;

	$(document).ready(function(){
		$("#mode").val("<?php echo $TPL_VAR["mode"]?>");
		$("#ret_url").val("<?php echo $TPL_VAR["ret_url"]?>");
		
<?php if($TPL_VAR["row"]!=""){?>
		$("#idx").val("<?php echo $TPL_VAR["row"]["idx"]?>");
		$("#gallery_title").val("<?php echo $TPL_VAR["row"]["title"]?>");
		$("#gallery_content").val("<?php echo $TPL_VAR["row"]["content"]?>");
		
<?php if($TPL_VAR["row"]["artist_idx"]>0){?>
				var li = $("<button type='button' class='small secondary medium-6 columns'><?php echo $TPL_VAR["row"]["artist_name"]?><input type='hidden' name='gallery_artist' value='<?php echo $TPL_VAR["row"]["artist_idx"]?>'/></button>").click(function(){if(confirm("삭제하시겠습니까?")){$(this).remove();}});
				$("#gallery_artist").append(li);
<?php }?>
<?php }?>
		$("#gallery_display_<?php if($TPL_VAR["row"]["display_yn"]=='N'){?>N<?php }else{?>Y<?php }?>").attr("checked", true);
		
		CKEDITOR.replace("gallery_content").on('instanceReady', function()
		{
			var writer = this.dataProcessor.writer;
			writer.indentationChars = '';
			writer.lineBreakChars = '';
		});
		
		$("#search_keyword").keydown(function(e){
			if (event.keyCode == 13) {
				searchArtistAjax();
			}
		});
	});
	
	function checkForm(){
		document.galleryForm.submit();
	}
	
	function cencel(){
		document.galleryForm.reset();
		history.back();
	}
	
	function deleteGallery(){
		if(confirm("삭제하시겠습니까?")){
			document.location.href = "/admin/gallery/delete?idx=<?php echo $TPL_VAR["row"]["idx"]?>";
		}
	}
	
	function searchArtistAjax(){
		var type = $("#search_type");
		var keyword = $("#search_keyword");
		
		if(!isProcess){
			isProcess = true;
		
			$.ajax({
				type: 'POST',
				url: "/admin/common/searchArtistAjax",
				data: { "search_type" : type.val(),
						 "search_keyword" : keyword.val(),
						 "page" : page },
				async: true,
				dataType: 'json',
				success: function(data) {
					isProcess = false;
					var tbody = $("#search_artist_body");
					var pageNum = $("#search_page_num");
					
					tbody.empty();
					if(data != null && data.list != null){
						var str = "";
						var pagelist = "";
						var rows = data.list;
						page = data.page;
						totalPage = data.total_page;
						
						pageNum.html(page + " / " +totalPage);
						
						for(var i=0; i<rows.length; i++){
							var row = rows[i];
							str += "<tr class='row' idx="+row.idx+" aname='"+row.name+"' tax='"+row.tax_yn+"'>"
								+ "<td class='small-2 large-2 columns'>"+(data.total_num-i)+"</td>"
								+ "<td class='small-3 large-3 columns'>"+row.idx+"</td>"
								+ "<td class='small-7 large-7 columns'>"+row.name+"</td></tr>";
						}
						
						tbody.append(str);
						tbody.find("tr").click(function(){
							var artistlist = $("#gallery_artist");
							var tr = $(this);
							var li = $("<button type='button' class='small secondary medium-6 columns'>"+tr.attr("aname")+"<input type='hidden' name='gallery_artist' value='"+tr.attr("idx")+"'/></button>").click(function(){if(confirm("삭제하시겠습니까?")){$(this).remove();}});
							
							artistlist.empty();
							artistlist.append(li);
							$('#popSearchArtist').foundation('reveal', 'close');
						});
					}
				},
				error: function(xhr, status) {
					alert('[' + status + ']\n\n' + xhr.responseText);
					isProcess = false;
				}
			});
		}
	}
	
	function nextPage(){
		if(page < totalPage){
			page++;
			searchArtistAjax();
		}
	}
	
	function prevPage(){
		if(page > 1){
			page--;
			searchArtistAjax();
		}
	}
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>게시판관리</a></li>
  <li class="current"><a>갤러리등록</a></li>
</ul>

<!-- 저자 추가 모달 -->
<div id="popSearchArtist" class="reveal-modal" data-reveal>
	<h4 class="modal-title">작가 추가</h4>
	
	<table>
		<thead>
			<tr class="row">
				<th class="small-2 medium-2 columns">No.</th>
				<th class="small-3 medium-3 columns">저자번호</th>
				<th class="small-7 medium-7 columns">작가명</th>
			</tr>
		</thead>
		<tbody id="search_artist_body">
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="row">
					<button type="button" class="tiny secondary small-4 medium-2 columns" onclick="prevPage();">이전</button>
					<span class="small-4 medium-8 columns" id="search_page_num" style="text-align: center; line-height: 34px; font-weight:bold;"></span>
					<button type="button" class="tiny secondary small-4 medium-2 columns" onclick="nextPage();">다음</button>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div class="row">
						<div class="small-4 large-3 columns">
							<select id="search_type">
								<option value="name">저자명</option>
								<option value="id">아이디</option>
							</select>
						</div>
						<div class="small-6 large-7 columns">
							<input type="text" id="search_keyword" plaseholder="검색어"/>
						</div>
						<button type="submit" class="tiny small-2 large-2 columns" onclick="searchArtistAjax();">검색</button>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div><!-- /.modal -->

<form name="galleryForm" id="galleryForm" action="/admin/gallery/process" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode"/>
	<input type="hidden" name="idx" id="idx"/>
	<input type="hidden" name="ret_url" id="ret_url"/>
	
	<label for="gallery_image">갤러리 이미지
		<input type="file" name="gallery_image" id="gallery_image"/>
		<span class="label_info"></span>
	</label>
	<label for="gallery_title">작품명
		<input type="text" name="gallery_title" id="gallery_title"/>
		<span class="label_info"></span>
	</label>
	<label for="gallery_content">작품설명
		<textarea name="gallery_content" id="gallery_content"></textarea>
	</label>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>작가</label>
			<button class="tiny" data-reveal-id="popSearchArtist" onclick="searchArtistAjax();">작가 추가</button><br/>
			<div id="gallery_artist"></div>
		</div>
	</div>
	<div class="row" style="margin-bottom:1rem;">
	    <div>
			<label>표시여부</label>
	     	<input type="radio" name="gallery_display" id="gallery_display_Y" value="Y"/><label for="gallery_display_Y">표시</label>
			<input type="radio" name="gallery_display" id="gallery_display_N" value="N"/><label for="gallery_display_N">숨김</label>
		</div>
	</div>
	<div class="row">
		<button type="button" class="tiny small-4 large-4 columns" onclick="checkForm();"><?php if($TPL_VAR["mode"]=="new"){?>등록<?php }else{?>변경<?php }?></button>
<?php if($TPL_VAR["mode"]=="modify"){?><button type="button" class="tiny alert small-4 large-4 columns" onclick="deleteGallery();">삭제</button><?php }?>
		<button type="button" class="tiny secondary small-4 large-4 columns" onclick="cencel();">취소</button>
	</div>
</form>