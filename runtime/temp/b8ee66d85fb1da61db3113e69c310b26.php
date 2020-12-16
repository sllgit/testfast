<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:101:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\organization\servicestation\detail.html";i:1606792202;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
                                <!--<style>-->
    <!--.form-control {-->
        <!--border:none;-->
        <!--background-color: #ffffff!important;-->
    <!--}-->
<!--</style>-->
<form id="detail-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Service_id'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-service_id" class="form-control" type="text" disabled value="<?php echo $row['service_id']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-shop_id" class="form-control" type="text" disabled value="<?php echo $row['name']; ?>">
            </div>
        </div>
    <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Type'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <select  id="c-type" class="form-control selectpicker" disabled>
                    <option value="">请选择服务站</option>
                    <option value="0" <?php if($row['type'] == 0): ?> selected <?php endif; ?>>县服务站</option>
                    <option value="1" <?php if($row['type'] == 1): ?> selected <?php endif; ?>>乡服务站</option>
                    <option value="2" <?php if($row['type'] == 2): ?> selected <?php endif; ?>>村服务站</option>
                    <option value="3" <?php if($row['type'] == 3): ?> selected <?php endif; ?>>银行</option>
                    <option value="4" <?php if($row['type'] == 4): ?> selected <?php endif; ?>>担保</option>
                </select>
            </div>
        </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Area'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-area"  disabled class="form-control" type="text" value="<?php echo htmlentities($row['area']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Country'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-country" class="form-control" disabled type="text" value="<?php echo htmlentities($row['country']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Village'); ?>::</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-village" class="form-control" type="text" disabled value="<?php echo htmlentities($row['village']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('pid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pid" class="form-control" disabled type="text" value="<?php echo htmlentities($row['pid']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Bank_id'); ?>::</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-bank_id" data-source="organization/servicestation/getbank?model=bank" disabled data-field="bank_name" data-primary-key="id" class="form-control selectpage" data-multiple="true" name="row[bank_id]" type="text" value="<?php echo htmlentities($row['bank_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Break'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-break" class="form-control" rows="5" disabled cols="50"><?php echo htmlentities($row['break']); ?></textarea>
        </div>
    </div>
     <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>::</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-status" class="form-control" name="" type="text" disabled
                       <?php switch($row['status']): case "0": ?>value="异常"<?php break; case "1": ?>value="正常"<?php break; default: endswitch; ?>
                >
            </div>
        </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">提交入库时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-createtime" disabled class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" type="text" value="<?php echo $row['createtime']?datetime($row['createtime']):''; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">修改时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-updatetime" disabled class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" type="text" value="<?php echo $row['updatetime']?datetime($row['updatetime']):''; ?>">
        </div>
    </div>
        <div class="hide layer-footer">
            <label class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-8">
                <button type="reset" class="btn btn-primary btn-embossed btn-close" onclick="Layer.closeAll();"><?php echo __('Close'); ?></button>
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
