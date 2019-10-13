<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-02
 * Time: 21:53
 */

namespace App\Validate;


use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Validate\Validate;

abstract class BaseValidate extends Validate
{

    public function goCheck ( $params = [] )
    {
        if( empty( $params) )
        {
            $request = ContextManager::getInstance()->get('Request');
            $params = $request->getRequestParam();
        }

        $validate = new Validate();
        $validateColumn = $this->run($validate);
        $checkResult = $validateColumn->validate($params);
        if( $checkResult !== true )
        {
            throw new \Exception($validate->getError()->__toString());
        }
    }

    protected function run ( Validate $validate ) {}

}
