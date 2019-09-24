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
function returnJsonInfo($data,$count=''){
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

function nonceStr() {
    static $seed = array(0,1,2,3,4,5,6,7,8,9);
    $str = '';
    for($i=0;$i<8;$i++) {
        $rand = rand(0,count($seed)-1);
        $temp = $seed[$rand];
        $str .= $temp;
        unset($seed[$rand]);
        $seed = array_values($seed);
    }
    return $str;
}

/**
 * 图片保存至本地
 * @param $baseurl
 * @return array
 */
function uploads($baseurl){
    $logo_data = $baseurl;
    //$logo_data = $_POST['logo_base64'];

    if(!empty($logo_data)){
        //$data = file_get_contents('./1.txt');
        $reg = '/data:image\/(\w+?);base64,(.+)$/si';
        preg_match($reg,$logo_data,$match_result);

        $file_name = time().'.'.$match_result[1];

        $logo_path = 'static/image/'.$file_name;
        $num = file_put_contents($logo_path,base64_decode($match_result[2]));


        if(!empty($num)){
            //上传成功之后，再进行缩放操作
            //$image = \think\Image::open($logo_path);

            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
            //$image->thumb(102, 36)->save($logo_path);

            $result = ['code'=>200,'msg'=>'ok','name'=>$logo_path];
        }else{
            $result = ['code'=>100,'msg'=>'no'];
        }
    }else{
        $result = ['code'=>100,'msg'=>'参数错误'];
    }
    return $result;
}

/**
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return bool|string
 * cookie加密解密
 */
function authcode($string, $operation = 'DECODE', $key = 'encrypt', $expiry = 0)

{
    $ckey_length = 4;
    // 密匙
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
        substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
        sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
        ) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}
//获取当前url
function get_current_url()
{
    $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
    $parse = parse_url($url);
    if(isset($parse['query'])) {
        parse_str($parse['query'],$params);
        $url   =  $parse['path'].'?'.http_build_query($params);
    }

    $url = preg_replace("/&code=[^&]*/i", "", $url);
    $url = preg_replace("/&state=[^&]*/i", "", $url);
    $url = preg_replace("/&appid=[^&]*/i", "", $url);
    return $url;
}
