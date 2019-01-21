<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 23:55
 */
namespace app\admin\model;

use think\Model;

class Goods extends Model{
    // 添加
    public function add($data){
        $data['addtime'] = time();
        // 1. 开启事务
        try{
            $this->startTrans();
            // 插入 goods表 数据
            $result_goods = $this->validate('Goods')->allowField(true)->save($data);
            // 获取 goods表插入的id
            $goods_id = $this->getLastInsID();
            // 插入 goods_attr表数据
            model('GoodsAttr')->addGoodsAttr($data,$goods_id);
            model('GoodsImg')->addGoodsImg('pic',$goods_id);
        }catch (\Exception $e){
            // 回滚事务
            $this->rollback();
            $this->error = $e->getMessage();
            return false;
            exit;
        }
        // 3. 提交
        $this->commit();

        // 返回信息，验证错误返回错误信息，成功返回影响记录
        return true;
    }

    // 更新
    public function editGoods($data=[]){
        return $this->isUpdate(true)->save($data);
    }

    // 查询商品列表数据
    public function listData($is_delete = false){
        // 查询条件
        $where['is_del'] = 0;//默认查询非伪删除的数据

        // 是否是查询 回收站的
        if ($is_delete){
            $where['is_del'] = 1;
        }
        // 1. 商品名
        if (input('keyword')){
            $where['goods_name'] = ['like','%'.input('keyword').'%'];
        }
        // 2. 分类为顶级分类，不查询
        if (input('cate_id') != 0){
            // (1) 得到该分类下的子分类
            $cate_list = get_cate_tree(\model('Category')->getCategoryList(),input('cate_id'),0,true);
            // (2) 遍历出 分类id
            $cate_list_id[] = input('cate_id');//将本身加入数组
            foreach ($cate_list as $v){
                // 转换为数组，再将id放入集合
                $cate_list_id[] = $v->toArray()['id'];
            }
            // (3) 拼接 到 where in
            $where['cate_id'] = ['in',$cate_list_id];
        }
        // 3. 查询类型
        if (in_array(input('intro_type'),['is_rec','is_new','is_hot'])){// 类型：热销、新品、推荐
            $where[input('intro_type')] = '1';
        }
        // 商品分页查询
        // paginate(param1,param2,param3) param1:每页显示条数 param2:是否显示当前页码 param3:url地址参数
//         $this->where($where)->paginate(2,false,['query'=>input()]);
//         dump($this->getLastSql());

        // 返回数据
        return $this->where($where)->paginate(2,false,['query'=>input()]);
    }

    // 修改 商品 热卖、新品、推荐
    public function changeFieldVaue($id, $field){
        $result = $this->field($field)->find($id)[$field]? 0:1;
        $this->where('id','eq',$id)->update([$field=>$result]);
        // 返回修改的是 0还是1
        return $result;
    }
}