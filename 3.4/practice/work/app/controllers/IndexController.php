<?php
use \Utils\ExtraFunc ;
interface CustomDefine {
    const VERSION = 'version.0.0.0';
}

// PHP 7.3 才支援常數
trait custom_map{
	protected function getHost(){
		return array(
			"00" => "abc"
		) ;
	}
}

class IndexController extends BaseController implements CustomDefine
{
	use custom_map ;
	/**
	 * @Route("/index")
	 */
	public function indexAction() {
		$logger = ExtraFunc::GenLogger() ;
		$logger->debug("enter index");

		echo self::VERSION."<br>" ;
		echo var_dump($this->getHost())."<br>";
		echo (empty("") ? "Empty" : "Value")."<br>";
	}

}
