<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-23
 * Time: 09:56
 */

namespace App\HttpController\Api\V1;


use App\Bean\UserBean;
use App\HttpController\Base;
use App\Lib\WxEntData;
use App\Model\UserModel;
use App\Utility\Curl;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use App\Utility\UserToken;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Spl\SplBean;
use function PHPSTORM_META\type;

class User extends Base
{

    public function login() {

        $input = $this->request()->getParsedBody();
        $url = 'api.weixin.qq.com';
        $path = '/sns/jscode2session';
        $params = [
            'appid' => Config::getInstance()->getConf('XCX.appid'),
            'secret' => Config::getInstance()->getConf('XCX.secret'),
            'js_code' => $input['code'],
            'grant_type' => 'authorization_code'
        ];
        $params = http_build_query($params);
        $domain = $path.'?'.$params;
        $client = new Curl();
        $result = $client->get($url, $domain, 443);
        $result = json_decode($result, true);
        $wxBiz = new WxEntData(Config::getInstance()->getConf('XCX.appid'), $result['session_key']);
        $errorCode = $wxBiz->decryptData($input['encryptedData'], $input['iv'], $data);
        $data = json_decode($data, true);
        if ($errorCode == 0) {
            $openId = $data['openId'];
            $nickname = $data['nickName'];
            $logo = $data['avatarUrl'];

            $userModel = new UserModel(MysqlPool::defer());

            $userModel->getDb()->startTransaction();

            try {
                $userData =  $userModel->findOne([ 'open_id' => $openId]);
                if( !$userData )
                {
                    $userBean = new UserBean();
                    $userBean->setNickname($nickname);
                    $userBean->setLogo($logo);
                    $userBean->setOpenId($openId);
                    $userBean->setPreLogTime(date('Y-m-d H:i:s'));
                    $userModel->add($userBean->toArray(null, SplBean::FILTER_NOT_NULL));
                    $userData = $userModel->findOne([ 'open_id' => $openId]);
                }
                $redis = RedisPool::defer();
                $key = UserToken::getInstance()->getToken($userData['id']);
                $redis->set($key, json_encode($userData));
                $userModel->getDb()->commit();
                return $this->returnJson('succ' , [
                    'token' => $key,
                    'nickname' => $nickname,
                    'logo' => $logo
                ]);
            }catch ( \Exception $exception ) {
                $userModel->getDb()->rollback();
                Logger::getInstance()->info($exception->getMessage());
            }finally{
                $userModel->getDb()->commit();
            }

        } else {
            print($errorCode . "\n");
        }
    }


    public function getUserInfo()
    {
        $user = $this->checkAuth();
        return $this->returnJson('succ' , $user );
    }

    public function goProposal ()
    {
        return $this->returnJson('感谢您的宝贵意见');
    }

}
