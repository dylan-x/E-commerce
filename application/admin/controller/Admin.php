<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/15
 * Time: 20:07
 */
namespace app\admin\controller;

use think\Db;

class Admin extends Common{
    // 用户列表
    public function index(){
        $user_list = Db::name('admin')->alias('a')->field('a.*,r.role_name')->join('shop_role r','a.role_id=r.id','left')->select();
        $this->assign('user_list',$user_list);
        return $this->fetch();
    }
    // 添加用户
    public function add(){
        if (request()->isGet()){
            // 获取角色列表
            $role_list = Db::name('role')->select();
            $this->assign('role_list',$role_list);
            return $this->fetch();
        }
        // 添加数据
        $result = model('Admin')->addUser(input());
        if (!$result){// 插入失败
            return $this->error(model('Admin')->getError());
        }
        return $this->success('添加成功','admin/index');
    }
    // 编辑用户
    public function edit(){
        if (request()->isGet()){
            // 获取编辑的用户
            $user_id = input('id/d');
            if ($user_id <= 1 ){
                return $this->error('参数错误');
            }
            // 获取角色列表
            $role_list = Db::name('role')->select();
            // 查询数据
            $row = model('admin')->find($user_id);
            $this->assign('role_list',$role_list);
            $this->assign('row',$row);
            return $this->fetch();
            exit;
        }
        // 接收数据
        $result = model('admin')->updateUser(input());
        if (!$result){// 更新失败
            return $this->error(model('admin')->getError());
        }
        return $this->success('更新成功','admin/index');
    }

    // 删除用户
    public function delete(){
        $user_id = input('id/d');
        if ($user_id <= 1){
            return $this->error('参数错误');
            exit;
        }
        $result = model('admin')->where('id','eq',$user_id)->delete();
        if (!$result){
            // 删除失败
            return $this->error(model('admin')->getError());
        }
        return $this->success('删除成功','admin/index');
    }
}