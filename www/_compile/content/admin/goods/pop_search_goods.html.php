<?php /* Template_ 2.2.3 2016/07/27 10:09:16 D:\Work\JeongWeb\www\_template\content\admin\goods\pop_search_goods.html */?>
<script>
	var isProcess = true;
	var page = 1;
	var totalPage = 1;

	$(document).ready(function(){
		isProcess = false;
		searchGoodsAjax();
	});
	
	function searchGoodsAjax(){
		var type = $("#search_type");
		var keyword = $("#search_keyword");
		var artistIdx = $("#select_artist_idx").val();
		
		if(!isProcess){
			isProcess = true;
		
			$.ajax({
				type: 'POST',
				url: "/admin/common/searchGoodsAjax",
				data: { "search_type" : type.val(),
						 "search_keyword" : keyword.val(),
						 "page" : page,
						 "artist_idx" : "<?php echo $TPL_VAR["artist_idx"]?>" },
				async: true,
				dataType: 'json',
				success: function(data) {
					isProcess = false;
					var tbody = $("#search_goods_body");
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
							str += "<tr class='row' onclick='parent.popSelectGoodsResult(\""+row.oc_code+"\", \""+row.title+"\", \"\", \""+row.cost_price+"\", \""+row.sale_price+"\", \""+row.tax_yn+"\")'>"
								+ "<td class='small-2 large-2 columns'>"+row.oc_code+"</td>"
								+ "<td class='small-4 large-4 columns'>"+row.title+"</td>"
								+ "<td class='small-4 large-4 columns'>"+row.artist_name+"</td>"
								+ "<td class='small-2 large-2 columns'>"+number_format(row.cost_price)+"</td></tr>"
						}
						
						tbody.append(str);
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
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

<h4 class="modal-title">상품 찾기</h4>

<table>
	<thead>
		<tr class="row">
			<th class="small-2 medium-2 columns">오채 코드</th>
			<th class="small-2 medium-2 columns">업체 코드</th>
			<th class="small-3 medium-3 columns">상품명</th>
			<th class="small-3 medium-3 columns">저자명</th>
			<th class="small-2 medium-2 columns">단가</th>
		</tr>
	</thead>
	<tbody id="search_goods_body">
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6" class="row">
				<button type="button" class="tiny secondary small-4 medium-2 columns" onclick="prevPage();">이전</button>
				<span class="small-4 medium-8 columns" id="search_page_num" style="text-align: center; line-height: 34px; font-weight:bold;"></span>
				<button type="button" class="tiny secondary small-4 medium-2 columns" onclick="nextPage();">다음</button>
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<div class="row">
					<div class="small-4 large-3 columns">
						<select id="search_type">
							<option value="title">상품명</option>
							<option value="author_name">저자명</option>
						</select>
					</div>
					<div class="small-6 large-7 columns">
						<input type="text" id="search_keyword" plaseholder="검색어"/>
					</div>
					<button type="submit" class="tiny small-2 large-2 columns" onclick="searchGoodsAjax();">검색</button>
				</div>
			</td>
		</tr>
	</tfoot>
</table>