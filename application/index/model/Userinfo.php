<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/20
 * Time: 20:25
 */
namespace app\index\model;

use think\Db;
use think\Model;

class Userinfo extends Model{
    // 添加 地址
    public function addDistrict(){
        // 接收数据
        $data = input();
        $data['user_id'] = get_user_id();
        if (empty($data['is_default'])){// 是否设置为常用地址
            $data['is_default'] = 0;
        }else{
            $data['is_default'] = 1;
        }
        // 拼接地址
        $data['district_ids'] = implode(',',$data['district_ids']);

        return Db::name('user_district')->insert($data);
    }

    // 得到 地址
    public function getDistrict($is_default=false){
        $user_id = get_user_id();
        $where = [
            'user_id'=>$user_id
        ];
        if ($is_default){// 如果传递了查询 只为常用的
            $where['is_default'] = 1;
        }
        // 查询用户 有那些地址
        $row = Db::name('user_district')->where($where)->select();
        // 查询地址
        foreach ($row as $key=>$value){
            // 查询 不同的 地址组合
            $district = Db::name('district')->where('id','in',$value['district_ids'])->select();
            // 初始化每一个不同的地址
            $row[$key]['district'] = '';
            foreach ($district as $v){
                // 拼接地址
                $row[$key]['district'] .= $v['name'];
            }
        }
        // dump($row);
        return $row;
    }

}