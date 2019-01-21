<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/18
 * Time: 10:04
 */
namespace app\index\controller;

use think\Db;

class Order extends Common{
    public function index(){

        return $this->fetch();
    }

    // 核对订单信息
    public function check(){
        $user_id = get_user_id();
        if ($user_id === false){// 是否登录
            return $this->error('请先登录','user/login');
            exit;
        }
        // 查询地址
        $district_list = model('userinfo')->getDistrict();
        // 模板赋值
        $this->assign('district_list',$district_list);
        // 获取购物车中的内容
        $cart_list = model('Cart')->listData();
        $this->assign('cart_list',$cart_list);
        return $this->fetch();
    }

    // 获得选择的地址信息
        public function getDistrictDesc(){
        $id = input('id/d');
        // 查询
        $row = Db::name('user_district')->find($id);
        return json_encode($row);
    }

    // 提交订单
    public function submit(){
        $data = input();
        dump($data);
    }
}