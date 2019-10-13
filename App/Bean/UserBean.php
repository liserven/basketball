<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 14:27
 */

namespace App\Bean;


class UserBean extends BaseBean
{
    public $open_id;

    public $nickname;

    public $logo;

    public $pre_log_time;

    public $status;

    /**
     * @return mixed
     */
    public function getOpenId()
    {
        return $this->open_id;
    }

    /**
     * @param mixed $open_id
     */
    public function setOpenId($open_id)
    {
        $this->open_id = $open_id;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
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
    public function getPreLogTime()
    {
        return $this->pre_log_time;
    }

    /**
     * @param mixed $pre_log_time
     */
    public function setPreLogTime($pre_log_time)
    {
        $this->pre_log_time = $pre_log_time;
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


    public function getUserInfo()
    {

    }

}
