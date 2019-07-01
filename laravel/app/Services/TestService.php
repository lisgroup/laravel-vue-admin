<?php

namespace App\Services;

class TestService
{

    protected $stack = [];

    public function init()
    {
        $this->stack = ['学院君', 'Laravel学院', '单元测试'];
    }

    public function stackContains($value)
    {
        return in_array($value, $this->stack);
    }

    public function getStackSize()
    {
        return count($this->stack);
    }

    public function invalidArgument()
    {
        throw new \InvalidArgumentException('无效的参数');
    }
}
