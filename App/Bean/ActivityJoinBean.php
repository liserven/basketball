<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 14:26
 */

namespace App\Bean;


class ActivityJoinBean extends BaseBean
{
    public $user_id;

    public $activity_id;

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
    public function getActivityId()
    {
        return $this->activity_id;
    }

    /**
     * @param mixed $activity_id
     */
    public function setActivityId($activity_id)
    {
        $this->activity_id = $activity_id;
    }


}
