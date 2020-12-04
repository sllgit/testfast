<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\loan\user\edit.html";i:1606477799;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
</style>
<form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-nickname" data-rule="required" class="form-control" name="row[nickname]" type="text" value="<?php echo htmlentities($row['nickname']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sex'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="col-xs-12 col-sm-8" >
                <?php echo build_radios('row[sex]', ['0'=>'男', '1'=>'女'],$row['sex']); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Age'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-age" class="form-control" name="row[age]" type="number" min="0" value="<?php echo htmlentities($row['age']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Phone'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-phone" class="form-control" name="row[phone]" type="text" value="<?php echo htmlentities($row['phone']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Area'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-area" class="form-control" name="row[area]" type="text" value="0">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Country'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-country" class="form-control" name="row[country]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Village'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-village" class="form-control" name="row[village]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Address'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-address" class="form-control" name="row[address]" type="text" value="<?php echo htmlentities($row['address']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-idcard" class="form-control" name="row[idcard]" type="text" value="<?php echo htmlentities($row['idcard']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Home_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-home_num" class="form-control" name="row[home_num]" type="number" min="0" value="<?php echo htmlentities($row['home_num']); ?>" style="width: 80%">&nbsp;&nbsp;<span>人</span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Home_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-home_name" class="form-control" name="row[home_name]" type="text" placeholder="多个以逗号隔开" value="<?php echo htmlentities($row['home_name']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_rating'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-credit_rating" class="form-control selectpicker" name="row[credit_rating]">
                <option value="1" selected>A</option>
                <option value="2">AA</option>
                <option value="3">AAA</option>
                <option value="4">AAA+</option>
                <option value="0">无信用</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Credit_grade'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-credit_grade" class="form-control" name="row[credit_grade]" type="number" min="0" value="<?php echo htmlentities($row['credit_grade']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_poor'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_poor]', ['0'=>'贫困户', '1'=>'已脱贫享受政策'],$row['is_poor']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Is_user'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[is_user]', ['1'=>'是', '0'=>'否'],$row['is_user']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_price'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_price" class="form-control" step="0.01" name="row[loan_price]" type="number" min="0" placeholder="单位：元" value="<?php echo htmlentities($row['loan_price']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_term'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-loan_term" class="form-control" name="row[loan_term]" type="number" min="0" style="width: 80%" value="<?php echo htmlentities($row['loan_term']); ?>"><span>月</span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_use'); ?>:</label>
        <div class="col-xs-12 col-sm-8 field">
            <?php echo build_radios('row[loan_use]', [
            '1'=>'林果业',
            '2'=>'畜牧业',
            '3'=>'食用菌',
            '4'=>'药材业',
            '5'=>'蔬菜业',
            '6'=>'其他',
            '7'=>'【特色工业】农副产品深加工',
            '8'=>'【特色工业】其他',
            '9'=>'【现代服务业】生态旅游',
            '10'=>'【特色工业】中药材深加工',
            '11'=>'【现代服务业】电子商务',
            '12'=>'【现代服务业】其他',
            ],$row['loan_use']); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_desc'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-loan_desc" class="form-control" rows="5" name="row[loan_desc]" cols="50"><?php echo htmlentities($row['loan_desc']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Loan_bank'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-loan_bank" class="form-control selectpicker" name="$row[loan_bank]">
                <?php if(is_array($bankinfo) || $bankinfo instanceof \think\Collection || $bankinfo instanceof \think\Paginator): if( count($bankinfo)==0 ) : echo "" ;else: foreach($bankinfo as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['loan_bank'])?$row['loan_bank']:explode(',',$row['loan_bank']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard_up'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-idcard_up" class="form-control hide" size="50" name="$row[idcard_up]" type="text" value="<?php echo htmlentities($row['idcard_up']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-idcard_up" class="btn btn-danger plupload cropper" data-input-id="c-idcard_up" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-idcard_up"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-idcard_up"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard_down'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-idcard_down" class="form-control hide" size="50" name="row[idcard_down]" type="text" value="<?php echo htmlentities($row['idcard_down']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-idcard_down" class="btn btn-danger plupload cropper" data-input-id="c-idcard_down" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-idcard_down"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-idcard_down"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Idcard_copy'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-idcard_copy" class="form-control hide" size="50" name="row[idcard_copy]" type="text" value="<?php echo htmlentities($row['idcard_copy']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-idcard_copy" class="btn btn-danger plupload cropper" data-input-id="c-idcard_copy" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-idcard_copy"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-idcard_copy"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Accountbook_copy'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-accountbook_copy" class="form-control hide" size="50" name="row[accountbook_copy]" type="text" value="<?php echo htmlentities($row['accountbook_copy']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-accountbook_copy" class="btn btn-danger plupload cropper" data-input-id="c-accountbook_copy" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-accountbook_copy"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-accountbook_copy"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Userloan_petition'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-userloan_petition" class="form-control hide" size="50" name="row[userloan_petition]" type="text" value="<?php echo htmlentities($row['userloan_petition']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-userloan_petition" class="btn btn-danger plupload cropper" data-input-id="c-userloan_petition" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-userloan_petition"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-userloan_petition"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Userloan_promise'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-userloan_promise" class="form-control hide" size="50" name="row[userloan_promise]" type="text" value="<?php echo htmlentities($row['userloan_promise']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-userloan_promise" class="btn btn-danger plupload cropper" data-input-id="c-userloan_promise" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-userloan_promise"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-userloan_promise"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Userloan_entrust'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-userloan_entrust" class="form-control hide" size="50" name="row[userloan_entrust]" type="text" value="<?php echo htmlentities($row['userloan_entrust']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-userloan_entrust" class="btn btn-danger plupload cropper" data-input-id="c-userloan_entrust" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-userloan_entrust"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-userloan_entrust"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Other_idcard_up'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-other_idcard_up" class="form-control hide" size="50" name="row[other_idcard_up]" type="text" value="<?php echo htmlentities($row['other_idcard_up']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-other_idcard_up" class="btn btn-danger plupload cropper" data-input-id="c-other_idcard_up" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-other_idcard_up"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-other_idcard_up"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Other_idcard_down'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-other_idcard_down" class="form-control hide" size="50" name="row[other_idcard_down]" type="text" value="<?php echo htmlentities($row['other_idcard_down']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-other_idcard_down" class="btn btn-danger plupload cropper" data-input-id="c-idcard_down" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-other_idcard_down"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-other_idcard_down"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Other_accountbook'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-other_accountbook" class="form-control hide" size="50" name="row[other_accountbook]" type="text" value="<?php echo htmlentities($row['other_accountbook']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-other_accountbook" class="btn btn-danger plupload cropper" data-input-id="c-other_accountbook" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-other_accountbook"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-other_accountbook"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Other_accountbook_copy'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-other_accountbook_copy" class="form-control hide" size="50" name="row[other_accountbook_copy]" type="text" value="<?php echo htmlentities($row['other_accountbook_copy']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-other_accountbook_copy" class="btn btn-danger plupload cropper" data-input-id="c-other_accountbook_copy" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-other_accountbook_copy"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-other_accountbook_copy"></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Marry_card'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-marry_card" class="form-control hide" size="50" name="row[marry_card]" type="text" value="<?php echo htmlentities($row['marry_card']); ?>" />
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-marry_card" class="btn btn-danger plupload cropper" data-input-id="c-marry_card" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/webp" data-multiple="false" data-preview-id="p-marry_card"><i class="fa fa-upload"></i>上传</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-marry_card"></ul>
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
