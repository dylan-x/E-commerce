<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/20
 * Time: 17:27
 */
namespace app\index\controller;

use think\Db;

class Userinfo extends Common{
    // 添加用户地址
    public function address(){
        // 是否登录
        $user_id = get_user_id();
        if (!$user_id){// 如果没有登录
            return $this->error('请先登录','User/login');
            exit;
        }
        if (request()->isGet()){
            // 获取地址
            $district = $this->getDistrict();
            $this->assign('district',$district);
            // 查询改用户的地址
            $district_list = model('userinfo')->getDistrict();
            // dump($district_list);
            $this->assign('district_list',$district_list);
            return $this->fetch();
        }
        // 添加地址
        if (request()->isPost()){
            // 调用模型插入数据
            $userinfo =  model('Userinfo');
            if (!$userinfo->addDistrict()){// 如果插入数据失败
                return $this->error($userinfo->getError());
                exit;
            }
            return $this->success('插入成功');
        }
    }

    // 获取层级地址
    public function getDistrict(){
        return get_district();
    }
}