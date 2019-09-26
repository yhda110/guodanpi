<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 首页控制器
 */

namespace app\index\controller;

use app\index\model\threadModel;
use app\user\model\User;
use system\userService\userService;
use think\Controller;
use think\Loader;
use app\index\model\ImgModel;
use think\Request;
header("Content-type: text/html; charset=utf-8");
class Thread extends Controller
{
    public $userId;
    public $ImgModel;
    static $errorResult = array(
        501 => '未登录',
        10000 => '标题不能为空',
        10001 => '内容不能为空',
        10002 => '上传文字违法',
        10003 => '上传图片违法',
        10004 => '用户不存在',
        11000 => '上传失败',
        11001 => '操作失败'
    );

    public function __construct(Request $request = null)
    {
        $this->ImgModel = new ImgModel();

        Loader::import('userService/userService', SYSTEM_PATH);
        $userLogin = new userService();
        $this->userId = $userLogin->isLogin();
        parent::__construct($request);
    }

    /**
     * @param Request $request
     * 帖子上传
     * TODO 上传完成后为待审核状态，需添加审核接口
     */
    public function threadUpload(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        $userid = $this->userId;
        $param = $request->param();
        $content = $request->param('content','');
        if (!$userid) {
            $userid = $request->param('userid', 0);
            if ($userid == 0) {
                returnJsonErrorInfo(self::$errorResult[501], 501);
                exit();
            }
        }
        //校验用户信息
        $UserModel = new User();
        $userInfo = $UserModel->getOne("id=$userid");
        if (empty($userInfo)) {
            returnJsonErrorInfo(self::$errorResult[10004], 10004);
            exit();
        }
        $threadModel = new threadModel();
        if (!$param['title']) {
            returnJsonErrorInfo(self::$errorResult[10000], 10000);
            exit();
        }
        if (!$content) {
            returnJsonErrorInfo(self::$errorResult[10001], 10001);
            exit();
        }
        // TODO 需要添加type验证

        if ($param['imglist']) {
            $pic_list = '';
            $param['imglist'] = explode(',', $param['imglist']);
            for ($i = 0; $i < count($param['imglist']); $i++) {
                $pic_id = $this->ImgModel->insertImgByUrl($param['imglist'][$i]);
                $pic_list = $pic_id . ',' . $pic_list;
            }
            $pic_list = substr($pic_list, 0, strlen($pic_list) - 1);
        }

        $insertParam = array(
            'type' => $request->param('type', 0),
            'userid' => $userid,
            'title' => $param['title'],
            'content' => $content,
            'img_list' => $pic_list
        );
        $insertId = $threadModel->uploadThread($insertParam);
        if ($insertId > 0) {
            returnJsonInfo(array('id' => $insertId));
        }
    }

    //获取帖子接口
    public function getThread(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        $userId = $request->param('userid', 0);
        $offset = $request->param('offset', 0);
        $sort = $request->param('sort','desc');
        $limit = $request->param('limit',10);
        $threadModel = new threadModel();
        $ImgModel = new ImgModel();
        if ($userId == 0) {
            //获取全部帖子
            $info = $threadModel->getThread(0, $offset,$limit,$sort);
        } else {
            if ($this->userId) {
                $userId = $this->userId;
            }
            $info = $threadModel->getThread($userId, $offset,$limit,$sort);
        }
        if(empty($info)){
            returnJsonErrorInfo('未获取到数据', 500);exit();
        }
        foreach ($info as $key=>$item) {
            $pic_id = explode(',', $item['img_list']);
            $pic_img = [];
            for ($i = 0; $i < count($pic_id); $i++) {
                $pic_img[$i] = $ImgModel->getUrlById($pic_id[$i])['pic_url'];
            }
            if($item['is_published'] == 0){
                $info[$key]['is_published_text'] = '等待审核';
            }else if($item['is_published'] == 1){
                $info[$key]['is_published_text'] = '已发布';
            }else if($item['is_published'] == 2){
                $info[$key]['is_published_text'] = '审核未通过';
            }
            $info[$key]['img_list'] = $pic_img;
        }
        $count = $threadModel->countThread($userId);
        $result = array(
            'count' => $count,
            'info' => $info
        );
            returnJsonInfo($result);
    }

    /**
     * 更改帖子状态 包含删除
     */
    public function publishThread(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        if($this->userId == 0){
            returnJsonErrorInfo(self::$errorResult[501],501);exit();
        }
        $User = new User();
        $userInfo = $User->getOne($this->userId);
        if(empty($userInfo)){
            returnJsonErrorInfo(self::$errorResult[10004],10004);exit();
        }
        if($userInfo['type'] != 1){
            returnJsonErrorInfo(self::$errorResult[11001],11001);exit();
        }
        $id = $request->param('thread_id');
        $state = $request->param('state',0);
        $is_del = $request->param('is_del',0);
        $type = $request->param('type',0);
        $threadModel = new threadModel();
        $res = $threadModel->updateThread($id,$state,$is_del,$type);
        if(!$res){
            returnJsonErrorInfo(self::$errorResult[11001],11001);exit();
        }
        returnJsonInfo('操作成功');
    }

}

