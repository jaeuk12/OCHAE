<?php /* Template_ 2.2.3 2016/08/29 12:33:10 D:\Work\OCHAE\www\_template\content\member\my_address.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		
	});
	
	function editAddress(idx){
		if(typeof idx == "undefined"){
			idx = "";
		}
		
		if(!isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/member/editAddressFromAjax',
				data: {"idx" : idx},
				async: true,
				dataType: 'json',
				success: function(data) {
					modalProgress.close();
					
					if(data.code == 100){
						modalPop.show(data.content);
					}
					else{
						alert(data.message);
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
	
	function deleteAddress(idx){
		document.location = "/member/deleteAddress?idx="+idx;
	}
</script>

<article class="my_account">
	<?php echo $TPL_VAR["my_account_left"]?>

	<div class="right_container">
		<div class="address_content">
			<h3>ADDRESS<a href="javascript:editAddress();">ADD</a></h3>
			<ul>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
				<li>
					<h5>
						<?php echo $TPL_V1["shipping_name"]?>

					</h5>
					<ul>
						<li><b>ZIPCODE</b><?php echo $TPL_V1["post_code"]?></li>
						<li><b>ADDRESS</b><?php echo $TPL_V1["address1"]?></li>
						<li><b>DETAIL</b><?php echo $TPL_V1["address2"]?></li>
						<li><b>CONTACT 1</b><?php echo $TPL_V1["contact1"]?></li>
						<li><b>CONTACT 2</b><?php echo $TPL_V1["contact2"]?></li>
					</ul>
					<div class="btn_group">
						<button onclick="deleteAddress(<?php echo $TPL_V1["idx"]?>)">REMOVE</button>
						<button onclick="editAddress(<?php echo $TPL_V1["idx"]?>)">EDIT</button>
					</div>
				</li>
<?php }}?>
			</ul>
		</div>
	</div>
</article>