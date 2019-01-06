<?php
namespace app\admin\controller;
use think\Db;

class Category extends Common{
    // 商品分类
    public function categoryList(){
        $db = Db::name('category');
        // 1. 查询
        $cate_list = $db->select();
        $cate_list = get_array_tree($cate_list);
        // 2. 分配数据
        $this->assign('cate_list',$cate_list);
        return $this->fetch();
    }
    // 添加分类
    public function categoryAdd(){
        
        $db = Db::name('category');
        // 1. 如果有数据
        if (!$_POST){
            // 查询分类
            $cate_list = $db->select();
            
            $cate_list = get_array_tree($cate_list);
            // dump($cate_list);
            // exit;
            
            $this->assign('cate_list',$cate_list);
            return $this->fetch();
            exit;
        }
        $data = $_POST;
            // var_dump($data);
            // dump($db);
            // dump($db->select());

        // 2. 插入数据
        if ($db->insert($data)){
            $this->success('插入成功');
        }else{
            $this->error('插入失败');
        }
    }
    
}