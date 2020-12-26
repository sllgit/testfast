<?php

namespace app\api\model;

use think\Model;

class LoanEnterpriseInfo Extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;


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
        $list = self::field('nickname,phone,loan_price,loan_starttime')
            ->whereTime('createtime','-7 day')

            ->select();
        $list=collection($list)->toArray();
        foreach ($list as $k => $v){
            $list[$k]['phone'] = substr($v['phone'], 0, 3) . '****' . substr($v['phone'], 4);
        }
        return $list ?? [];
    }


    /**
     * 贷款类型数据分析
     * @param int $type 企业类型 0普通 1龙头
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getLoanEnterpriceInfo($type = 0)
    {
        //企业
        $list = self::field('count(1) as entercount,sum(loan_price) as enterprice')
            ->where(['is_faucet'=>$type])
            ->select();
        $list=collection($list)->toArray();
        $list = count($list) > 0 ? $list[0] : [];
        $list['enterprice'] = (float)($list['enterprice'] / 10000);
        return $list;
    }

    /**
     * 风险分担模式
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSharingMode()
    {
        $list = self::field('loan_price,loanmodel,loan_status_type')
            ->select();
        $list = collection($list)->toArray();
        $data = [];
        foreach ($list as $k => $v){
            if ($v['loanmodel'] == 1) { $v['loanmodel'] = 'gd'; }
            elseif ($v['loanmodel'] == 2) { $v['loanmodel'] = 'hz'; }
            elseif ($v['loanmodel'] == 3) { $v['loanmodel'] = 'hh'; }
            elseif ($v['loanmodel'] == 4) { $v['loanmodel'] = 'hzu'; }
            if ($v['loan_status_type'] == 0) {
                if (isset($data[$v['loanmodel']]['normal'])) {
                    $data[$v['loanmodel']]['normal']['counts']++;
                    $data[$v['loanmodel']]['normal']['price'] += $v['loan_price'];
                }else{
                    $data[$v['loanmodel']]['normal']['counts'] = 1;
                    $data[$v['loanmodel']]['normal']['price'] = $v['loan_price'];
                }
            }elseif ($v['loan_status_type'] == 1){
                if (isset($data[$v['loanmodel']]['overdue'])) {
                    $data[$v['loanmodel']]['overdue']['counts']++;
                    $data[$v['loanmodel']]['overdue']['price'] += $v['loan_price'];
                }else{
                    $data[$v['loanmodel']]['overdue']['counts'] = 1;
                    $data[$v['loanmodel']]['overdue']['price'] = $v['loan_price'];
                }
            }elseif ($v['loan_status_type'] == 2){
                if (isset($data[$v['loanmodel']]['bad'])) {
                    $data[$v['loanmodel']]['bad']['counts']++;
                    $data[$v['loanmodel']]['bad']['price'] += $v['loan_price'];
                }else{
                    $data[$v['loanmodel']]['bad']['counts'] = 1;
                    $data[$v['loanmodel']]['bad']['price'] = $v['loan_price'];
                }
            }
        }

        foreach ($data as $k => $v){
            $all = 0;
            foreach ($v as $kk => $vv){
                $all+=$vv['counts'];
            }
            $data[$k]['normal']['radio'] = isset($data[$k]['normal']) != 0 ? round(($data[$k]['normal']['counts'] / $all) * 100 ,2) . '%' : '0%';
            $data[$k]['overdue']['radio'] = isset($data[$k]['overdue']) != 0 ? round(($data[$k]['overdue']['counts'] / $all) * 100 ,2) . '%' : '0%';
            $data[$k]['bad']['radio'] = isset($data[$k]['bad']) != 0 ? round(($data[$k]['bad']['counts'] / $all) * 100 ,2) . '%' : '0%';
        }

        return $data ?? [];
    }

}
