<?php
/**
 *  @file:   params.php
 *  @brief:  配置额外参数
**/

$params = [];
$SERVER_NAME = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
$params['admin_home']   = '/default/index';
$params['admin_logout'] = '/default/logout';
$params['www_url'] = 'http://'. str_replace('admin.', '', $SERVER_NAME) .'/';
return $params;