<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:09
 */

namespace App\HttpController;


use App\Model\UserModel;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use mysql_xdevapi\Exception;


class Base extends Controller
{

    protected function onException(\Throwable $throwable): void
    {
        $response = ContextManager::getInstance()->get('Response');
        $data = [
            'error_code'        => $throwable->getCode(),
            'msg'               => $throwable->getMessage(),
            'bol'               => false,
            'data'              => [],
        ];
        if( $throwable instanceof  \Exception ){
            //如果异常是自己抛出的
            $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(Status::CODE_OK);
            $this->response()->end();
        }else{
            //否则还抛系统异常
            parent::onException($throwable); // TODO: Change the autogenerated stub

        }
    }

    public function __hook(?string $actionName, Request $request, Response $response)
    {
        $context = ContextManager::getInstance();
        $context->set('Request', $request);
        $context->set('Response', $response);
        return parent::__hook($actionName, $request, $response); // TODO: Change the autogenerated stub
    }

    //统一封装返回接口
    public function returnJson($msg = 'ok', $data = [], $bool= true, $code = 200, $errCode = 0 )
    {
        $data  = [
            'error_code'        => $errCode,
            'msg'               => $msg,
            'bol'               => $bool,
            'data'              => $data,
        ];
        $response = $this->response();
        $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        $response->withStatus($code);
        $this->response()->end();
    }

    protected function checkAuth ()
    {
        $token = $this->request()->getHeader('token');
        $redis = RedisPool::defer();
        $user = $redis->get($token[0]);
        if( !empty($user) )
        {
            $user = json_decode($user, true);
            $userModel = new UserModel(MysqlPool::defer());
            $userData = $userModel->findOne([ 'id' => $user['id'] ]);
            if( $userData['status'] == 2 )
            {
                throw new \Exception('账号被冻结', UserModel::STATUS_EXCEPTION);
            }
            return $userData;
        }else{
            throw new \Exception('login time out' , UserModel::LOGIN_TOU);
        }
    }

    public function index()
    {
        // TODO: Implement index() method.
    }
}
