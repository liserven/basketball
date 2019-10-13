<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:09
 */

namespace App\HttpController\Api\V1;


use App\HttpController\Base;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;

class Index extends Base
{
    public function test()
    {
         $redisConfig = new RedisConfig([
             'host' => '127.0.0.1',
             'port' => 6379,
         ]);

         $redis = new \EasySwoole\Redis\Redis($redisConfig);
         $result = $redis->geoHash('court', 'nanyijie');
         $this->returnJson('ok', $result);

    }
}
