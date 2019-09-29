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

class threadModel extends Model
{
    protected $table = 'gdp_thread';

    function getInfo()
    {
        $res = DB::table($this->table)->order('id desc')->limit()->select();
        return $res;
    }

    function uploadThread($param)
    {
        $res = Db::table($this->table)->insertGetId($param);
        return $res;
    }

    function countThread($userid)
    {
        if ($userid == 0) {
            $res = Db::table($this->table)->select();
        } else {
            $res = Db::table($this->table)
                ->where("userid=$userid and is_del=0")
                ->select();
        }
        $count = count($res);
        return $count;
    }

    function getThread($userid, $offset, $limit, $sort)
    {
        if ($userid == 0) {
            $res = Db::table($this->table)
                ->where('is_del=0 and is_published=1')
                ->order("id $sort")
                ->limit($offset, $limit)
                ->field('id,type,userid,title,content,img_list,create_time,is_published')
                ->select();
        } else {
            $res = Db::table($this->table)
                ->where("userid=$userid and is_del=0")
                ->order("id $sort")
                ->limit($offset, $limit)
                ->field('id,type,userid,title,content,img_list,is_published,create_time')
                ->select();
        }
        return $res;
    }

    function updateThread($id,$state=0,$is_del=0,$type=0)
    {
        $data = array();
        if($is_del == 1){  //传入 1 则为删除帖子
            $data['is_del'] = $is_del;
        }
        if($state != 0){  // 传入数字则为更改状态
            $data['is_published'] = $state;
        }
        if($type != 0){
            $data['type'] = $type;
        }
        $res = DB::table($this->table)->where("id=$id")->update($data);
        if($res == 1){
            return true;
        }
        return false;
    }
    function getOneThread($id)
    {
        $res = DB::table($this->table)->where("id=$id and is_del=0 and is_published=1")->find();
        return $res;
    }
}