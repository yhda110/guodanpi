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
use system\index\WXBizMsgCrypt;
use think\Request;
use app\index\controller\WechatService;
use app\index\model\wechatModel;
use app\user\model\User;
use app\index\model\ImgModel;
header("Content-type: text/html; charset=utf-8");
class Wechat
{
    static $returnCode = array(
        0 => '成功',
        501=> 'openid插入失败',
        14000 => 'openid获取失败',
        14001 => '用户信息获取失败'
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
        }else{
            return false;
        }
    }

    /**
     * 微信登录方法
     * @param Request $request
     */
    public function login(Request $request)
    {
        $state = trim($request->get('state',''));
        $weixinService = new WechatService();
        $codeUrl = $weixinService->getCodeUrl($state);
        redirect($codeUrl);
    }
    /**
     * 微信登录回调
     * @param Request $request
     */
    public function wechatLogin(Request $request)
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $user = array();
        $wechatService = new WeChatService();
        $imgModel = new ImgModel();
        $code = trim($request->get('code', ''));
        $state = trim($request->get('state', '/')); //携带参数 处理业务场景
        $data = $wechatService->getToken($code);        //获取access_token
        $data = $wechatService->refreshAccessToken($data['refresh_token']);   //刷新access_token
        $userinfo = $wechatService->getUserInfo($data['openid'], $data['access_token']);        //获取用户信息
        //  获取openid 查询是否存在用户
        $check_open = new wechatModel();
        $User = new User();
        $openid = $data['openid'];
        $check_open_data = $check_open->check_user("openid='$openid'");
        if(!empty($check_open_data)){
            //  存在openid 查询user表中是否有用户信息
            $userOpen = $check_open_data['id'];
            $userAccount = $User->getOne("openid=$userOpen");
            // 用户信息已存在

            $userAccount['pic_url'] = $imgModel->getUrlById($userAccount['pic_id'])['pic_url'];
            $user = $userAccount;
        }else{
            //  不存在openid

            $insert = array();
            $insert['openid'] =$data['openid'];
            $id = $check_open->insert_open($insert);
            if(isset($id)){
                $result = array();
                $result['openid'] = $id;
                $result['nickname'] = $userinfo['nickname'];
                $result['sex'] = $userinfo['sex'];
                $result['city'] = $userinfo['city'];
                $result['province'] = $userinfo['province'];
                $result['country'] = $userinfo['country'];
                $result['ip'] = $ip;
                $result['user'] = 'gdp_'.nonceStr();
                $result['pass'] = md5('000000');
                $insert_id = $imgModel->insertImgByUrl($userinfo['headimgurl']);
                $result['pic_id'] = $insert_id;
                $new_user_id = $User->insertUser($result);
                $new_user_info = $User->getOne("id=$new_user_id");
                // 获取用户头像
                $new_user_info['pic_url'] = $imgModel->getUrlById($new_user_info['pic_id'])['pic_url'];
                $user = $new_user_info;
            }else{
                echo returnJsonErrorInfo(self::$returnCode[501],501);
            }
        }
        echo returnJsonInfo($user);
    }



}
