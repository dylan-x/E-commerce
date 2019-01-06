<?php
namespace app\admin\controller;

// 因为在同一命名空间下，所以不用use
class Index extends Common
{
    public function index(){
        // 渲染视图
        return $this->fetch();
    }
    public function top(){
        // 渲染视图
        return $this->fetch();
    }
    public function main(){
        // 渲染视图
        return $this->fetch();
    }
    public function menu(){
        // 渲染视图
        return $this->fetch();
    }
}