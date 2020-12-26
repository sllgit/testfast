<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\{ServiceStation,LoanUserInfo,PaymentLog,Bank};

/**
 * 信用评价系统数据概览接口
 */
class Credit extends Api
{

    protected $noNeedLogin = ['*'];

    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 信用评价系统数据概览
     *
     * @ApiTitle    (信用评价系统数据概览)
     * @ApiSummary  (信用评价系统数据概览)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Credit/index)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiReturn   ({
        "code": 200,
        "msg": "成功",
        "time": "1608254759",
        "data": {
                "map":[{"mzjx":{"price":"贷款金额","creditcount":"守信数量","support":"产业扶持"}}],
                "comment": [{"credit_rating": "信用分类名称","counts": "分类户数"}],
                "country": {"counts": "乡总数","credit": "乡信用数量","nocredit": "乡非信用数量","creditratio": "乡信用比例","nocreditratio": "乡非信用比例"},
                "village": {"counts": "村总数","credit": "村信用数量","nocredit": "村非信用数量","creditratio": "村信用比例","nocreditratio": "村非信用比例"},
                "comments": [{"label": "信用等级名称","nums": "信用等级比例"}],
                "newloaninfo": [{"id": "用户id","nickname": "姓名","credit_rating": "级别","credit_code": "评分","address": "地址"}],
                "trend": [{"month": "月份","nums": "人数"}],
                "rank": {"nopoor": [{"label": "非贫困等级","nums": "非贫困等级比例"}],
                         "poor": [{"label": "贫困等级","nums": "贫困等级比例"}]
                        },
                "ranking": {"countrydata": [{"name": "乡名称","ratio": "乡百分比"}],
                            "villagedata": [{"name": "村名称","ratio": "村百分比"}]
                           }
                }
        })
     */
    public function index()
    {
        $pms = $this->pms;
        $data = [];

        //地图数据
        $data['map'] = $this->getMapData();

        //信用评价数据
        $data['comment'] = $this->getCreditRating();

        //乡信用占比
        $data['country'] = LoanUserInfo::getCountryCredit();

        //村信用占比
        $data['village'] = LoanUserInfo::getVillageCredit();

        //信用等级比例
        $data['comments'] = LoanUserInfo::getCreditRating();

        //新增采集信用农户信息展示
        $data['newloaninfo'] = $this->getNewLoanInfo();

        //近半年信用人数趋势
        $data['trend'] = LoanUserInfo::getHalfYearsTrend();

        //贫困、非贫困信用等级分析
        $data['rank']['nopoor'] = LoanUserInfo::getCreditRating(["is_poor"=>0]);
        $data['rank']['poor'] = LoanUserInfo::getCreditRating(["is_poor"=>1]);

        //乡镇（村）信用前五名
        $data['ranking'] = LoanUserInfo::getCreditList();

        $this->success('成功',$data,200);
    }

    /**
     * 信用评价数据
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getCreditRating()
    {
        $list = LoanUserInfo::field('credit_rating,count(1) as counts')
            ->group('credit_rating')
            ->select();
        $list=collection($list)->toArray();

        return $list;
    }

    protected function getNewLoanInfo()
    {
        $list = LoanUserInfo::field('id,nickname,country,village,credit_rating,credit_code')->order('id','desc')->limit(5)->select();
        $list = collection($list)->toArray();
        foreach ($list as $k => $v){
            if (empty($v['village'])){
                $list[$k]['address'] = $v['country'];
            }else{
                $list[$k]['address'] = $v['village'];
            }
            unset($list[$k]['country'],$list[$k]['village']);
        }
        return $list ?? [];

    }

}
