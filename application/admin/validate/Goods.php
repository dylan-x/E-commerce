<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 23:37
 */
namespace app\admin\validate;
use think\Validate;

class Goods extends Validate{
    // 验证规则
    protected $rule = [
        'goods_name' => 'require',
        'cate_id' => 'require|gt:0',
        'shop_price' => 'require|gt:0',
        // 自定义验证规则
        'market_price' => 'require|checkMarketPrice',
    ];
    // 设置错误信息
    protected $message = [
        'goods_name.require' => '商品名必须存在',
        'cate_id.require' => '请选择商品分类',
        'shop_price.require' => '本店售价必须存在',
        'shop_price.gt' => '本店售价必须大于0',
        'market_price.require' => '本店售价必须存在',
        'market_price.checkMarketPrice' => '本店售价不能比市场价格高',
    ];
    // 自定义验证
    public function checkMarketPrice($value, $rule, $data){
        // 如果本地价格 大于 市场价格，false
        // 也可使用 $value
        if ($data['shop_price'] > $data['market_price']){
            return false;
        }
        return true;
    }
}