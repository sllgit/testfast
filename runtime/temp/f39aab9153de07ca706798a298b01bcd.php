<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\loan\user\detail.html";i:1608102131;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
    .btn-trash{
        display: none!important;
    }
</style>
<form id="detail-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-id" readonly class="form-control" type="text" value="<?php echo $row['id']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Local_no'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-local_no" readonly class="form-control" type="text" value="<?php echo $row['local_no']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-nickname" readonly class="form-control" type="text" value="<?php echo $row['nickname']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Phone'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-phone" readonly class="form-control" type="text" value="<?php echo $row['phone']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo __('Address'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-area" class="form-control" disabled type="text" value="<?php echo htmlentities($row['a_c_v']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-idcard" class="form-control" disabled name="row[idcard]" type="text" value="<?php echo htmlentities($row['idcard']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_poor'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="is_poor" class="form-control" name="" type="text" disabled
                   <?php switch($row['is_poor']): case "是": ?>value="是"<?php break; case "否": ?>value="否"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_rating'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="credit_rating" class="form-control" name="" type="text" disabled
                   <?php switch($row['credit_rating']): case "无信用": ?>value="无信用"<?php break; case "A": ?>value="A"<?php break; case "AA": ?>value="AA"<?php break; case "AAA": ?>value="AAA"<?php break; case "AAA+": ?>value="AAA+"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_starttime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_starttime" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_starttime]" disabled type="text" value="<?php echo htmlentities($row['loan_starttime']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_endtime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_endtime" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_endtime]" disabled type="text" value="<?php echo htmlentities($row['loan_endtime']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Payback_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-payback_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[payback_time]" disabled type="text" value="<?php echo htmlentities($row['payback_time']); ?>">
        </div>
    </div>
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
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_use'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_use" class="form-control" name="" type="text" disabled
                   <?php switch($row['loan_use']): case "无": ?>value="无"<?php break; case "第一产业": ?>value="第一产业"<?php break; case "第二产业": ?>value="第二产业"<?php break; case "第三产业": ?>value="第三产业"<?php break; default: endswitch; ?>
            >
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
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loan_bank" class="form-control selectpicker" name="$row[loan_bank]" disabled>
                <?php if(is_array($bankinfo) || $bankinfo instanceof \think\Collection || $bankinfo instanceof \think\Paginator): if( count($bankinfo)==0 ) : echo "" ;else: foreach($bankinfo as $key=>$vo): ?>
                <option value="<?php echo $vo; ?>" <?php if(in_array(($vo), is_array($row['loan_bank'])?$row['loan_bank']:explode(',',$row['loan_bank']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank_desc'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-loan_bank_desc" class="form-control" rows="5" placeholder="" name="row[loan_bank_desc]" disabled cols="50"><?php echo htmlentities($row['loan_bank_desc']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_joincredit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-is_joincredit" class="form-control" name="" type="text" disabled
                   <?php switch($row['is_joincredit']): case "未参加过": ?>value="未参加过"<?php break; case "良好": ?>value="良好"<?php break; case "优秀": ?>value="优秀"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_safe'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-is_safe" class="form-control" name="" type="text" disabled
                   <?php switch($row['is_safe']): case "否": ?>value="否"<?php break; case "是": ?>value="是"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Check_status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-check_status" class="form-control" name="" type="text" disabled
                   <?php switch($row['check_status']): case "0": ?>value="待审核"<?php break; case "1": ?>value="审核不通过"<?php break; case "2": ?>value="银行审核中"<?php break; case "3": ?>value="银行审核通过"<?php break; case "4": ?>value="银行审核未通过"<?php break; default: endswitch; ?>
            >
        </div>
    </div>
    <?php if(($row['check_status'] == 3) OR ($row['check_status'] == 4)): ?>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Bank_info'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <textarea id="c-bank_info" class="form-control" rows="5" placeholder="" name="row[bank_info]" disabled cols="50"><?php echo $row['bank_info']; ?></textarea>
            </div>
        </div>
    <?php endif; ?>
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
