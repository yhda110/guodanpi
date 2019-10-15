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

//获取用户信息
Route::rule('api/user/getUserInfo',"index/Wechat/getUserInfo");

Route::rule('index',"index/Index/index");
Route::rule('show',"index/Index/show");
//帖子上传
Route::rule('api/thread/getThread',"index/Thread/getThread");
Route::rule('api/thread/getOneThread',"index/Thread/getOneThread");
Route::rule('api/thread/upload',"index/Thread/threadUpload");

//新闻咨询
Route::rule('api/news',"index/Index/news");
Route::rule('api/news/detail',"index/Index/newsGetDetail");


//微信登录
Route::rule('wechat',"index/Wechat/index");
Route::rule('wechat/login',"index/Wechat/login");
Route::rule('wechat/wechatLogin',"index/Wechat/wechatLogin");

//标签路由


//七牛token
Route::rule('api/Image/QiniuGetToken',"index/Index/QiniuGetToken");


Route::rule('api/uploadImage',"index/Index/index_InfoAction");
Route::rule('api/hello',"index/Index/test");
Route::rule('api/userInfo',"user/Index/index");
Route::rule('api/login',"user/Index/login");
Route::rule('wechatLogin',"user/Index/wechat_login");
Route::rule('wechatUrl',"index/Index/wechatUrl");


//后台管理
Route::rule('admin',"admin/Index/index");

Route::rule('api/admin/user/getUser',"admin/Index/getUsers");
Route::rule('api/admin/user/updateUserStatus',"admin/Index/updateUserStatus");

Route::rule('api/admin/login',"admin/Index/login");
Route::rule('api/admin/thread/publishThread',"admin/Index/publishThread");

Route::rule('api/admin/thread/getThread',"admin/Index/getThread");
Route::rule('api/admin/thread/getOneThread',"admin/Index/getOneThread");

Route::rule('api/admin/tag/getAll',"admin/Tags/getAllTag");
Route::rule('api/admin/tag/insertTag',"admin/Tags/insertTag");
Route::rule('api/admin/tag/deleteTag',"admin/Tags/deleteTag");
Route::rule('api/admin/tag/updateTag',"admin/Tags/updateTag");
Route::rule('api/documents',"Reflection/Api/Doc/Documents@run");

//人像识别
Route::rule('api/aiperson/do',"index/Index/presonAi");

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
