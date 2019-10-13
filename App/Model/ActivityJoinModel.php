<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-04
 * Time: 11:32
 */

namespace App\Model;


class ActivityJoinModel extends BaseModel
{
    protected $table = 'activity_join';


    public function getJoinCount($activityId)
    {
        $result = $this->getDb()->where( 'id' , $activityId , '=')->count($this->table);
        return $result;
    }


}
