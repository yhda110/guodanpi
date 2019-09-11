<?php
namespace system\userService;
use think\Cookie;
use app\user\model\User;
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/11
 * @Time: 11:35
 */

class userService
{
    public function isLogin()
    {
        $userModel = new User();
        if(Cookie::has('_token') && Cookie::has('_pass')){
               $_token = Cookie::get('_token');
               $_pass = Cookie::get('_pass');
               $_token = authcode($_token,'DECODE');
               $info = $userModel->getUserByUser($_token);
               if(md5($info['pass'],'gdpweb') != $_pass){
                   return 0;
//                   return 0;
               }
               if(empty($info)){
                   return 0;
               }else{
                   return $info['id'];
               }
        }else{
            return 0;
        }
    }
    public function doLogin($info)
    {
        $_token = authcode($info['user'],'ENCODE');
        $_pass = md5($info['pass'],'gdpweb');
        Cookie::set('_token',$_token);
        Cookie::set('_pass',$_pass);
        Cookie::set('is_login',1);
        return 1;
    }
}