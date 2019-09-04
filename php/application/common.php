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
/**
 * @param $msg
 * @param string $code
 * @return 错误信息
 */
function returnJsonErrorInfo($msg,$code=''){
    header('Content-Type: text/javascript; charset=utf-8');
    $result['flag'] = false;
    $result['code'] = $code;
    $result['msg'] = $msg;
    $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo $json;
}

/**
 * @param $data
 * @return 成功信息
 */
function returnJsonInfo($data){
    header('Content-Type: text/javascript; charset=utf-8');
    $result['flag'] = true;
    $result['code'] = 0;
    $result['msg'] = '成功';
    $result['data'] = $data;
    $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo $json;
}

/**
 * @param $curl
 * @param bool $https
 * @param string $method
 * @param null $data
 * @return 请求信息
 */
function _request($curl, $https = true, $method = "GET", $data = null){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $curl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($https){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    }
    if($method == 'POST'){
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data))
        );
    }

    $content = curl_exec($ch);
    return $content;
}

/**
 * @param $url
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}