<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/18
 * Time: 15:42
 */
namespace app\index\model;

use think\Db;
use think\Model;

class District extends Model{
    // 查找地址
    public function getNextDistrict($id=0){
        // 默认返回一级，省市
        return Db::name('District')->where('parent_id',$id)->select();
    }
}