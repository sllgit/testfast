<?php

namespace app\admin\model\loanenterprise;

use think\Model;


class Enterprise extends Model
{

    

    

    // 表名
    protected $name = 'loan_enterprise_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    /**
     * 获取银行列表
     * @return array
     */
    function getbankinfo()
    {
        $bank_list = \db('bank')->field('id,bank_name')->where(['status'=>1])->select();
        $bank_data = [];
        foreach ($bank_list as $v){
            $bank_data[$v['id']] = $v['bank_name'];
        }
        return $bank_data;
    }


    /**
     * 获取逾期原因
     * @return array
     */
    public static function getOverdueReason($type = 'admin')
    {
        $data = [
            "产业周期长"=>"产业周期长",
            "产业亏损"=>"产业亏损",
            "家庭意外"=>"家庭意外",
            "用途变更"=>"用途变更",
            "其他"=>"其他",
        ];
        if ($type == 'admin'){
            return $data;
        }elseif ($type == 'api'){
            $arr = [];
            foreach ($data as $k => $v){
                $arrs['key'] = $k;
                $arrs['value'] = $v;
                $arr[] = $arrs;
            }
            return $arr;
        }
    }

    /**
     * 贷款模式
     * @param string $type
     * @return array
     */
    public static function getLoanModel($type = 'admin')
    {
        $data=['1'=>'共担模式', '2'=>'互助模式','3'=>'互惠模式', '4'=>'合作模式'];
        if ($type == 'admin'){
            return $data;
        }elseif ($type == 'api'){
            $arr = [];
            foreach ($data as $k => $v){
                $arrs['key'] = $k;
                $arrs['value'] = $v;
                $arr[] = $arrs;
            }
            return $arr;
        }
    }
    /**
     * 带贫方式
     * @param string $type
     * @return array
     */
    public static function getUppoorType($type = 'admin')
    {
        $data=[
            '1'=>'统一供种(供苗、幼崽等)',
            '2'=>'技术指导方式带贫',
            '3'=>'提供原料或作为企业原料等产品保底价或优惠价回收方式带贫',
            '4'=>'吸纳就业方式带贫',
            '5'=>'针对农产品产业通过加工、延伸产业链方式带贫',
            '6'=>'贫困户土地经营权、林权等入股或土地流转企业方式带贫',
            '7'=>'其他',
        ];
        if ($type == 'admin'){
            return $data;
        }elseif ($type == 'api'){
            $arr = [];
            foreach ($data as $k => $v){
                $arrs['key'] = $k;
                $arrs['value'] = $v;
                $arr[] = $arrs;
            }
            return $arr;
        }
    }
    /**
     * 贷款状态类型
     * @param string $type
     * @return array
     */
    public static function getLoanStatusType($type = 'admin')
    {
        $data=[
            '0'=>'正常',
            '1'=>'逾期',
            '2'=>'不良',
        ];
        if ($type == 'admin'){
            return $data;
        }elseif ($type == 'api'){
            $arr = [];
            foreach ($data as $k => $v){
                $arrs['key'] = $k;
                $arrs['value'] = $v;
                $arr[] = $arrs;
            }
            return $arr;
        }
    }




}
