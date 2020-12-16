<?php

namespace app\api\model;

use think\Db;
use think\Model;
use app\api\model\Admin;

class ServiceStation Extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 三级服务站体系数据查询
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getServiceCount()
    {
        //服务站统计
        $list = self::field('id,type,count(1) as counts')
            ->where(['status'=>1,'type'=>['<',3]])
            ->group('type')
            ->select();
        $list=collection($list)->toArray();
        $list = array_column($list,'counts','type');
        $county = isset($list['0']) ? $list['0'] : 0 ;
        $town = isset($list['1']) ? $list['1'] : 0 ;
        $village = isset($list['2']) ? $list['2'] : 0 ;
        //服务站人员统计
        $service_ids = self::where(['status'=>1,'type'=>['<',3]])->column('id');
        $admincount = Admin::where(['status'=>'normal','service_id'=>['in',$service_ids]])->count();

        return ["county"=>$county,"town"=>$town,"village"=>$village,"admincount"=>$admincount];
    }

}