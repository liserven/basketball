<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-04
 * Time: 11:21
 */

namespace App\HttpController\Api\V1;


use App\Bean\ActivityBean;
use App\Bean\ActivityJoinBean;
use App\HttpController\Base;
use App\Model\ActivityJoinModel;
use App\Model\ActivityModel;
use App\Model\UserModel;
use App\Utility\Pool\MysqlPool;
use App\Validate\ActivityValidate;
use App\Validate\IdMustValidate;
use EasySwoole\Log\Logger;

class Activity extends Base
{

    public function index()
    {
        parent::index(); // TODO: Change the autogenerated stub
    }

    //获取活动列表
    public function getList ()
    {
        $model = new ActivityModel(MysqlPool::defer());
        $input = $this->request()->getQueryParams();
        $limit = isset($input['limit']) && $input['limit'] > 0 ? $input['limit'] : 10;
        $page = isset($input['page'])  && $input['page'] > 0 ? $input['page'] : 1;
        $where = [];
        $join = [];
        $groupBy = '';
        $orderBy = 'activity.id DESC';
        if( isset($input['module']) && $input['module'] == 1 )
        {
            //我发布的
            $user = $this->checkAuth();
            $where ['activity.user_id'] = $user['id'];
        }elseif ( isset($input['module']) && $input['module'] == 2 ){
            //我参与的
            $user = $this->checkAuth();
            $join = [
                'activity_join as aj' => [
                    'aj.activity_id = activity.id', 'LEFT'
                ]
            ];
            $where = [ 'aj.user_id' => $user['id'] ];
            $orderBy = 'aj.id DESC';
        }
        $data = $model->findAll($where, 'activity.*', ( $page-1)*$limit , $limit, $join, $orderBy, $groupBy);
        if($data){
            foreach ( $data as $k => &$val )
            {
                $val['is_join'] = 2;
                $token = $this->request()->getHeader('token');
                if( $token[0] )
                {
                    $user = $this->checkAuth();
                    $isToJoin = $model->getDb()->where('user_id', $user['id'])->where('activity_id', $val['id'])->getOne('activity_join');
                    $val['is_join'] = $isToJoin ? 1 :2 ;
                }
                $val['join_count'] = $model->getDb()->where('activity_id', $val['id'])->count('activity_join');
                $val['user'] = $model->getDb()->where('id', $val['user_id'])->getOne('user');

            }
        }
        return $this->returnJson('succ', $data);
    }

    //创建活动
    public function create()
    {
        $user = $this->checkAuth();
        (new ActivityValidate())->goCheck();
        $input = $this->request()->getParsedBody();
        $activityBean = new ActivityBean($input);
        $activityBean->setUserId($user['id']);
        $activityBean->add();
        return $this->returnJson('发布成功' , []);
    }


    //获取活动详情
    public function getActivityById ()
    {
        (new IdMustValidate())->goCheck();
        $input = $this->request()->getQueryParams();
        $model = new ActivityModel(MysqlPool::defer());
        $result = $model->findOne([ 'id' => $input['id']]);
        $result['join_count'] = $model->getDb()->where('activity_id', $result['id'])->count('activity_join');
        $result['user'] = $model->getDb()->where('id', $result['user_id'])->getOne('user');
        $result['c_count'] = $model->getDb()->where('user_id', $result['user_id'])->count('activity');
        $result['is_join'] = 2;
        $token = $this->request()->getHeader('token');
        if( $token[0] )
        {
            $user = $this->checkAuth();
            $isToJoin = $model->getDb()->where('user_id', $user['id'])->where('activity_id', $result['id'])->getOne('activity_join');
            $result['is_join'] = $isToJoin ? 1 :2 ;
        }
        return $this->returnJson('succ', $result);
    }


    //加入活动
    public function joinActivity ()
    {
        $user = $this->checkAuth();
        (new IdMustValidate())->goCheck();
        $input = $this->request()->getParsedBody();
        $model = new ActivityModel(MysqlPool::defer());
        $joinModel = new ActivityJoinModel(MysqlPool::defer());
        $model->getDb()->startTransaction();
        try{
            $activityData = $model->findOne([ 'id' => $input['id']]);  //查活动详情
            if( !$activityData )
            {
                return $this->returnJson('该活动已不存在', [], false);
            }
            $joinCount = $joinModel->getJoinCount($activityData['id']);
            if( $joinCount >= $activityData['max_num'] )
            {
                return $this->returnJson('活动人数已满', [], false);
            }
            $joinData = $joinModel->findOne([ 'activity_id' => $activityData['id'], 'user_id' => $user['id']]);
            if( $joinData )
            {
                return $this->returnJson('您已参加该活动', [], false );
            }

            $joinBean = new ActivityJoinBean();
            $joinBean->setActivityId($activityData['id']);
            $joinBean->setUserId($user['id']);
            $joinModel->add($joinBean->toArray(null, $joinBean::FILTER_NOT_NULL));
            $model->getDb()->commit();
            return $this->returnJson('加入成功');
        }catch (\Exception $exception){
            $model->getDb()->rollback();
            \EasySwoole\EasySwoole\Logger::getInstance()->info($exception->getMessage());
            return $this->returnJson('系统繁忙', [], false);
        }finally{
            $model->getDb()->commit();
        }

    }
}
