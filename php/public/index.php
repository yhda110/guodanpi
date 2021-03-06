<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 李子杰 <ansheng1021@163.com>

// +----------------------------------------------------------------------

// [ 应用入口文件 ]
$default_controller = 'Main';
$default_action = 'Index';

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
// 在这里引入Composer的自动加载文件
include "../vendor/autoload.php";
include "../vendor/qiniu/php-sdk/autoload.php";