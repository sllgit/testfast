<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\{ServiceStation,LoanUserInfo,PaymentLog,Bank,Otherorganization};
use think\Config;

/**
 * 产业支撑系统数据概览接口
 */
class Industry extends Api
{

    protected $noNeedLogin = ['*'];

    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 产业支撑系统数据
     *
     * @ApiTitle    (产业支撑系统数据)
     * @ApiSummary  (产业支撑系统数据)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Industry/index)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiReturn   ({
        "code": 200,
        "msg": "成功",
        "time": "1608254759",
        "data": {
                "map":[{"mzjx":{"price":"贷款金额","creditcount":"守信数量","support":"产业扶持"}}],
                "otherorganization":[{"lavel":"名称","counts":"个数"}],
                "layoutplan":[{"lavel":"名称","content":"内容"}],
                "countrylist":[{"lavel":"地区名称","hzs":"合作社数量","cyfpjd":"产业扶贫基地数量","cyfpdp":"产业扶贫大棚数量"}],
                "coupling":[{"top":"上方内容","middle":"中间变色内容","bottom":"下方值的内容"}],
                "leading":[{"lavel":"产业名称","price":"产业金额","radio":"产业百分比"}]
            }
        })
     */
    public function index()
    {
        $pms = $this->pms;
        $data = [];

        //地图数据
        $data['map'] = $this->getMapData();

        //全县扶贫基地、龙头企业、农村专业合作社
        $data['otherorganization'] = Otherorganization::getOtherOrganization();

        //产业布局规划
        $data['layoutplan'] = $this->getLayoutPlan();

        //乡镇产业数量
        $data['countrylist'] = Otherorganization::getcountryList();

        //利益联结
        $data['coupling'] = $this->getCoupling();

        //主导产业
        $data['leading'] = $this->getLeading();

        $this->success('成功',$data,200);
    }

    /**
     * 产业布局规划
     * @return array
     */
    protected function getLayoutPlan()
    {
        $layoutplan = Config::get('site.layoutplan');
        $data = [];
        foreach ($layoutplan as $k => $v){
            $arr['lavel'] = $k;
            $arr['content'] = $v;
            $data[] = $arr;
        }
        return $data ?? [];
    }

    /**
     * 利益联结
     * @return array
     */
    protected function getCoupling(){
        $coupling = Config::get('site.coupling');
        $data = [];
        foreach ($coupling as $k => $v){
            $arr = [];
            list($arr['top'],$arr['middle']) = explode(' ',$k);
            $arr['bottom'] = $v;
            $data[] = $arr;
        }
        return $data ?? [];
    }

    /**
     * 主导产业
     * @return array
     */
    protected function getLeading()
    {
        $leading = Config::get('site.leading');
        $allprice = array_sum($leading);
        $data = [];
        foreach ($leading as $kk => $vv){
            $arr['lavel'] = $kk;
            $arr['price'] = $vv . '万元';
            $arr['radio'] = $allprice != 0 ? round(($vv / $allprice) * 100 ,2) . '%' : '0%';
            $data[] = $arr;
        }
        return $data ?? [];
    }


}
