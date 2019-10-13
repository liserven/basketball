<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-03
 * Time: 14:00
 */

namespace App\Validate;


use EasySwoole\Validate\Validate;

class CourtValidate extends BaseValidate
{
    protected function run(Validate $validate)
    {

        $validate->addColumn('name' , '名字不能为空')
            ->required('名字不能为空')
            ->lengthMax(20, '名称长度不能超过20位')
            ->lengthMin(5, '名称最小不能小于五个字');
        $validate->addColumn('logo', '球场图片不能为空')->required('球场图片不能为空')->notEmpty('球场图片不能为空')->url('图片地址不合法');
        $validate->addColumn('address', '球场地址不能为空')->required('球场地址不能为空')->notEmpty('球场地址不能为空');
        $validate->addColumn('lat', '球场经度为空')->required('球场经度为空')->notEmpty('球场经度为空')->decimal(null, '经度格式错误');
        $validate->addColumn('long', '球场维度为空')->required('球场维度为空')->notEmpty('球场维度为空')->decimal(null, '维度格式错误');
//        $validate->addColumn('message', '球场描述为空')->required('球场描述为空')->notEmpty('球场描述为空');
        $validate->addColumn('is_money', '球场是否收费？')->required('球场是否收费？')->notEmpty('球场是否收费？');
        $validate->addColumn('money')->integer('收费价格错误');
        return $validate;
    }
}
