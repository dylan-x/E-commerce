<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/15
 * Time: 21:23
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Rule extends Model{
    // 得到所有的 rule
    public function getRuleTree($id=0,$is_clear=false){
        // 查询所有数据
        $data = Db::name('Rule')->select();
        // 得到层级数据
        return get_cate_tree($data,$id,0,$is_clear);// 使用分类函数，将数据分层
    }
}