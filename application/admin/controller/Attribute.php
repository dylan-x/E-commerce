<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/13
 * Time: 22:06
 */
namespace app\admin\controller;

use think\Db;

class Attribute extends Common{
    // 属性列表
    public function index(){
        // 查询数据
        $attribute_list = model('Attribute')->listData();
        // 模板赋值
        $this->assign('attribute_list',$attribute_list);
        return $this->fetch();
    }
    // 添加属性
    public function add(){
        if (request()->isGet()){// 不是post提交的数据
            // 1. 查询 类型
            $type_list = model('type')->select();
            // 2. 模板赋值
            $this->assign('type_list',$type_list);
            // 3. 渲染模板
            return $this->fetch();
        }
        // 接收添加的数据
        if (request()->isPost()){
            $data = input();
            // 实例化 属性模型类
            $attribute = model('Attribute');
            // 添加数据
            $result = $attribute->addAttribute($data);
            if (!$result){// 插入失败
                return $this->error($attribute->getError());
            }
            return $this->success('插入成功','attribute/index');
        }
    }
    // 编辑属性
    public function edit(){
        if (request()->isGet()){// 非post，就显示修改界面
            $id = input('id/d');
            // 查询数据
            $row = model('Attribute')->find($id);
            $type_list = model('type')->select();
            // 模板赋值
            $this->assign('row',$row);
            $this->assign('type_list',$type_list);
            return $this->fetch();
        }
        // 更新数据
        if (request()->isPost()){
            $data = input();
            // 实例化 属性模型类
            $attribute = model('Attribute');
            // 添加数据
            $result = $attribute->updateAttribute($data);
            if (!$result){// 更新失败
                return $this->error($attribute->getError());
            }
            return $this->success('更新成功','attribute/index');
        }
    }
    // 删除属性
    public function delete(){
        // 得到要删除的 id
        $id = input('id/d');
        // 删除数据
        $result = Db::name('Attribute')->where('id','eq',$id)->delete();
        if (!$result){// 如果删除失败
            return $this->error('删除失败');
        }
        return $this->success('删除成功','attribute/index');
    }
}