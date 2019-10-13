<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-09-22
 * Time: 17:33
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Redis\Config\RedisConfig;

class RedisPool extends AbstractPool
{
    /**
     * 创建redis连接池对象
     * @return bool
     */
    protected function createObject() :RedisObject
    {
        // TODO: Implement createObject() method.
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        $config = new RedisConfig([
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);
//        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new RedisObject($config);
//        $connected = $redis->connect(3.0);
        if($redis){
            if(!empty($conf['auth'])){
                $redis->auth($conf['auth']);
            }
            //选择数据库,默认为0
            if(!empty($conf['db'])){
                $redis->select($conf['db']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}
