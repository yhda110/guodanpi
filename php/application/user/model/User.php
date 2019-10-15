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

class User extends Model
{
    protected $table = 'gdp_user';
    function getAll($where='',$order='',$limit='')
    {
        $res = DB::table($this->table)->where($where)->order($order)->limit($limit)->select();
        return $res;
    }
    function getOne($where)
    {
        $res = DB::table($this->table)->where($where)
            ->field('id,type,nickname,sex,pic_id,phone,create_time')
            ->find();
        return $res;
    }
    function getUserByUser($user)
    {
        $res = DB::table($this->table)->where("user='$user'")->find();
        return $res;
    }
    function insertUser($data)
    {
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }
    function updateUser($where,$data)
    {
        $update_data =  $data;
        $res = DB::table($this->table)->where($where)->update($data);
        return $res;
    }

    function fieldUser($field,$where)
    {
        $res = DB::table($this->table)->field($field)->where($where)->find();
        return $res;
    }
    function adminGetUser($status)
    {
        $res = DB::table($this->table)
            ->field('id,nickname,user,sex,pic_id,ip,create_time,status')
            ->where("status=$status")
            ->select();
        return $res;
    }
}