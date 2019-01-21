<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/12
 * Time: 14:52
 */
namespace app\admin\controller;
use think\Controller;

class Login extends Controller {
    // 登录 页面
    public function index(){
        // 直接访问，则显示
        if (request()->isGet()){
            return $this->fetch();
            exit;
        }

        // 1. 验证数据
        $admin = model('Admin');
        $result = $admin->login(input());
        if ($result === false){// 是否登录成功
            // 返回错误信息
            return $this->error($admin->getError());
            exit;
        }

        return $this->success('登录成功','index/index');
    }
    // 退出
    public function logout(){
        // 清除cookie信息
        cookie('admin_info',null);
        $this->success('完全退出','login/index');
    }
    // 验证码
    public function makeCaptcha(){
        $captcha = new \think\captcha\Captcha(config('captcha'));
        return $captcha->entry();
    }
}