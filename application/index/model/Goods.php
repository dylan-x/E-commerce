<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 14:09
 */
namespace app\index\model;

use think\Db;
use think\Model;

class Goods extends Model{
    // 查询 热卖、推荐、新品 商品
    public function getRHNGoods($field){
        return Db::name('goods')->where($field,1)->order('id desc')->limit(5)->select();
    }
    // 得到商品详细信息
    public function getGoodsInfo($goods_id){
        $goods_info = Db::name('Goods')->find($goods_id);
        // 查询图片
        $goods_info['images'] =  Db::name('Goods_img')->where('goods_id',$goods_id)->select();
        // 唯一属性
        $attribute = Db::name('goods_attr')->alias('a')->join('shop_attribute b','a.attr_id=b.id','left')->field('a.*,b.attr_name,b.attr_type')->where('a.goods_id',$goods_id)->select();
        // 保留属性唯一
        $goods_info['unique'] = [];
        foreach ($attribute as $key=>$value){
            if ($value['attr_type'] == 1){// 只取 单一属性
                $goods_info['unique'][] = $value;
            }else{// 多选属性
                $goods_info['radio'][$value['attr_id']][] = $value;
            }
        }
        // dump($goods_info);
        return $goods_info;
    }
}