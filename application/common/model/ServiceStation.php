<?php

namespace app\common\model;

use think\Model;

/**
 * 会员余额日志模型
 */
class ServiceStation Extends Model
{

    // 表名
    protected $name = 'service_station';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];
}
