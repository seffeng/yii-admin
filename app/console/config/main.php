<?php
/**
 *  @file:   main.php
 *  @brief:  配置文件
**/

return [
    'id' => APP_NAME,
    'basePath' => dirname(__DIR__),
    'vendorPath' => LIB_PATH . 'vendor',
    'runtimePath' => ROOT_PATH .'data/runtime/'. APP_NAME,
    'controllerNamespace' => 'appdir\console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=my_118456',
            'username' => 'user',
            'password' => 'pass',
            'charset'  => 'utf8',
            'tablePrefix' => 'yi_',
        ],
    ],
];