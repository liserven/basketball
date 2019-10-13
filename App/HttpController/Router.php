<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:08
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Message\Status;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
    public function initialize(RouteCollector $routeCollector)
    {
        // TODO: Implement initialize() method.
        $routeCollector->addGroup('/api/v1', function (RouteCollector $routeCollector) {



            $routeCollector->post('/login', '/Api/V1/User/login');  //登录
            $routeCollector->get('/text', '/Api/V1/Index/test');  //登录
            $routeCollector->post('/createt_court', '/Api/V1/Court/create'); //发布球场
            $routeCollector->get( '/get_court_list', '/Api/V1/Court/getList' );
            $routeCollector->post( '/upload_img', '/Api/V1/Upload/uploadImg' );


            //活动
            $routeCollector->get( '/get_activity_list', '/Api/V1/Activity/getList' );
            $routeCollector->get( '/get_activity_detail', '/Api/V1/Activity/getActivityById' );
            $routeCollector->post( '/create_activity', '/Api/V1/Activity/create' );
            $routeCollector->post( '/join_activity', '/Api/V1/Activity/joinActivity' );


            //用户
            $routeCollector->post( '/sub_proposal', '/Api/V1/User/goProposal' );


            //通知
            $routeCollector->get( '/get_user_notice', '/Api/V1/Notice/getNotice' );


        });


        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
            $response->write('未找到处理方法');
            return false;//结束此次响应
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
            $response->write('未找到路由匹配');
            return 'index';//重定向到index路由
        });
        $this->setGlobalMode(false);
    }
}
