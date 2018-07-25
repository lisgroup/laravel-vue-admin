<?php

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');

//解决虚拟主机或二级目录下的css样式找不到的问题
if (!defined('__ROOT__'))
{
    $_root = rtrim(dirname(trim($_SERVER['SCRIPT_NAME'], '/')), '/');
    define('__ROOT__', (($_root == '/' || $_root == '\\') ? '' : $_root));
}

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
