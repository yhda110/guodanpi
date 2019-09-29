<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/21
 * Time: 18:57
 */

namespace app\user\controller;
use think\Request;
use app\user\model\User;
use app\user\model\Login;
use app\user\model\Token;
use think\config;
use system\index\WXBizDataCrypt;
class Index
{

    static $result =array(
        0 => '成功',
        500 => '失败',
        501 => '用户名不存在',
        502 => '密码错误',
        503 => '手机号已经注册',
        504 => '参数为空',
        14001 => 'code为空',
        14002 => '用户信息获取失败',
        14003 => '用户信息更新失败',
        50000 => '用户创建失败'
    );
    public function index()
    {
        $user = new User;
        $info = $user->getAll();
        if(empty($info)){
            returnJsonErrorInfo(self::$result[500],500);
        }else{
            returnJsonInfo($info);
        }

    }
    public function login(Request $request)
    {
        $login = new Login;
        $loginInfo = $request->param();
        if(!isset($loginInfo['mobile']) || !isset($loginInfo['password'])){
            returnJsonErrorInfo(self::$result[500],500);
            exit();
        }
        $is_username = $login->checkUser('mobile='.$loginInfo['mobile']);
        if(empty($is_username)){
            returnJsonErrorInfo(self::$result[501],501);
        }
        if($loginInfo['password'] === $is_username['password']){
            returnJsonInfo($is_username);
        }else{
            returnJsonErrorInfo(self::$result[502],502);
        }
    }
    public function insert()
    {
        $data = [
            'user_name' => '李子杰update',
            'pass_word' => '111111',
            'mobile' => '17636339702',
            'open_id' => 'asd',
            'access_token' => 'hahah'
        ];
        $user = new User;
        $info = $user->updateUser('id=1',$data);
        echo json_encode($info);
    }
    /*
     * 微信小程序登录接口
     * 需要传入code,iv,encryptedData
     * GET请求
     * */
    public function wechat_login(Request $request){
        $code = $request->param('code');
        $iv = $request->param('iv');
        $encryptedData = $request->param('encryptedData');
         if(!isset($code)){
            returnJsonErrorInfo(self::$result[14001],14001);
            exit();
        }
        $appid = config::get('appid');
        $appsecret = config::get('appsecret');
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$code.'&grant_type=authorization_code';
        $wechat_info =json_decode($this->get_by_curl($url),true);
        if(empty($wechat_info)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }
        $this->checkOpenID($wechat_info,$iv,$encryptedData);
    }
    /*
     * check用户openid
     * 通过微信提供的工具类解密iv和encryptedData获取用户的个人信息
     * 判断openid是否存在
     * 存在则更新数据库用户信息
     * 不存在则创建新用户
     * */
    private function checkOpenID($wechat,$iv,$encryptedData)
    {
        require_once(ROOT_PATH."system/wxBizDataCrypt.php");
        $pc = new WXBizDataCrypt\WXBizDataCrypt(config::get('appid'),$wechat['session_key']);
        $errCode = $pc->decryptData($encryptedData, $iv, $datas);
        $datas =  json_decode($datas,true);
        if($errCode != 0){
            returnJsonErrorInfo(self::$result[14002],14002);
            exit();
        }
        $loginModel = new login;
        $userInfo = $loginModel->checkOpenID("openid='$wechat[openid]'");
        if($userInfo){
//            $this->getUserInfo($wechat['openid'],$wechat['session_key'],$iv,$encryptedData);
            $data = array();
            $userid = $userInfo['id'];
            $data['wechat_avatar'] = $datas['avatarUrl'];
            $data['wechat_name'] = $datas['nickName'];
            $data['wechat_place'] = $datas['province'].$datas['city'];
            $updateResult = $loginModel->updateUserInfo($data,"id=$userid");
            if($updateResult == 1){
                returnJsonInfo($loginModel->checkOpenID('openid='.$data['openid']));
            }else{
                returnJsonInfo($userInfo);
            }
        }else{
            $data = array();
            $data['openid']=$wechat['openid'];
            $data['wechat_avatar'] = $datas['avatarUrl'];
            $data['wechat_name'] = $datas['nickName'];
            $data['wechat_place'] = $datas['province'].$datas['city'];
            $insert_id = $loginModel->createWechatUser($data);
            if(isset($insert_id)){
                $userInfo = $loginModel->checkOpenID("openid='$wechat[openid]'");
                returnJsonInfo($userInfo);
            }else{
                returnJsonErrorInfo(self::$result[14002],14002);
            }
        }
    }


    function getUserInfo($openid,$session_key,$iv,$encryptedData){
        $token = $this->refreshToken();
        if($token){
            $token = $token[0];
        }

        exit();
    }
    private function getToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.config::get('appid').'&secret='.config::get('appsecret');
        $result = $this->get_by_curl($url);
        return json_decode($result,true);
    }
    private function refreshToken(){
        $token = new Token;
        $access_token = $token->getToken();
        if (empty($access_token)){
            $access_token = array();
            $access_token['access_token']=$this->getToken();
            $result = $token->insertToken($access_token['access_token']);
            return $result;
        }else{
            return $access_token;
        }
    }
    private function get_by_curl($url,$post = false){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}