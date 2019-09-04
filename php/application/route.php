<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 李子杰 <ansheng1021@163.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('index1',"index/Index/index");
//微信测试Token验证
Route::rule('wechat',"index/Wechat/index");
Route::rule('wechat/login',"index/Wechat/login");
Route::rule('wechat/wechatLogin',"index/Wechat/wechatLogin");

Route::rule('indexInfo',"index/Index/index_InfoAction");
Route::rule('hello',"index/Index/test");
Route::rule('userInfo',"user/Index/index");
Route::rule('login',"user/Index/login");
Route::rule('wechatLogin',"user/Index/wechat_login");
Route::rule('wechatUrl',"index/Index/wechatUrl");

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];
