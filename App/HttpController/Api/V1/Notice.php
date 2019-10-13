<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-13
 * Time: 11:01
 */

namespace App\HttpController\Api\V1;


use App\HttpController\Base;
use App\Model\NoticeModel;

class Notice extends Base
{
    public function getNotice()
    {
        $user = $this->checkAuth();
        $result = NoticeModel::getInstance()->getNoticeByUserId($user['id']);
        return $this->returnJson('succ' , $result);
    }
}
