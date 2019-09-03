<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3
 * Time: 15:26
 */

namespace app\index\controller;
define("APPID", 'wx65fd96ce2196d7d7');
define("SECRET", 'd79b93f918606fc2742fccc1ab43e079');
define("REDIRECT_URL", 'https://www.lzjrys.store/wechat/wechatLogin');
define("SCOPE", 'snsapi_userinfo');
class WechatService
{
    function getCodeUrl($state)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
        $url = sprintf($url, APPID, urlencode(REDIRECT_URL), SCOPE, $state);
        return $url;
    }
    function getToken($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $url = sprintf($url, APPID, SECRET, $code);
        $content = _request($url,true,"GET"); //TODO 返回结果可能有问题
        $data = json_decode($content, TRUE);

        if (!empty($data['errcode'])) {
            return false;
        }

        return $data;
    }

    function refreshAccessToken($refreshToken)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';
        $url = sprintf($url, APPID, $refreshToken);
        $content = _request($url,true,"GET");
        $data = json_decode($content, TRUE);

        if (!empty($data['errcode'])) {
            return false;
        }

        return $data;
    }
    function getUserInfo($openId, $accessToken)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
        $url = sprintf($url, $accessToken, $openId);
        $content = _request($url,true,"GET");
        $data = json_decode($content, TRUE);

        if (!empty($data['errcode'])) {
            return false;
        }

        return $data;
    }
}