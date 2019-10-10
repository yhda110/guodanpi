<?php
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/4
 * @Time: 18:53
 */

namespace app\admin\controller;
use app\admin\BaseController;
use app\index\model\ImgModel;
use app\user\model\User;
use Firebase\JWT\JWT;
use system\RedisService\RedisService;
use system\tokenService\tokenService;
use think\Loader;
use think\Request;
use app\index\model\threadModel;
class Index extends BaseController
{
    private $UserModel;
    private $tokenService;
    static $result = array(
        401 => 'token验证失败',
        500 => '账号名或者密码为空',
        501 => '用户不存在',
        502 => '无登录权限',
        503 => '密码错误',
        10001 => '内容不能为空',
        10004 => '用户不存在',
        11000 => '上传失败',
        11001 => '操作失败',
        14001 => '未获取到数据',
        14002 => '帖子id为空',
        14003 => '帖子不存在'
    );
    public function __construct(Request $request = null)
    {
        Loader::import('tokenService/tokenService', SYSTEM_PATH);
        $this->UserModel = new User();
        $this->tokenService = new tokenService();
        parent::__construct($request);
    }
    /**
     * 校验token是否存在 如果没传token 则需要登录获取token
     * 传过来token后 验证token是否过期 如果token过期则需要登录
     */
    public function index()
    {
        $this->assign('__PUBLIC__', '/static');
        return $this->fetch();
    }
    public function login(Request $request)
    {
//        if (!Request::instance()->isPost()) exit();
        $this->UserModel = new User();
        $ImgModel = new ImgModel();
        $parme = $request->param();
        $user = trim($parme['username']);
        $pass = trim($parme['password']);
        if(isset($user) && isset($pass)){
            $userInfo = $this->UserModel
                ->fieldUser(['id','user','nickname','type','pass','pic_id','create_time'],"user='$user'");
            if(empty($userInfo)){
               returnJsonErrorInfo(self::$result[501],501);exit();
            }
            $userType = $userInfo['type'];
            if($userType != 1){
                returnJsonErrorInfo(self::$result[502],502);exit();
            }
            if(md5($pass) != $userInfo['pass']){
                returnJsonErrorInfo(self::$result[503],503);exit();
            }
            $userInfo['pass'] = '******';
            $userInfo['pic_url'] = $ImgModel->getUrlById($userInfo['pic_id'])['pic_url'];
            //获取token
            session_id(md5($userInfo['id']));
            Loader::import('RedisService/RedisService', SYSTEM_PATH);
            $redisService = new RedisService();
            $token = $this->tokenService->getToken($userInfo['id']);
            $userInfo['token'] = $token;
            //存入Redis
            setcookie('PHPSESSID', session_id(), 0, '/', $_SERVER['SERVER_NAME'], false, true);
            $redisService->setRedis(session_id(),$token,7000);
            returnJsonInfo($userInfo);
        }else{
            returnJsonErrorInfo(self::$result[500],500);
            exit();
        }
    }

    /**
     *
     * · 获取全部帖子  根据审核状态筛选
     * · 根据用户筛选
     * · 排序  => 正序 倒序
     */
    public function getThread(Request $request)
    {
        //todo 解注释
        if (!Request::instance()->isPost()) exit();
//        $token = input("server.HTTP_TOKEN");
//        $tokenCheck = $this->tokenService->checkToken($token,$_COOKIE['PHPSESSID']);
//        if(!$tokenCheck){
//            returnJsonErrorInfo(self::$result[401],401);exit();
//        }
        $userId = $request->param('userid', 0);
        $offset = $request->param('offset', 0);
        $sort = $request->param('sort', 'desc');
        $limit = $request->param('limit', 10);
        $is_published = $request->param('is_published', 9);
        Loader::import('RedisService/RedisService', SYSTEM_PATH);
        $redis = new RedisService();
        $threadModel = new threadModel();
        $UserModel = new User();
        $result = $threadModel->adminThread($userId, $offset, $limit, $sort,$is_published);
        $info = $result['info'];
        if (empty($info)) {
            returnJsonErrorInfo(self::$result[14001],14001);
            exit();
        }
        foreach ($info as $key => $item) {
            //获取pv
            $thread_userId = $item['userid'];
            $user_name = $UserModel->getOne("id=$thread_userId")['nickname'];
            $info[$key]['nick_name'] = $user_name;
            $thread_pv = $redis->getRedis(config("Redis.thread") . $item['id']);
            if (!$thread_pv) {
                $redis->setRedis(config('Redis.thread') . $item['id'], 0);
            }
            $info[$key]['pv'] = $redis->getRedis(config("Redis.thread") . $item['id']);
            //格式化帖子状态
            if ($item['is_published'] == 0) {
                $info[$key]['is_published_text'] = '等待审核';
            } else if ($item['is_published'] == 1) {
                $info[$key]['is_published_text'] = '已发布';
            } else if ($item['is_published'] == 2) {
                $info[$key]['is_published_text'] = '审核未通过';
            }
        }
        $result = array(
            'count' => $result['count'],
            'info' => $info
        );
        returnJsonInfo($result);
    }
    public function getOneThread(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        //todo 解注释
//        $token = input("server.HTTP_TOKEN");
//
//        $userId = $this->tokenService->checkToken($token,$_COOKIE['PHPSESSID']);
//        if(!$userId){
//            returnJsonErrorInfo(self::$result[401],401);exit();
//        }
        $threadModel = new threadModel();
        $ImgModel = new ImgModel();
        $UserModel = new User();
        $thread_id = $request->param('thread_id');
        if(!$thread_id){
            returnJsonErrorInfo(self::$result[14002],14002);exit();
        }
        $thread_info = $threadModel->getOneThread($thread_id);
        if(empty($thread_info)){
            returnJsonErrorInfo(self::$result[14003],14003);exit();
        }
        Loader::import('tokenService/tokenService', SYSTEM_PATH);
        //获取pv
        $redis = new RedisService();
        $pv = $redis->getRedis(config('Redis.thread').$thread_id)+1;
        $redis->setRedis(config('Redis.thread').$thread_id,$pv);
        $thread_info['pv'] = $pv;
        $img_list = $thread_info['img_list'];
        $img_list = explode(',',$img_list);
        for($i=0;$i<count($img_list);$i++){
            $img_list[$i] = $ImgModel->getUrlById($img_list[$i])['pic_url'];
        }
        $thread_info['img_list'] = $img_list;
        $thread_id = $thread_info['userid'];
        $thread_info['nick_name'] = $UserModel->getOne("id=$thread_id")['nickname'];
        returnJsonInfo($thread_info);
    }

    /**
     * 更改帖子状态 包含删除
     * is_del = 1 删除帖子
     * state = 0 等待审核  = 1 审核通过  = 2 审核被拒
     */
    public function publishThread(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        //todo 解注释
//        $token = input("server.HTTP_TOKEN");
//
//        $userId = $this->tokenService->checkToken($token,$_COOKIE['PHPSESSID']);
//        if(!$userId){
//            returnJsonErrorInfo(self::$result[401],401);exit();
//        }
//        if ($userId == 0) {
//            returnJsonErrorInfo(self::$result[501], 501);
//            exit();
//        }
        $User = new User();
        $userId = 22;
        $userInfo = $User->getOne("id=$userId");
        if (empty($userInfo)) {
            returnJsonErrorInfo(self::$result[10004], 10004);
            exit();
        }
        if ($userInfo['type'] != 1) {
            returnJsonErrorInfo(self::$result[11001], 11001);exit();
        }
        $id = $request->param('thread_id');
        $state = $request->param('state', 0);
        $is_del = $request->param('is_del', 0);
        $type = $request->param('type', 0);
        $threadModel = new threadModel();
        $res = $threadModel->updateThread($id, $state, $is_del, $type);
        if (!$res) {
            returnJsonErrorInfo(self::$result[11001], 11001);
            exit();
        }
        returnJsonInfo('操作成功');
    }
}