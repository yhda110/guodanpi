<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 李子杰 <ansheng1021@163.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*返回错误信息*/
function returnJsonErrorInfo($msg,$code=''){
    header('Content-Type: text/javascript; charset=utf-8');
    $result['flag'] = false;
    $result['code'] = $code;
    $result['msg'] = $msg;
    $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo $json;
}
function returnJsonInfo($data){
    header('Content-Type: text/javascript; charset=utf-8');
    $result['flag'] = true;
    $result['code'] = 0;
    $result['msg'] = '成功';
    $result['data'] = $data;
    $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo $json;
}