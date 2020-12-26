<?php

namespace app\api\validate;

use think\Validate;

class Loan extends Validate
{
    /**
     * 验证规则
     */
    protected $rule =   [
        'nickname' => 'require',
        'phone'     => 'require|regex:phone',
        'area'  => 'require',
        'country'  => 'require',
        'idcard'    => 'regex:idCard',
        'credit_rating'    => 'require',
        'loan_price'    => 'require',
        'loan_use'    => 'require',
        'loan_bank'    => 'require',
        'loan_starttime'    => 'require',
        'loan_endtime'    => 'require',
        'payback_time'    => 'require',
        'year_interest_rate'    => 'require',
        'discount_rate'    => 'require',
        'discount_price'    => 'require',
        'is_poor'    => 'require',
        'distribution'    => 'require',
        'entrustprove'    => 'require|regex:img',
        'loan_status_type'    => 'require',
        'credit_code'    => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'nickname.require' => '贷款人姓名不能为空',
        'phone.require'   => '贷款人手机号不能为空',
        'phone.regex'   => '贷款人手机号格式不正确',
        'area.require'   => '区县名称不能为空',
        'country.require'   => '乡镇名称不能为空',
        'idcard.require'   => '身份证号不能为空',
        'idcard.regex'   => '身份证号格式不正确',
        'credit_rating.require'   => '信用评级不能为空',
        'loan_price.require'   => '贷款金额不能为空',
        'loan_use.require'   => '贷款用途不能为空',
        'loan_bank.require'   => '贷款银行类别不能为空',
        'loan_starttime.require'   => '借款日不能为空',
        'loan_endtime.require'   => '到期日不能为空',
        'payback_time.require'   => '还款日期不能为空',
        'year_interest_rate.require'   => '年利率不能为空',
        'discount_rate.require'   => '贴息利率不能为空',
        'discount_price.require'   => '应贴息金额不能为空',
        'is_poor.require'   => '是否为贫困户不能为空',
        'entrustprove.require'   => '委托证明不能为空',
        'entrustprove.regex'   => '委托证明格式不正确',
        'credit_code.require'   => '社会信用码不能为空',
        'deputy_username.require'   => '法定代表人不能为空',
        'address.require'   => '注册地址不能为空',
        'staff_num.require'   => '员工人数不能为空',
        'uppoor_num.require'   => '带贫困户数不能为空',
        'uppoor_type.require'   => '带贫方式不能为空',
        'uppoor_roster.require'   => '带贫名单不能为空',
        'loanmodel.require'   => '贷款模式不能为空',
        'is_faucet.require'   => '是否为龙头企业不能为空',
        'loan_type.require'   => '是否为龙头企业不能为空',
        'loan_status_type.require'   => '贷款状态类型不能为空',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'useradd'  => [
           'nickname',
           'phone',
           'area',
           'country',
           'idcard',
           'credit_rating',
           'loan_price',
           'loan_use',
           'loan_bank',
           'loan_starttime',
           'loan_endtime',
           'payback_time',
           'year_interest_rate',
           'discount_rate',
           'discount_price',
           'is_poor',
           'distribution',
           'entrustprove',
           'loan_type',
           'loan_status_type',
        ],
        'enteradd'  => [
           'nickname',
           'credit_code',
           'deputy_username',
           'phone',
           'address',
           'staff_num',
           'uppoor_num',
           'uppoor_type',
           'uppoor_roster',
           'area',
           'country',
           'credit_rating',
           'loan_price',
           'loan_use',
           'loan_bank',
           'loan_starttime',
           'loan_endtime',
           'payback_time',
           'year_interest_rate',
           'discount_rate',
           'discount_price',
           'is_joincredit',
           'is_faucet',
           'loanmodel',
           'entrustprove',
           'loan_type',
           'loan_status_type',
        ],
    ];
    /**
     * [$regex 正则]
     * @var [type]
     */
    protected $regex = [  'img' => '/.*?(jpg|jpeg|gif|png)/',
                          'phone' => '/^[1][2,3,4,5,6,7,8,9][0-9]{9}$/',
                          'idCard'=> '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/'
    ];
}
