<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/15
 * Time: 19:12
 */
namespace app\admin\controller;

use think\Db;

class Role extends Common{
    // 角色列表
    public function index(){
        $role_list = Db::name('role')->select();
        $this->assign('role_list',$role_list);
        return $this->fetch();
    }
    // 添加角色
    public function add(){
        if (request()->isGet()){
            return $this->fetch();
            exit;
        }
        $result = Db::name('role')->insert(input());
        if (!$result){
            return $this->error('插入失败');
        }
        return $this->success('插入成功','role/index');
    }
    // 编辑
    public function edit(){
        $role_id = input('id/d');
        // 是否为普通管理员
        if ($role_id <= 1){
            return $this->error('参数错误');
            exit;
        }
        if (request()->isGet()){
            // 查询数据
            $row = Db::name('role')->find($role_id);
            $this->assign('row',$row);
            return $this->fetch();
            exit;
        }
        $data = input();
        $result = Db::name('Role')->where('id',$data['id'])->setField(['role_name'=>$data['role_name']]);
        if (!$result){
            return $this->error('编辑失败');
        }
        return $this->success('编辑成功','role/index');
    }
    // 删除
    public function delete(){
        $role_id = input('id/d');
        // 是否为普通管理员
        if ($role_id <= 1){
            return $this->error('参数错误');
            exit;
        }
        // 删除数据
        $row = Db::name('role')->delete($role_id);
        if (!$row){
            return $this->error('删除失败');
        }
        return $this->success('删除成功','role/index');
    }

    // 分配权限
    public function disfetch(){
        // 修改那个 角色
        $role_id = input('id/d');
        if (request()->isGet()){
            // 获取所有的权限信息
            $rule_list = model('Rule')->getRuleTree();
            $row = Db::name('Role')->find($role_id);
            // 模板赋值
            $this->assign('rule_list',$rule_list);
            $this->assign('row',$row);
            // 渲染模板
            return $this->fetch();
            exit;
        }
        // 接收 用户提交的权限
        $rule_ids = implode(',',input('rule/a'));
        // 数据入库
        $result = Db::name('Role')->where('id',$role_id)->setField('rule_ids',$rule_ids);
        if (!$result){
            return $this->error('权限操作失败');
            exit;
        }
        return $this->success('权限操作成功','role/index');
    }
}