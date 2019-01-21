<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 19:39
 */
namespace app\admin\model;

use think\Model;

class Category extends Model{
    public function getCategoryList(){
        return $this->select();
    }

    /**
     * @param int $id 查询的父级分类的id
     * @param bool $is_clear 是否清空之前查询的数据
     * @return array
     */
    public function getCategoryTree($id=0,$is_clear=true){
        return get_cate_tree($this->select(),$id,0,$is_clear);
    }
}