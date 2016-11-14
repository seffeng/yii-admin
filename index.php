<?php
/**
 * @description: 入口文件
 * @file: index.php
 * @charset: UTF-8
**/

define('ROOT_PATH',     __DIR__ .'/');
define('LIB_PATH',      ROOT_PATH .'library/');
define('APP_PATH',      ROOT_PATH .'app/');
define('APP_NAME',      'admin');
define('YII_DEBUG',     TRUE);
define('YII_ENV',       'dev');

require(LIB_PATH . 'vendor/autoload.php');
require(LIB_PATH . 'vendor/yiisoft/yii2/Yii.php');
require(APP_PATH . 'common/config/alias.php');

$config = yii\helpers\ArrayHelper::merge(
    require(APP_PATH . 'common/config/main.php'),
    require(APP_PATH . APP_NAME .'/config/main.php')
);
require(APP_PATH .'common/function/f.g.php');

define('THIS_TIME',     time());
define('THIS_IP',       get_ip());
define('THIS_IP_LONG',  ip_long(THIS_IP));

(new yii\web\Application($config)) -> run();