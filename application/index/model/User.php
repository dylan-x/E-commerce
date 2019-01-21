<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 17:16
 */
namespace app\index\model;

use think\Db;
use think\Model;

class User extends Model {
    // 注册
    public function regist($data){
        // 用户名是否重复
        if (User::get(['username'=>$data['username']])){
            $this->error = '用户名重复';
            return false;
        }
        // 对应的混淆内容
        $data['salt'] = rand(10000,99999);
        // 使用自定义的混淆
        $data['password'] = md6($data['password'],$data['salt']);
        // 插入数据
        return User::isUpdate(false)->allowField(true)->save($data);
    }

    // 登录
    public function login($data=[]){
        $where = [
            'username'=>['eq',$data['username']],
        ];
        // 查询用户
        $user_info = Db::name('User')->where($where)->find();
        if (!$user_info){// 是否有这个用户
            $this->error = '账号错误';
            return false;
        }
        // 验证密码
        if ($user_info['password'] != md6($data['password'],$user_info['salt'])){
            $this->error = '密码错误';
            return false;
        }
        // 保存用户状态
        unset($user_info['password']);// 清除session中的密码
        unset($user_info['salt']);
        session('user_info',$user_info);
        // 设置了负载均衡时，可使用memcache来实现，session共享

        // 同步cookie中的数据到数据库
        model('Cart')->cookieToDb();

        return true;
    }
}