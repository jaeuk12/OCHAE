<?
class Lemon_Email{
	/*
	 * 일반적인 메일 전송 - 화이트 도메인으로 등록 안되어 있을 경우 다음, 구글 등 특정 메일로는 전송이 안된다
	 */
	function send($from, $to, $subject, $mail_body, $header="") {
		if($header == ""){
			$header = "MIME-Version: 1.0\r\nContent-type: text/html;charset=UTF-8\r\nFrom: $from\r\n";
		}
		
		$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		
		if(mail($to, $subject, $mail_body, $header, '-f'.$from))
			return true;
		else
			return false;
	}
}
?>
