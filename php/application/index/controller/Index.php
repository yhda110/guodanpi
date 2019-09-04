<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 首页控制器
 */
namespace app\index\controller;
use app\index\model\bannerModel;
use think\Request;
use system\index\WXBizDataCrypt\WXBizDataCrypt;
use think\Model;
use think\db;
use system\index\test;
use app\index\model\indexModel;
use system\index\WXBizMsgCrypt;

header("Content-type: text/html; charset=utf-8");
class Index
{
    public function index()
    {
//        return view();
    }

    /**
     * @throws \think\exception\DbException
     * @throws db\exception\DataNotFoundException
     * @throws db\exception\ModelNotFoundException
     */
    public function index_InfoAction()
    {
        //获取banner图
        $result = array();
        $banner_info = new bannerModel();
        $info = new indexModel();
        //获取首页banner
        $result['banner'] = $banner_info->getBanner(3);
        //获取首页type
        $result['type'] =  DB::table('gdp_type')->order('id desc')->select();
        //获取首页信息
        $result['info'] = $info->getInfo();

        returnJsonInfo($result);
    }
    public function test()
    {
        require_once(ROOT_PATH."system/test.php");
        $haha = new test\test();
        $haha->index();
    }


}
