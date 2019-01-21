<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/15
 * Time: 21:07
 */
// 权限控制器
namespace app\admin\controller;

use think\Db;

class Rule extends Common{
    // 权限列表
    public function index(){
        $data = model('Rule')->getRuleTree();
        $this->assign('data', $data);
        return $this->fetch();
    }
    // 权限添加
    public function add(){
        if (request()->isGet()){
            // 查询 层级有关系的 Rule
            $rule_list = model('Rule')->getRuleTree();
            // 模板赋值
            $this->assign('rule_list',$rule_list);
            return $this->fetch();
        }
        // 数据写入
        $result = db('Rule')->insert(input());
        if (!$result){// 写入失败
            return $this->error('写入失败');
            exit;
        }
        return $this->success('写入成功','rule/index');
    }
    // 编辑权限
    public function edit(){
        $rule_id = input('id/d');
        if (request()->isGet()){
            // 查询该权限
            $row = Db::name('rule')->find($rule_id);
            $rule_list = model('Rule')->getRuleTree();

            // 模板赋值
            $this->assign('rule_list',$rule_list);
            $this->assign('row',$row);
            return $this->fetch();
            exit;
        }
        // 修改数据
        $data = input();
        // 修改数据
        $result = model('Rule')->isUpdate(true)->save($data);
        if (!$result){ // 修改数据失败
            return $this->error(model('Rule')->getError());
            exit;
        }
        return $this->success('修改成功','rule/index');
    }
    // 权限删除
    public function delete(){
        $rule_id = input('id/d');
        // 删除权限
        $result = model('Rule')->delete($rule_id);
        if (!$result){ // 删除失败
            return $this->error(model('rule')->getError());
        }
        return $this->success('删除成功','rule/index');
    }
}