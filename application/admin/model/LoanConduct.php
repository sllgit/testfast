<?php

namespace app\admin\model;

use think\Model;


class LoanConduct extends Model
{

    

    

    // 表名
    protected $name = 'loan_conduct';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];








}
