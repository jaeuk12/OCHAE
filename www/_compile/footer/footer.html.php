<?php /* Template_ 2.2.3 2016/08/26 14:21:44 D:\Work\OCHAE\www\_template\footer\footer.html */?>
<script>
	$(document).ready(function(){
		if (window.matchMedia('(max-width: 767px)').matches) {
			var h4 = $(".footer > div > h4");
		
			h4.click(function(){
				var t = $(this).next();
				
				if(t.css("display") == "none"){
					t.slideDown();
				}
				else{
					t.slideUp();
				}
			});
	    }
	});
</script>

<div class="footer">
	<div>
		<h4>OPENING HOURS</h4>
		<ul>
			<li>Time<span>10:00 ~ 18:00</span></li>
			<li>Lunch<span>12:30 ~ 13:30 (1HR)</span></li>
			<li>Day Off<span>Monday (월)</span></li>
		</ul>
	</div>
	<div>
		<h4>CONTACT US</h4>
		<ul>
			<li>Call<span>02 - 414 - 5070</span></li>
			<li><a href="mailto:customer@ochae.com">Email<span>customer@ochae.com</span></a></li>
			<li class="location"><a href="/location">Location<span></span></a></li>
		</ul>
	</div>
	<div>
		<h4>ABOUT</h4>
		<ul>
			<li><a href="/">GALLERY OCHAE</a></li>
			<li><a href="/member/question">CUSTOMER SUPPORT</a></li>
			<!--<li><a href="/">PARTNERSHIP</a></li>-->
		</ul>
	</div>
	<div>
		<h4>LEGAL</h4>
		<ul>
			<li><a href="/">PRIVACY POLICIES</a></li>
			<li><a href="/">TERMS OF USE</a></li>
		</ul>
	</div>
</div>