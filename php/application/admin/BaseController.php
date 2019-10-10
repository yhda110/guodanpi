<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3
 * Time: 14:29
 */

namespace app\admin;
use think\Request;
use think\Controller;
class BaseController extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

}