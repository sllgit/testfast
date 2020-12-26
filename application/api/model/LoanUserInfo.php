<?php

namespace app\api\model;

use app\admin\model\loan\User;
use app\common\model\Areas;
use think\Config;
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
            ->whereTime('createtime','-7 day')
//            ->fetchSql(1)
            ->select();
        $list=collection($list)->toArray();
        foreach ($list as $k => $v){
            $list[$k]['phone'] = substr($v['phone'], 0, 3) . '****' . substr($v['phone'], 4);
            $list[$k]['idcard'] = substr($v['idcard'], 0, 6) . '********' . substr($v['idcard'], 14);
        }
        return $list ?? [];
    }

    /**
     * 获取信用评价数据比例
     * @param int $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCreditRating($where=[])
    {
        $list = self::field('credit_rating,count(1) as counts')
            ->where($where)
            ->group('credit_rating')
            ->select();
        $list=collection($list)->toArray();
        if (!empty($list)){
            $creditdata = array_column($list,'counts','credit_rating');
            $total = array_sum($creditdata);
            $AAA_ = isset($creditdata['AAA+']) ? round(($creditdata['AAA+'] / $total) * 100,2) : "0";
            $AAA = isset($creditdata['AAA']) ? round(($creditdata['AAA'] / $total) * 100,2) : "0";
            $AA = isset($creditdata['AA']) ? round(($creditdata['AA'] / $total) * 100,2) : "0";
            $A = isset($creditdata['A']) ? round(($creditdata['A'] / $total) * 100,2) : "0";
            $No = 100 - ($AAA_ + $AAA + $AA + $A);
        }else{
            $AAA_ = $AAA = $AA = $A = $No = 0;
        }

        $data[] = ["label"=>"AAA+","nums"=>$AAA_];
        $data[] = ["label"=>"AAA","nums"=>$AAA];
        $data[] = ["label"=>"AA","nums"=>$AA];
        $data[] = ["label"=>"A","nums"=>$A];
        $data[] = ["label"=>"无信用","nums"=>$No];
        return $data;

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
        $list = self::field('country,sum(loan_price) as prices')
            ->group('country')
            ->order('prices','desc')
            ->limit(7)
            ->select();
        $list=collection($list)->toArray();
        return $list ?? [];
    }

    /**
     *  贷款类型数据分析
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getLoanUserInfo()
    {
        $list = self::field('count(1) as usercount,sum(loan_price) as userprice')
            ->select();
        $list=collection($list)->toArray();
        $list = count($list) > 0 ? $list[0] : [];
        $list['userprice'] = (float)($list['userprice'] / 10000);
        return $list;
    }

    /**
     * 获取乡信用数据内容
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCountryCredit()
    {
        $data = [];
        $data['counts'] = \model('Areas')->where(['pid'=>1,'status'=>1])->count();
        $list = self::field('country,count(1) as counts')
            ->group('country')
            ->select();
        $list = collection($list)->toArray();
        $data['credit'] = 0;
        $data['nocredit'] = 0;
        $creditcountry = Config::get('site.creditcountry');
        $creditcountry = str_replace('，',',',$creditcountry);
        $creditcountry = explode(',',$creditcountry);
        foreach ($list as $k => $v){
            if (in_array($v['country'],$creditcountry)){
                $data['credit'] ++;
            }else{
                $data['nocredit'] ++;
            }
        }
        $all = (int)($data['credit'] + $data['nocredit']);
        $data['creditratio'] = $all != 0 ? round(($data['credit'] / $all) * 100,2) : 0;
        $data['nocreditratio'] = ($all != 0 ? round(($data['nocredit'] / $all) * 100,2) : 0) > 0 ? 100 - $data['creditratio'] : 0;

        return $data;
    }

    /**
     * 获取村信用数据内容
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getVillageCredit()
    {
        $data = [];
        $data['counts'] = \model('Areas')->where(['pid'=>['not in',[0,1]],'status'=>1])->count();
        $list = self::field('village,count(1) as counts')
            ->group('village')
            ->select();
        $list = collection($list)->toArray();
        $data['credit'] = 0;
        $data['nocredit'] = 0;
        $creditvillage = Config::get('site.creditvillage');
        $creditvillage = str_replace('，',',',$creditvillage);
        $creditvillage = explode(',',$creditvillage);
        foreach ($list as $k => $v){
            if (in_array($v['village'],$creditvillage)){
                $data['credit'] ++;
            }else{
                $data['nocredit'] ++;
            }
        }
        $all = (int)($data['credit'] + $data['nocredit']);
        $data['creditratio'] = $all != 0 ? round(($data['credit'] / $all) * 100,2) : 0;
        $data['nocreditratio'] = ($all != 0 ? round(($data['nocredit'] / $all) * 100,2) : 0) > 0 ? 100 - $data['creditratio'] : 0;

        return $data;
    }

    /**
     * 获取半年信用人数趋势
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getHalfYearsTrend()
    {
        list($startdate) = self::getdatetime(6);
        $list = self::field('DATE_FORMAT(loan_starttime,\'%Y-%m\') as month,count(1) as nums')
            ->where(['loan_starttime'=>['>=',$startdate],'credit_rating'=>['in',['A','AA','AAA','AAA+']]])
            ->group('month')
//            ->fetchSql(true)
            ->select();
        $list = collection($list)->toArray();
        foreach ($list as $k => $v){
            $list[$k]['month'] = intval(substr($v['month'],-2));
        }
        return $list ?? [];
    }

    /**
     * 获取产业分布数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getDistribution()
    {
        $list = self::field('loan_use as lavel,count(1) as nums')
            ->group('loan_use')
            ->select();
        $list = collection($list)->toArray();
        $total = array_column($list,'nums');
        $all = array_sum($total);
        foreach ($list as $k => $v){
            $list[$k]['ratio'] =  $all != 0 ? round(($v['nums'] / $all) * 100,2) : 0;
        }
        return $list ?? [];
    }

    /**
     * 获取乡和乡下第一守信村的比例
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCreditList()
    {
        $countrylist = Areas::field('name')->where(['pid'=>1,'status'=>1])->select();
        $countrylist = collection($countrylist)->toArray();
        foreach ($countrylist as $k => $v){
            $ratio = self::getCreditRaidio('country',$v['name']);
            $countrylist[$k]['ratio'] = $ratio;
        }
        $data['countrydata'] = self::getArrayVrsort($countrylist,'ratio',5);
        $villagedata = [];
        foreach ($data['countrydata'] as $k => $v){
            $village = LoanUserInfo::where(['country'=>$v['name']])->group('village')->column('village');
            $arr = [];
            foreach ($village as $kk => $vv){
                $arr[$kk]['name'] = $vv;
                $ratio = self::getCreditRaidio('village',$vv);
                $arr[$kk]['ratio'] = $ratio;
            }
            $arr = self::getArrayVrsort($arr,'ratio',1);
            $villagedata = array_merge($villagedata,$arr);
        }
        $data['villagedata'] = $villagedata;

        return $data;
    }

    /**
     * 扶贫小额信贷年度走势
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAnnualTrend()
    {
        //2017、2018、2019、2020
        //年贷款户数、贷款额度、户均贷款平均额度（或单笔贷款平均额度）、分年度户贷率、分年度贷款余额
        //分项目总计

        //获取当前年份和前三年的年份
        $years = self::getYears(3);
        $list = self::field('DATE_FORMAT(loan_starttime,\'%Y\') as years,count(1) as counts,sum(loan_price) as sums')
            ->group('years')
            ->select();
        $villagecount = db('areas')->where(['pid'=>['not in',[0,1]],'status'=>1])->count();

        $list = collection($list)->toArray();
        foreach ($list as $k => $v){
            if (!in_array($v['years'],$years)){
                unset($list[$k]);
            }else{
                $list[$k]['average'] = round($v['sums'] / $v['counts'],2);
                $list[$k]['yearradio'] = $villagecount != 0 ? round(($v['counts'] / $villagecount) * 100,2) . '%' : '0%';
            }
        }
        return $list;
    }

    /**
     * 县级风险补偿金数据
     * @return mixed
     */
    public static function getCompensation()
    {
        //省农信担保：40%；省中小企业反担保集团20%；县政府30%；责任银行10%。
        $allprice = Config::get('site.compensation');
        $data['allprice'] = $allprice;
        $data['snd'] = ["radio"=>"40%","price"=>$allprice * 0.4];
        $data['xzz'] = ["radio"=>"30%","price"=>$allprice * 0.3];
        $data['dbqy'] = ["radio"=>"20%","price"=>$allprice * 0.2];
        $data['zryh'] = ["radio"=>"10%","price"=>$allprice * 0.1];
        return $data;
    }

    /**
     * 获取逾期不良原因类型数据
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOverdueReason()
    {
        $list = self::field('overduereason,count(1) as counts,sum(loan_price) as price')
            ->whereNotNull('overduereason')
            ->where(['overduereason'=>['neq','']])
            ->group('overduereason')
            ->select();
        $list = collection($list)->toArray();
        if (!empty($list)){
            $total = self::whereNotNull('overduereason')->count();

            foreach ($list as $k => $v){
                $list[$k]['radio'] =  $total != 0 ? round(($v['counts'] / $total) * 100,2) : 0;
            }
        }else{
            $list = [];
            $OverdueReason = User::getOverdueReason('api');
            foreach ($OverdueReason as $k => $v){
                $list[$k]['overduereason'] = $v['value'];
                $list[$k]['counts'] = 0;
                $list[$k]['price'] = 0;
                $list[$k]['radio'] = 0;
            }
        }

        return $list ?? [];
    }

    /**
     * 获取正常还款 - 逾期还款 - 不良还款 数据
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getPriceType()
    {
        $list = self::field('loan_price,loan_status_type')->select();
        $list = collection($list)->toArray();
        $total = self::count();
        $normal = $overdue = $bad = 0;
        $normalnum = $overduenum = $badnum = $all = 0;
        $data = [];
        foreach ($list as $k => $v){
            if ($v['loan_status_type'] == 1){//逾期
                $overdue += $v['loan_price'];
                $normalnum ++;
                $all ++;
            }elseif ($v['loan_status_type'] == 0){//正常
                $normal += $v['loan_price'];
                $overduenum ++;
                $all ++;
            }else{
                $bad += $v['loan_price'];
                $badnum ++;
                $all ++;
            }
        }
        $data['normal'] = $normal;
        $data['overdue'] = $overdue;
        $data['bad'] = $bad;
        $data['normalradio'] = $all != 0 ? round(($normalnum / $all) * 100 ,2) : 0;
        $data['overdueradio'] = $all != 0 ? round(($overduenum / $all) * 100 ,2) : 0;
        $data['badradio'] = ($all != 0 ? round(($bad / $all) * 100 ,2) : 0) > 0 ? 100 - ($data['normalradio'] + $data['overdueradio']) : 0;

        return $data ?? [];
    }

    /**
     * 逾期月份数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOverdueDayRadio()
    {
        $list = self::field('loan_endtime,payback_time')->select();
        $list = collection($list)->toArray();
        $total = 0;
        $one = $two = $three = $fore = $five = 0;
        foreach ($list as $k => $v){
            if ($v['payback_time'] > $v['loan_endtime']) {//逾期
                $payback_time = strtotime($v['payback_time']);
                $loan_endtime = strtotime($v['loan_endtime']);
                $day = ($payback_time - $loan_endtime) / 86400;
                if ($day >=1 && $day <=3){$one ++;}
                elseif ($day >=4 && $day <=10){$two ++;}
                elseif ($day >=11 && $day <=30){$three ++;}
                elseif ($day >=30 && $day <=69){$fore ++;}
                elseif ($day >=70){$five ++;}
                $total ++;
            }
        }
        $data['one'] = $total != 0 ? round(($one / $total) * 100 , 2) : 0;
        $data['two'] = $total != 0 ? round(($two / $total) * 100 , 2) : 0;
        $data['three'] = $total != 0 ? round(($three / $total) * 100 , 2) : 0;
        $data['fore'] = $total != 0 ? round(($fore / $total) * 100 , 2) : 0;
        $data['five'] = ($total != 0 ? round(($five / $total) * 100 , 2) : 0) > 0 ? 100 - ($data['one'] + $data['two'] + $data['three'] + $data['fore']) : 0;

        return $data ?? [];
    }

    /**
     * 逾期前五名排行
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOverdueRanking()
    {
        $list = self::field('country,sum(loan_price) as price')
            ->where(['loan_status_type'=>1])
            ->group('country')
            ->order('price','desc')
            ->limit(0,5)
            ->select();
        $list = collection($list)->toArray();
        return $list ?? [];
    }

    /**
     * 查询日期
     * @param $agomonth //前几月月初 6代表前六个月的月初
     * @return array
     */
    public static function getdatetime($agomonth)
    {
        //前几月
        $starttime = date("Y-m",mktime(0,0,0,date('m')-$agomonth,1,date('Y')));
        return [$starttime];
    }

    /**
     * 获取 守信的比例
     * @param $field 字段名
     * @param $value 字段值
     * @return float|int
     * @throws \think\Exception
     */
    protected static function getCreditRaidio($field,$value)
    {
        $credit = LoanUserInfo::where(["$field"=>$value,'credit_rating'=>['in',['A','AA','AAA','AAA+']]])->count();
        $nocredit = LoanUserInfo::where(["$field"=>$value,'credit_rating'=>['not in',['A','AA','AAA','AAA+']]])->count();
        $all = $credit + $nocredit;
        $ratio = $all != 0 ? round(($credit / $all) * 100,2) : 0 ;
        return $ratio;
    }

    /**
     * 处理数组 排序 并返回规定条数数组
     * @param $data 数组
     * @param $columnname 要排序的列名
     * @param $limit 返回数组条数
     */
    protected static function getArrayVrsort($data,$columnname,$limit,$order = SORT_DESC)
    {
        $last_names = array_column($data,$columnname);
        array_multisort($last_names,$order,$data);
        $data = array_splice($data,0,$limit);
        return $data;
    }

    protected static function getYears($num)
    {
        $nowy = date("Y");
        $years = [];
        for ($i=0;$i<=$num;$i++){
            array_unshift($years,($nowy - $i));
        }
        return $years;
    }

}