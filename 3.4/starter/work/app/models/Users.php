<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

// @see https://docs.phalcon.io/3.4/db-models/#finding-records
//
// By default, the model Store\Toys\RobotParts will map to the table robot_parts.
//
// 關鍵字 : Filtering Resultsets
//

class Users extends Model
{

	public $id;
	public $name;
	public $email;

    //
    // 使用到的控制項
    //   SignupController
    //   DBController
    // 留意 ! 下列會影響到資料儲存
    //

	// public function initialize()
    // {
	// 	// 預設表單是 小寫，如需要使用 大寫 表單名稱
    //     // $this->setSource('USERS_VIEW'); 
    // }

	// public function afterSave()
    // {
	// 	// $this->di // 無法使用
	// 	$logger = $this->getDI()->get("logger") ;
	// 	$logger->log("after save");
    // }

	// public function validation()
    // {
    //     $validator = new Validation();
    //     $validator->add(
    //         'name',
    //         new InclusionIn(
    //             [
	// 				"message" => "value must be A or B",
    //                 'domain' => [
    //                     'A',
    //                     'B',
	// 					"test"
    //                 ]
    //             ]
    //         )
    //     );
    //     $validator->add(
    //         'email',
    //         new Uniqueness(
    //             [
    //                 'message' => 'email must be unique',
    //             ]
    //         )
    //     );
    //     return $this->validate($validator);
    // }
}
