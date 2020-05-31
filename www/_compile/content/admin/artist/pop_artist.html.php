<?php /* Template_ 2.2.3 2016/07/27 10:09:14 D:\Work\JeongWeb\www\_template\content\admin\artist\pop_artist.html */?>
<script>
	var isProcess = false;
	var page = 1;
	var totalPage = 1;

	$(document).ready(function(){
		searchArtistAjax();
	});
	
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
							str += "<tr class='row' onclick='parent.popSearchArtistResult("+row.idx+", \""+row.name+"\")'>"
								+ "<td class='small-2 large-2 columns'>"+(data.total_num-i)+"</td>"
								+ "<td class='small-3 large-3 columns'>"+row.idx+"</td>"
								+ "<td class='small-7 large-7 columns'>"+row.name+"</td></tr>";
						}
						
						tbody.append(str);
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