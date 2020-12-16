<?php

namespace app\common\model;

use think\Model;

/**
 * 还款记录
 */
class PaymentLog Extends Model
{

    // 表名
    protected $name = 'payment_log';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];
}