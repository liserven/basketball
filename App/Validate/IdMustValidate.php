<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-03
 * Time: 14:33
 */

namespace App\Validate;


use EasySwoole\Validate\Validate;

class IdMustValidate extends BaseValidate
{
    protected function run(Validate $validate)
    {
        $validate->addColumn('id', '参数错误')->notEmpty('参数必须')
            ->integer('参数类型错误')->required('参数缺失');
        return $validate;
    }
}
