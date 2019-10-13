<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 14:04
 */

namespace App\Bean;


use App\Lib\Redis\Redis;
use App\Model\CourtModel;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;

class CourtBean extends BaseBean
{

    const REDIS_GEO_NAME = 'court';
    const REDIS_GEO_COURT_NAME_PREFIX = 'court_prefix_';

    protected $id;

    public $name;

    public $order;

    public $status;

    public $logo;

    public $lat;

    public $long;

    public $address;

    public $is_money;

    public $money;

    public $user_id;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * @param mixed $long
     */
    public function setLong($long)
    {
        $this->long = $long;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getisMoney()
    {
        return $this->is_money;
    }

    /**
     * @param mixed $is_money
     */
    public function setIsMoney($is_money)
    {
        $this->is_money = $is_money;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    public function add () {
        $model = new CourtModel(MysqlPool::defer());
        $model->getDb()->startTransaction();
        try{

            $model->add($this->toArray(null, $this::FILTER_NOT_NULL));
            $insertId =$model->getDb()->getInsertId();
            /** @var RedisObject $redis */
            $redis = RedisPool::defer();
            $redis->geoAdd($this::REDIS_GEO_NAME, $this->getLong(), $this->getLat(), $this::REDIS_GEO_COURT_NAME_PREFIX.$insertId);
            $geoHash = $redis->geoHash($this::REDIS_GEO_NAME, $this::REDIS_GEO_COURT_NAME_PREFIX.$insertId);
            $model->updated(['id' => $insertId], [ 'geo_hash' => $geoHash]);
//            $redis = Redis::getInstance()->redis;
//            $r = $redis->rawCommand('geoadd', "court {$this->getLong()} {$this->getLat()} $reuslt");
            $model->getDb()->commit();
        }catch (\Exception $exception) {
            $model->getDb()->rollback();
            throw new \Exception($exception->getMessage());
        }finally{
            $model->getDb()->commit();
        }


    }

}
