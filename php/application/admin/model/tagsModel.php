<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 */

namespace app\admin\model;
use think\Model;
use think\db;

class tagsModel extends Model
{
    protected $table = 'gdp_tags';
    //获取所有tag
    function getAllTags()
    {
        $res = DB::table($this->table)->where('is_del=0')->select();
        return $res;
    }
    //插入tag
    function insertTag($name)
    {
        $data = array();
        $data['tag_name'] = $name;
        $res = Db::table($this->table)->insertGetId($data);
        return $res;
    }
    //删除tag
    function deleteTag($id,$is_del)
    {
        $data = array();
        $data['is_del'] = $is_del;
        $res = DB::table($this->table)->where("id=$id")->update($data);
        return $res;
    }
    //更新tag
    function updateTag($id,$data)
    {
        $res = DB::table($this->table)->where("id=$id")->update($data);
        return $res;
    }
}