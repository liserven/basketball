<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-09-22
 * Time: 17:36
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\Mysqli\Mysqli;

class MysqlConnection extends Mysqli implements PoolObjectInterface
{
    function gc()
    {
        $this->resetDbStatus();
        $this->getMysqlClient()->close();
    }

    function objectRestore()
    {
        $this->resetDbStatus();
    }

    function beforeUse(): bool
    {
        return $this->getMysqlClient()->connected;
    }
}
