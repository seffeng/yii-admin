<?php
/**
 *  @file:   alias.php
 *  @brief:  别名配置文件
**/

Yii::setAlias('common',     dirname(__DIR__));
Yii::setAlias('appdir',     dirname(dirname(__DIR__)));
isset($_SERVER['HTTP_HOST']) && Yii::setAlias('www_url',    '//'.$_SERVER['HTTP_HOST']);