<?php
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/5
 * @Time: 11:13
 */

namespace system\tokenService;

use Firebase\JWT\JWT;
use system\RedisService\RedisService;
use think\Controller;
use think\Loader;
class tokenService extends Controller
{

    public function __construct(Request $request = null)
    {

        parent::__construct($request);
    }
    public function getToken($userid)
    {
        $key = 'gdp';
        $token = [
            "iss"=>"",  //签发者 可以为空
            "aud"=>"", //面象的用户，可以为空
            "iat" => time(), //签发时间
            "nbf" => time(), //在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time()+7000, //token 过期时间
            "uid" => $userid //记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
        ];
        $jwt = JWT::encode($token,$key,"HS256");
        return $jwt;
    }
    public function checkToken($token,$session_id)
    {
        if(!$token){
            return false;
        }
        Loader::import('RedisService/RedisService', SYSTEM_PATH);
        $session = $session_id;
        $redis = new RedisService();
        $redisToken = $redis->getRedis($session);


        if(!$redisToken){
            return false;
        }
        if($token != $redisToken){
            $redis->removeKey($session);
            return false;
        }
        $key = 'gdp';
        try{
            $tokenInfo = JWT::decode($redisToken,$key,["HS256"]);
        }catch (\Exception $e){
            return false;
        }
        return json_encode($tokenInfo->uid);
    }
}