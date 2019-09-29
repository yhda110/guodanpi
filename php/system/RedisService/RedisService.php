<?php
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/5
 * @Time: 11:13
 */

namespace system\RedisService;

use think\Loader;
use think\Controller;

class RedisService extends Controller
{
    public $redis;

    public function __construct(Request $request = null)
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->auth(config('Redis.redis_auth'));
        parent::__construct($request);
    }

    public function getRedis($key)
    {
        return $this->redis->get($key);
    }

    public function setRedis($key, $value)
    {
        $this->redis->set($key,$value);
    }
}