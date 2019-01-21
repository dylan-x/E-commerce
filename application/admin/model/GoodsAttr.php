<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/14
 * Time: 19:48
 */
namespace app\admin\model;

use think\Model;

class GoodsAttr extends Model{
    // 添加 到goods_attr中
    public function addGoodsAttr($data,$goods_id){
        $attr_ids = $data['attr_ids'];
        $attr_values = $data['attr_values'];
        $list = [];
        $tmp = [];
        foreach($attr_ids as $key=>$v){
            // 数据去重
            $attr_id_value = $v.'-'.$attr_values[$key];
            if (in_array($attr_id_value,$tmp)){

            }
            // 在模板中，放入已经添加过的值
            $tpm[] = $attr_id_value;
            // 准备要添加的数据
            $list[] = [
                'goods_id'=>$goods_id,
                'attr_id'=>$v,
                'attr_values'=>$attr_values[$key]
            ];
        }
        // dump($list);
        // 录入数据
        $this->saveAll($list);
        return true;
    }
}