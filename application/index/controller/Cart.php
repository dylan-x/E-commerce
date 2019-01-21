<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/17
 * Time: 21:36
 */
namespace app\index\controller;

class Cart extends Common {
    // 显示购物车
    public function index(){
        $cart_list = model('Cart')->listData();
        $this->assign('cart_list',$cart_list);
        return $this->fetch();
    }

    // 添加到购物车
    public function add(){
        $goods_id = input('id/d');//商品id
        $goods_count = input('goods_count/d');//商品数量
        $goods_attr_ids = input('goods_attr_ids/a',[]);//商品
        $goods_attr_ids = implode(',',$goods_attr_ids);// 将商品属性id，用','连接
        // 录入数据
        $result = model('Cart')->addCart($goods_id,$goods_attr_ids,$goods_count);
        if ($result === false){// 录入失败
            return $this->error(model('Cart')->getError());
            exit;
        }
        // 添加成功
        return $this->success('添加完成','cart/index');
    }

    // 删除
    public function delete(){
        // 得到要修改的 数据
        $id = input('id/d');
        $goods_attr_ids = input('goods_attr_ids');
        $result = model('Cart')->deleteCart($id,$goods_attr_ids);
        if ($result === false){// 删除失败
            return $this->error(model('Cart')->getError());
            exit;
        }
        return $this->success('删除成功','cart/index');
    }

    // 修改数量
    public function changeCartNumber(){
        $goods_id = input('goods_id/d');
        $goods_count = input('goods_count/d');
        $goods_attr_ids = input('goods_attr_ids','');
        // 修改数据
        $result = model('Cart')->changeCart($goods_id,$goods_attr_ids,$goods_count);
        if ($result === false){ // 是否修改成功
            return json(['status'=>0,'msg'=>model('Cart')->getError()]);
        }
        return json(['status'=>1,'msg'=>'ok']);
    }
}