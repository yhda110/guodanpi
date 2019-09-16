<?php
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/4
 * @Time: 18:53
 */

namespace app\admin\controller;
use app\user\model\User;
use think\Request;
use think\db;
use think\controller;
use app\admin\BaseController;
class Index extends BaseController
{
    private $UserModel;
    static $result = array(
        500 => '账号名或者密码为空',
        501 => '用户不存在',
        502 => '无登录权限',
        503 => '密码错误'
    );


    public function login(Request $request)
    {
        $this->UserModel = new User();
        $parme = $request->param();
        $user = trim($parme['username']);
        $pass = trim($parme['password']);
        if(isset($user) && isset($pass)){
            $userInfo = $this->UserModel->fieldUser(['id','user','nickname','type','pass'],"user='$user'");
            if(empty($userInfo)){
               returnJsonErrorInfo(self::$result[501],501);
               exit();
            }
            $userType = $userInfo['type'];
            if($userType != 1){
                returnJsonErrorInfo(self::$result[502],502);
                exit();
            }
            if(md5($pass) != $userInfo['pass']){
                returnJsonErrorInfo(self::$result[503],503);
                exit();
            }
            $userInfo['pass'] = '******';
            returnJsonInfo($userInfo);
        }else{
            returnJsonErrorInfo(self::$result[500],500);
            exit();
        }
    }
}