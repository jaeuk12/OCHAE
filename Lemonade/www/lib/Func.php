<?

class Func {

	public function getRatioSize($sizeW,$sizeH,$maxW,$maxH){
		if($sizeW>$maxW && $sizeH>$maxH){
			if(round($sizeW/$maxW,1)>round($sizeH/$maxH,1)){
				$return['width'] = $maxW;
				$return['height'] = round(($sizeH*$maxW)/$sizeW);
			}
			else {
				$return['width'] = round(($sizeW*$maxH)/$sizeH);
				$return['height'] = $maxH;
			}
		}
		else if($sizeW>$maxW || $sizeH>$maxH){
			if($sizeW>$maxW){
				$return['width'] = $maxW;
				$return['height'] = round(($sizeH*$maxW)/$sizeW);
			}
			else if($sizeH>$maxH){
				$return['width'] = round(($sizeW*$maxH)/$sizeH);
				$return['height'] = $maxH;
			}
		}
		else if($sizeW<=$maxW && $sizeH<=$maxH){
			$return['width'] = $sizeW;
			$return['height'] = $sizeW;
		}

		return $return;
	}

	/**
	 * 한글 자르기
	 *
	 * @param unknown_type $strSrc
	 * @param unknown_type $start
	 * @param unknown_type $end
	 * @return unknown
	 */
	public function ksubstr($strSrc, $start, $end = ""){
		$check1 = strlen($strSrc);
		if($start < 0)
		$start = strlen($strSrc) + $start;

		if($this->IsHangul($strSrc, $start)==1)
		++$start;

		if(!strlen($end))
			return substr($strSrc, $start);
		else {
			if ($end < 0) {
				$pos = $end + strlen($strSrc) -1;

				if($this->IsHangul($strSrc, $pos)==0)
					--$end;
			} else {
				$pos = $end + $start -1;

				if($this->IsHangul($strSrc, $pos)==0)
					--$end;
			}
		}
		if ($check1 > $end) {
			$suffix = "..";
		}
		return substr($strSrc, $start, $end).$suffix;
	}

	public function IsHangul($strSrc, $pos){
		$isHangul = 1;

		for($i=0 ; $i<=$pos ; ++$i)
		{
		if(ord($strSrc[$i]) > 127)
		++$isHangul;
		else
		$isHangul = -1;
		}

		return $isHangul%2;
	}

	/**
	 * stripslashes
	 * @param $str
	 * @return stripslashes string
	 */
	public function stripSlashes($str) {
		return stripslashes($str);
	}

	// &nbsp; 제거. 공백여러개는 공백한개로
	function stripNBSP($str){
		$str = str_replace("&nbsp;"," ",$str);
		$str = preg_replace("/　[　]+/"," ",$str);
		return $this->stripSpace($str);
	}

	// 공백문자 여러개 제거
	function stripSpace($str){
		return preg_replace("/[ ]+/"," ",$str);
	}

	// 원하는 태그 외의 나머지 태그 모두 제거
	function stripHtmlTag($str,$allow='',$allowTableMaxWidth=''){
		$tags = array("!doctype","html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "p", "br", "pre", "font", "hr", "img", "map", "ul", "ol", "menu", "dir", "dl", "center", "blockquote", "strong", "b", "em", "embed", "i", "kbd", "code", "tt", "body", "dfn", "cite", "samp", "var", "sub", "sup", "basepoint", "blink", "u", "a", "address", "table", "tr", "td", "nobr", "wbr", "form", "textarea", "input", "frameset", "noframes", "frame", "img", "div", "tbody", "span", "link", "script", "tont", "object", "param", "area", "iframe", "meta", "script", "style", "!embed", "li", "select", "marquee");

		// 주석 제거
		$str = preg_replace("/<!--[\w\W]*-->/U","",$str);

		// 스크립트 제거
		$str = preg_replace("/<script [\w\W]+<\/script>/iU","",$str);

		// ㅡ. xml 부분제거
		// ㅡ. table width 값 100%로 강제 치환
		// ㅡ. table 안 img width 값이 $allowTableMaxWidth 보다 크면 $allowTableMaxWidth 으로 강제 치환
		$str = preg_replace("/<\?xml[\w\W]*\?>/iU","",$str);
		preg_match_all("/(<table [^\>]*)(width=[\'\"]{0,1}[0-9\%]+[\'\"]{0,1})([^\>]*>)/",$str,$match);
		for($i=0;$i<sizeof($match[0]);$i++){
        	$str = str_replace($match[0][$i],$match[1][$i]." width='100%' ".$match[3][$i],$str);
		}
		preg_match_all("/(<img [^\>]*)(width=[\'\"]{0,1}([0-9]+)[\'\"]{0,1})([^\>]*>)/",$str,$match);
		for($i=0;$i<sizeof($match[0]);$i++){
		        if($match[3][$i]>$allowTableMaxWidth){
		                $str = str_replace($match[0][$i],$match[1][$i]." width='".$allowTableMaxWidth."' ".$match[4][$i],$str);
		        }
		}

		if(preg_match_all("/<[\/]*([^>]*)[\/]*>/",$str,$match)){
			for($i=0;$i<sizeof($match[1]);$i++){
				// 매치된 태그에 대해 공백 구분으로 나눈다 ex. font color='red'
				$tmp = explode(" ",$match[1][$i]);
				if(in_array(strtolower($tmp[0]),$tags)){		// 태그어인지 검사
					if($allow!=""){
						if(!in_array(strtolower($tmp[0]),$allow)){	// 허용태그에 포함안된 태그이면 제거
							$str = preg_replace("/<[\/]*".$tmp[0]."[^>]*[\/]*>/i","",$str);
						}
					}
					else {
						$str = preg_replace("/<[\/]*".$tmp[0]."[^>]*[\/]*>/i","",$str);
					}
				}
			}
		}

		// 자바 스크립트 제거
		$str = preg_replace("/function[\w\W]*{[\w\W]*}/","",$str);
		$str = preg_replace("/(body|td) \{[\w\W]*\}/Ui","",$str);

		return trim($this->stripNBSP($str));
	}

	// 현재 주가 지금 월의 몇번째 주인지 확인
	function getWeek(){
		$firstDayNo = date("w", mktime(0,0,0, date("n"),1,date("Y")));
		$fWeekLast = 6 - $firstDayNo + 1;
		
		$lastDay = date("t");

		// 마지막 날짜에서 첫주 마지막일을 뺀다
		$v1 = $lastDay - $fWeekLast;
		$v2 = ceil($v1/7);
		
		// 전체 주 수
		$totalWeek = $v2 + 1;

		// 오늘날짜
		$cday = date("j");
		
		// 오늘날짜에서 첫주 마지막일을 뺀다
		$remain = $cday - $fWeekLast;
		
		// 음수면 첫주
		if($remain<=0){
			$rs['week'] = "1";
			$rs['first'] = 1;
			$rs['last'] = $fWeekLast;
		}
		else {
			// 아니면 7로 나눈 값을 올림하여 +1 한다
			$rs['week'] = ceil($remain/7) + 1;
			$rs['first_day'] = ($fWeekLast+1) + 7*($rs['week']-2);
			$rs['last_day'] = ($fWeekLast) + 7*($rs['week']-1);
			
			if($rs['last']>$lastDay)
				$rs['last'] = $lastDay;
		}
		
		$rs['year'] = date("Y");
		$rs['month'] = date("n");
		$rs['first_date'] = date("Y-m")."-".str_pad($rs['first_day'],2,'0',STR_PAD_LEFT);
		$rs['last_date'] = date("Y-m")."-".str_pad($rs['last_day'],2,'0',STR_PAD_LEFT);
		
		
		return $rs;
	}
	
	function strcut_utf8($str, $len, $checkmb = false, $tail = '') {
		/**
		 * UTF-8 Format
		 * 0xxxxxxx = ASCII, 110xxxxx 10xxxxxx or 1110xxxx 10xxxxxx 10xxxxxx
		 * latin, greek, cyrillic, coptic, armenian, hebrew, arab characters consist of 2bytes
		 * BMP(Basic Mulitilingual Plane) including Hangul, Japanese consist of 3bytes
		 **/
		preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match); // target for BMP

		$m = $match[0];
		$slen = strlen($str); // length of source string
		$tlen = strlen($tail); // length of tail string
		$mlen = count($m); // length of matched characters

		if ($slen <= $len) return $str;
		if (!$checkmb && $mlen <= $len) return $str;

		$ret = array();
		$count = 0;
		for ($i = 0; $i < $len; $i++) {
			$count += ($checkmb && strlen($m[$i]) > 1) ? 2 : 1;
			if ($count + $tlen > $len) break;
			$ret[] = $m[$i];
		}
		return join('', $ret).$tail;
	}
	
	function getAreaName($areacode){
		$areaname = '';
		
		switch($areacode){
			case 1: $areaname='강원'; break;
			case 2: $areaname='경기'; break;
			case 3: $areaname='경남'; break;
			case 4: $areaname='경북'; break;
			case 5: $areaname='서울'; break;
			case 6: $areaname='전남'; break;
			case 7: $areaname='전북'; break;
			case 8: $areaname='제주'; break;
			case 9: $areaname='충남'; break;
			case 10: $areaname='충북'; break;
			case 11: $areaname='인천'; break;
			case 12: $areaname='부산'; break;
			case 13: $areaname='울산'; break;
			case 14: $areaname='대구'; break;
			case 15: $areaname='광주'; break;
			case 16: $areaname='대전'; break;
		}
		
		return $areaname;
	}
	
	/**
	 * CKEditor 이미지 업로드
	 */
	function ckUpload($path, $fileName){
		if (($_FILES['upload'] == "none") || (empty($_FILES['upload']['name'])) ){
			$message = "파일이 없습니다";
		}
		else if ($_FILES['upload']["size"] == 0){
			$message = "파일크기가 0입니다";
		}
		else if (($_FILES['upload']["type"] != "image/pjpeg") && ($_FILES['upload']["type"] != "image/jpeg") && ($_FILES['upload']["type"] != "image/png") && ($_FILES['upload']["type"] != "image/gif")){
			$message = "이미지 파일이 아닙니다";
		}
		else {
			if(!is_dir($path)){
				mkdir($path);
			}
		
			$path .= "/".date("Ym");
			if(!is_dir($path)){
				mkdir($path);
			}
	
			$fileUpload = Lemon_Instance::getObject("Lemon_FileUpload");
			$fileUpload->setFile($_FILES['upload'], $path."/".$fileName);
			$fileUpload->setOverwrite();
			$fileUpload->upload();
			$savefile =$fileUpload->getSaveFile();
			$savefile = str_replace($_SERVER['DOCUMENT_ROOT'], "", $savefile);
		}
		
		$funcNum = $_GET['CKEditorFuncNum'] ;
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$savefile', '$message');</script>";
	}
	
	function isValidEmail($email){
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		if (preg_match($regex, $email)) {
			return true;
		} else {
			return false;
		}
	}
	
	function xmlToArray($xml, $options = array()) {
	    $defaults = array(
	        'namespaceSeparator' => ':',//you may want this to be something other than a colon
	        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
	        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
	        'autoArray' => true,        //only create arrays for tags which appear more than once
	        'textContent' => '$',       //key used for the text content of elements
	        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
	        'keySearch' => false,       //optional search and replace on tag and attribute names
	        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
	    );
	    $options = array_merge($defaults, $options);
	    $namespaces = $xml->getDocNamespaces();
	    $namespaces[''] = null; //add base (empty) namespace
	 
	    //get attributes from all namespaces
	    $attributesArray = array();
	    foreach ($namespaces as $prefix => $namespace) {
	        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
	            //replace characters in attribute name
	            if ($options['keySearch']) $attributeName =
	                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
	            $attributeKey = $options['attributePrefix']
	                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
	                    . $attributeName;
	            $attributesArray[$attributeKey] = (string)$attribute;
	        }
	    }
	 
	    //get child nodes from all namespaces
	    $tagsArray = array();
	    foreach ($namespaces as $prefix => $namespace) {
	        foreach ($xml->children($namespace) as $childXml) {
	            //recurse into child nodes
	            $childArray = Func::xmlToArray($childXml, $options);
	            list($childTagName, $childProperties) = each($childArray);
	 
	            //replace characters in tag name
	            if ($options['keySearch']) $childTagName =
	                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
	            //add namespace prefix, if any
	            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
	 
	            if (!isset($tagsArray[$childTagName])) {
	                //only entry with this key
	                //test if tags of this type should always be arrays, no matter the element count
	                $tagsArray[$childTagName] =
	                        in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
	                        ? array($childProperties) : $childProperties;
	            } elseif (
	                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
	                === range(0, count($tagsArray[$childTagName]) - 1)
	            ) {
	                //key already exists and is integer indexed array
	                $tagsArray[$childTagName][] = $childProperties;
	            } else {
	                //key exists so convert to integer indexed array with previous value in position 0
	                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
	            }
	        }
	    }
	 
	    //get text content of node
	    $textContentArray = array();
	    $plainText = trim((string)$xml);
	    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
	 
	    //stick it all together
	    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
	            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
	 
	    //return node as array
	    return array($xml->getName() => $propertiesArray);
	}
}
?>