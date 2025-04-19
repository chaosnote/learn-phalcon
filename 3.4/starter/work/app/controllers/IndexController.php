<?php

class IndexController extends BaseController
{

	/**
	 * @Route("/index")
	 */
	public function indexAction() {
		$this->logger->debug("enter index");
	}

	/**
	 * @Route("/index/elk")
	 */
	public function elkAction() {
		$this->logger->debug("enter elk");
		$this->Debug([
			"user_id"=>"abc",
			"user_pw"=>"123456"
		]) ;
		$this->Info([
			"user_id"=>"abc",
			"user_pw"=>"123456"
		]) ;
	}

	/**
	 * @Route("/index/find")
	 */
	public function findAction()
	{
		// view -> 變數 -> 傳遞至頁面
		$this->view->users = Users::find();

		// 取得 id 為 1 的使用者
		// $user = Users::findFirst(1);

		// 範例:
		// 測試單筆與過濾規則
		// $this->view->guest = Users::findFirst(['name'=>'chris']) ;
		// 則可在畫面中使用
		// <?php echo $guest->email ;

		// 測試:
		// $users = $this->db->fetchAll("select * from users") ;
		// foreach ($users as $user) {
		// 	$this->logger->debug("ID: ".$user['name']) ;
		// }
	}

	/**
	 * @Route("/index/walk")
	 * @see https://docs.phalcon.io/3.4/routing/#routing
	 *    動態增加路徑
	 */
	public function walkAction()
	{
		$routes = $this->router->getRoutes();

		// 不好處理… 連結範例則只能參考 mix array
		foreach ($routes as $route) {
			$paths = $route->getPaths();
			echo "===== =====<br/>";
			var_dump($paths);
			echo "<br/>";
		}
	}
}
