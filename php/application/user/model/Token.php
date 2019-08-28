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

class Token extends Model
{
    protected $table = 'yda_Token';
    function getToken()
    {
        $res = DB::table($this->table)->select();
        return $res;
    }
    function insertToken($token)
    {
        $res = DB::table($this->table)->insertGetId($token);
        if($res>0){
            $result = DB::table($this->table)->where('id='.$res)->select();
            return $result;
        }
    }

}