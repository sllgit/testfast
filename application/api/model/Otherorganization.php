<?php

namespace app\api\model;

use think\Model;

class Otherorganization Extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;


    /**
     * 获取其他机构数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOtherOrganization()
    {
        $list = self::field('type,count(1) as counts')->where(['status'=>1])
            ->group('type')
            ->select();
        $list = collection($list)->toArray();
        foreach ($list as $k => $v){
            if ($v['type'] == 1){
                $list[$k]['lavel'] = '合作社';
            }elseif ($v['type'] == 2){
                $list[$k]['lavel'] = '产业扶贫基地';
            }if ($v['type'] == 3){
                $list[$k]['lavel'] = '产业扶贫大棚';
            }
            unset($list[$k]['type']);
        }
        return $list ?? [];
    }

    /**
     * 乡镇 其他机构数据 统计
     * @return array
     */
    public static function getcountryList()
    {
        $area = db('areas')->where(['pid'=>1,'status'=>1])->column('name');
        $otherorganization = db('otherorganization')->field('country,type')->where(['status'=>1])->select();
        $data = [];
        foreach ($area as $k => $v){
            $data[$k]['lavel'] = $v;
            $data[$k]['hzs'] = 0;
            $data[$k]['cyfpjd'] = 0;
            $data[$k]['cyfpdp'] = 0;
            foreach ($otherorganization as $kk => $vv){
                if ($v == $vv['country']){
                    if ($vv['type'] == 1){
                        $data[$k]['hzs'] ++;
                    }elseif ($vv['type'] == 2){
                        $data[$k]['cyfpjd'] ++;
                    }elseif ($vv['type'] == 3){
                        $data[$k]['cyfpdp'] ++;
                    }
                }
            }
        }
        return $data;
    }

}