<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3
 * Time: 16:06
 */

namespace app\index\model;
use think\Model;
use think\db;
class wechatModel extends Model
{
    protected $table = 'gdp_open';
    function check_user($open)
    {
        $res = DB::table($this->table)->where($open)->find();
        return $res;
    }
    function insert_open($data)
    {
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }

}