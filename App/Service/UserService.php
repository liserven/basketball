<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-04
 * Time: 11:11
 */

namespace App\Service;

use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Singleton;

/**
 * Class UserService
 * @package App\Service
 * 用户业务层
 */
class UserService
{
    use Singleton;

    private $userModel;

    public function __construct()
    {

    }


    public function getUserInfo($uid)
    {

    }
}
