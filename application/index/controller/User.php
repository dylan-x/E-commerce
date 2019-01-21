<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 17:10
 */
namespace app\index\controller;

class User extends Common{
    // 用户的注册
    public function register(){
        if (request()->isGet()){
            // 用户注册
            return $this->fetch();
            exit;
        }
        // 接收数据
        $data = input();
        $result = model('User')->regist($data);
        if (!$result){ // 注册失败
            return $this->error('注册失败');
            exit;
        }
        return $this->success('注册成功','user/login');
    }

    // 用户登录
    public function login(){
        // 显示页面
        if (request()->isGet()){
            return $this->fetch();
            exit;
        }
        $data = input();
        $result = model('User')->login($data);
        if (!$result){
            return $this->error(model('User')->getError());
            exit;
        }
        return $this->success('登录成功','index/index');
    }
}