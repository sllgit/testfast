<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\{ServiceStation,LoanUserInfo,PaymentLog,Bank};

/**
 * 金融扶贫系统数据概览接口
 */
class Financial extends Api
{

    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 金融扶贫系统数据概览
     *
     * @ApiTitle    (金融扶贫系统数据概览)
     * @ApiSummary  (金融扶贫系统数据概览)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/Financial/index)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiReturn   ({
         'code':'200',
         'msg':'返回成功',
         'time':'返回成功',
         'data':{"service": {"county": "县服务中心","town": "乡服务站","village": "村服务部","admincount": "三级服务体系人员"},"newloaninfo": [{"nickname": "姓名","phone": "手机号","idcard": "身份证号","loan_price": "金额","loan_starttime": "贷款日"}],"comment": {"AAA+": "AAA+类型百分比","AAA": "AAA类型百分比","AA": "AA类型百分比","A": "A类型百分比","无信用": "无信用类型百分比"},"rankinglist": {"地区名称": "地区金额"},"loantype": {"user_农户": {"usercount": "农户数量","userprice": "农户贷款总金额"}}}
        })
     */
    public function index()
    {
        $pms = $this->pms;
        $data = [];
        //三级服务站体系
        $data['service'] = ServiceStation::getServiceCount();
        //新增贷款信息
        $data['newloaninfo'] = LoanUserInfo::getNewLoanInfo();
        //贷款实时监控分析
        //信用评价数据
        $data['comment'] = LoanUserInfo::getCreditRating();
        //地区贷款前七名排行榜
        $data['rankinglist'] = LoanUserInfo::getRankingList();
        //贷款类型数据分析
        $data['loantype']['user'] = LoanUserInfo::getLoanUserInfo();//农户
        //农户脱贫产业分布

        //放款主题数据分析
        $this->success('成功',$data,200);
    }

}
