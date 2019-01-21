<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 13:43
 */
namespace app\index\controller;

use think\Controller;

class Common extends Controller{
    public function __construct()
    {
        // 调用父类的构造方法
        parent::__construct();
        // 查询分类
        $cate_list = model('Category')->getCategory();
        // 模板赋值
        $this->assign('cate_list',$cate_list);
    }
}