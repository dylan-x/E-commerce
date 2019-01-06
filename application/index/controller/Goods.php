<?php
namespace app\index\controller;
use think\Controller;
use think\Config;

class Goods extends Controller{
    // 测试页面
    public function index(){
        return 'goods content index action';
    }
    public function getConfig(){
        // 开启debug调试
        Config::set('app_debug',true);
        var_dump(Config::get('session'));
        // 使用助手函数
        config('app_trace',true);   // 开启 tp 的 trace工具
    }
    public function getExtConfig(){
        dump(config('redis'));
    }
    // 调用视图
    public function add(){
        // Config::set('app_debug',true);
        return $this->fetch();
    }
}