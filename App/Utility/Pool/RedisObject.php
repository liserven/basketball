<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-09-22
 * Time: 17:34
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\Redis\Redis;

class RedisObject extends Redis implements PoolObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
        $this->disconnect();
    }

    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }

    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }
}
