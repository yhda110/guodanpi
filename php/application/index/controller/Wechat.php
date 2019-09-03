<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 首页控制器
 */
namespace app\index\controller;
use system\index\WXBizMsgCrypt;
use think\Request;
use app\index\controller\WechatService;
header("Content-type: text/html; charset=utf-8");
class Wechat
{
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
    public function login(Request $request)
    {
        $state = trim($request->get('state',''));
        $weixinService = new WechatService();
        $codeUrl = $weixinService->getCodeUrl($state);
        $this->redirect($codeUrl);
    }
    public function wechatLogin(Request $request)
    {
        $wechatService = new WeChatService();
        $code = trim($request->get('code', ''));
        $state = trim($request->get('state', '/')); //携带参数 处理业务场景
        $data = $wechatService->getToken($code);        //获取access_token
        $data = $wechatService->refreshAccessToken($data['refresh_token']);        //刷新access_token
        $userinfo = $wechatService->getUserInfo($data['openid'], $data['access_token']);        //获取用户信息
//        获取openid 查询是否存在用户
        if(!empty($userinfo)){
//            存在用户
        }else{
//            不存在用户
        }
    }
    function redirect($url)
    {
            header("Location: $url");
            exit();
    }


}
