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
//M站
Route::rule('index',"index/Index/index");
Route::rule('show',"index/Index/show");

//微信登录
Route::rule('wechat',"index/Wechat/index");
Route::rule('wechat/login',"index/Wechat/login");
Route::rule('wechat/wechatLogin',"index/Wechat/wechatLogin");

//标签路由
Route::rule('tag/getAll',"index/Tags/getAllTag");
Route::rule('tag/insertTag',"index/Tags/insertTag");
Route::rule('tag/deleteTag',"index/Tags/deleteTag");
Route::rule('tag/updateTag',"index/Tags/updateTag");


Route::rule('uploadImage',"index/Index/index_InfoAction");
Route::rule('hello',"index/Index/test");
Route::rule('userInfo',"user/Index/index");
Route::rule('login',"user/Index/login");
Route::rule('wechatLogin',"user/Index/wechat_login");
Route::rule('wechatUrl',"index/Index/wechatUrl");


//后台管理
Route::rule('admin/login',"admin/Index/login");



//人像识别
Route::rule('aiperson/do',"index/Index/presonAi");

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
