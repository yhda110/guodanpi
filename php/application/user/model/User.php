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
    protected $table = 'yda_User';
    function getAll($where='',$order='',$limit='')
    {
        $res = DB::table($this->table)->where($where)->order($order)->limit($limit)->select();
        return $res;
    }
    function getOne($where)
    {
        $res = DB::table($this->table)->where($where)->select();
        return $res;
    }
    function insertUser($data)
    {
        $insert_data = $data;
        $res = DB::table($this->table)->insertGetId($insert_data);
        return $res;
    }
    function updateUser($where,$data)
    {
        $update_data =  $data;
        $res = DB::table($this->table)->where($where)->update($data);
        return $res;
    }
}