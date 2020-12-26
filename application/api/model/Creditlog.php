<?php

namespace app\api\model;

use think\Model;


class Creditlog extends Model
{

    

    

    // 表名
    protected $name = 'creditlog';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







}
