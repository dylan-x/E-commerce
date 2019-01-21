<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/14
 * Time: 22:56
 */
namespace app\admin\model;

use think\Image;
use think\Model;

class GoodsImg extends Model{
    // 添加相册地址
    /**
     * @param $field        获取文件的name值
     * @param $goods_id     商品id
     */
    public function addGoodsImg($field, $goods_id){
        // 插入表的数组
        $list = [];
        // 获取文件
        $files = request()->file($field);
        foreach($files as $file){
            $info = $file->validate(['ext'=>'jpeg,jpg,png,gif'])->move('uploads');
            if (!$info){
                // 如果文件上传异常，忽略
                continue;
            }
            // 组装本地的地址
            $goods_img = str_replace('\\','/','uploads/'.$info->getSaveName());
            // 生成 缩略图
            $img = Image::open($goods_img);
            // 缩略图的保存位置
            $goods_thumb = 'uploads/'.date('Ymd').'/'.'thumb_'.$info->getFileName();
            // 生成 缩略图
            $img->thumb(150,150)->save($goods_thumb);
            // 上传至资源服务器
            img_to_cdn($goods_img);
            img_to_cdn($goods_thumb);
            $list[] = [
                'goods_id'=>$goods_id,
                'goods_image'=>$goods_img,
                'goods_thumb'=>$goods_thumb,
            ];
        }
        if ($list){
            db('goods_img')->insertAll($list);
        }
    }
}