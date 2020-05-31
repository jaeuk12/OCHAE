<?
/*
* HTML 로 에러를 표시해주는 HtmlException
*/

class Lemon_HtmlException extends Exception
{
	var $comment;

   public function __construct($message, $comment='', $code = 0) {
	   $this->comment = $comment;
	   parent::__construct($message, $code);
   }

   public function __toString() {
		$tpl = new Template_;
		if ($this->code == '404' ) {
			header('HTTP/1.0 404 Not Found');
			$tpl->define(array('index' => "404.html"));
		}else {
			$tpl->define(array('index' => "error.html"));
		}
		
		$tpl->assign(array(
			'ERROR_MSG' => $this->message,
			'COMMENT' => $this->comment
		));
		return $tpl->print_('index').'';
   }
}

?>