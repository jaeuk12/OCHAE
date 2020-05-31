<?
/*
* 여러 필요한 메소드 모음 클래스
*/
class Lemon_Func {
	
	/** 
	 * 국내/국외 IP 체크  	
	 */	
	public static function countryCode() {
		// IP 조회 API 신청
		// http://whois.kisa.or.kr/kor/whois/openAPI_KeyCre.jsp
		
		$ip = Lemon_Func::remoteIp() ; 
		$url = "http://whois.kisa.or.kr/openapi/whois.jsp?query=$ip&key=2014081319581860981760&answer=json" ;
		$ch = curl_init(); //파라미터:url -선택사항
		curl_setopt($ch,CURLOPT_URL,$url); //여기선 url을 변수로
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_NOSIGNAL, 1);
		//	curl_setopt($ch,CURLOPT_POST, 1);   //Method를 POST로 지정.. 이 라인이 아예 없으면 GET
		$data 		= curl_exec($ch);	
		
		$json =    json_decode($data, true) ;		
		if ( $json['whois']['countryCode'] == "KR" ) return "ko" ;
		else return "en" ;		
		/* 		
	 		$data = file_get_contents($url);
			$json =    json_decode($data, true) ;
			echo "*".  $json['whois']['countryCode'] . "*"   ;		
	 	*/
	}
	
	public static function remoteIp(){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}		
		return $_SERVER['REMOTE_ADDR']  ;
	}
	
	
	
	public static function get_time() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	/*
	* 파일 사이즈등을 읽기 쉬운 Kbyte, Mbyte 로 변환
	*/
	public static function formatSize($val, $digits = 3, $mode = 'SI', $bB = 'B'){
        $si = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        $iec = array('', 'Ki', 'Mi', 'Gi', 'Ti', 'Pi', 'Ei', 'Zi', 'Yi');
        switch(strtoupper($mode)) {
            case 'SI' : $factor = 1000; $symbols = $si; break;
            case 'IEC' : $factor = 1024; $symbols = $iec; break;
            default : $factor = 1000; $symbols = $si; break;
        }
        switch($bB) {
            case 'b' : $val *= 8; break;
            default : $bB = 'B'; break;
        }
        for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
            $val /= $factor;
        $p = strpos($val, ".");
        if($p !== false && $p > $digits) $val = round($val);
        elseif($p !== false) $val = round($val, $digits-$p);
        return round($val, $digits) . " " . $symbols[$i] . $bB;
    }

	/*
	* 브라우저 종류를 알아냄
	*/
	public static function getBrowserKind()
	{
		$sAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		
		if(strstr($sAgent, 'msie')){
			if(strstr($sAgent, "msie 6.0")){
				$sAgent = 'ie6';
			}elseif(strstr($gSbrver, "msie 7.0")){
				$sAgent = 'ie7';
			}else{
				$sAgent = 'ie';
			}
		}elseif(strstr($sAgent, 'konqueror') || strstr($sAgent, 'safari')){
			$sAgent = 'safari';
		}elseif(strstr($sAgent, 'firefox')){
			$sAgent = 'firefox';
		}elseif(strstr($sAgent, 'opera')){
			$sAgent = 'opera';
		}

		return $sAgent;
	}
	
	public static function array_sort($array, $on, $order=SORT_ASC)
		{
		    $new_array = array();
		    $sortable_array = array();
		
		    if (count($array) > 0) {
		        foreach ($array as $k => $v) {
		            if (is_array($v)) {
		                foreach ($v as $k2 => $v2) {
		                    if ($k2 == $on) {
		                        $sortable_array[$k] = $v2;
		                    }
		                }
		            } else {
		                $sortable_array[$k] = $v;
		            }
		        }
		
		        switch ($order) {
		            case SORT_ASC:
		                asort($sortable_array);
		            break;
		            case SORT_DESC:
		                arsort($sortable_array);
		            break;
		        }
		
		        foreach ($sortable_array as $k => $v) {
		            $new_array[$k] = $array[$k];
		        }
		    }
		
		    return $new_array;
	}
}

?>
