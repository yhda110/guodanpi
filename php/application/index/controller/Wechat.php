<?php
/**
 * 微信登录控制器
 * Created by PhpStorm.
 * @author : 李子杰
 * @email : ansheng1021@163.com
 * @Date: 2019/9/3
 * @Time: 15:26
 */

namespace app\index\controller;

use system\userService\userService;
use think\Request;
use app\index\model\wechatModel;
use app\user\model\User;
use app\index\model\ImgModel;
use think\Loader;

header("Content-type: text/html; charset=utf-8");

class Wechat
{
    static $returnCode = array(
        0 => '成功',
        501 => 'openid插入失败',
        14000 => 'openid获取失败',
        14001 => '用户信息获取失败',
        50000 => '用户已被封禁'
    );

    public function index()
    {
        $data = Request::instance()->get();
        $signature = $data['signature'];
        $timestamp = $data['timestamp'];
        $nonce = $data['nonce'];
        $echostr = $data['echostr'];

        /**
         * 计算微信签名
         */
        $token = 'guodanpi';
        //将参数组成一维数组
        $signeSeed = [$token, $timestamp, $nonce];
        //对参数字典序排序
        sort($signeSeed, SORT_STRING);
        //拼接成字符串
        $signeStr = implode($signeSeed);
        //加密字符串成签名
        $signeHash = sha1($signeStr);

        if ($signeHash == $signature) {
            echo $echostr;
        } else {
            return false;
        }
    }

    /**
     * 微信登录方法
     * @param Request $request
     */
    public function login(Request $request)
    {
        Loader::import('WechatService/WechatService', SYSTEM_PATH);
        $state = trim($request->get('state', ''));
        $weixinService = new WechatService();
        $codeUrl = $weixinService->getCodeUrl($state);
        redirect($codeUrl);
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo(Request $request)
    {
        Loader::import('userService/userService', SYSTEM_PATH);
        $ImgModel = new ImgModel();
        $userLogin = new userService();
        $userModel = new User();
        $user_id = $userLogin->isLogin();
        if ($user_id > 0) {
            $id = $user_id;
        } else {
            $id = $request->param('userid', 0);
//            $current_url = get_current_url();
//            redirect('/wechat/login?state=' . $current_url);
        }
        $userInfo = $userModel->getOne("id=$id");
        $userLogin->doLogin($userInfo);
        if (empty($userInfo)) {
            returnJsonErrorInfo(self::$returnCode[14001], 14001);
            exit();
        } else {
            $pic_img = $ImgModel->getUrlById($userInfo['pic_id'])['pic_url'];
            $userInfo['pic_img'] = $pic_img;
        }
        returnJsonInfo($userInfo);
    }

    /**
     * 微信登录回调
     * @param Request $request
     */
    public function wechatLogin(Request $request)
    {
        Loader::import('userService/userService', SYSTEM_PATH);
        Loader::import('WechatService/WechatService', SYSTEM_PATH);
        $userSerivce = new userService();
        $ip = $_SERVER["REMOTE_ADDR"];
        $user = array();
        $wechatService = new WeChatService();
        $imgModel = new ImgModel();
        $code = trim($request->get('code', ''));
        $state = trim($request->get('state', '/')); //携带参数 处理业务场景
        $data = $wechatService->getToken($code);        //获取access_token
        $data = $wechatService->refreshAccessToken($data['refresh_token']);   //刷新access_token
        if (empty($data)) {
            redirect('https://test.lzjrys.store/wechat/login');
            exit();
        }
        $userinfo = $wechatService->getUserInfo($data['openid'], $data['access_token']);        //获取用户信息
        //  获取openid 查询是否存在用户
        $check_open = new wechatModel();
        $User = new User();
        $openid = $data['openid'];
        $check_open_data = $check_open->check_user("openid='$openid'");
        if (!empty($check_open_data)) {
            //  存在openid 查询user表中是否有用户信息
            $userOpen = $check_open_data['id'];
            $update = [
                "nickname" => $userinfo['nickname'],
                "sex" => $userinfo['sex'],
                "province" => $userinfo['province'],
                "country" => $userinfo['country']
            ];
            //更新头像
            $old_info = $User->getOne("openid=$userOpen");
            $update_img = $imgModel->updateImgUrl($old_info['pic_id'], ['pic_url' => $userinfo['headimgurl']]);
            //更新用户信息
            $User->updateUser("openid=$userOpen", $update);
            $userAccount = $User->getOne("openid=$userOpen");
            // 用户信息已存在
            $userAccount['pic_url'] = $imgModel->getUrlById($userAccount['pic_id'])['pic_url'];
            $user = $userAccount;
        } else {
            //  不存在openid
            $insert = array();
            $insert['openid'] = $data['openid'];
            $id = $check_open->insert_open($insert);
            if (isset($id)) {
                $result = array();
                $result['openid'] = $id;
                $result['nickname'] = $userinfo['nickname'];
                $result['sex'] = $userinfo['sex'];
                $result['city'] = $userinfo['city'];
                $result['province'] = $userinfo['province'];
                $result['country'] = $userinfo['country'];
                $result['ip'] = $ip;
                $result['user'] = 'gdp_' . nonceStr();
                $result['pass'] = md5('000000');
                $insert_id = $imgModel->insertImgByUrl($userinfo['headimgurl']);
                $result['pic_id'] = $insert_id;
                $new_user_id = $User->insertUser($result);
                $new_user_info = $User->getOne("id=$new_user_id");
                // 获取用户头像
                $new_user_info['pic_url'] = $imgModel->getUrlById($new_user_info['pic_id'])['pic_url'];
                $user = $new_user_info;
            } else {
                returnJsonErrorInfo(self::$returnCode[501], 501);
            }
        }
        if ($user['status'] == 0) {
            $userSerivce->doLogin($user);
            if ($state != '') {
                redirect($state . '&type=isLogin');
            } else {
                redirect('https://www.lzjrys.store&type=isLogin');
            }
        } else {
            returnJsonErrorInfo(self::$returnCode[50000], 50000);
        }
    }


}
