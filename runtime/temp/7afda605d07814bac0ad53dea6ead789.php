<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\auth\admin\edit.html";i:1608026201;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
                                <link rel="stylesheet" href="/assets/css/auth-admin/auth_admin.css">
<form id="edit-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
    <?php echo token(); ?>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Group'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_select('group[]', $groupdata, $groupids, ['class'=>'form-control selectpicker', 'multiple'=>'', 'data-rule'=>'required']); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2"><?php echo __('Service_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control hide" id="service_id" name="row[service_id]" value="<?php echo htmlentities($row['service_id']); ?>"/>
            <input type="text" class="form-control" readonly id="service_name" value="<?php echo htmlentities($row['service_name']); ?>"/>
            <button type="button" class="btn btn-primary select" data-toggle="modal" data-target="#myModal">选择服务站</button>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2"><?php echo __('Username'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="username" name="row[username]" value="<?php echo htmlentities($row['username']); ?>" data-rule="required;username" />
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2"><?php echo __('登录手机号'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="phone" name="row[phone]" value="<?php echo htmlentities($row['phone']); ?>" data-rule="required:mobile" />
        </div>
    </div>
    <div class="form-group">
        <label for="nickname" class="control-label col-xs-12 col-sm-2"><?php echo __('Nickname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="nickname" name="row[nickname]" autocomplete="off" value="<?php echo htmlentities($row['nickname']); ?>" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="control-label col-xs-12 col-sm-2"><?php echo __('Email'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="email" class="form-control" id="email" name="row[email]" value="<?php echo htmlentities($row['email']); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label col-xs-12 col-sm-2"><?php echo __('Password'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="password" class="form-control" id="password" name="row[password]" autocomplete="new-password" value="" data-rule="password" />
        </div>
    </div>
    <!--<div class="form-group">-->
        <!--<label for="loginfailure" class="control-label col-xs-12 col-sm-2"><?php echo __('Loginfailure'); ?>:</label>-->
        <!--<div class="col-xs-12 col-sm-8">-->
            <!--<input type="number" class="form-control" id="loginfailure" name="row[loginfailure]" value="<?php echo $row['loginfailure']; ?>" data-rule="required" />-->
        <!--</div>-->
    <!--</div>-->
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[status]', ['normal'=>__('Normal'), 'hidden'=>__('Hidden')], $row['status']); ?>
        </div>
    </div>
    <!-- 模态框 -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- 模态框头部 -->
                <div class="modal-header">
                    <h4 class="modal-title">选择服务站</h4>
                    <input type="text" class="hide" id="nowselectid">
                    <input type="text" disabled id="nowselectname">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- 模态框主体 -->
                <div class="modal-body">
                    <div id="editbody">
                        <div class="addbody1">
                            <?php if($servicedatas['area'] != []): ?>
                            <div class="jia xian"></div><span class="spans" sid="<?php echo $servicedatas['area']['id']; ?>"><?php echo $servicedatas['area']['name']; ?></span>
                            <?php endif; if($servicedatas['data'] != []): if(is_array($servicedatas['data']) || $servicedatas['data'] instanceof \think\Collection || $servicedatas['data'] instanceof \think\Paginator): $i = 0; $__LIST__ = $servicedatas['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                <div class="one <?php if($servicedatas['area'] != []): ?>hide<?php endif; ?>">
                                    <?php if($v['son'] != []): ?>
                                    <div class="jia xiang"></div><span class="spans" sid="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></span>
                                    <?php if(is_array($v['son']) || $v['son'] instanceof \think\Collection || $v['son'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                                    <div class="two hide">
                                        <span class="spans" sid="<?php echo $vv['id']; ?>"><?php echo $vv['name']; ?></span>
                                    </div>
                                    <?php endforeach; endif; else: echo "" ;endif; else: ?>
                                    <span class="spans" sid="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; endif; if($servicedatas['other'] != []): ?>
                                <div class="other">
                                    <?php if(is_array($servicedatas['other']) || $servicedatas['other'] instanceof \think\Collection || $servicedatas['other'] instanceof \think\Paginator): $i = 0; $__LIST__ = $servicedatas['other'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                    <span class="spans" sid="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></span>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 模态框底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">确定</button>
                </div>

            </div>
        </div>
    </div>
    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled submits"><?php echo __('OK'); ?></button>
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
