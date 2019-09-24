<?php
/**
 * @Created by PhpStorm.
 * @Author: 李子杰
 * @email:ansheng1021@163.com
 * @Date: 2019/9/5
 * @Time: 11:13
 */

namespace system\ImageService;

use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use think\Controller;
use app\index\model\ImgModel;
const Access_Key = 'UId8ejdTbMI3uU3wiHSc6HTChU6C-zLVaZYlGjiM';
const Secret_Key = 'zzCOjCMrjUrN1uQ6yvOgyNK01LpRAfUlwngjF963';

//define('Access_Key','UId8ejdTbMI3uU3wiHSc6HTChU6C-zLVaZYlGjiM');
//define('Secret_Key','zzCOjCMrjUrN1uQ6yvOgyNK01LpRAfUlwngjF963');
class ImageService extends Controller
{
    public function _initialize()
    {

    }

    public function QiniuGetToken()
    {
        require_once APP_PATH . '../vendor/qiniu/php-sdk/autoload.php';
//        $accessKeys = Access_Key;
//        $secretKeys = Secret_Key;
        $auth = new Auth(Access_Key, Secret_Key);
        $bucket = 'gdp';
        $domain = 'lzjrys.store';
        $token = $auth->uploadToken($bucket);
        $result = array(
            'token' => $token,
            'domain' => $domain
        );
        return $result;
    }

    public function uploadImg($base64_url)
    {
        $ImgModel = new ImgModel();
        $imageSave = uploads('data:image/png;base64,' . $base64_url);
        if (!$imageSave) {
            return false;
            exit();
        }
        $filePath = $imageSave['name'];
//        $file = request()->file('img');
//        // 要上传图片的本地路径
//        $filePath = $file->getRealPath();
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        // $controllerName=$this->getContro();
//        // 上传到七牛后保存的文件名
        $key = substr(md5($filePath), 0, 5) . date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '../vendor/qiniu/php-sdk/autoload.php';
//        $accessKeys = Access_Key;
//        $secretKeys = Secret_Key;
        $auth = new Auth(Access_Key, Secret_Key);
        $bucket = 'gdp';
        $domain = 'pxjwk309w.bkt.clouddn.com';
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            $id = $ImgModel->insertImgByUrl($domain . '/' . $ret['key']);
            unlink($filePath);
            if ($id > 0) {
                return $domain . '/' . $ret['key'];
            } else {
                return $domain . '/' . $ret['key'];
            }
        }


    }
}