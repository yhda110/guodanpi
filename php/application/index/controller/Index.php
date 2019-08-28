<?php
namespace app\index\controller;
use system\index\test;
class Index
{
    public function index()
    {
        return view();
    }
    public function test()
    {
        require_once(ROOT_PATH."system/test.php");
        $haha = new test\test();
        $haha->index();
    }
}
