<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-10-03
 * Time: 14:25
 */

namespace App\Validate;


use EasySwoole\Validate\Validate;

class ActivityValidate extends BaseValidate
{
    protected function run(Validate $validate)
    {
        $validate->addColumn('title', '标题不能为空')->notEmpty('标题不能为空')
        ->lengthMax(100, '标题过长')
        ->lengthMin(6, '标题过短')
        ->required('标题必须');
        $validate->addColumn('logo' , '活动图片必须')
            ->url('图片格式错误')
            ->required('图片必须')->notEmpty('图片不能为空');
        $validate->addColumn('is_money', '收费标准不能为空')->required('收费标准不能为空')
            ->notEmpty('收费标准不能为空')->integer('收费格式错误')
            ->max(100000000, '价格过高')->min(0, '价格应该大于0元');
        $validate->addColumn('address', '球场地址不能为空')->required('球场地址不能为空')->notEmpty('球场地址不能为空');
        $validate->addColumn('lat', '球场经度为空')->required('球场经度为空')->notEmpty('球场经度为空')->decimal(null, '经度格式错误');
        $validate->addColumn('long', '球场维度为空')->required('球场维度为空')->notEmpty('球场维度为空')->decimal(null, '维度格式错误');
        $validate->addColumn('message', '球场描述为空')->required('球场描述为空')->notEmpty('球场描述为空');
        $validate->addColumn('max_num')->min(0, '限定人数过少')->max(100 , '限定人数过多' )->required('请限定人数')->integer('限定人数内容格式错误')
            ->notEmpty('限定人数参数必须');
        return $validate;
    }
}
