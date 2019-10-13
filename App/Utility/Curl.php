<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 14:45
 */

namespace App\Utility;


use Swoole\Coroutine\Http\Client;

class Curl
{
    public function common ($method = 'GET', $url , $path ,  $port = 80  , $isSsl = false ,  $params = [] ,  $timeOut = 3.0 )
    {

        $client = new \Swoole\Coroutine\Http\Client($url, $port , $isSsl );
        if( $method == 'GET' )
        {
            $client->get($path);
        }else{
            $client->post($path, $params);
        }
        $result = $client->body;
        $client->close();
        return $result;
    }

    public function get($url, $path , $port = 80 )
    {
        return $this->common('GET', $url, $path, 443, true);
    }

}
