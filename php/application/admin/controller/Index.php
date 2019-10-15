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
use phpDocumentor\Reflection\DocBlock\Tags\Example;
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
        11002 => '没有权限',
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
    public function index()
    {
        $this->assign('__PUBLIC__', 'static/admin');
        return $this->fetch();
    }
    /**
     * @title 用户登录
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/login","1":"请求方式：post","2":"接口备注：获取token"}
     * @param {"name":"username","type":"string","required":true,"default":"无","desc":"用户名"}
     * @param {"name":"password","type":"string","required":true,"default":"无","desc":"密码"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"id","type":"int","required":true,"desc":"用户ID","level":2}
     * @return {"name":"nick_name","type":"string","required":true,"desc":"用户名","level":2}
     * @return {"name":"user","type":"string","required":true,"desc":"用户账号","level":2}
     * @return {"name":"type","type":"int","required":true,"desc":"账号类型","level":2}
     * @return {"name":"pass","type":"string","required":true,"desc":"用户密码","level":2}
     * @return {"name":"pic_url","type":"string","required":true,"desc":"用户头像链接","level":2}
     * @return {"name":"pic_id","type":"int","required":true,"desc":"用户头像id","level":2}
     * @return {"name":"create_time","type":"string","required":true,"desc":"创建时间","level":2}
     * @return {"name":"token","type":"string","required":true,"desc":"token","level":2}
     */
    public function login(Request $request)
    {
//        if (!Request::instance()->isPost()) exit();
        $this->UserModel = new User();
        $ImgModel = new ImgModel();
        $parme = $request->param();
        $user = trim($parme['username']);
        $pass = trim($parme['password']);
        if (isset($user) && isset($pass)) {
            $userInfo = $this->UserModel
                ->fieldUser(['id', 'user', 'nickname', 'type', 'pass', 'pic_id', 'create_time'], "user='$user'");
            if (empty($userInfo)) {
                returnJsonErrorInfo(self::$result[501], 501);
                exit();
            }
            $userType = $userInfo['type'];
            if ($userType != 1) {
                returnJsonErrorInfo(self::$result[502], 502);
                exit();
            }
            if (md5($pass) != $userInfo['pass']) {
                returnJsonErrorInfo(self::$result[503], 503);
                exit();
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
            $redisService->setRedis(session_id(), $token, 7000);
            returnJsonInfo($userInfo);
        } else {
            returnJsonErrorInfo(self::$result[500], 500);
            exit();
        }
    }

    /**
     * @title 获取帖子列表
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/thread/getThread","1":"请求方式：post","2":"接口备注：需要进行token验证"}
     * @param {"name":"userid","type":"int","required":false,"default":"0","desc":"用户id"}
     * @param {"name":"limit","type":"int","required":false,"default":"10","desc":"长度"}
     * @param {"name":"offset","type":"int","required":false,"default":"0","desc":"偏移量"}
     * @param {"name":"sort","type":"string","required":false,"default":"desc","desc":"排序，传入desc按倒序排列，传入asc按正序排列"}
     * @param {"name":"is_published","type":"int","required":false,"default":9,"desc":"帖子状态 0：待审核 1：审核通过 2：审核驳回"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"count","type":"int","required":true,"desc":"返回数据总数","level":2}
     * @return {"name":"info","type":"array","required":true,"desc":"返回数据列表","level":2}
     * @return {"name":"id","type":"string","required":true,"desc":"帖子ID","level":3}
     * @return {"name":"title","type":"string","required":true,"desc":"帖子标题","level":3}
     * @return {"name":"type","type":"string","required":true,"desc":"帖子类型","level":3}
     * @return {"name":"content","type":"text","required":true,"desc":"文章内容","level":3}
     * @return {"name":"is_published","type":"int","required":true,"desc":"帖子发布状态","level":3}
     * @return {"name":"userid","type":"int","required":true,"desc":"发帖用户","level":3}
     * @return {"name":"create_time","type":"string","required":true,"desc":"创建时间","level":3}
     * @return {"name":"is_del","type":"int","required":true,"desc":"是否可用","level":3}
     * @return {"name":"pv","type":"int","required":true,"desc":"浏览量","level":3}
     * @return {"name":"is_published_text","type":"string","required":true,"desc":"帖子发布文字提示","level":3}
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
        $sort = $request->param('sort', 'desc') != '' ? $request->param('sort', 'desc') == '' : 'desc';
        $userId = $request->param('userid', 0) != '' ? $request->param('userid', 0) : 0;
        $offset = $request->param('offset', 0) != '' ? $request->param('offset', 0) : 0;
        $limit = $request->param('limit', 10) != '' ? $request->param('limit', 10) : 10;
        $is_published = $request->param('is_published', 0) != '' ? $request->param('is_published', 0) : 0;
        $is_del = $request->param('is_del', 0) != '' ? $request->param('is_del', 0) : 0;
        Loader::import('RedisService/RedisService', SYSTEM_PATH);
        $redis = new RedisService();
        $threadModel = new threadModel();
        $UserModel = new User();
        $result = $threadModel->adminThread($userId, $offset, $limit, $sort, $is_published, $is_del);
        $info = $result['info'];
        if (empty($info)) {
            returnJsonErrorInfo(self::$result[14001], 14001);
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
    /**
     * @title 获取帖子详情
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/thread/getOneThread","1":"请求方式：post","2":"接口备注：获取帖子详情"}
     * @param {"name":"thread_id","type":"int","required":true,"default":"无","desc":"帖子id"}
     * @param {"name":"is_published","type":"int","required":true,"default":"0","desc":"下一个帖子id"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"id","type":"int","required":true,"desc":"帖子ID","level":2}
     * @return {"name":"userid","type":"int","required":true,"desc":"发帖人ID","level":2}
     * @return {"name":"title","type":"string","required":true,"desc":"帖子标题","level":2}
     * @return {"name":"content","type":"text","required":true,"desc":"帖子内容","level":2}
     * @return {"name":"type","type":"int","required":true,"desc":"帖子类型","level":2}
     * @return {"name":"img_list","type":"array","required":true,"desc":"帖子图片","level":2}
     * @return {"name":"is_del","type":"int","required":true,"desc":"帖子是否已删除 0：未删除 1：已删除","level":2}
     * @return {"name":"is_published","type":"int","required":true,"desc":"帖子审核状态 0：等待审核 1：审核通过 2：审核驳回","level":2}
     * @return {"name":"create_time","type":"string","required":true,"desc":"创建时间","level":2}
     * @return {"name":"next_id","type":"int","required":true,"desc":"下一个帖子ID","level":2}
     * @return {"name":"pv","type":"string","required":true,"desc":"浏览量","level":2}
     * @return {"name":"nick_name","type":"string","required":true,"desc":"用户名","level":2}
     */
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
        $is_published = $request->param('is_published', 0) != '' ? $request->param('is_published', 0) : 0;
        if (!$thread_id) {
            returnJsonErrorInfo(self::$result[14002], 14002);
            exit();
        }
        $result = $threadModel->adminGetOneThread($thread_id, $is_published);
        $thread_info = $result['info'];
        $thread_info['next_id'] = $result['next_id']['id'];
        if (empty($thread_info)) {
            returnJsonErrorInfo(self::$result[14003], 14003);
            exit();
        }
        Loader::import('RedisService/RedisService', SYSTEM_PATH);
        //获取pv
        $redis = new RedisService();
        $pv = $redis->getRedis(config('Redis.thread') . $thread_id) + 1;
        $redis->setRedis(config('Redis.thread') . $thread_id, $pv);
        $thread_info['pv'] = $pv;
        $img_list = $thread_info['img_list'];
        $img_list = explode(',', $img_list);
        for ($i = 0; $i < count($img_list); $i++) {
            $img_list[$i] = $ImgModel->getUrlById($img_list[$i])['pic_url'];
        }
        $thread_info['img_list'] = $img_list;
        $thread_id = $thread_info['userid'];
        $thread_info['nick_name'] = $UserModel->getOne("id=$thread_id")['nickname'];
        returnJsonInfo($thread_info);
    }

    /**
     * @title 更新帖子状态
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/thread/publishThread","1":"请求方式：post","2":"接口备注：更新帖子状态"}
     * @param {"name":"thread_id","type":"int","required":true,"default":"无","desc":"帖子id"}
     * @param {"name":"is_del","type":"int","required":false,"default":"0","desc":"传1删除帖子 传0恢复帖子"}
     * @param {"name":"state","type":"int","required":false,"default":"9","desc":"0 等待审核 1 审核通过 2 驳回审核"}
     * @param {"name":"type","type":"int","required":false,"default":"0","desc":"更新帖子类型"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
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
            returnJsonErrorInfo(self::$result[11002], 11002);
            exit();
        }
        $id = $request->param('thread_id');
        $state = $request->param('state', 9);
        $is_del = $request->param('is_del', 0);
        $type = $request->param('type', 0);
        $threadModel = new threadModel();
        $res = $threadModel->updateThread($id, $state, $is_del, $type);
//        echo json_encode($res);
//        exit();
        if (!$res) {
            returnJsonErrorInfo(self::$result[11001], 11001);
            exit();
        }
        returnJsonInfo('操作成功');
    }
    /**
     * @title 获取用户列表
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/user/getUser","1":"请求方式：post","2":"接口备注：获取用户列表"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"id","type":"int","required":true,"desc":"用户ID","level":2}
     * @return {"name":"nickname","type":"string","required":true,"desc":"用户名","level":2}
     * @return {"name":"user","type":"string","required":true,"desc":"用户账号","level":2}
     * @return {"name":"pic_id","type":"int","required":true,"desc":"用户头像id","level":2}
     * @return {"name":"ip","type":"string","required":true,"desc":"用户ip","level":2}
     * @return {"name":"pic_img","type":"string","required":true,"desc":"用户头像url","level":2}
     * @return {"name":"status","type":"int","required":true,"desc":"用户账号使用情况","level":2}
     * @return {"name":"create_time","type":"string","required":true,"desc":"创建时间","level":2}
     */
    public function getUsers(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
        //todo 解注释
//        $token = input("server.HTTP_TOKEN");
//        $userId = $this->tokenService->checkToken($token,$_COOKIE['PHPSESSID']);
//        if(!$userId){
//            returnJsonErrorInfo(self::$result[401],401);exit();
//        }
        $status = $request->param('status', 0);
        $UserModel = new User();
        $ImgModel = new ImgModel();
        $userInfo = $UserModel->adminGetUser($status);
        foreach ($userInfo as $key => $item) {
            $userInfo[$key]['pic_img'] = $ImgModel->getUrlById($item['pic_id'])['pic_url'];
            if ($item['sex'] == 1) {
                $userInfo[$key]['sex'] = '男';
            } else {
                $userInfo[$key]['sex'] = '女';
            }
            if ($item['status'] == 0) {
                $userInfo[$key]['status'] = '使用中';
            } else {
                $userInfo[$key]['status'] = '已封禁';
            }
        }
        returnJsonInfo($userInfo);
    }
    /**
     * @title 获取用户列表
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/user/updateUserStatus","1":"请求方式：post","2":"接口备注：封禁&解封用户"}
     * @param {"name":"userid","type":"int","required":false,"default":"无","desc":"用户id"}
     * @param {"name":"status","type":"int","required":true,"default":"无","desc":"0：恢复用户 1：封禁用户"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     */
    public function updateUserStatus(Request $request)
    {
        $UserModel = new User();
        try {
            $userId = $request->param('userid');
        } catch (\Exception $e) {
            returnJsonErrorInfo(self::$result[10004], 10004);
            exit();
        }
        $userInfo = $UserModel->getOne("id=$userId");
        if (!$userInfo) {
            returnJsonErrorInfo(self::$result[10004], 10004);
            exit();
        }
        $state = $request->param('status');
        if($state != 0 || $state != 1){
            returnJsonErrorInfo(self::$result[11001],11001);exit();
        }
        $update = [
            'status' => $state
        ];
        $result = $UserModel->updateUser("id=$userId",$update);
        if($result == 1){
            returnJsonInfo('操作成功');
        }else{
            returnJsonErrorInfo(self::$result[11001],11001);
        }
    }
}