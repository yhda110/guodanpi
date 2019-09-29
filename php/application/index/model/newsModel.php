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

class newsModel extends Model
{
    protected $table = 'gdp_sp_title';
    function getnews($limit,$offset)
    {
        $res = array();
        $res['count'] = count(Db::table($this->table)->select());
        $res['data'] = DB::table($this->table)
            ->order('id asc')
            ->limit($offset,$limit)
            ->select();
        return $res;
    }
    function fomatDetail($pid)
    {
        $html = array();
        $html['html'] = DB::table('gdp_sp_detail')
            ->where("pid='$pid'")
            ->value('html');
        return $html;
    }

}