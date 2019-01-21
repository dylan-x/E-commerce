<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/17
 * Time: 23:06
 */
namespace app\index\model;

use think\Db;
use think\Model;

class Cart extends Model{
    // 显示购物车
    public function listData(){
        // 是否登录
        $user_id = get_user_id();
        if ($user_id === false){// 没有登录
            // 读取cookie中的 购物车临时存储
            $cart = [];
            // 取cookie中的信息
            $list = cookie('listcart')? cookie('listcart'):[];
            // 转换数据格式
            foreach ($list as $key=>$value){
                // 将下标内容拆分 提取其它信息
                $tmp = explode('-',$key);
                $cart[] = [
                    'goods_id'          => $tmp[0],
                    'goods_attr_ids'    => $tmp[1],
                    'goods_count'       => $value,
                ];
            }
        }else{
            $cart = Db::name('cart')->where('user_id',$user_id)->select();
        }
        foreach ($cart as $key=>$value){
            // 根据商品id，获取商品的基本信息
            $cart[$key]['goods_info'] = Db::name('Goods')->where('id',$value['goods_id'])->find();
            // 查询购物车 商品属性信息
            $cart[$key]['attrs'] = Db::name('goods_attr')->alias('a')->field('b.attr_name,a.attr_values')->join('shop_attribute b','a.attr_id=b.id','left')->where('a.id','in',$value['goods_attr_ids'])->select();
        }
        return $cart;
    }
    // 添加到购物车
    public function addCart($goods_id,$goods_attr_ids,$goods_count){
        $user_id = get_user_id();
        if ($user_id === false){// 是否登录
            // 没有登录，将购物车的信息，存入cookie
            $cart = cookie('cartlist')? cookie('cartlist'):[];// 读取cookie的数据
            // 拼接要存储的 购物信息
            $key = $goods_id.'-'.$goods_attr_ids;
            // 判断该信息是否存在
            if (in_array($key,$cart)){
                // 有重复，则添加数量
                $cart[$key] += $goods_count;
            }else{
                // 没有，就将数据添加进去
                $cart[$key] = $goods_count;
            }
            // 将数据存入cookie中
            cookie('cartlist',$cart);
        }else{// 如果登录了
            // 组装条件
            $map = [
                'user_id'   => $user_id,
                'goods_id'  => $goods_id,
                'goods_attr_ids' => $goods_attr_ids,
            ];
            // 如果 已有数据
            if (Cart::get($map)){
                // 指定 goods_count 累加。第二个参数，指定累加的数量
                Db::name('Cart')->where($map)->setInc('goods_count',$goods_count);
            }else{
                // 添加数量
                $map['goods_count'] = $goods_count;
                // 插入数据
                Db::name('Cart')->insert($map);
            }
        }
    }
    // 删除 购物车中的数据
    public function deleteCart($goods_id,$goods_attr_ids){
        $user_id = get_user_id();
        if ($user_id === false){// 是否登录
            // 取出 cookie中的信息
            $list = cookie('listcart')? cookie('listcart'):[];
            // 组装 key
            $key = $goods_id.'-'.$goods_attr_ids;
            // 删除数组中的数据
            unset($list[$key]);
            // 重新写入cookie
            cookie('listcart',$list);
        }else{// 如果登录了
            $map = [
                'user_id'          => $user_id,
                'goods_id'       => $goods_id,
                'goods_attr_ids'    => $goods_attr_ids,
            ];
            // 找到相对的 购物车的数据
            Db::name('cart')->where($map)->delete();
        }
    }
    // 修改
    public function changeCart($goods_id,$goods_attr_ids,$goods_count){
        $user_id = get_user_id();
        if ($user_id === false){// 没有登录
            // 取出 cookie中的信息
            $list = cookie('listcart')? cookie('listcart'):[];
            // 组装 key
            $key = $goods_id.'-'.$goods_attr_ids;
            // 修改数组中的数据
            $list[$key] = $goods_count;
            // 重新写入cookie
            cookie('listcart',$list);
        }else{// 登录了
            $map = [
                'user_id'          => $user_id,
                'goods_id'       => $goods_id,
                'goods_attr_ids'    => $goods_attr_ids,
            ];
            // 找到相对的 购物车的数据
            Db::name('cart')->where($map)->setField('goods_count',$goods_count);
        }

    }

    // 将数据转义到数据库中
    public function cookieToDb(){
        $user_id = get_user_id();
        if ($user_id === false){// 是否登录
            return false;
        }
        // 获取cookie中的数据
        $list = cookie('listcart')? cookie('listcart'):[];
        // 组装 key
        foreach ($list as $key=>$value) {
            $tmp = explode('-',$key);
            $map = [
                'user_id'          => $user_id,
                'goods_id'       => $tmp[0],
                'goods_attr_ids'    => $tmp[1],
            ];
            // 修改数据
            if (Db::name('Cart')->where($map)->field()){// 如果找到了，就修改
                Db::name('Cart')->where($map)->setField('goods_count',$value);
            }else{// 没找到，就插入
                $map['goods_count'] = $value;
                Db::name('Cart')->insert($map);
            }
        }
    }
}