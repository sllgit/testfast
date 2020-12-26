<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\{ServiceStation,LoanUserInfo,LoanEnterpriseInfo,BankPrice};
use think\Config;

/**
 * 金融扶贫系统数据概览接口
 */
class Financial extends Api
{

    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    protected $poor_count = 0;

    /**
     * 初始化操作
     *
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->poor_count = Config::get('site.poor_count');
    }

    /**
     * 金融扶贫系统数据概览
     *
     * @ApiTitle    (金融扶贫系统数据概览)
     * @ApiSummary  (金融扶贫系统数据概览)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Financial/index)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiReturn   ({
            "code":200,
            "msg":"返回成功",
            "time":"返回成功",
            "data":{
            "map":[{"mzjx":{"price":"贷款金额","creditcount":"守信数量","support":"产业扶持"}}],
            "allprice":"累计金融贷款扶贫金额",
            "service": {"county": "县服务中心","town": "乡服务站","village": "村服务部","admincount": "三级服务体系人员"},
            "newloaninfo": [{"nickname": "姓名","phone": "手机号","idcard": "身份证号","loan_price": "金额","loan_starttime": "贷款日"}],
            "monitor": [{"collectionrate": "采集率","credit": "有信率","houseloanrate": "户贷率","overdue": "逾期率","bad": "不良率","average": "户均获贷额度"}],
            "comment": [{"label": "信用类型名称","nums": "信用类型百分比"}],
            "rankinglist": [{"country": "地区名称","prices":"地区金额"}],
            "loantype": {"user": {"usercount": "农户数量","userprice": "农户贷款总金额"},
                         "nofaucet": {"entercount": "企业数量","enterprice": "企业贷款总金额"},
                         "faucet": {"entercount": "龙头企业数量","enterprice": "龙头企业贷款总金额"}
                        },
            "distribution": [{"label": "产业名称","nums": "产业数量","ratio":"产业百分比"}],
            "bankprice": [{"bank_name": "放款银行名称","price": "放款银行金额","ratio":"放款银行百分比"}]
            }
        })
     */
    public function index()
    {
        $pms = $this->pms;
        $data = [];

        //累计金融扶贫贷款金额
        $data['allprice'] = $this->getAllPrice();

        //地图数据
        $data['map'] = $this->getMapData();

        //三级服务站体系
        $data['service'] = ServiceStation::getServiceCount();

        //新增贷款信息
        $data['newloaninfo'] = $this->getNewLoanInfo();

        //贷款实时监控分析
        $data['monitor']['collectionrate'] = $this->getCollectionRate(["is_poor"=>['in',['是','否']]]);//采集率
        $data['monitor']['credit'] = $this->getCollectionRate(['credit_rating'=>['in',['A','AA','AAA','AAA+']]]);//有信率
        $data['monitor']['houseloanrate'] = $this->getCollectionRate(["is_poor"=>"是"]);//户贷率
        list( $data['monitor']['overdue'] , $data['monitor']['bad'] ) = $this->getBeOverdue();//逾期率 - 不良率
        $data['monitor']['average'] = $this->getAverage();//户均获贷额度

        //信用评价数据
        $data['comment'] = LoanUserInfo::getCreditRating();

        //地区贷款前七名排行榜
        $data['rankinglist'] = LoanUserInfo::getRankingList();

        //贷款类型数据分析
        $data['loantype']['user'] = LoanUserInfo::getLoanUserInfo();//农户
        $data['loantype']['nofaucet'] = LoanEnterpriseInfo::getLoanEnterpriceInfo(0);//企业
        $data['loantype']['faucet'] = LoanEnterpriseInfo::getLoanEnterpriceInfo(1);//龙头企业

        //农户脱贫产业分布
        $data['distribution'] = LoanUserInfo::getDistribution();

        //放款主题数据分析
        $data['bankprice'] = $this->getBankPrice();

//        halt($data);
        $this->success('成功',$data,200);
    }

    /**
     * 获取新增贷款数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getNewLoanInfo(){
        //农户新增
        $loanuser = LoanUserInfo::getNewLoanInfo();
        //企业新增
        $loanenter = LoanEnterpriseInfo::getNewLoanInfo();

        $list = array_merge($loanuser,$loanenter);

        return $list ?? [];
    }

    /**
     * 获取银行放款金额
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getBankPrice()
    {
        $list = BankPrice::field('bank_name,price')->select();
        $list = collection($list)->toArray();
        $allprice = array_sum(array_column($list,'price'));
        foreach ($list as $k => $v){
            $list[$k]['ratio'] = $allprice != 0 ? round(($list[$k]['price'] / $allprice) * 100,2) . '%' : "0%";
            $list[$k]['price'] = $list[$k]['price'] / 10000;
        }
        return $list ?? [];
    }

    /**
     * 采集率 有信率 户贷率
     * @param array $where
     * @return float|int
     * @throws \think\Exception
     */
    protected function getCollectionRate($where=[])
    {

        $count = LoanUserInfo::where($where)->count();
        $radio = $this->poor_count != 0 ? round(($count / $this->poor_count) * 100 ,2) : 0;
        return $radio;
    }

    /**
     * 逾期率 - 不良率
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getBeOverdue()
    {
        $list = LoanUserInfo::field('loan_price,loan_status_type')
//            ->where(['is_poor'=>1])
            ->select();
        $list = collection($list)->toArray();
        $allprice = LoanUserInfo::field('id')
//            ->where(['is_poor'=>1])
            ->sum('loan_price');
        $creditprice = 0;
        $badprice = 0;
        foreach ($list as $k => $v){
            if ($v['loan_status_type'] ==1 ){
                $creditprice += $v['loan_price'];
            }elseif ($v['loan_status_type'] == 2){
                $badprice += $v['loan_price'];
            }
        }
        $creditradio = $allprice != 0 ? round(($creditprice / $allprice) * 100 ,2) : 0;
        $badradio = $allprice != 0 ? round(($badprice / $allprice) * 100 ,2) : 0;

        return [$creditradio,$badradio];
    }

    /**
     * 户均获贷额度
     * @return float|int
     */
    protected function getAverage()
    {
        $list = LoanUserInfo::where(['is_poor'=>1])->column('loan_price');
        $allpeople = count($list);
        $allprice = array_sum($list);
        $radio = $allpeople != 0 ? round(($allprice / $allpeople), 2) : 0;

        return $radio;
    }

    /**
     * 扶贫贷款总金额
     * @return float|int
     */
    protected function getAllPrice()
    {
        $userallprice = LoanUserInfo::sum('loan_price');
        $enterallprice = LoanEnterpriseInfo::sum('loan_price');
        $allprice = $userallprice + $enterallprice;

        return $allprice ?? 0;
    }

}
