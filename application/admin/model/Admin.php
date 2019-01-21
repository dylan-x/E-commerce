<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/12
 * Time: 15:06
 */
namespace app\admin\model;

use think\Model;

class Admin extends Model{
    // 管理员登录
    public function login($data){
        // 比对验证码
        $obj = new \think\captcha\Captcha();
        if (!$obj->check($data['verify'])){
            $this->error = '验证码错误';
            return false;
        }
        // 比对用户名，密码
        $map = [
            'username' => $data['username'],
            'password' => md5($data['password'])
        ];
        // 查询
        $user_info = $this->get($map);
        if (!$user_info){// 没查询到，则账号密码错误
            $this->error = '账号信息错误';
            return false;
        }

        // 是否记住
        if (isset($data['remeber'])){
            $expire = 3600*24*7;
        }else{
            // 不记住，销毁cookie
            $expire = 0;
        }
        // 保存cookie
        cookie('admin_info',$user_info->toArray(),$expire);
    }
    // 添加用户
    public function addUser($data){
        // 检查用户名的唯一性
        if ($this->get(['username'=>$data['username']])){
            $this->error = '用户名存在';
            return false;
        }
        // 加密密码
        $data['password'] = md5($data['password']);
        // 插入数据
        return $this->isUpdate(false)->save($data);
    }
    // 更新用户
    public function updateUser($data){
        // 排除自己，是否于其它用户名重复
        $where = [
            'username' => $data['username'],
            'id'       => ['neq',$data['id']],
        ];
        // 检测用户名是否重复
        if ($this->get($where)){
            $this->error = '用户名存在';
            return false;
        }
        //
        // 是否输入密码
        if ($data['password'] == '' || !$data['password']){
            // 不更新密码，使用原密码
            unset($data['password']);
        }else{
            // 加密密码
            $data['password'] = md5($data['password']);
        }
        // 插入数据
        return $this->isUpdate(true)->save($data);
    }

}