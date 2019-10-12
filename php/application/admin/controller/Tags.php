<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 标签控制器
 */

namespace app\admin\controller;

use system\tokenService\tokenService;
use think\Request;
use app\admin\model\tagsModel;
use think\Controller;
use think\Loader;

header("Content-type: text/html; charset=utf-8");

class Tags extends Controller
{
    public $tokenService;
    static $result = array(
        401 => 'token验证失败',
        501 => '暂无数据',
        502 => '标签已存在',
        503 => '插入数据库失败'
    );

    public function __construct(Request $request = null)
    {
//        Loader::import('tokenService/tokenService', SYSTEM_PATH);
//        $this->tokenService = new tokenService();
        parent::__construct($request);
    }
    /**
     * @title 获取标签列表
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/tap/getAll","1":"请求方式：post","2":"接口备注：查询标签列表"}
     * @param {"name":"limit","type":"int","required":false,"default":"10","desc":"长度"}
     * @param {"name":"offset","type":"int","required":true,"default":"0","desc":"页数"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"count","type":"int","required":true,"desc":"总数","level":2}
     * @return {"name":"data","type":"array","required":true,"desc":"标签列表","level":2}
     * @return {"name":"id","type":"int","required":true,"desc":"标签ID","level":3}
     * @return {"name":"tag_name","type":"string","required":true,"desc":"标签名","level":3}
     * @return {"name":"is_del","type":"int","required":true,"desc":"是否删除 0：未删除 1：已删除","level":3}
     * @return {"name":"create_time","type":"string","required":true,"desc":"创建时间","level":3}
     */
    public function getAllTag(Request $request)
    {
//        $token = input("server.HTTP_TOKEN");
//        $tokenCheck = $this->tokenService->checkToken($token,$_COOKIE['PHPSESSID']);
//        if(!$tokenCheck){
//            returnJsonErrorInfo(self::$result[401],401);exit();
//        }
        $limit = $request->param('limit', 10) != ''?$request->param('limit', 10):10;
        $offset = $request->param('offset', 0)!= ''?$request->param('offset', 0):0;
        $tagModel = new tagsModel();
        $data = $tagModel->getAllTags($limit, $offset);
        if (!empty($data['info'])) {
            returnJsonInfo($data);
        } else {
            returnJsonErrorInfo(self::$result[501], 501);
        }
    }

    /**
     * @title 插入标签
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/tag/insertTag","1":"请求方式：post","2":"接口备注：新建标签,已经存在的无法插入"}
     * @param {"name":"tag_name","type":"string","required":true,"default":"10","desc":"长度"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     */
    public function insertTag(Request $request)
    {
        if (!Request::instance()->isPost()) exit();
//            $user = $this->tokenService->checkToken(input('server.HTTP_TOKEN'),$_COOKIE['PHPSESSID']);
//            if(!$user){
//                returnJsonErrorInfo(self::$result[401],401);exit();
//            }
        $tagModel = new tagsModel();
        $tag_name = $request->param('tag_name');
        $is_exist = $tagModel->getOneTag($tag_name);
        if ($is_exist) {
            returnJsonErrorInfo(self::$result[502], 502);
            exit();
        }
        $insert_id = $tagModel->insertTag($tag_name);
        if ($insert_id > 0) {
            returnJsonInfo($insert_id);
        } else {
            returnJsonErrorInfo(self::$result[503], 503);
        }


    }

    /**
     * @title 删除标签
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/tag/insertTag","1":"请求方式：post","2":"接口备注：删除标签"}
     * @param {"name":"tag_id","type":"int","required":true,"default":"无","desc":"标签ID"}
     * @param {"name":"is_del","type":"int","required":true,"default":"无","desc":"1：删除 0：恢复"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     */
    public function deleteTag(Request $request)
    {
        if (!Request::instance()->isPost()) exit();

//            $user = $this->tokenService->checkToken(input('server.HTTP_TOKEN'),$_COOKIE['PHPSESSID']);
//            if(!$user){
//                returnJsonErrorInfo(self::$result[401],401);exit();
//            }
        $tagModel = new tagsModel();
        $tag_id = $request->param('tag_id');
        $is_del = $request->param('is_del') == 1 ? 1 : 0;
        $insert_id = $tagModel->deleteTag($tag_id, $is_del);
        if ($insert_id == 1) {
            returnJsonInfo('删除成功');
        } else {
            returnJsonErrorInfo('删除失败', 500);
        }


    }

    /**
     * @title 更新标签
     * @desc  {"0":"接口地址：https://test.lzjrys.store/api/admin/tag/updateTag","1":"请求方式：post","2":"接口备注：更新标签名"}
     * @param {"name":"tag_id","type":"int","required":true,"default":"无","desc":"标签ID"}
     * @param {"name":"tag_name","type":"string","required":true,"default":"无","desc":"标签名"}
     * @return {"name":"flag","type":"boolean","required":true,"desc":"成功true 失败false","level":1}
     * @return {"name":"code","type":"string","required":true,"desc":"返回码 0成功","level":1}
     * @return {"name":"msg","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     */
    public function updateTag(Request $request)
    {
        if (!Request::instance()->isPost()) exit();

//            $user = $this->tokenService->checkToken(input('server.HTTP_TOKEN'),$_COOKIE['PHPSESSID']);
//            if(!$user){
//                returnJsonErrorInfo(self::$result[401],401);exit();
//            }
        $tagModel = new tagsModel();
        $tag_id = $request->param('tag_id');
        $tag_name = $request->param('tag_name');
        $data = array();
        $data['tag_name'] = $tag_name;
        $insert_id = $tagModel->updateTag($tag_id, $data);
        if ($insert_id == 1) {
            returnJsonInfo('更新成功');
        } else {
            returnJsonErrorInfo('更新失败', 500);
        }

    }

}
