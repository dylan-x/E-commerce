<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 15:35
 */
namespace app\admin\controller;
use app\admin\model;

class Category extends Common{
    // 分类列表
    public function index(){
        if(request()->isGet()){
            // 查询分类数据
            $cate_list = model('category')->getCategoryTree();
            // 分配数据
            $this->assign('cate_list',$cate_list);
            // 渲染模板
            return $this->fetch();
        }
    }
    // 添加分类
    public function add(){
        // 是否为提交数据，不提交数据，默认为get
        if (request()->isGet()){
            // 查询分类数据
            $cate_list = model('category')->getCategoryTree();
            // 分配数据
            $this->assign('cate_list',$cate_list);
            return $this->fetch();
        }
        // 得到数据
        $data = input();
        // 插入数据
        if (model('category')->insert($data)){
            $this->success('插入成功', 'category/index');
            exit;
        }
        $this->error('插入失败');
    }
    public function edit(){
        if (request()->isGet()){
            // 拿到编辑的id
            $id = input('id');
            $result = model('category')->find($id);
            $cate_list = model('category')->getCategoryTree();
            // 渲染模板
            $this->assign('result', $result);
            $this->assign('cate_list', $cate_list);
            return $this->fetch();
        }
    }
    public function delete(){
        $id = input('id');
        if (model('category')->where(['id'=>$id])->delete()){
            $this->success('删除成功','category/index');
            exit;
        }
        $this->error('删除失败');
    }
}