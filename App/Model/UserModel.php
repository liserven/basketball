<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-01
 * Time: 19:54
 */

namespace App\Model;


class UserModel extends BaseModel
{
    protected $table = 'user';
    const LOGIN_TOU = 40004;
    const STATUS_EXCEPTION = 40005;

}
