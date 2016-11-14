<?php
/**
 *  @file:   login.php
 *  @brief:  登录文件
 **/

$this->context->layout = FALSE;
$web_url = Yii::getAlias('@web');
?>
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
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/plugins/font-awesome/css/font-awesome.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/skins.min.css">
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/plugins/iCheck/square/blue.css">
        <link rel="stylesheet" href="<?php echo $web_url; ?>/assets/css/default.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="<?php echo $web_url; ?>/assets/js/html5shiv.min.js"></script>
            <script src="<?php echo $web_url; ?>/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="alert-box"></div>
        <div class="login-box">
            <div class="login-logo">
                <a><b>Admin</b>LTE</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body box" id="main_content">
                <p class="login-box-msg">Sign in to start your session</p>
                <form>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="username" placeholder="username">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="userpass" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary btn-block btn-flat" id="login_btn" data-loading-text="Loading..." autocomplete="off">登录</button>
                        </div><!-- /.col -->
                    </div>
                </form>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <script src="<?php echo $web_url; ?>/assets/js/jquery.min.js"></script>
        <script src="<?php echo $web_url; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_login.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_alert.js"></script>
        <script src="<?php echo $web_url; ?>/assets/js/cls_ajax.js"></script>
        <script>
            CLS_LOGIN.init({user_id : 'username', pass_id : 'userpass', btn_id : 'login_btn', 'login_url' : ''});
        </script>
    </body>
</html>
