<?php
/**
 *  @file:   main.php
 *  @brief:  默认布局文件
 **/

use yii\helpers\Url;
use appdir\admin\models\Admin;

$web_url = Yii::getAlias('@web');
if (!Admin::isLogin()) {
    to_url(Url::to(Yii::$app->user->loginUrl));
}
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Blank Page</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/plugins/bootstrap/css/bootstrap.min.css">
        <!-- Bootstrap-Switch -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/plugins/font-awesome/css/font-awesome.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/skins.min.css">
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/default.css">
        <!-- ./wrapper -->
        <script src="<?php echo $web_url; ?>/assets/js/jquery.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo $web_url; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!-- Bootstrap-Switch -->
        <script src="<?php echo $web_url; ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?php echo $web_url; ?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- Ueditor -->
        <script src="<?php echo $web_url; ?>/assets/plugins/ueditor/ueditor.config.js"></script>
        <script src="<?php echo $web_url; ?>/assets/plugins/ueditor/ueditor.all.min.js"></script>
        <script src="<?php echo $web_url; ?>/assets/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $web_url; ?>/assets/js/app.min.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_global.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_ajax.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_menu.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_alert.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="<?php echo $web_url; ?>/assets/js/html5shiv.min.js"></script>
            <script src="<?php echo $web_url; ?>/assets/js/respond.min.js"></script>
        <![endif]-->
        <script>
            var _menu_url = "<?php echo Url::to(['menu/get-menu']); ?>";
            CLS_AJAX.set_url(_menu_url).set_call(function(_res){CLS_MENU.init(_res);}).send();    /*load-menu*/
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody(); ?>
        <div class="alert-box"></div>
        <!-- Site wrapper -->
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="javascript:;" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>A</b>LT</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Admin</b>LTE</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Notifications: style can be found in dropdown.less -->
                            <li class="dropdown notifications-menu">
                                <a href="<?php echo Url::to(Yii::$app->params['www_url']); ?>" target="_blank">前台首页</a>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo $web_url; ?>/assets/images/user2-160x160.jpg" class="user-image" alt="User Image">
                                    <span class="hidden-xs">&nbsp;</span>
                                </a>
                                <ul class="dropdown-menu">
                                <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo $web_url; ?>/assets/images/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        <p>
                                        <?php
                                            $user = \Yii::$app->user->identity;
                                            echo $user->ad_username;
                                            if(isset($user->adminGroup->adg_name) && $user->adminGroup->adg_name) echo ' - '. $user->adminGroup->adg_name;
                                        ?>
                                        <small>
                                        <?php
                                            if(isset($user->adminInfo->ai_name) && $user->adminInfo->ai_name) echo $user->adminInfo->ai_name .'<br />';
                                            if(isset($user->adminInfo->ai_nickname) && $user->adminInfo->ai_nickname) echo $user->adminInfo->ai_nickname;
                                        ?>
                                        </small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="javascript:;" class="btn btn-default btn-flat" onclick="javascript:CLS_MENU.set_url('<?php echo Url::to(["default/update-info"]); ?>');">修改资料</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo Url::to(Yii::$app->params['admin_logout']); ?>" class="btn btn-default btn-flat">退出</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" id="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class="treeview"><a href="javascript:;"><i class="fa fa-spin fa-spinner"></i> <span>Loading...</span> </a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 id="breadcrumb_h"><?php echo isset($this->params['breadcrumb']['0']) ? $this->params['breadcrumb']['0'] : '首页'; ?></h1>
                    <ol class="breadcrumb">
                        <li><a href="javascript:;"  id="breadcrumb_a"><?php echo isset($this->params['breadcrumb']['1']) ? $this->params['breadcrumb']['1'] : '后台管理'; ?></a></li>
                        <li class="active" id="breadcrumb_li"><?php echo isset($this->params['breadcrumb']['2']) ? $this->params['breadcrumb']['2'] : '首页'; ?></li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="box" id="main_content">
                        <?php echo $content; ?>
                    </div>
                    <div class="modal fade" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="modal_title">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modal_title">提示</h4>
                                </div>
                                <div class="modal-body" id="modal_body">......</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" class="btn btn-primary" id="dialog_ok">确定</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <div id="totop" style="display: block; opacity: 1;">
                <a title="返回顶部"><img src="<?php echo $web_url; ?>/assets/images/scrollup.png"></a>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <a href="//www.miitbeian.gov.cn/" target="_blank">备案号</a>
                </div>
                <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="//weibo.com/seffeng" target="_blank">@Seffeng</a></strong>
                <a href="//weibo.com/seffeng" target="_blank" class="fa fa-weibo" title="微博"></a>
                <a href="//seffeng.blog.163.com" target="_blank" class="fa fa-rss" title="博客"></a>
                <a href="//pan.baidu.com/share/home?uk=2636118461" target="_blank" class="fa fa-paw" title="网盘"></a>
                <a href="//github.com/seffeng" target="_blank" class="fa fa-github" title="GitHub"></a>
            </footer>
        </div>
        <script src="<?php echo $web_url; ?>/assets/js/totop.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_form.js"></script>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>