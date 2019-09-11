<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 首页控制器
 */

namespace app\index\controller;

use system\ImageService\ImageService;
use system\userService\userService;
use think\Controller;
use think\db;
use app\index\model\indexModel;
use think\Loader;
use app\index\model\ImgModel;
use think\Request;
use think\response\View;
use app\index\controller\Wechat;
require '../vendor/autoload.php';
header("Content-type: text/html; charset=utf-8");
const APP_ID = '17207036';
const API_KEY = 'WpCGiK8IXmffOlqBFIv0Bp5T';
const SECRET_KEY = 'YXS29TjHMNHtwb8bWN6ssYGoIt8CamsY';
class Index extends Controller
{
    public $isLogin;
    public function __construct(Request $request = null)
    {
        Loader::import('userService/userService',SYSTEM_PATH);
        $userLogin = new userService();
        $this->isLogin = $userLogin->isLogin();
        parent::__construct($request);
    }

    public function index()
    {
        if($this->isLogin == 0){
            $current_url  = get_current_url();
            redirect('/wechat/login?state='.$current_url);
        }
        $this->assign('__PUBLIC__','/static');
        return $this -> fetch();
    }
    public function show()
    {
        $this->assign('__PUBLIC__','/static');
        return $this -> fetch();
    }


    /**
     * @throws \think\exception\DbException
     * @throws db\exception\DataNotFoundException
     * @throws db\exception\ModelNotFoundException
     */
    public function index_InfoAction(Request $request)
    {
        //获取banner图
        $base64_url = $request->param('img');
        Loader::import('ImageService/ImageService',SYSTEM_PATH);
        $imgCdn = new ImageService();
        $imgUrl = $imgCdn->uploadImg($base64_url);
        if($imgUrl){
            unset($base64_url);
            returnJsonInfo($imgUrl);
        }
//        $result = array();
//        $banner_info = new bannerModel();
//        $info = new indexModel();
//        //获取首页banner
//        $result['banner'] = $banner_info->getBanner(3);
//        //获取首页type
//        $result['type'] = DB::table('gdp_type')->order('id desc')->select();
//        //获取首页信息
//        $result['info'] = $info->getInfo();
//
//        returnJsonInfo($result);
    }

    function presonAi(Request $request)
    {
        $imgModel = new ImgModel();
//        $image = file_get_contents('/static/Image/test.jpg');
        $img = $request->param('imgCode');
        if(!$img){
            returnJsonErrorInfo('图片为空',500);
            exit();
        }
        $image = base64_decode($img);
        Loader::import('PersonAiService/AipBodyAnalysis',SYSTEM_PATH);
        $client = new \AipBodyAnalysis(APP_ID, API_KEY, SECRET_KEY);
        $client->bodySeg($image);

        $options = array();
        $options["type"] = "foreground";

        $result = $client->bodySeg($image, $options);
        if(!$result['foreground']){
            returnJsonInfo($request);
            exit();
        }
        $imageSave = uploads('data:image/png;base64,'.$result['foreground']);
        if($imageSave){
            $id = $imgModel->insertImgByUrl('www.lzjrys.store/'.$imageSave['name']);
            if($id>0){
                $url =  $imgModel->getUrlById($id);
                returnJsonInfo($url);
            }else{
                returnJsonErrorInfo('插入失败',500);
            }
        }else{
            echo 'error';
        }
    }

}
//function func_is_base64($str)
//{
//    if($str==base64_encode(base64_decode($str))){
//        return true;
//    }else{
//        return false;
//    }
//}
