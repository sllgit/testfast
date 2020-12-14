<?php

namespace app\admin\model\loan;

use think\Model;


class User extends Model
{

    

    

    // 表名
    protected $name = 'loan_user_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    function getbankinfo()
    {
        $bank_list = \db('bank')->field('id,bank_name')->where(['status'=>1])->select();
        $bank_data = [];
        foreach ($bank_list as $v){
            $bank_data[$v['id']] = $v['bank_name'];
        }
        return $bank_data;
    }







}
