/**
 * http://www.juso.go.kr/addrlink/addrLinkApi.do
 * U01TX0FVVEgyMDE2MDgyNDAxMTIxNDE0NzQ2
 */

var bScroll, bOldScroll = false;
var isLoad = false;
var isAnimate = false;
var isProcess = false;

/**
 * 우편주소 검색
 */
function postPop(keyword, page){
	if(!isProcess){
		modalProgress.show();
		
		$.ajax({
			type: 'POST',
			url: '/common/searchPostAjax',
			data: {'keyword' : keyword,
					'page' : page},
			async: true,
			dataType: 'json',
			success: function(data) {
				modalProgress.close();
				
				if(data.code == 0){
					postModalPop.show(data.content);
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

/**
 * 모바일 메뉴 토글
 */
function navMobileMenuToggle(){
	$(".oc_nav_menu .nav_mobile_icon").click(function(){
		var menuBar = $(".oc_nav_menu .nav_menu_bar");
		if(menuBar.hasClass("active")){
			menuBar.removeClass("active");
		}
		else{
			menuBar.addClass("active");
		}
	});
	
	var lis = $(".oc_nav_menu .nav_menu_bar .nav_bar ul li");
	
	lis.click(function(){
		var t = $(this);
		
		if(!t.hasClass("active")){
			lis.removeClass("active");
			t.addClass("active");
		}
	});
}

/**
 * 메인 슬라이더 초기화
 */
function ocSlider(){
	contentFullHeight();
	$(window).bind("load", imageFullHeight);
	$(window).bind("resize", contentFullHeight);
	
	$(".oc_slider_arrow.l").click(function(){
		sliderChange(false);
	});
	$(".oc_slider_arrow.r").click(function(){
		sliderChange(true);
	});
	
//	$(".num_first").html($(".oc_slider_content.active").index()+1);
//	$(".num_limit").html($(".oc_slider_box").children().length);
	
	var navCircle = $(".oc_slider_nav .nav_content ul");
	for(var i=0; i<$(".oc_slider_box").children().length; i++ ){
		navCircle.append("<li></li>");
	}
	
	navCircle.children(":first-child").addClass("active");
	
	$(".oc_slider_txt").mouseenter(function(){$(".oc_slider_arrow").stop().fadeOut();});
	$(".oc_slider_txt").mouseleave(function(){$(".oc_slider_arrow").stop().fadeIn();});
	
	navTextChange();
}

/**
 * 메인 컨텐츠영역 자동조절
 */
function contentFullHeight(){
	var ocSliderContainer = $(".oc_slider_container");
	var topNavHeight = $(".oc_nav_menu").height();
	var dh = $("body").height()-topNavHeight;
	ocSliderContainer.height(dh);
	imageFullHeight();
}

/**
 * 이미지 높이 자동조절
 */
function imageFullHeight(){
	$.each($(".oc_slider_content"), function(k, v){
		var $c = $(v);
		var $v = $c.find("> img");
		var topNavHeight = $(".oc_nav_menu").height();
		var dw = $(".layout_contents").width();
		var dh = $("body").height()-topNavHeight;
		var vw = $v.width();
		var vh = $v.height();
		var cAspect = dw / dh;
		var iAspect = vw / vh;
		
		if (iAspect < cAspect) {
			$v.width(dw);
			$v.height((vh / vw) * dw);
			$v.css("margin-left", 0);
			$v.css("margin-top", -($v.height()-dh)/2);
		}
		else {
			$v.width((vw / vh) * dh);
			$v.height(dh);
			$v.css("margin-left", -($v.width()-dw)/2);
			$v.css("margin-top", 0);
		}
		
		if(isLoad){
			if(k != 0){
				if(!$c.hasClass("active")){
					$c.css("display", "none");
				}
			}
		}
		
	});
}

/**
 * 메인 슬라이더 
 */
function sliderChange(b){
	if(!isLoad){
		return;
	}

	var obj = $(".oc_slider_content.active");
	var nextObj = "";

	if(!isAnimate && $(".oc_slider_content").length > 1){
		isAnimate = true;
		obj.removeClass("active").fadeOut(600, function(){isAnimate=false;});
		
		if(b){
			nextObj = obj.next();
			if(nextObj.index() == -1){
				nextObj = obj.parent().children(":first-child");
			}

			nextObj.css({"left":nextObj.width(), "display":"block"}).addClass("active").stop().animate({left:0});
		}
		else{
			nextObj = obj.prev();
			if(nextObj.index() == -1){
				nextObj = obj.parent().children(":last-child");
			}
			
			nextObj.css({"left":-nextObj.width(), "display":"block"}).addClass("active").stop().animate({left:0});
		}

		navTextChange();
		
		var navCircle = $(".oc_slider_nav .nav_content ul li.active");
		navCircle.removeClass("active");
		
		if(b){
			if(navCircle.next().index() == -1){
				navCircle.parent().children(":first-child").addClass("active");
			}
			else{
				navCircle.next().addClass("active");
			}
		}
		else{
			if(navCircle.prev().index() == -1){
				navCircle.parent().children(":last-child").addClass("active");
			}
			else{
				navCircle.prev().addClass("active");
			}
		}
		
		//$(".num_first").html($(".oc_slider_content.active").index()+1);
	}
}

/**
 * 메인 슬라이더 텍스트 변경
 */
function navTextChange(){
	var title = $(".oc_slider_content.active").attr("title");
	var txt = $(".oc_slider_content.active").attr("txt");
	var link = $(".oc_slider_content.active").attr("link");
	var btnLink = $(".oc_slider_txt .txt_box .link");
	
	if(link == "" || typeof link == "undefined"){
		link = "#";
	}
	
	$(".oc_slider_txt .txt_box h4").fadeOut(400, function(){$(this).html(title).fadeIn(400)});
	$(".oc_slider_txt .txt_box div").html(txt);
	
	if(link == "#"){
		btnLink.css("display", "none");
		btnLink.children("a").attr("href", "/");
	}
	else{
		btnLink.css("display", "inline-block");
		btnLink.children("a").attr("href", link);
	}
}

/**
 * 엔터키 체크 
 */
function enterCheck(e) {
	if (!e) e = window.event;
    if (e.keyCode) code = e.keyCode;
    else if (e.which) code = e.which;
	
	if (code == 13 || e.charCode == 13) {
		if(this.value =='')
			window.event.returnValue = "";
		if(window.netscape)
			e.preventDefault();
		return true;
	}
	else{
		return false;
	}
}

/**
 * 숫자만 입력 체크
 */
function numberCheck(e) {
	e = e || window.e;
	var keyID = (e.which) ? e.which : e.keyCode;
	if( ( keyID >=48 && keyID <= 57 ) || ( keyID >=96 && keyID <= 105 ) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 )
	{
		return;
	}
	else
	{
		return false;
	}
}

/**
 * 이벤트 중복실행 방지
 */
function offEventBubble(e){
	var evt = e || window.event;
    if(evt.stopPropagation) {
        evt.stopPropagation();  // W3C 표준
    }
    else {
        evt.cancelBubble = true; // 인터넷 익스플로러 방식
    }
}

/**
* 천단위 콤마
*/
function number_format(str){
    str = ""+str+"";
    var retValue = "";
    for(i=0; i<str.length; i++){
            if(i > 0 && (i%3)==0)
                retValue = str.charAt(str.length - i -1) + "," + retValue;
            else
                retValue = str.charAt(str.length - i -1) + retValue;
    }
    return retValue;
}

/**
 * 로그인 팝업 열기
 */
function loginShow(){
	$("html").css("overflow", "hidden");
	$(".login_pop").css("display", "table").animate({opacity: 1}, 600);
	$(".login_pop input.pw").unbind("keydown").bind("keydown", function(e){
		var content = $(".login_pop .login_layout .login_content input[type=password].re");

		if (e.keyCode == 13){
			if(content.css("display") == "none"){
				login();
			}
		}
	});
}

/**
* 로그인 팝업 닫기
*/
function loginClose(){
	$(".login_pop").animate({opacity: 0}, 600, function() {
		var content = $(".login_pop .login_layout .login_content");
		var id = content.children("input[type=email]");
		var pw = content.children("input[type=password].pw");
		var pw_re = content.children("input[type=password].re");
		var btnLogin = content.children("button.login");
		var btnJoin = content.children("button.join");
		var others = content.children(".others");
		var joinInfo = content.children(".join_info");
		
		$(this).css("display", "none");
		$("html").css("overflow", "auto");
		
		id.val("");
		pw.val("");
		pw_re.val("");
		pw_re.css("display", "none");
		joinInfo.css("display", "none");
		
		btnLogin.css("width","75%");
		btnJoin.css({"width":"25%"});
		others.css("display", "inline-block");
	});
}

/**
 * 회원가입
 */

function join(){
	var content = $(".login_pop .login_layout .login_content");
	var id = content.children("input[type=email]");
	var pw = content.children("input[type=password].pw");
	var pw_re = content.children("input[type=password].re");
	var btnLogin = content.children("button.login");
	var btnJoin = content.children("button.join");
	var others = content.children(".others");
	var joinInfo = content.children(".join_info");
	
	if(pw_re.css("display") == "none"){
		pw_re.fadeIn();
		joinInfo.fadeIn();
		others.fadeOut();
		btnLogin.css("width","25%");
		btnJoin.css({"width":"75%"});
	}
	else{
		if(validateLogin(true) && !isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/member/joinAjax',
				data: {'id' : id.val() ,'pw' : pw.val()},
				async: true,
				dataType: 'json',
				success: function(data) {
					modalProgress.close();
					alert(data['message']);
					if(data['code'] == 100){
						location.reload();
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
}

/**
 * 회원 로그인
 */
function login(){
	var content = $(".login_pop .login_layout .login_content");
	var id = content.children("input[type=email]");
	var pw = content.children("input[type=password].pw");
	var pw_re = content.children("input[type=password].re");
	var btnLogin = content.children("button.login");
	var btnJoin = content.children("button.join");
	var others = content.children(".others");
	var joinInfo = content.children(".join_info");
	
	if(pw_re.css("display") != "none"){
		id.val("");
		pw.val("");
		pw_re.val("");
		pw_re.fadeOut();
		joinInfo.fadeOut();
		others.fadeIn();
		btnLogin.css("width","75%");
		btnJoin.css({"width":"25%"});
	}
	else{
		if(validateLogin() && !isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/member/loginAajx',
				data: {'id' : id.val() ,'pw' : pw.val()},
				async: true,
				dataType: 'json',
				success: function(data) {
					modalProgress.close();
					if(data['code'] == 100){
						location.reload();
					}
					else{
						alert(data['message']);
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
}

/**
 * 아이디/패스워드 체크
 */
function validateLogin(isJoin){
	var content = $(".login_pop .login_layout .login_content");
	var id = content.children("input[type=email]");
	var pw = content.children("input[type=password].pw");
	var pw_re = content.children("input[type=password].re");
	
	if($.trim(id.val()) == ""){
		alert("이메일을 입력해주세요");
		id.focus();
		return false;
	}
	else if(!isValidEmail(id.val())){
		alert("올바른 이메일주소가 아닙니다");
		id.focus();
		return false;
	}
	else if($.trim(pw.val()) == ""){
		alert("비밀번호를 입력해주세요");
		pw.focus();
		return false;
	}
	else if(pw.val().length < 6 || pw.val().length > 16){
		alert("비밀번호는 6자~26자 이내로 입력해주세요");
		pw.focus();
		return false;
	}
	
	if(isJoin){
		if($.trim(pw_re.val()) == ""){
			alert("비밀번호확인을 입력해주세요");
			pw_re.focus();
			return false;
		}
		else if(pw.val() != pw_re.val()){
			alert("두개의 비밀번호가 일치하지 않습니다");
			pw_re.focus();
			return false;
		}
	}
	
	return true;
}

/**
 * 이메일체크
 */
function isValidEmail(email){
	//var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	var filter = /[0-9a-zA-Z][_0-9a-zA-Z-\.]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/;
	return filter.test(email);
}

/**
 * 비밀번호 찾기
 */
function forgotPassword(){
	var findPop = $(".find_pop");
	var content = $(".find_pop .find_layout .find_content");
	var id = content.children("input[type=email]");
	
	if(findPop.css("display") == "none"){
		loginClose();
		findPop.css("display", "table").animate({opacity: 1}, 600, function(){
			$("html").css("overflow", "hidden");
		});
	}
	else{
		if(!isValidEmail(id.val())){
			alert("올바른 이메일주소가 아닙니다");
			id.focus();
		}
		else if(!isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/member/findPassword',
				data: {'id' : id.val()},
				async: true,
				dataType: 'json',
				success: function(data) {
					modalProgress.close();
					alert(data['message']);
					if(data['code'] == 100){
						findClose();
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
}

/**
 * 비밀번호 찾기 팝업 닫기
 */
function findClose(){
	$(".find_pop").animate({opacity: 0}, 600, function() {
		var id = $(".find_pop .find_layout .find_content input[type=email]");
		
		$(this).css("display", "none");
		$("html").css("overflow", "auto");
		
		id.val("");
	});
}

/**
* 회원 메뉴
*/
function myShow(){
	var myMenu = $("ul.my_pop");
	
	myMenu.unbind("mouseenter mouseleave").mouseleave(function(){
		myMenu.slideUp();
	});
	
	if(myMenu.css("display") == "none"){
		myMenu.slideDown();
	}
	else{
		myMenu.slideUp();
	}
}

/**
 * 상품 상세보기
 */
function detail(ocCode){
	location.href = "/goods/detail?oc_code="+ocCode;
}

/**
 * 이미지 모달팝
 */
var imgModalPop = {
	modal:$("<div class='modal_pop'><div class='bg'></div><div class='container'><button class='close' onclick='imgModalPop.close()'>X</button><div class='content'><img/></div></div></div>"),
	show : function(src){
		this.modal.find(".content img").attr("src", src);
		$("html").css("overflow", "hidden");
		$("body").append(this.modal);
		this.modal.fadeIn(400);
	},
	close : function(){
		$("html").css("overflow", "auto");
		this.modal.fadeOut(400, function(){$(this).remove});
	}
}

/**
 * 모달팝
 */
var modalPop = {
	modal:$("<div class='modal_pop'><div class='bg'></div><div class='container'><button class='close' onclick='modalPop.close()'>X</button><div class='content'></div></div></div>"),
	show : function(content, width){
		if(typeof width != "undefined"){
			this.modal.find(".content").css("width", width);
		}
		this.modal.find(".content").html(content);
		$("html").css("overflow", "hidden");
		$("body").append(this.modal);
		this.modal.fadeIn(400);
	},
	close : function(){
		$("html").css("overflow", "auto");
		this.modal.fadeOut(400, function(){$(this).remove});
	}
}

/**
 * 모달팝
 */
var postModalPop = {
	modal:$("<div class='modal_pop'><div class='bg'></div><div class='container'><button class='close' onclick='postModalPop.close()'>X</button><div class='content'></div></div></div>"),
	show : function(content, width){
		if(typeof width != "undefined"){
			this.modal.find(".content").css("width", width);
		}
		this.modal.find(".content").html(content);
		$("html").css("overflow", "hidden");
		$("body").append(this.modal);
		this.modal.fadeIn(400);
	},
	close : function(){
		$("html").css("overflow", "auto");
		this.modal.fadeOut(400, function(){$(this).remove});
	}
}

/**
 * 모달팝
 */
var modalProgress = {
	modal:$("<div class='modal_pop'><div class='bg'></div><div class='container'><div class='content'><div class='load-container load5'><div class='loader'>Loading...</div><a href='#load5'>&lt; View Source &gt;</a></div></div></div></div>"),
	show : function(content, width){
		isProcess = true;
		if(typeof width != "undefined"){
			this.modal.find(".content").css("width", width);
		}
		
		$("html").css("overflow", "hidden");
		$("body").append(this.modal);
		this.modal.fadeIn(400);
	},
	close : function(){
		isProcess = false;;
		$("html").css("overflow", "auto");
		this.modal.fadeOut(400, function(){$(this).remove});
	}
}

/**
 * 카트
 */
function addCart(ocCode, qty, optionIdx){
	if(!isProcess){
		modalProgress.show();
		
		$.ajax({
			type: 'POST',
			url: '/cart/addCartAjax',
			data: {"oc_code" : ocCode,
					"qty" : qty,
					"option_idx" : optionIdx},
			async: true,
			dataType: 'json',
			success: function(data) {
				modalProgress.close();
				
				alert(data.message);
				if(data.code == 100){
				}
				else if(data.code == 101){
					loginShow();
				}
			},
			error: function(xhr, status) {
				//alert('[' + status + ']\n\n' + xhr.responseText);
				modalProgress.close();
			}
		});
	}
}
