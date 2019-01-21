<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/13
 * Time: 22:08
 */
namespace app\admin\model;

use think\Model;
use think\Db;

class Attribute extends Model{
    // 查询 所有属性
    public function listData(){
        // 方式一：连接查询
        return Db::name('attribute')->field(' a.*,t.type_name')->alias('a')->join('shop_type t','a.type_id=t.id','left')->select();
        // 方式二：使用两次sql查询
        // 优点，数据多时，先查出一条再取一条，查询比连接查询更快
    }

    // 添加 属性
    public function addAttribute($data){
        // 判断是否为 手动输入 属性值
        if ($data['attr_input_type'] == 1){
            // 删除 默认属性
            unset($data['attr_values']);
        }else{
            if (!$data['attr_values']){// 如果属性值为空
                $this->error = '列表选择，必须设置属性值';
                return false;
            }
        }
        // 插入数据
        return $this->insert($data);
    }

    // 更新 属性
    public function updateAttribute($data){
        // 判断是否为 手动输入 属性值
        if ($data['attr_input_type'] == 1){
            // 清空原来的 默认属性
            $data['attr_values'] = '';
        }else{
            if (!$data['attr_values']){// 如果属性值为空
                $this->error = '列表选择，必须设置属性值';
                return false;
            }
        }
        // 更新数据
        return $this->isUpdate(true)->setField($data);
    }
}