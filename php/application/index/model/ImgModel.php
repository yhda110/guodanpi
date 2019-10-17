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

class ImgModel extends Model
{
    protected $table = 'gdp_image';

    function getUrlById($id)
    {
        $res = DB::table($this->table)->where("id=$id")->find();
        return $res;
    }

    function insertImgByUrl($url, $jump = '')
    {
        $data = array();
        $data['pic_url'] = $url;
        $data['jump_url'] = $jump;
        $res = Db::table($this->table)->insertGetId($data);
        return $res;
    }

    function updateImgUrl($id,$data)
    {
        $res = DB::table($this->table)->where("id=$id")->update($data);
        return $res;
    }
}