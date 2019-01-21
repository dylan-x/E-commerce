<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('get_cate_tree')){
    /**
     * 得到层级分类
     * @param $array    遍历的分类数组
     * @param int $id   遍历的那个分类层级
     * @param int $lev  代表当前层级
     * @return array
     */
    function get_cate_tree($array, $id=0, $lev=0, $is_clear=false){
        static $list = [];
        if ($is_clear){// 清空数组
            $list = [];
        }
        foreach ($array as $value){
            // 如果遍历到当前分类
            if ($value['parent_id'] == $id){
                // 放入数据
                $value['lev'] = $lev;
                $list[] = $value;
                // 继续遍历该分类的子节点
                get_cate_tree($array, $value['id'], $lev+1);
            }
        }
        return $list;
    }
}
if (!function_exists('img_to_cdn')){
    /**
     * 文件上传至 资源服务器
     * @param $local_path           上传文件的地址
     * @param string $server_path   保存文件的地址
     * @return bool                 是否上传成功
     */
    function img_to_cdn($local_path, $server_path=''){
        // 实例化ftp对象实例
        $obj = new \ftp(config('ftp.host'),config('ftp.port'),config('ftp.user'),config('ftp.pass'));
        // 有指定服务端的地址，就是用，否则按照本地目录存储
        $server_path = $server_path? $server_path:$local_path;
        return $obj->up_file($local_path,$server_path);
    }
}
if (!function_exists('md6')){
    /**
     * 混淆加密
     * @param $password             混淆加密的密码
     * @param $salt                 混淆值
     * @return string               混淆后的密码
     */
    function md6($password,$salt){
        return md5(md5($password).$salt);
    }
}
if (!function_exists('get_user_id')){
    /**
     * @return mixed 返回false，或者返回已经登录的用户id
     */
    function get_user_id(){
        // 读取session中的用户信息
        $user_info = session('user_info');
        // 是否存在用户信息
        if (!$user_info){
            return false;
        }
        // 返回用户id
        return $user_info['id'];
    }
}
if (!function_exists('get_district')){
    /**
     * 查询地址
     * @return Json
     */
    function get_district(){
        $id = input('id/d');
        if (request()->isAjax()){// 前台请求
            // 查看是否的到地区值
            if (!$id){
                return json(['status'=>0,'msg'=>'参数错误']);
            }
            // 查询数据
            $result = model('District')->getNextDistrict($id);
            if (!$result){
                return json(['status'=>0,'msg'=>'没有查询到数据']);
            }
            // 返回 id 下一级地区
            return json(['status'=>1,'msg'=>'查询成功','data'=>$result]);
        }else{// 自己调用
            return model('District')->getNextDistrict();
        }
    }
}