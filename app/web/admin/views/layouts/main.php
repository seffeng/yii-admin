<?php
/**
 * 布局文件
*/

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use zxf\web\admin\components\AdminAsset;

AdminAsset::register($this);
AdminAsset::addScriptForIE($this);

$this->beginPage();
$web_url = Yii::getAlias('@web');
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo ($this->title ? ($this->title .' - ') : '') . Yii::$app->name; ?></title>
        <?php $this->head(); ?>
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <?php
            $this->beginBody();
            $getMenuUrl = Url::to(['menu-nav/get-menu-nav']);
            $this->registerJs(<<<js
            var _menu_url = '{$getMenuUrl}';
            CLS_AJAX.set_url(_menu_url).set_call(function(_res){CLS_MENU.init(_res);}).send();    /*load-menu*/
js
            );
        ?>
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
                                    <img src="<?php echo $web_url; ?>/static/images/user2-160x160.jpg" class="user-image" alt="User Image">
                                    <span class="hidden-xs">&nbsp;</span>
                                </a>
                                <ul class="dropdown-menu">
                                <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo $web_url; ?>/static/images/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        <p>
                                        <?php
                                            $user = \Yii::$app->user->identity;
                                            echo ArrayHelper::getValue($user, 'ad_username');
                                            $adg_name = ArrayHelper::getValue($user, 'adminGroup.adg_name');
                                            echo ($adg_name ? ' - ' : '') . $adg_name;
                                        ?>
                                        <small>
                                        <?php
                                            echo ArrayHelper::getValue($user, 'adminInfo.ai_name') .'<br />';
                                        ?>
                                        </small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="javascript:;" class="btn btn-default btn-flat" onclick="javascript:CLS_MENU.set_url('<?php echo Url::to(['admin/update-self']); ?>');">修改资料</a>
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
                <a title="返回顶部"><img src="<?php echo $web_url; ?>/static/images/scrollup.png"></a>
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
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>