<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 18:23
 */

namespace App\Utility;


use EasySwoole\Component\Singleton;

class UserToken
{

    use Singleton;

    public function getToken ($key)
    {
        $str = uniqid() . $key . rand(1000, 9999);
        $sign = hash('sha256', $str);
        return $sign;
    }

}
