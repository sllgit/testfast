<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\admin\model\loan\User;
use app\admin\model\loanenterprise\Enterprise;
use think\Db;
use think\Exception;

/**
 * 录入数据接口
 */
class Loan extends Api
{

    protected $noNeedLogin = ['*'];

    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 页面初始化数据
     * @ApiTitle    (页面初始化数据)
     * @ApiSummary  (页面初始化数据)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Loan/init)
     * @ApiReturn   ({
    'code':'200',
    'msg':'成功',
    'time':'1608540255',
    'data':{
        "banklist":[{"key":"银行键","value":"银行值"}],
        "overduereason":[{"key":"逾期原因键","value":"逾期原因值"}],
        "purposeloan":[{"key":"贷款用途键","value":"贷款用途值"}],
        "distribution":[{"key":"产业分布类型键","value":"产业分布类型值"}],
        "isjoincredit":[{"key":"信用等级评定键","value":"信用等级评定值"}],
        "loanmodel":[{"key":"贷款模式键","value":"贷款模式值"}],
        "uppoortype":[{"key":"带贫方式键","value":"带贫方式值"}]
    }
    })
     */
    public function init()
    {
        //银行
        $data['banklist'] = db('bank')->field('id,bank_name')->where(['status'=>1])->select();
        //逾期原因
        $data['overduereason'] = User::getOverdueReason('api');
        //贷款用途
        $data['purposeloan'] = User::getLoanUse('api');
        //产业分布类型
        $data['distribution'] = User::getDistribution('api');
        //是否参加过信用等级评定
        $data['isjoincredit'] = User::getIsJoincredit('api');
        //贷款模式
        $data['loanmodel'] = Enterprise::getLoanModel('api');
        //带贫方式
        $data['uppoortype'] = Enterprise::getUppoorType('api');
        //贷款状态类型
        $data['loanstatustype'] = Enterprise::getLoanStatusType('api');


        $this->success('成功',$data,200);
    }

    /**
     * 农户录入数据
     * @ApiTitle    (农户录入数据)
     * @ApiSummary  (农户录入数据)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Loan/userInsertData)
     * @ApiParams   (name="nickname", type=string, required=true, description="贷款人姓名")
     * @ApiParams   (name="phone", type="integer", required=true, description="贷款人手机号")
     * @ApiParams   (name="area", type=string, required=true, description="区县名称")
     * @ApiParams   (name="country", type=string, required=true, description="乡镇名称")
     * @ApiParams   (name="village", type=string, required=true, description="村名称")
     * @ApiParams   (name="idcard", type=string, required=true, description="身份证号")
     * @ApiParams   (name="credit_rating", type=string, required=true, description="信用评级")
     * @ApiParams   (name="credit_code", type=string, required=true, description="信用评分")
     * @ApiParams   (name="loan_price", type="float", required=true, description="贷款金额")
     * @ApiParams   (name="loan_use", type=string, required=true, description="贷款用途")
     * @ApiParams   (name="distribution", type=string, required=true, description="产业分布类型")
     * @ApiParams   (name="loan_bank", type=string, required=true, description="贷款银行类别")
     * @ApiParams   (name="loan_bank_desc", type=string, required=true, description="贷款银行详细信息")
     * @ApiParams   (name="is_joincredit", type=string, required=true, description="是否参加过信用等级评定")
     * @ApiParams   (name="loan_starttime", type="integer", required=true, description="借款日")
     * @ApiParams   (name="loan_endtime", type="integer", required=true, description="到期日")
     * @ApiParams   (name="payback_time", type="integer", required=true, description="还款日期")
     * @ApiParams   (name="overduereason", type=string, required=false, description="逾期原因")
     * @ApiParams   (name="year_interest_rate", type="float", required=true, description="年利率")
     * @ApiParams   (name="discount_rate", type="float", required=true, description="贴息利率")
     * @ApiParams   (name="discount_price", type="float", required=true, description="应贴息金额")
     * @ApiParams   (name="supportprice", type="float", required=true, description="产业扶持金额")
     * @ApiParams   (name="is_poor", type="integer", required=true, description="是否为贫困户")
     * @ApiParams   (name="loan_type", type=string, required=true, description="贷款类型")
     * @ApiParams   (name="is_safe", type=string, required=true, description="是否购买贷款保证保险")
     * @ApiParams   (name="entrustprove", type=string, required=true, description="委托证明")
     * @ApiParams   (name="loan_status_type", type=string, required=true, description="贷款状态类型")
     * @ApiReturn   ({
    'code':'200',
    'msg':'录入成功',
    'time':'1608540255',
    'data':{}
    })
     */
    public function userInsertData()
    {
        $pms = $this->pms;

        $result = $this->validate($pms,'app\api\validate\Loan.useradd');
        if(true !== $result){
            $this->error($result,[],203);
        }

        Db::startTrans();
        try {
            $nowtime = time();
            $pms['entrustprove'] = rtrim($pms['entrustprove'],',');
            $pms['insert_type'] = 1;
            //借贷业务号码
            $pms['local_no'] = date("Ymd").rand(9999,9999999);
            $pms['createtime'] = $nowtime;
            $lastinsid = db('loan_user_info')->insertGetId($pms);
            //还款记录
            $pms['day'] = $this->getdays($pms['payback_time'],$pms['loan_endtime']);
            $pms['loan_id'] = $lastinsid;
            $pms['type'] = 1;
            model('PaymentLog')->allowField(true)->save($pms);
            model('Creditlog')->allowField(true)->save($pms);

            Db::commit();
            $this->success('录入成功',[],200);
        } catch (\Exception $e) {
            $this->error($e->getMessage(),[],203);
            Db::rollback();
        }

    }

    /**
     * 企业录入数据
     * @ApiTitle    (企业录入数据)
     * @ApiSummary  (企业录入数据)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Loan/enterInsertData)
     * @ApiParams   (name="is_faucet", type="integer", required=true, description="是否为龙头企业")
     * @ApiParams   (name="area", type=string, required=true, description="区县名称")
     * @ApiParams   (name="country", type=string, required=true, description="乡镇名称")
     * @ApiParams   (name="village", type=string, required=true, description="村名称")
     * @ApiParams   (name="nickname", type=string, required=true, description="企业名称")
     * @ApiParams   (name="credit_code", type=string, required=true, description="社会信用代码")
     * @ApiParams   (name="deputy_username", type=string, required=true, description="法定代表人")
     * @ApiParams   (name="phone", type=string, required=true, description="联系电话")
     * @ApiParams   (name="address", type=string, required=true, description="注册地址")
     * @ApiParams   (name="staff_num", type=string, required=true, description="员工人数")
     * @ApiParams   (name="loan_price", type="float", required=true, description="贷款金额")
     * @ApiParams   (name="loan_starttime", type="integer", required=true, description="借款日")
     * @ApiParams   (name="loan_endtime", type="integer", required=true, description="到期日")
     * @ApiParams   (name="payback_time", type="integer", required=true, description="还款日期")
     * @ApiParams   (name="overduereason", type=string, required=false, description="逾期原因")
     * @ApiParams   (name="year_interest_rate", type="float", required=true, description="年利率")
     * @ApiParams   (name="discount_rate", type="float", required=true, description="贴息利率")
     * @ApiParams   (name="discount_price", type="float", required=true, description="应贴息金额")
     * @ApiParams   (name="supportprice", type="float", required=true, description="产业扶持金额")
     * @ApiParams   (name="uppoor_num", type=string, required=true, description="带贫困户数")
     * @ApiParams   (name="loan_bank", type=string, required=true, description="贷款银行类别")
     * @ApiParams   (name="loan_bank_desc", type=string, required=true, description="贷款银行详细信息")
     * @ApiParams   (name="loanmodel", type=string, required=true, description="贷款模式")
     * @ApiParams   (name="loan_type", type=string, required=true, description="借贷类型")
     * @ApiParams   (name="uppoor_type", type=string, required=true, description="带贫方式")
     * @ApiParams   (name="uppoor_roster", type=string, required=true, description="带贫名单")
     * @ApiParams   (name="loan_type", type=string, required=true, description="贷款类型")
     * @ApiParams   (name="entrustprove", type=string, required=true, description="委托证明")
     * @ApiParams   (name="loan_status_type", type="integer", required=true, description="贷款状态类型")
     * @ApiReturn   ({
    'code':'200',
    'msg':'录入成功',
    'time':'1608540255',
    'data':{}
    })
     */
    public function enterInsertData()
    {
        $pms = $this->pms;

        $result = $this->validate($pms,'app\api\validate\Loan.enteradd');
        if(true !== $result){
            $this->error($result,[],203);
        }
        Db::startTrans();
        try{

            $params['createtime'] = time();
            $pms['entrustprove'] = rtrim($pms['entrustprove'],',');
            $pms['uppoor_type'] = rtrim($pms['uppoor_type'],',');
            $pms['uppoor_roster'] = rtrim($pms['uppoor_roster'],',');
            $pms['insert_type'] = 1;
            $lastinsid = db('loan_enterprise_info')->insertGetId($pms);
            //还款记录
            $pms['day'] = $this->getdays($params['payback_time'],$pms['loan_endtime']);
            $pms['loan_id'] = $lastinsid;
            $pms['type'] = 2;
            model('PaymentLog')->allowField(true)->save($pms);
            model('Creditlog')->allowField(true)->save($pms);

            Db::commit();
            $this->success('录入成功',[],200);
        }catch (Exception $e){
            $this->error($e->getMessage(),[],203);
            Db::rollback();
        }

    }

    /**
     *图片上传
     * @ApiTitle    (图片上传)
     * @ApiSummary  (图片上传)
     * @ApiMethod   (POST)
     * @ApiRoute    (/Loan/uploads)
     * @ApiParams   (name="img", type="integer", required=true, description="图片")
     * @ApiReturn ({"msg":"返回成功","data":"图片路径","code":200
    })
     */
    public function uploads()
    {
        $file = $_FILES;
        if(!$file){
            $this->success('上传错误，请重试！',[],203);
        }
        foreach($file as $image){
            $name = $image['name'];
            $type = strtolower(substr($name, strrpos($name, '.') + 1));
            $allow_type = array('jpg', 'jpeg', 'gif', 'png');
            //判断文件类型是否被允许上传
            if (!in_array($type, $allow_type)) {
                continue;
            }
            $path = '/uploads/'.date('Ymd');
            $upload_path = ROOT_PATH . 'public' . $path; //上传文件的存放路径
            if(!is_dir($upload_path)){
                mkdir($upload_path);
            }
            $file_name = date('Ymd').time().rand(100,999).'.'.$type;
            //开始移动文件到相应的文件夹
            if (move_uploaded_file($image['tmp_name'], $upload_path . DS . $file_name)) {
                $url = $path . DS . $file_name;
            }
        }
        $this->success('上传成功',$url,200);

    }

    /**
     * 获取日期差异天数
     * @param $start
     * @param $end
     * @return float
     */
    public function getdays($start,$end)
    {
        $startdate=strtotime($start);
        $enddate=strtotime($end);
        $days=round(($enddate-$startdate)/3600/24) ;
        return $days;
    }

}
