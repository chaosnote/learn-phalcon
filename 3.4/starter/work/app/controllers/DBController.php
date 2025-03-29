<?php

use Phalcon\Db\Adapter\Pdo\Factory;

// @see https://docs.phalcon.io/3.4/db-layer/#connecting-using-factory
// @see https://docs.phalcon.io/3.4/api/Phalcon_Db/

class DBController extends BaseController
{

	private $options = [
		'host'     => 'mariadb',
		'dbname'   => 'simulate',
		'port'     => 3306,
		'username' => 'chris',
		'password' => '123456',
		'adapter'  => 'mysql',
	];

	public function indexAction() {}

	/* 
	path : /factory 
	*/
	public function factoryAction()
	{
		$factory_db = Factory::load($this->options);
		$users = $factory_db->fetchAll("select * from users");

		$length = count($users); // array length
		echo $length . "<br/>";

		foreach ($users as $user) {
			// $this->logger->debug("ID: ".$user['name']) ;
			echo "ID: " . $user['name'] . "<br/>";
		}

		// 其餘方式
		// 使用 ini 檔
	}

	/* 
	path : /findingRows 
	*/
	public function findingRowsAction()
	{
		$sql = "select * from users";

		$result = $this->db->query($sql);
		while ($user = $result->fetch()) {
			echo $user['name'] . "<br/>";
			echo $user['email'] . "<br/>";
		}
	}

	/*
	path : /bindingParameters
	*/
	public function bindingParametersAction()
	{
		// 同 Golang
		$sql = "select * from users where `name` = ?";

		// 命名概念相同 exe.. 返回是否成功
		$result = $this->db->query($sql, ["chris"]);
		while ($user = $result->fetch()) {
			echo $user['name'] . "<br/>";
			echo $user['email'] . "<br/>";
		}
	}

	//
	// Typed placeholders
	// 

	//
	// @see https://docs.phalcon.io/3.4/db-layer/#insertingupdatingdeleting-rows
	// Inserting/Updating/Deleting Rows
	//


	/* 
	path : /insertAsDict
	*/
	public function insertAsDictAction()
	{
		$date = date("Hs");

		$success = $this->db->insertAsDict(
			'users',
			[
				'name' => $date,
				'email' => "xxx@email.com"
			]
		);

		echo $success;
	}

	//
	// Profiler 觀測部份(暫時不需要、除非有效能問題)
	//

	//
	// 以下表單建立方式(load sql execute 會比較順手)
	//

	//
	// PHQL
	// 概念同 Model 並增加擴充方式
	//

	//
	// ODM
	// 對應 MongoDB 或是 Firebase 這類
	// 

	/* 
	path : /model
	*/
	public function modelAction()
	{
		/** @var Users $user */
		$user = Users::findFirst(6); // 當指定 id 不存在時，會產生錯誤、需做驗證
		if ($user == null){
			echo "underfind" ;
			return ;
		}
		$user->name = "test" ;
		$user->email = "test@gmail.com" ;
		$user->save() ;
		echo "update<br/>" ;

		if ($user->validation()){
			echo "=>True<br/>" ;
			return ;
		}
		echo "=>False<br/>" ;

		
		// var_dump($user) ;
	}

	//
	// Phalcon ORM
	// 	Query Builder => struct when execute
	//
	// $robots = Robots::query()
    // ->where('type = :type:')
    // ->andWhere('year < 2000')
    // ->bind(['type' => 'mechanical'])
    // ->order('name')
    // ->execute();


	//
	// Dynamic Updates
	// 即使只修改了模型中的一個欄位，也會更新資料庫中該記錄的所有欄位
	//

	//
	// Record Snapshots
	// 網站那些資料需重複使用
	//

	//
	// multiple databases
	// 除非資料來源不相同、不然直接 haproxy + bitnami/mariadb 實在些
	//

	// 
	// Cache
	//


	//
	// Model Events
	//	例: 
	//		日期字串 轉換 TimeStamp ，再轉至另一欄位
	//		註冊成功、觸發郵件寄送
	//

	//
	// Models Metadata
	//

	//
	// Model Relationships
	//	類似 SQL join 、未確定效能
	//

	//
	// Transactions
	//	$this->db->begin();  // 開始
	//	$this->db->commit(); // 送出
	//
	//	$this->db->rollback(); //  例外=>回滾
	//


}
