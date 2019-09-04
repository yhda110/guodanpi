<?php
/**
 * Created by PhpStorm.
 * User: 李子杰
 * Date: 2019/8/22
 * Time: 11:32
 * 标签控制器
 */

namespace app\index\controller;

use think\Request;
use app\index\model\tagsModel;

header("Content-type: text/html; charset=utf-8");

class Tags
{
    public function getAllTag()
    {
        $tagModel = new tagsModel();
        $data = $tagModel->getAllTags();
        if (!empty($data)) {
            returnJsonInfo($data);
        } else {
            returnJsonErrorInfo('暂无数据', 500);
        }
    }

    /**
     * @param Request $request
     */
    public function insertTag(Request $request)
    {
        if (Request::instance()->isPost()) {
            $tagModel = new tagsModel();
            $tag_name = $request->param('tag_name');
            $insert_id = $tagModel->insertTag($tag_name);
            if ($insert_id > 0) {
                returnJsonInfo($insert_id);
            } else {
                returnJsonErrorInfo('新建失败', 500);
            }
        }else{
            returnJsonErrorInfo('请求失败', 500);
        }

    }

    /**
     * @param Request $request
     */
    public function deleteTag(Request $request)
    {
        if (Request::instance()->isPost()) {
            $tagModel = new tagsModel();
            $tag_id = $request->param('tag_id');
            $is_del = $request->param('is_del') == 1 ? 1:0;
            $insert_id = $tagModel->deleteTag($tag_id,$is_del);
            if ($insert_id == 1) {
                returnJsonInfo('删除成功');
            } else {
                returnJsonErrorInfo('删除失败', 500);
            }
        }else{
            returnJsonErrorInfo('请求失败', 500);
        }

    }

    /**
     * @param Request $request
     */
    public function updateTag(Request $request)
    {
        if (Request::instance()->isPost()) {
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
        }else{
            returnJsonErrorInfo('请求失败', 500);
        }
    }

}
