<?php
namespace app\admin\controller;

class Goods extends Common{
    // 商品列表
    public function goodsList(){

        return $this->fetch();
    }
    // 商品添加
    public function goodsAdd(){
        return $this->fetch();
    }
    // 商品编辑
    public function goodsEdit(){
        return $this->fetch();
    }
}