<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\{LoanUserInfo,LoanEnterpriseInfo,Creditlog};
use think\Config;

/**
 * 风险防控系统数据概览接口
 */
class Risk extends Api
{

    protected $noNeedLogin = ['*'];

    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 风险防控系统数据
     *
     * @ApiTitle    (风险防控系统数据)
     * @ApiSummary  (风险防控系统数据)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Risk/index)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiReturn   ({
        "code": 200,
        "msg": "成功",
        "time": "1608254759",
        "data": {
            "map":[{"mzjx":{"price":"贷款金额","creditcount":"守信数量","support":"产业扶持"}}],
            "annualtrend":[{"years":"年份","counts":"贷款户数","sums":"贷款金额","average":"户均平均金额","yearradio":"年度户贷率"}],
            "overduereason":[{"overduereason":"逾期原因","counts":"逾期笔数","price":"逾期金额","radio":"逾期百分比"}],
            "compensation":{"allprice":"总风险补偿金","snd":{"radio":"省农信担保占比","price":"省农信担保金额"},"xzz":"县政府","dbqy":"省中小企业反担保集团","zryh":"责任银行"},
            "pricetype":{"normal":"正常还款金额","overdue":"逾期金额","bad":"不良金额","normalradio":"正常率","overdueradio":"逾期率","badradio":"不良率"},
            "overduedayradio":{"one":"逾期1-3天","two":"逾期4-10天","three":"逾期10-30天","fore":"逾期30-69天","five":"逾期70天以上"},
            "price":{"collectionprice":"正在催收金额","violationprice":"历史违约金额"},
            "creditpriceranking":[{"country":"乡镇（村）占比名称","price":"乡镇（村）占比金额","radio":"乡镇（村）占比百分比"}],
            "sharingmode":[{
                    "1":[{"normal":{"counts":"共担正常个数","price":"共担正常金额","radio":"共担正常百分比"}},{"normal":{"counts":"共担逾期个数","price":"共担逾期金额","radio":"共担逾期百分比"}},{"normal":{"counts":"共担不良个数","price":"共担不良金额","radio":"共担不良百分比"}}],
                    "2":[{"normal":{"counts":"互助正常个数","price":"互助正常金额","radio":"互助正常百分比"}},{"normal":{"counts":"互助逾期个数","price":"互助逾期金额","radio":"互助逾期百分比"}},{"normal":{"counts":"互助不良个数","price":"互助不良金额","radio":"互助不良百分比"}}],
                    "3":[{"normal":{"counts":"互惠正常个数","price":"互惠正常金额","radio":"互惠正常百分比"}},{"normal":{"counts":"互惠逾期个数","price":"互惠逾期金额","radio":"互惠逾期百分比"}},{"normal":{"counts":"互惠不良个数","price":"互惠不良金额","radio":"互惠不良百分比"}}],
                    "4":[{"normal":{"counts":"合作正常个数","price":"合作正常金额","radio":"合作正常百分比"}},{"normal":{"counts":"合作逾期个数","price":"合作逾期金额","radio":"合作逾期百分比"}},{"normal":{"counts":"合作不良个数","price":"合作不良金额","radio":"合作不良百分比"}}]
            }],
            "overdueranking":[{"country":"逾期地区名称","price":"逾期金额"}]
        }
        })
     */
    public function index()
    {
        $pms = $this->pms;
        $data = [];

        //地图数据
        $data['map'] = $this->getMapData();

        //扶贫小额信贷年度走势
        $data['annualtrend'] = LoanUserInfo::getAnnualTrend();

        //逾期不良原因类型
        $data['overduereason'] = LoanUserInfo::getOverdueReason();

        //县级风险补偿金
        $data['compensation'] = LoanUserInfo::getCompensation();

        //正常还款-逾期金额-不良
        $data['pricetype'] = LoanUserInfo::getPriceType();

        //逾期时间比例
        $data['overduedayradio'] = LoanUserInfo::getOverdueDayRadio();

        //正在催收金额-历史违约金额
        $data['price']['collectionprice'] = Config::get('site.collectionprice');
        $data['price']['violationprice'] = Config::get('site.violationprice');

        //乡镇（村）占比前五名
        $data['creditpriceranking'] = $this->getCreditPriceRanking();

        //风险分担模式
        $data['sharingmode'] = LoanEnterpriseInfo::getSharingMode();

        //乡镇前五名逾期排名
        $data['overdueranking'] = LoanUserInfo::getOverdueRanking();

        $this->success('成功',$data,200);
    }

    /**
     * 获取乡镇贷款金额占比前五名
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getCreditPriceRanking()
    {
        //农户
        $list = Creditlog::field('country,sum(loan_price) as price')
            ->group('country')
            ->order('price','desc')
            ->limit(0,5)
            ->select();
        $allprice = Creditlog::sum('loan_price');
        $list = collection($list)->toArray();
        foreach ($list as $k => $v){
            $list[$k]['radio'] = $allprice != 0 ? round((($v['price'] ?? 0) / $allprice) * 100 ,2) . '%' : '0%';
        }
        return $list ?? [];
    }


}
