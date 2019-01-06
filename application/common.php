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
if (!function_exists('get_array_tree')){
    /**
     * 无限极分类的层级格式化
     * $array Array 源数据
     * $id int 寻找下一级的类别id
     * $lev int 分类的层级
     * $return Array
     */
    function get_array_tree($array, $id=0, $lev=0){
        static $list = [];
        foreach ($array as $key => $value) {
            // 如果是我们要遍历的层级 parent_id
            if ($value['parent_id'] == $id){
                // 放入层级
                $value['lev'] = $lev;
                // 放入该层级的数据
                $list[] = $value;
                // 递归改层级的下一级， 即：数组中的 parent_id=当前的id
                get_array_tree($array, $value['id'], $lev+1);
            }
        }
        return $list;
    }
}