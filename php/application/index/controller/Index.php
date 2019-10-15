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
use think\Loader;
use app\index\model\ImgModel;
use think\Request;
use app\index\model\newsModel;

//require '../vendor/autoload.php';
header("Content-type: text/html; charset=utf-8");
//const APP_ID = '17207036';
//const API_KEY = 'WpCGiK8IXmffOlqBFIv0Bp5T';
//const SECRET_KEY = 'YXS29TjHMNHtwb8bWN6ssYGoIt8CamsY';
class Index extends Controller
{
    public $userId;
    public $ImgModel;

    public function __construct(Request $request = null)
    {
        $this->ImgModel = new ImgModel();

        Loader::import('userService/userService', SYSTEM_PATH);
        $userLogin = new userService();
        $this->userId = $userLogin->isLogin();
        parent::__construct($request);
    }

    public function index()
    {
        if ($this->userId == 0) {
            $current_url = get_current_url();
            redirect('/wechat/login?state=' . $current_url);
        }
        $this->assign('__PUBLIC__', '/static/guodanpi');
        return $this->fetch();
    }

    public function QiniuGetToken()
    {
        Loader::import('ImageService/ImageService', SYSTEM_PATH);
        $imgService = new ImageService();
        $result = $imgService->QiniuGetToken();
        returnJsonInfo($result);
    }

    /*
     * 获取咨询
     *
     * */
    public function news(Request $request)
    {
        $newsModel = new newsModel();
        $limit = $request->param('limit', 10);
        $offset = $request->param('offset', 0);
        $result = $newsModel->getnews($limit, $offset);
        if ($result['count'] > 0) {
            returnJsonInfo($result);
        } else {
            returnJsonErrorInfo('没有更多了', 500);
        }
    }

    public function newsGetDetail(Request $request)
    {
        $newsModel = new newsModel();
        $pid = $request->param('pid', 0);
        if ($pid == 0) {
            returnJsonErrorInfo('pid不正确', 500);
            exit();
        }
        $result = $newsModel->fomatDetail($pid);
        if ($result) {
            returnJsonInfo($result);
        } else {
            returnJsonErrorInfo('没有数据', 500);
        }
    }
    /**
     * @param Request $request
     */
    /*
     * 单张图片上传
     * */
    public function index_InfoAction(Request $request)
    {
        //获取banner图
        $base64_url = $request->param('img');
        Loader::import('ImageService/ImageService', SYSTEM_PATH);
        $imgCdn = new ImageService();
        $imgUrl = $imgCdn->uploadImg($base64_url);
        if ($imgUrl) {
            unset($base64_url);
            returnJsonInfo($imgUrl);
        }
    }


    function presonAi(Request $request)
    {
        $imgModel = new ImgModel();
        $img = $request->param('imgCode');
        if (!$img) {
            returnJsonErrorInfo('图片为空', 500);
            exit();
        }
        $image = base64_decode($img);
        Loader::import('PersonAiService/AipBodyAnalysis', SYSTEM_PATH);
        $client = new \AipBodyAnalysis(APP_ID, API_KEY, SECRET_KEY);
        $client->bodySeg($image);

        $options = array();
        $options["type"] = "foreground";

        $result = $client->bodySeg($image, $options);
        if (!$result['foreground']) {
            returnJsonInfo($request);
            exit();
        }
        $imageSave = uploads('data:image/png;base64,' . $result['foreground']);
        if ($imageSave) {
            $id = $imgModel->insertImgByUrl('www.lzjrys.store/' . $imageSave['name']);
            if ($id > 0) {
                $url = $imgModel->getUrlById($id);
                returnJsonInfo($url);
            } else {
                returnJsonErrorInfo('插入失败', 500);
            }
        } else {
            echo 'error';
        }
    }


}

