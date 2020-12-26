<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Applyedit extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'apply_edit';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function admin()
    {
        return $this->belongsTo('app\admin\model\Admin', 'apply_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
    public function admins()
    {
        return $this->belongsTo('app\admin\model\Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
