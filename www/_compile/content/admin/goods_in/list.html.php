<?php /* Template_ 2.2.3 2016/05/23 19:08:48 /home/hosting_users/ochae/www/_template/content/admin/goods_in/list.html */?>
<style>
</style>

<script>
</script>

<ul class="breadcrumbs">
	<li class="unavailable"><a>상품관리</a></li>
	<li class="current"><a>입고관리</a></li>
</ul>

<article>
	<section>
		<table>
			<thead>
				<tr>
					<th>No.</th>
					<th>오채코드</th>
					<th>업체 상품코드</th>
					<th>상품명</th>
					<th>작가명</th>
					<th>표시 여부</th>
					<th>변경일</th>
					<th>등록일</th>
					<th>MD</th>
				</tr>
			</thead>
			<tbody id="goods_in_list">
			</tbody>
			<tfoot>
				<tr>
					<td colspan="9" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
				</tr>
				<tr>
					<td colspan="9" align="center">
						<form name="searchForm" id="searchForm" action="/admin/goods/inlist" method="get">
							<div class="row">
								<div class="small-4 large-3 columns">
									<select name="search_type" id="search_type">
										<option value="title">상품명</option>
										<option value="oc_code">오채코드</option>
										<option value="code">업체 상품코드</option>
										<option value="author_name">저자명</option>
									</select>
								</div>
								<div class="small-6 large-7 columns">
									<input type="text" name="search_keyword" id="search_keyword">
								</div>
								<button type="submit" class="tiny small-2 large-2 columns">검색</button>
							</div>
						</form>
					</td>
				</tr>
			</tfoot>
		</table>
	</section>
</article>