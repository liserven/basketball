<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-13
 * Time: 11:15
 */

namespace App\Model;


class NoticeModel extends ApiModel
{
    protected $table = 'notice';

    public function getNoticeByUserId( int $userId ) :array
    {
        return $this->findAll([ 'user_id' => $userId ]);
    }

}
