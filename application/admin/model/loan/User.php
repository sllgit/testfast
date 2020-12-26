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
     * 获取产业分布类型
     * @return array
     */
    public static function getDistribution($type = 'admin')
    {
        $data = [
            "种植业"=>"种植业",
//            "林业"=>"林业",
//            "畜牧业"=>"畜牧业",
            "养殖业"=>"养殖业",
            "其它"=>"其它",
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
     * 贷款用途
     * @return array
     */
    public static function getLoanUse($type = 'admin')
    {
        $data = [
            '第一产业'=>'第一产业',
            '第二产业'=>'第二产业',
            '第三产业'=>'第三产业'
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
     * 是否参加过等级评定
     * @param string $type
     * @return array
     */
    public static function getIsJoincredit($type = 'admin')
    {
        $data = [
            '未参加过'=>'未参加过',
            '良好'=>'良好',
            '优秀'=>'优秀'
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
