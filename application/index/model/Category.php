<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 14:39
 */
namespace app\index\model;

use think\Db;
use think\Model;

class Category extends Model{
    // 查询 所有分类
    public function getCategory(){
        return Db::name('category')->select();
    }
}