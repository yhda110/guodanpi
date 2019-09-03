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
    /*
     * 微信登录
     * */
    public function login(Request $request)
    {
        $state = trim($request->get('state',''));
        $weixinService = new WechatService();
        $codeUrl = $weixinService->getCodeUrl($state);
        $this->redirect($codeUrl);
    }
    /*
     * 微信登录回调
     * */
    public function wechatLogin(Request $request)
    {
        $wechatService = new WeChatService();
        $code = trim($request->get('code', ''));
        $state = trim($request->get('state', '/')); //携带参数 处理业务场景
        $data = $wechatService->getToken($code);        //获取access_token
        $data = $wechatService->refreshAccessToken($data['refresh_token']);   //刷新access_token
        $userinfo = $wechatService->getUserInfo($data['openid'], $data['access_token']);        //获取用户信息
        //  获取openid 查询是否存在用户
        // todo 需要处理用户openid与平台账户逻辑
        $check_open = new wechatModel();
        $openid = $data['openid'];
        $check_open_data = $check_open->check_user("openid='$openid'");
        if(!empty($check_open_data)){
            //  存在用户
            echo json_encode($check_open_data);
        }else{
            //  不存在用户
            $insert = array();
            $insert['openid'] =$data['openid'];
            $id = $check_open->insert($insert);
            if($id){
                echo '创建成功,id为'.$id;
            }else{
                echo '数据库插入失败';
            }
        }
    }
    function redirect($url)
    {
            header("Location: $url");
            exit();
    }


}
