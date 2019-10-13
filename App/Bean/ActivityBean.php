<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 14:01
 */

namespace App\Bean;


use App\Model\ActivityModel;
use App\Utility\Pool\MysqlPool;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Spl\SplBean;

class ActivityBean extends SplBean
{
    protected $id;

    protected $user_id;

    protected $title;

    protected $message;

    protected $is_money;

    protected $money;

    protected $start_time;

    protected $join_end_time;

    protected $max_num;

    protected $order;

    protected $status;

    protected $logo;

    protected $address;

    protected $lat;

    protected $long;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
    public function getMoeny()
    {
        return $this->moeny;
    }

    /**
     * @param mixed $moeny
     */
    public function setMoeny($moeny)
    {
        $this->moeny = $moeny;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return mixed
     */
    public function getJoinEndTime()
    {
        return $this->join_end_time;
    }

    /**
     * @param mixed $join_end_time
     */
    public function setJoinEndTime($join_end_time)
    {
        $this->join_end_time = $join_end_time;
    }

    /**
     * @return mixed
     */
    public function getMaxNum()
    {
        return $this->max_num;
    }

    /**
     * @param mixed $max_num
     */
    public function setMaxNum($max_num)
    {
        $this->max_num = $max_num;
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


    public function add ()
    {
        $model = new ActivityModel(MysqlPool::defer());
        $model->getDb()->startTransaction();
        try{
            $model->add($this->toArray(null, $this::FILTER_NOT_NULL));
            var_dump($this->toArray(null, $this::FILTER_NOT_NULL));
            $model->getDb()->commit();
            return true;
        }catch (\Exception $exception ){
            $model->getDb()->rollback();
            Logger::getInstance()->info($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }finally{
            $model->getDb()->commit();
        }
    }

    public function getDetailById ( )
    {
        $model = new ActivityModel(MysqlPool::defer());
        $result = $model->findOne([ 'id' => $this->getId()]);
        $result['join_count'] = $model->getDb()->where('activity_id', $result['id'])->count('activity_join');
        $result['user'] = $model->getDb()->where('id', $result['user_id'])->getOne('user');
        $result['c_count'] = $model->getDb()->where('user_id', $result['user_id'])->count('activity');
        $result['is_join'] = 2;
        $token = $this->request()->getHeader('token');
        if( $token )
        {
            $user = $this->checkAuth();
            $isToJoin = $model->getDb()->where('user_id', $user['id'])->where('activity_id', $result['id'])->getOne('activity_join');
            $result['is_join'] = $isToJoin ? 1 :2 ;
        }
        return $result;
    }

}
