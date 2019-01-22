<?php
namespace app\index\controller;
use think\Db;

/**
 * 收藏控制器
 */
class Store extends Common
{
	
	//收藏,取消收藏
	public function change()
	{
		//首先判断,用户是否登录
		$user_info = session('user_info');
		if (!$user_info) {
			//没有登录
			$this -> error('请先登录','index/login');
		}

		//已经登录
		//获取商品id
		$goods_id = input('id/d');
		$map = [
			'goods_id'=>$goods_id,
			'user_id'=>$user_info['id'],
		];
		//首先查看store表,商品是否已经收藏
		$result = Db::name('store') -> where('goods_id',$goods_id) -> find();
		if (!$result) {
			//商品没有收藏 则将添加到收藏表
			$map['add_time'] = time();
			Db::name('store') -> insert($map);
		}else{
			//收藏了 则删除
			Db::name('store') -> where($map) -> delete();
		}

	}

	//收藏夹页面显示
	public function index()
	{
		//首先判断,用户是否登录
		$user_info = session('user_info');
		if (!$user_info) {
			//没有登录
			$this -> error('请先登录','user/login');
		}
		//已经登录
		//获取用户id
		$user_id = $user_info['id'];
		//从store数据表中查询对应用户的收藏信息
		$store_info = Db::name('store') -> where('user_id',$user_id) -> select();
		$data = [];
		foreach ($store_info as $key => $value) {
			$goods_id = $value['goods_id'];
			$goods_info = Db::name('goods') -> find($goods_id);
			$data[$key] = $goods_info;
		}
		$this -> assign('data',$data);
		return $this -> fetch();
	}

}
?>