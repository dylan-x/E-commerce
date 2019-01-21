<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/13
 * Time: 18:50
 */
namespace app\admin\controller;

use think\Db;

class Type extends Common{
    // 类型列表
    public function index(){
        $type_list = model('Type')->select();
        $this->assign('type_list',$type_list);
        return $this->fetch();
    }
    // 类型添加
    public function add(){
        if (request()->isGet()){// 如果不是post提交的数据
            return $this->fetch();
        }
        // 接收数据
        $data = input();
        $type_model = model('Type');
        if (!$type_model->insert($data)){
            return $this->error($type_model->getError());
        }
        return $this->success('插入成功','type/index');
    }
    //类型编辑
    public function edit(){
        if (request()->isGet()){// 如果是get，则显示
            $id = input('id/d');
            // 获取数据
            $row = Db::name('Type')->find(['id'=>['eq',$id]]);
            if (!$row){// 是否查询到数据
                return $this->error('编辑失败');
            }
            // 分配数据
            $this->assign('row',$row);
            // 渲染模板
            return $this->fetch();
            exit;
        }
        // 更新数据
        if (request()->isPost()){
            $id = input('id/d');
            // 使用 type 模型类
            $type_name = input('type_name');
            // 操作数据
            $result = Db::name('type')->where('id','eq',$id)->update(['type_name'=>$type_name]);
            if (!$result){// 操作失败
                return $this->error('更新失败');
                exit;
            }
            return $this->success('更新成功','type/index');
        }
    }
    //类型删除
    public function delete(){
        if (request()->isGet()){
            $id = input('id/d');
            // 操作数据
            $result = Db::name('type')->where('id','eq',$id)->delete();
            if (!$result){// 删除数据
                return $this->error('删除失败');
                exit;
            }
            return $this->success('删除成功');
        }
    }
}