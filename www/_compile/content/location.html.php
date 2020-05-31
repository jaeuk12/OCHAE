<?php /* Template_ 2.2.3 2016/07/25 23:05:21 D:\Work\OCHAE\www\_template\content\location.html */?>
<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=5c84061d0a50e5599d4fd82d73108fd4"></script>
<script>
	$(document).ready(function(){
		var map = $("#testMap");
		var dh = $("body").height()-($(".oc_nav_menu").height() + $(".footer").outerHeight(true));
		map.width($("body").width());
		map.height(dh-5);
		
		try {document.execCommand('BackgroundImageCache', false, true);} catch(e) {}
		
		var oPoint = new nhn.api.map.LatLng(37.517037, 127.118281);
		var oSize = new nhn.api.map.Size(28, 37);
		var oOffset = new nhn.api.map.Size(14, 37);
		var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);
	
		nhn.api.map.setDefaultPoint('LatLng');
		oMap = new nhn.api.map.Map('testMap' ,{
					point : oPoint,
					zoom : 11,
					enableWheelZoom : true,
					enableDragPan : true,
					enableDblClickZoom : false,
					mapMode : 0,
					activateTrafficMap : false,
					activateBicycleMap : false,
					minMaxLevel : [ 1, 14 ]
				});
				
		var mapZoom = new nhn.api.map.ZoomControl(); // - 줌 컨트롤 선언
			mapZoom.setPosition({left:20, bottom:30}); // - 줌 컨트롤 위치 지정
			oMap.addControl(mapZoom); // - 줌 컨트롤 추가.
				
		var mapInfoTestWindow = new nhn.api.map.InfoWindow(); // - info window 생성
		mapInfoTestWindow.setVisible(false); // - infowindow 표시 여부 지정.
		oMap.addOverlay(mapInfoTestWindow);     // - 지도에 추가.
		
		var oLabel = new nhn.api.map.MarkerLabel(); // - 마커 라벨 선언.
		oMap.addOverlay(oLabel); // - 마커 라벨 지도에 추가. 기본은 라벨이 보이지 않는 상태로 추가됨.
	
		mapInfoTestWindow.attach('changeVisible', function(oCustomEvent) {
				if (oCustomEvent.visible) {
						oLabel.setVisible(false);
				}
		});
	
	
		oMap.attach('mouseenter', function(oCustomEvent) {
				var oTarget = oCustomEvent.target;
				// 마커위에 마우스 올라간거면
				if (oTarget instanceof nhn.api.map.Marker) {
						var oMarker = oTarget;
						oLabel.setVisible(true, oMarker); // - 특정 마커를 지정하여 해당 마커의 title을 보여준다.
				}
		});
	
		oMap.attach('mouseleave', function(oCustomEvent) {
				var oTarget = oCustomEvent.target;
				// 마커위에서 마우스 나간거면
				if (oTarget instanceof nhn.api.map.Marker) {
						oLabel.setVisible(false);
				}
		});
		
		oMap.attach('click', function(oCustomEvent) {
				var oPoint = oCustomEvent.point;
				var oTarget = oCustomEvent.target;
				mapInfoTestWindow.setVisible(false);
				// 마커 클릭하면
				if (oTarget instanceof nhn.api.map.Marker) {
						// 겹침 마커 클릭한거면
						if (oCustomEvent.clickCoveredMarker) {
								return;
						}
						// - InfoWindow 에 들어갈 내용은 setContent 로 자유롭게 넣을 수 있습니다. 외부 css를 이용할 수 있으며, 
						// - 외부 css에 선언된 class를 이용하면 해당 class의 스타일을 바로 적용할 수 있습니다.
						// - 단, DIV 의 position style 은 absolute 가 되면 안되며, 
						// - absolute 의 경우 autoPosition 이 동작하지 않습니다. 
						mapInfoTestWindow.setContent("<div style='width:250px; background-color:#fff; border:#333 1px solid; padding:11px;'>"
							+"<h4 style='font-weight:bold;'>갤러리 오채</h4>"
							+"<div style='font-size:13px;'>"
							+"<span style='font-weight:bold; font-size:13px;'>서울특별시 송파구 위례성대로 51 (방이동 88-21)<br/>소마미술관 입구 건너편</span>"
							+"<br/><br/>화~일 10:00 ~ 18:00<br/>월요일 휴일<br/><br/>Tel. 02-414-5070"
							+"</div>"
							+"</div>");
						mapInfoTestWindow.setPoint(oTarget.getPoint());
						mapInfoTestWindow.setVisible(true);
						mapInfoTestWindow.setPosition({right : 9, top : 12});
						mapInfoTestWindow.autoPosition();
						return;
				}
		});
	
		var oMarker = new nhn.api.map.Marker(oIcon, { title : '갤러리오채'});
		oMarker.setPoint(oPoint);
		oMap.addOverlay(oMarker);
	});
</script>

<div id = "testMap"></div>