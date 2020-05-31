<?php /* Template_ 2.2.3 2016/04/25 17:13:10 /home/hosting_users/ochae/www/_template/content/admin/member/list.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<style>
    .table thead th:first-child {width:70px;}
    .table thead th:nth-child(5) {width:80px;}
    .table thead th:nth-child(6) {width:100px;}
    .table thead th:nth-child(7) {width:100px;}
    .table thead th:nth-child(8) {width:100px;}
</style>

<script>
    $(document).ready(function(){
        $("#member_list tr").click(function(){
            var idx = $(this).attr("idx");
            if(idx != "" && typeof idx != "undefined"){
                var retUrl = "ret_url="+encodeURIComponent(document.location);
                document.location.href = "/admin/member/info?"+retUrl+"&idx="+idx;
            }
        });
        
<?php if($TPL_VAR["search_type"]!=""){?>$("#search_type").val("<?php echo $TPL_VAR["search_type"]?>");<?php }?>
<?php if($TPL_VAR["search_keyword"]!=""){?>$("#search_keyword").val("<?php echo $TPL_VAR["search_keyword"]?>");<?php }?>
        
    });
</script>

<ul class="breadcrumbs">
  <li class="unavailable"><a>회원관리</a></li>
  <li class="current"><a>회원목록</a></li>
</ul>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>아이디</th>
            <th>이름</th>
            <th>성별</th>
            <th>생년월일</th>
            <th>수정일</th>
            <th>가입일</th>
        </tr>
    </thead>
    <tbody id="member_list">
<?php if($TPL_rows_1){$TPL_I1=-1;foreach($TPL_VAR["rows"] as $TPL_V1){$TPL_I1++;?>
        <tr idx="<?php echo $TPL_V1["idx"]?>">
            <td><?php echo $TPL_VAR["total_num"]-$TPL_I1?></td>
            <td><?php echo $TPL_V1["id"]?></td>
            <td><?php echo $TPL_V1["name"]?></td>
            <td><?php if($TPL_V1["gender"]=="M"){?>남<?php }elseif($TPL_V1["gender"]=="W"){?>여<?php }else{?><?php }?></td>
            <td><?php echo $TPL_V1["birthday"]?></td>
            <td><?php echo $TPL_V1["modify_date"]?></td>
            <td><?php echo $TPL_V1["reg_date"]?></td>
        </tr><?php }}?>
    </tbody>
    <tfoot>
    	<tr>
            <td colspan="7" class="pagination-centered"><?php echo $TPL_VAR["pagelist"]?></td>
        </tr>
        <tr>
            <td colspan="7" align="center">
                <form name="searchForm" id="searchForm" action="/admin/member/list" method="get">
                	<div class="row">
	                    <div class="small-4 large-3 columns">
	                        <select name="search_type" id="search_type">
	                            <option value="id">아이디</option>
	                            <option value="name">이름</option>
	                            <option value="tel">연락처</option>
	                            <option value="phone">휴대폰</option>
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