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

class bannerModel extends Model
{
    protected $table = 'gdp_banner';
    function getBanner($limit)
    {
        $res = DB::table($this->table)->order('id desc')->limit($limit)->select();
        return $res;
    }
}