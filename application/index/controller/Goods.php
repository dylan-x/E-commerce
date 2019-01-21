<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 21:53
 */
namespace app\index\controller;

use think\Db;

class Goods extends Common{
    // 商品详情页显示
    public function index(){
        $goods_id = input('id/d');
        // 查询数据
        $goods_info = model('Goods')->getGoodsInfo($goods_id);

        // 模板赋值
        $this->assign('goods_info', $goods_info);
        // 渲染模板
        return $this->fetch();
    }
}