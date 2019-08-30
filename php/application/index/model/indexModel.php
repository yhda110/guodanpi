<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 */

namespace app\index\model;
use think\Model;
use think\db;

class indexModel extends Model
{
    protected $table = 'gdp_info';
    function getInfo()
    {
        $res = DB::table($this->table)->order('id desc')->limit()->select();
        return $res;
    }
}