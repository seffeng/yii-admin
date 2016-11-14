<?php
/**
 *  @file:   main.php
 *  @brief:  配置文件
**/

return [
    'id' => APP_NAME,
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'appdir\admin\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'appdir\admin\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl' => ['/default/login'],
        ],
    ],
    'params' => include(__DIR__ . '/params.php'),
];