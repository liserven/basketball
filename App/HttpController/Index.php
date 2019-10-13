<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 19:43
 */

namespace App\HttpController;

use App\Bean\AuditText;
use App\Bean\XyqAuditBean;

class Index extends Base
{
    public function index()
    {
        $this->response()->write(111);
    }
}
