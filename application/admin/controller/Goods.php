<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 13:45
 */
namespace app\admin\controller;

use think\Db;
use think\response\Json;

class Goods extends Common{
    // 商品列表
    public function list(){
        // 查询分页数据
        $goods_list = model('Goods')->listData();
        $this->assign('goods_list',$goods_list);
        // 查询分类
        $cate_list = model('Category')->getCategoryTree();
        $this->assign('cate_list',$cate_list);
        // 渲染模板
        return $this->fetch();
    }
    // 商品添加
    public function add(){
        // 是否为get，get则表示没有提交数据
        if (request()->isGet()){
            // 查询分类列表
            $cate_list = model('Category')->getCategoryTree();
            $this->assign('cate_list',$cate_list);
            // 查询属性分类
            $type_list = model('type')->select();
            $this->assign('type_list',$type_list);
            // 渲染模板
            return $this->fetch();
        }
        if (request()->isPost()){
            $data = input();
            // 验证商品编号
            if (!$this->checkGoodsSn($data)){
                $this->error('编号存在');
            }
            // 图片是否正确
            if (!$this->uploadGoodsImage($data)){
                $this->error('图片格式错误');
            }
            // 添加
            $goods_model = model('Goods');
            // 添加 商品、商品属性
            $result = $goods_model->add($data);
            // 验证数据
            // 错误会返回错误信息，正确返回true
            if ($result === false){
                // 有错误信息
                return $this->error($goods_model->getError());
                exit;
            }
            return $this->success('插入成功','goods/list');
        }
    }
    // 编辑
    public function edit(){
        if (request()->isPost()){// 如果是提交的数据，更新
            $data = input();
            dump($data);
            exit;
        }
        // 得到编辑的文章
        $id = input('id');
        // 查询数据
        $row = model('Goods')->get(['id'=>$id]);// 得到该商品的信息
        $cate_list = model('Category')->getCategoryTree();// 得到分类的信息
        if (!$row){// 是否找到记录
            $this->error('未找到该条记录');
        }
        // 模板赋值
        $this->assign('row',$row);
        $this->assign('cate_list',$cate_list);
        // 显示模板
        return $this->fetch();
    }
    // 更新
    public function update(){
        $data = input();
        if (!$data['id']){// 如果没有接收到
            return $this->error('修改失败');
        }
        // 1. 验证数据
        $result = $this->validate($data,'Goods');
        if ($result !== true){
            return $this->error($result);
        }
        // 2. 检验货号
        if ($this->checkGoodsSn($data,true) === false){
            return $this->error('货号错误');
        }
        // 3. 检验图片是否更新
        if (!$data['goods_image'] || !$data['goods_thumb']){// 删除data中的数据
            unset($data['goods_image']);
            unset($data['goods_thumb']);
        }
        // 更新数据
        $result = model('Goods')->editGoods($data);
        if (!$result){// 如果没有受影响的记录
            return $this->error($result->getError());
        }
        return $this->success('更新成功','goods/list');
    }
    // 删除
    public function delete(){
        // 得到删除id
        $id = input('id');
        $delete_recycle = input('recycle');
        if ($delete_recycle){
            // 删除数据
            $result = Db::name('Goods')->where('id','eq',$id)->delete();
        }else{
            // 使用Db伪删除goods数据
            $result = Db::name('Goods')->where('id','eq',$id)->setField(['is_del'=>1]);
        }

        if (!$result){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
    // 回收
    public function roback(){
        $id = input('id');
        // 恢复数据
        $result = Db::name('Goods')->where('id','eq',$id)->setField(['is_del'=>0]);
        if (!$result){
            $this->error('恢复失败');
        }
        $this->success('恢复成功');
    }
    // 回收站
    public function recycle(){
        // 查询分页数据
        $goods_list = model('Goods')->listData(true);
        // 查询分类
        $cate_list = model('Category')->getCategoryTree();
        // 模板赋值
        $this->assign('goods_list',$goods_list);
        $this->assign('cate_list',$cate_list);
        // 渲染模板
        return $this->fetch();
    }
    // 点击改变 推荐、热门、新品
    public function changStatus(){
        // 1. 接收数据
        $id = input('id');
        $field = input('field');
        $goods_model = model('Goods');
        // 2. 使用模型修改数据
        $result = $goods_model->changeFieldVaue($id, $field);
        if ($result === false){// 修改失败
            return json(['status'=>0,'msg'=>$goods_model->getError()]);
        }
        /**
         * status:状态码
         * msg:信息提示
         * code:操作状态码
         */
        return json(['status'=>1,'msg'=>'ok','code'=>$result]);
    }

    // 商品编号
    /**
     * @param $data 查询的数据
     * @return bool 查询到返回false，没有返回true
     */
    private function checkGoodsSn(&$data,$is_update=false){
        if ($data['goods_sn']){
            // 查询 goods_sn 的条件
            $where = ['goods_sn'=>$data['goods_sn']];
            if ($is_update){// 是更新，排除当前id
                // 添加不是当前id的条件
                $where['id'] = ['neq',$data['id']];
            }
            // 查询商品编号
            if (model('Goods')->get($where)){
                // 查询到表示存在
                return false;
            }
        }else{
            // 没有提交货号，随机生成
            $data['goods_sn'] = 'SHOP'.strtoupper(uniqid());
        }
        return true;
    }

    /**
     * @param $data 要压缩的图片存放在数组中
     * @return bool 文件类型是否合法
     */
    private function uploadGoodsImage(&$data){
        // 1. 获取数据
        $file = request()->file('goods_img');
        // 是否上传了文件
        if (empty($file)){
            return false;
        }
        // 2. 文件上传
        // validate 验证文件的参数，move 上传文件的路径
        $info = $file->validate(['ext'=>'jpeg,jpg,png,gif'])->move('uploads');
        // 3. 上传文件的地址
        $data['goods_image'] = str_replace('\\','/','uploads/'.$info->getSaveName());

        // 压缩图
        // 1. 要压缩的图片
        $img = \think\Image::open($data['goods_image']);
        // 2. 计算压缩图片的地址
        $data['goods_thumb'] = 'uploads/'.date('Ymd').'/thumb_'.$info->getFilename();
        // 3. 生成略缩图
        $img->thumb(150,150)->save($data['goods_thumb']);
        // 上传到 资源服务器
        img_to_cdn($data['goods_image']);
        img_to_cdn($data['goods_thumb']);
        return true;
    }

    // 上传图片
    public function ajaxUploadImage(){
        // 2. 验证图片
        $data = [];
        if (!$this->uploadGoodsImage($data)){
            return json(['status'=>0,'msg'=>'上传失败']);
        }else{
            return json(['status'=>1,'msg'=>'上传成功','goods_image'=>$data['goods_image'],'goods_thumb'=>$data['goods_thumb']]);
        }
    }

    // 返回类型
    public function showAttribute(){
        $id = input('type_id/d');
        // 查询
        $data = Db::name('Attribute')->where('type_id','eq',$id)->select();
        if (!$data){
            return json(['status'=>0,'message'=>'获取数据失败']);
        }
        // 遍历 属性
        foreach ($data as $key=>$v){
            if ($v['attr_values'] != '' && $v['attr_values']){
                // 如果 attr_values 不为空，则拆分
                $data[$key]['attr_values'] = explode(',',$v['attr_values']);
                // $data[$key]['attr_values'] = explode(',',$v['attr_values']);
            }
        }
        $this->assign('data',$data);
        return json(['status'=>1,'message'=>'获取数据成功','data'=>$this->fetch()]);
    }
}