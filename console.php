#!/usr/bin/env php
<?php
/**
 *  @file:   console.php
 *  @brief:  人口文件
 *  @date:   2015-10-28
**/

define('ROOT_PATH',     __DIR__ .'/');
define('LIB_PATH',      ROOT_PATH .'library/');
define('APP_PATH',      ROOT_PATH .'app/');
define('APP_NAME',      'console');
define('YII_DEBUG',     TRUE);
define('YII_ENV',       'dev');

require(LIB_PATH . 'vendor/autoload.php');
require(LIB_PATH . 'vendor/yiisoft/yii2/Yii.php');
require(APP_PATH . 'common/config/alias.php');

$config = require(APP_PATH . APP_NAME .'/config/main.php');
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
