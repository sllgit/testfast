<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\loan\user\edit.html";i:1608806306;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <input type="hidden" name="row[applyedit_id]" value="<?php echo htmlentities($row['applyedit_id']); ?>">
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-nickname" data-rule="required" class="form-control" name="row[nickname]" type="text" value="<?php echo htmlentities($row['nickname']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Phone'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-phone" class="form-control" name="row[phone]" type="text" value="<?php echo htmlentities($row['phone']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo __('Address'); ?>:</label>
        <div class="col-sm-7">
            <select style="width:92PX;height:35px;border:1px solid #ccc;" id="countyId" data-rule="required" name="row[area]">
                <option value="嵩县">嵩县</option>
            </select>
            <select style="width:92PX;height:35px;border:1px solid #ccc;" id="townId" name="row[country]" data-value="<?php echo $row['country']; ?>">
                <option value="">请选择</option>
            </select>
            <select style="width:92PX;height:35px;border:1px solid #ccc;" id="villageId" name="row[village]" data-value="<?php echo $row['village']; ?>">
                <option value="">请选择</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-idcard" class="form-control" name="row[idcard]" type="text" value="<?php echo htmlentities($row['idcard']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_poor'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_poor]', ['是'=>'是', '否'=>'否'],$row['is_poor']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_rating'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-credit_rating" class="form-control selectpicker" name="row[credit_rating]">
                <option value="A" <?php if($row['credit_rating'] == 'A'): ?> selected <?php endif; ?>>A</option>
                <option value="AA" <?php if($row['credit_rating'] == 'AA'): ?> selected <?php endif; ?>>AA</option>
                <option value="AAA" <?php if($row['credit_rating'] == 'AAA'): ?> selected <?php endif; ?>>AAA</option>
                <option value="AAA+" <?php if($row['credit_rating'] == 'AAA+'): ?> selected <?php endif; ?>>AAA+</option>
                <option value="无信用" <?php if($row['credit_rating'] == '无信用'): ?> selected <?php endif; ?>>无信用</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_starttime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_starttime" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_starttime]" type="text" value="<?php echo htmlentities($row['loan_starttime']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_endtime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_endtime" data-rule="required" class="form-control datetimepicker Time" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[loan_endtime]" type="text" value="<?php echo htmlentities($row['loan_endtime']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Payback_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-payback_time" data-rule="required" class="form-control datetimepicker Time" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[payback_time]" type="text" value="<?php echo htmlentities($row['payback_time']); ?>">
        </div>
    </div>
    <div class="form-group Res hide">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Overduereason'); ?>:</label>
        <div class="col-xs-12 col-sm-8 field">
            <?php echo build_radios('row[overduereason]', $overduereason ); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Year_interest_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-year_interest_rate" class="form-control" data-rule="required" name="row[year_interest_rate]" type="number" min="0" value="<?php echo htmlentities($row['year_interest_rate']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Discount_rate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-discount_rate" class="form-control" data-rule="required" name="row[discount_rate]" type="number" min="0" value="<?php echo htmlentities($row['discount_rate']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Discount_price'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-discount_price" class="form-control" data-rule="required" name="row[discount_price]" type="number" min="0" value="<?php echo htmlentities($row['discount_price']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Supportprice'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-supportprice" class="form-control" data-rule="required" name="row[supportprice]" type="number" min="0" value="<?php echo htmlentities($row['supportprice']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_status_type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[loan_status_type]', $loanstatustype ,$row['loan_status_type']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_use'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[loan_use]', $loanuse ,$row['loan_use']); ?>
        </div>
    </div>
    <div class="form-group Dis">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Distribution'); ?>:</label>
        <div class="col-xs-12 col-sm-8 field">
            <?php echo build_radios('row[distribution]', $distribution,$row['distribution']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[loan_type]', ['新增'=>'新增', '续贷'=>'续贷'],$row['loan_type']); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loan_bank" class="form-control selectpicker" name="$row[loan_bank]">
                <?php if(is_array($bankinfo) || $bankinfo instanceof \think\Collection || $bankinfo instanceof \think\Paginator): if( count($bankinfo)==0 ) : echo "" ;else: foreach($bankinfo as $key=>$vo): ?>
                <option value="<?php echo $vo; ?>" <?php if(in_array(($vo), is_array($row['loan_bank'])?$row['loan_bank']:explode(',',$row['loan_bank']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank_desc'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-loan_bank_desc" class="form-control" rows="5" placeholder="" name="row[loan_bank_desc]" cols="50"><?php echo htmlentities($row['loan_bank_desc']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_joincredit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_joincredit]', $isjoincredit ,$row['is_joincredit']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_safe'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_safe]', ['否'=>'否', '是'=>'是'],$row['is_safe']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Entrustprove'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-entrustprove" class="form-control hide" size="50" name="row[entrustprove]" type="text" value="<?php echo htmlentities($row['entrustprove']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-entrustprove" class="btn btn-danger plupload cropper" data-input-id="c-entrustprove" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="true" data-preview-id="p-entrustprove"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-entrustprove"></ul>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
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
