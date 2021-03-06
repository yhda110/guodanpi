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

    function adminThread($userid, $offset, $limit, $sort, $is_published,$is_del) // is_published = 9 则为全部的
    {
        if ($is_published == '') {
            $where = 'id > 0 and is_del='.$is_del;
        } else {
            $where = 'is_published=' . $is_published . ' and is_del='.$is_del;
        }
        $total = Db::table($this->table)->where($where)->select();
        $count = count($total);
        if ($userid == 0) {
            $res = Db::table($this->table)
                ->where($where)
                ->order("id desc")
                ->limit($offset, $limit)
                ->field('id,type,userid,title,content,create_time,is_published,is_del')
                ->select();
        } else {
            $res = Db::table($this->table)
                ->where("userid=$userid and $where")
                ->order("id desc")
                ->limit($offset, $limit)
                ->field('id,type,userid,title,content,is_published,create_time,is_del')
                ->select();
        }
        return [
            'count' => $count,
            'info' => $res
        ];
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
            $total = Db::table($this->table)->where('is_del=0 and is_published=1')->select();
        } else {
            $res = Db::table($this->table)
                ->where("userid=$userid and is_del=0")
                ->order("id $sort")
                ->limit($offset, $limit)
                ->field('id,type,userid,title,content,img_list,is_published,create_time')
                ->select();
            $total = Db::table($this->table)->where("userid=$userid and is_del=0")->select();
        }
        return [
            'count' => count($total),
            'info' => $res
        ];
    }

    function updateThread($id, $state = 0, $is_del = 0, $type = 0)
    {
        $data = array();
        //传1删除 0恢复
        $data['is_del'] = $is_del;

        if ($state != 9) {  // 传入数字则为更改状态
            $data['is_published'] = $state;
        }
        if ($type != 0) {
            $data['type'] = $type;
        }
        $res = DB::table($this->table)->where("id=$id")->update($data);
        if ($res == 1) {
            return true;
        }
        return $res;
    }

    function getOneThread($id)
    {
        $res = DB::table($this->table)->where("id=$id and is_del=0 and is_published=1")->find();
        return $res;
    }
    function adminGetOneThread($id,$is_published)
    {
        $result = array();
        $next = DB::table($this->table)
            ->where("id < $id and is_published = $is_published")
            ->field('id')
            ->order('id desc')
            ->limit(0,1)
            ->find();
        $res = DB::table($this->table)->where("id=$id")->find();
        $result['next_id'] = $next;
        $result['info'] = $res;
        return $result;
    }
}