<?php

namespace app\api\model;

use think\Model;

class LoanUserInfo Extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 获取新增贷款数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getNewLoanInfo()
    {
        //默认一周
        $list = self::field('nickname,phone,idcard,loan_price,loan_starttime')
            ->whereTime('loan_starttime','-7 day')
//            ->fetchSql(1)
            ->select();
        $list=collection($list)->toArray();
        foreach ($list as $k => $v){
            $list[$k]['idcard'] = substr($v['idcard'], 0, 6) . '********' . substr($v['idcard'], 14);
        }
        return $list ?? [];
    }

    /**
     * 获取信用评价数据比例
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCreditRating()
    {
        $list = self::field('credit_rating,count(1) as counts')
            ->group('credit_rating')
            ->select();
        $list=collection($list)->toArray();
        $creditdata = array_column($list,'counts','credit_rating');
        $total = array_sum($creditdata);
        $AAA_ = isset($creditdata['AAA+']) ? intval(($creditdata['AAA+'] / $total) * 100) . '%' : "0";
        $AAA = isset($creditdata['AAA']) ? intval(($creditdata['AAA'] / $total) * 100) . '%' : "0";
        $AA = isset($creditdata['AA']) ? intval(($creditdata['AA'] / $total) * 100) . '%' : "0";
        $A = isset($creditdata['A']) ? intval(($creditdata['A'] / $total) * 100) . '%' : "0";
        $no = isset($creditdata['无信用']) ? intval(($creditdata['无信用'] / $total) * 100) . '%' : "0";
//        halt($creditdata);

        return ["AAA+"=>$AAA_,"AAA"=>$AAA,"AA"=>$AA,"A"=>$A,"无信用"=>$no];

    }

    /**
     * 获取地区贷款前七名排行
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRankingList()
    {
        $list = self::field('country,sum(loan_price) as sums')
            ->group('country')
            ->order('sums','desc')
            ->limit(7)
            ->select();
        $list=collection($list)->toArray();
        $list = array_column($list,'sums','country');
        return $list ?? [];
    }

    public static function getLoanUserInfo()
    {
        $list = self::field('count(1) as usercount,sum(loan_price) as userprice')
            ->select();
        $list=collection($list)->toArray();
        $list = count($list) > 0 ? $list[0] : [];
        $list['userprice'] = (float)($list['userprice'] / 10000);
        return $list;
    }

}