<?php

namespace app\index\controller;

use think\Controller;

class CommonController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
