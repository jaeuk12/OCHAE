<?php /* Template_ 2.2.3 2016/08/25 20:45:01 D:\Work\OCHAE\www\_template\content\member\edit_address.html */?>
<script>
	function postResult(zipNo, roadAddr, jibunAddr){
		$("input[name='post_code']").val(zipNo);
		$("input[name='address1']").val(roadAddr);
		$("input[name='address1_old']").val(jibunAddr);
		
		postModalPop.close();
	}
</script>

<form name="addressFrom" action="/member/editAddress" method="post" onsubmit="return false;">
	<input type="hidden" name="idx" value="<?php echo $TPL_VAR["row"]["idx"]?>"/>
	<div class="edit_address">
		<div class="my_account">
			<div class="address_content">
				<h3>EDIT ADDRESS</h3>
				<ul>
					<li>
						<h5>SHIPPING NAME<input type="text" name="shipping_name" value="<?php echo $TPL_VAR["row"]["shipping_name"]?>"/></h5>
						<ul>
							<li><b>ZIPCODE</b><input type="text" class="zipcode" name="post_code" placeholder="Zip Code" value="<?php echo $TPL_VAR["row"]["post_code"]?>" readonly/><button class="search_post" onclick="postPop()"></button></li>
							<li><b>ADDRESS</b><input type="text" name="address1" placeholder="Address" value="<?php echo $TPL_VAR["row"]["address1"]?>" readonly/><input type="text" name="address1_old" value="<?php echo $TPL_VAR["row"]["address1_old"]?>" readonly/></li>
							<li><b>DETAIL</b><input type="text" name="address2" placeholder="Address Detail" value="<?php echo $TPL_VAR["row"]["address2"]?>"/></li>
							<li><b>CONTACT 1</b><input type="text" name="contact1" placeholder="Contact1" value="<?php echo $TPL_VAR["row"]["contact1"]?>"/></li>
							<li><b>CONTACT 2</b><input type="text" name="contact2" placeholder="Contact2" value="<?php echo $TPL_VAR["row"]["contact2"]?>"/></li>
							<li><button onclick="document.addressFrom.submit()">SAVE</button></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</form>