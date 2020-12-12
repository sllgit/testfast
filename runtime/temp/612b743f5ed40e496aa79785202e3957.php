<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\phpstudy_pro\WWW\testfast\public/../application/admin\view\auth\admin\index.html";i:1607760223;s:71:"D:\phpstudy_pro\WWW\testfast\application\admin\view\layout\default.html";i:1602168705;s:68:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\meta.html";i:1602168705;s:70:"D:\phpstudy_pro\WWW\testfast\application\admin\view\common\script.html";i:1602168705;}*/ ?>
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
                                <meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="/assets/css/jstree/style.min.css">
<link rel="stylesheet" href="/assets/css/auth-admin/auth_admin.css">
<!--D:\phpStudy\PHPTutorial\WWW\testfast\public\assets\css\modules\layer\default\layer.css-->
<link rel="stylesheet" href="/assets/css/layui.css">
<link rel="stylesheet" href="/assets/css/modules/layer/default/layer.css">
<style>
    .layui-table-tips-c:before {
        right: -2.1px!important;
        top: -1px!important;
    }
    .layui-table-tips-c{
        padding: 0px!important;
        right: -12px!important;
    }
    #layui-table-page1{
        text-align: center!important;
    }
    .layui-laypage-btn,.layui-input{
        color: black!important;
    }
</style>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>
    <div class="panel-body">
        <div id="leftbody">
            <div class="leftbody1">
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
        <span id="service_id" class="hide"><?php echo $servicedatas['id']; ?></span>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="demoTable">
                    <div class="layui-inline">
                        <input class="layui-input" name="id" id="demoReload" autocomplete="off" placeholder="可通过账号或姓名进行查找">
                    </div>
                    <button class="layui-btn" data-type="reload">搜索</button>
                    <a href="/auth/admin/add" data-area='["50%","95%"]' class="btn btn-success btn-dialog <?php echo $auth->check('auth/admin/add')?'':'hide'; ?>" title="<?php echo __('Add'); ?>" style="float: right;margin-right: 3vw" ><i class="fa fa-plus"></i> <?php echo __('Add'); ?></a>
                    <a href="javascript:;" data-area='["50%","95%"]' class="edits btn btn-success btn-dialog hide" title="<?php echo __('Edit'); ?>" style="float: right;margin-right: 3vw" ><i class="fa fa-plus"></i> <?php echo __('Edit'); ?></a>

                </div>
                <table class="layui-hide" id="test" lay-filter="test"></table>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs <?php echo $auth->check('auth/admin/edit')?'':'hide'; ?>" lay-event="edit">修改</a>
    {{# if (d.status == 'normal'){}}
    <a class="layui-btn layui-btn-xs <?php echo $auth->check('auth/admin/status')?'':'hide'; ?>" lay-event="close">关停</a>
    {{# } else { }}
    <a class="layui-btn layui-btn-xs <?php echo $auth->check('auth/admin/status')?'':'hide'; ?>" lay-event="open">恢复</a>
    {{# } }}
    <a class="layui-btn layui-btn-danger layui-btn-xs <?php echo $auth->check('auth/admin/del')?'':'hide'; ?>" lay-event="del">删除</a>
</script>
<script src="/assets/js/jquery-3.4.1.min.js"></script>
<script src="/assets/js/jstree/jstree.min.js"></script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
