<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 */

namespace app\user\model;
use think\Model;
use think\db;

class Login extends Model
{
    protected $table = 'yda_User';
    function checkUser($where)
    {
        $res = DB::table($this->table)->where($where)->order('id desc')->limit(1)->select();
        return $res[0];
    }
    function checkPassword($where)
    {
        $res = DB::table($this->table)->where($where)->order('id desc')->limit(1)->select('password');
        return $res;
    }
    function checkOpenID($where)
    {
        $res = DB::table($this->table)->field('id,wechat_name,wechat_place,wechat_avatar,create_time')->where($where)->find();
        return $res;
    }
    function createWechatUser($data){
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }
    function updateUserInfo($data,$where){
        $res = DB::table($this->table)->where($where)->update($data);
        return $res;
    }
}