<?php
namespace app\index\controller;
use \redisCache;

class Index extends Common
{
    public function index()
    {
        // 查询
        $rec_list = model('Goods')->getRHNGoods('is_rec');//推荐
        $hot_list = model('Goods')->getRHNGoods('is_hot');//热卖
        $new_list = model('Goods')->getRHNGoods('is_new');//新品
        // 模板赋值
        $this->assign('is_index',1);
        $this->assign('rec_list',$rec_list);
        $this->assign('hot_list',$hot_list);
        $this->assign('new_list',$new_list);
        return $this->fetch();
    }
    public function test(){
        redisCache::getInstance();
        redisCache::set('name',['name'=>'paigu']);
        dump(redisCache::get('name'));
    }
}
