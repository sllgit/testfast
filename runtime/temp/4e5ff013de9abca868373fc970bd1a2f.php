<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:99:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\loanenterprise\enterprise\detail.html";i:1608805910;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo \think\Config::get('fastadmin.adminskin'); ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <style>
    .field label{
        width: 100%;
    }
    .btn-trash{
        display: none!important;
    }
</style>
<form id="detail-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_faucet'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="is_faucet" class="form-control" name="" type="text" disabled
                   <?php switch($row['is_faucet']): case "否": ?>value="否"<?php break; case "是": ?>value="是"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo __('Areas'); ?>:</label>
            <div class="col-sm-7">
                <input id="c-area" class="form-control" disabled type="text" value="<?php echo htmlentities($row['a_c_v']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-nickname" data-rule="required" class="form-control" disabled name="row[nickname]" type="text" value="<?php echo htmlentities($row['nickname']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_code'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-credit_code" data-rule="required" class="form-control" disabled name="row[credit_code]" type="text" value="<?php echo htmlentities($row['credit_code']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Deputy_username'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-deputy_username" class="form-control" name="row[deputy_username]" disabled type="text" min="0" value="<?php echo htmlentities($row['deputy_username']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Phone'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-phone" class="form-control" name="row[phone]" disabled type="text" value="<?php echo htmlentities($row['phone']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Address'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-address" class="form-control" name="row[address]" disabled type="text" value="<?php echo htmlentities($row['address']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Staff_num'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-staff_num" class="form-control" name="row[staff_num]" disabled placeholder="请输入主要股东数量" type="text" value="<?php echo htmlentities($row['staff_num']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_price'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-loan_price" class="form-control" name="row[loan_price]" disabled type="number" min="0" value="<?php echo htmlentities($row['loan_price']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_starttime'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-loan_starttime" data-rule="required" class="form-control datetimepicker" disabled data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_starttime]" type="text" value="<?php echo htmlentities($row['loan_starttime']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_endtime'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-loan_endtime" data-rule="required" class="form-control datetimepicker" disabled data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_endtime]" type="text" value="<?php echo htmlentities($row['loan_endtime']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Payback_time'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-payback_time" data-rule="required" class="form-control datetimepicker" disabled data-date-format="YYYY-MM-DD" data-use-current="true" name="row[payback_time]" type="text" value="<?php echo htmlentities($row['payback_time']); ?>">
            </div>
        </div>
        <div class="form-group Res hide">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Overduereason'); ?>:</label>
            <div class="col-xs-12 col-sm-8 field">
                <select  id="c-overduereason" class="form-control selectpicker" name="$row[overduereason]" disabled>
                    <?php if(is_array($overduereason) || $overduereason instanceof \think\Collection || $overduereason instanceof \think\Paginator): if( count($overduereason)==0 ) : echo "" ;else: foreach($overduereason as $key=>$vo): ?>
                    <option value="<?php echo $vo; ?>" <?php if(in_array(($vo), is_array($row['overduereason'])?$row['overduereason']:explode(',',$row['overduereason']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <!--<div class="form-group">-->
        <!--<label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_term'); ?>:</label>-->
        <!--<div class="col-xs-12 col-sm-8">-->
        <!--<input id="c-loan_term" class="form-control" name="row[loan_term]" disabled type="text" value="">-->
        <!--</div>-->
        <!--</div>-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Year_interest_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-year_interest_rate" class="form-control" data-rule="required" name="row[year_interest_rate]" type="number" disabled min="0" value="<?php echo htmlentities($row['year_interest_rate']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Discount_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-discount_rate" class="form-control" data-rule="required" name="row[discount_rate]" disabled type="number" min="0" value="<?php echo htmlentities($row['discount_rate']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Discount_price'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-discount_price" class="form-control" data-rule="required" name="row[discount_price]" disabled type="number" min="0" value="<?php echo htmlentities($row['discount_price']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Supportprice'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-supportprice" class="form-control" data-rule="required" name="row[supportprice]" disabled type="number" min="0" value="<?php echo htmlentities($row['supportprice']); ?>">
        </div>
    </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Uppoor_num'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-uppoor_num" class="form-control" name="row[uppoor_num]" disabled type="text" value="<?php echo htmlentities($row['uppoor_num']); ?>">
            </div>
        </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loan_bank" class="form-control selectpicker" name="$row[loan_bank]" disabled>
                <?php if(is_array($bankinfo) || $bankinfo instanceof \think\Collection || $bankinfo instanceof \think\Paginator): if( count($bankinfo)==0 ) : echo "" ;else: foreach($bankinfo as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['loan_bank'])?$row['loan_bank']:explode(',',$row['loan_bank']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_rating'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="credit_rating" class="form-control" name="" type="text" disabled
                   <?php switch($row['credit_rating']): case "无信用": ?>value="无信用"<?php break; case "A": ?>value="A"<?php break; case "AA": ?>value="AA"<?php break; case "AAA": ?>value="AAA"<?php break; case "AAA+": ?>value="AAA+"<?php break; default: endswitch; ?>
            >
        </div>
    </div><?php echo $row['loan_status_type']; ?>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_status_type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loan_status_type" class="form-control selectpicker" name="$row[loan_status_type]" disabled>
                <?php if(is_array($loanstatustype) || $loanstatustype instanceof \think\Collection || $loanstatustype instanceof \think\Paginator): if( count($loanstatustype)==0 ) : echo "" ;else: foreach($loanstatustype as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['loan_status_type'])?$row['loan_status_type']:explode(',',$row['loan_status_type']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loanmodel'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loanmodel" class="form-control selectpicker" name="$row[loanmodel]" disabled>
                <?php if(is_array($loanmodel) || $loanmodel instanceof \think\Collection || $loanmodel instanceof \think\Paginator): if( count($loanmodel)==0 ) : echo "" ;else: foreach($loanmodel as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['loanmodel'])?$row['loanmodel']:explode(',',$row['loanmodel']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_type'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-loan_type" class="form-control" name="" type="text" disabled
                       <?php switch($row['loan_type']): case "新增": ?>value="新增"<?php break; case "续贷": ?>value="续贷"<?php break; default: endswitch; ?>
                >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Uppoor_type'); ?>:</label>
            <div class="col-xs-12 col-sm-8 field">
                <?php echo build_checkboxs('row[uppoor_type][]', [
                '1'=>'统一供种(供苗、幼崽等)',
                '2'=>'技术指导方式带贫',
                '3'=>'提供原料或作为企业原料等产品保底价或优惠价回收方式带贫',
                '4'=>'吸纳就业方式带贫',
                '5'=>'针对农产品产业通过加工、延伸产业链方式带贫',
                '6'=>'贫困户土地经营权、林权等入股或土地流转企业方式带贫',
                '7'=>'其他',
                ],$row['uppoor_type']); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Uppoor_roster'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <div class="input-group">
                    <input id="c-uppoor_roster" class="form-control hide" size="50" name="row[uppoor_roster]" type="text" value="<?php echo htmlentities($row['uppoor_roster']); ?>" />
                    <div class="input-group-addon no-border no-padding">
                        <span><button type="button" id="plupload-uppoor_roster" class="btn btn-danger plupload hide" data-input-id="c-uppoor_roster" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="true" data-preview-id="p-uppoor_roster"><i class="fa fa-upload"></i>上传</button></span>
                    </div>
                    <span class="msg-box n-right"></span>
                </div>
                <ul class="row list-inline plupload-preview" id="p-uppoor_roster"></ul>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Entrustprove'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <div class="input-group">
                    <input id="c-entrustprove" class="form-control hide" size="50" name="row[entrustprove]" type="text" value="<?php echo htmlentities($row['entrustprove']); ?>" />
                    <div class="input-group-addon no-border no-padding">
                        <span><button type="button" id="plupload-entrustprove" class="btn btn-danger plupload hide" data-input-id="c-entrustprove" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="true" data-preview-id="p-entrustprove"><i class="fa fa-upload"></i>上传</button></span>
                    </div>
                    <span class="msg-box n-right"></span>
                </div>
                <ul class="row list-inline plupload-preview" id="p-entrustprove"></ul>
            </div>
        </div>
    </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
