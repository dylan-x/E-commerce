<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/16
 * Time: 15:31
 */
class redisCache{
    private static $redis = null;// 保存redis的连接
    private static $config = [];

    // 使用单例模式
    private function __construct(){}
    // 禁止克隆
    private function __clone(){}
    // 获得单例对象
    public static function getInstance(){
        if (self::$redis == null){
            // 得到配置文件的配置
            $config = config('redis');
            self::$redis = new \Redis();
            // 连接
            self::$redis->connect($config['host'],$config['port']);
        }
        return self::$redis;
    }

    /**
     * redis 设置字符串数据
     * @param $key          设置储存的key
     * @param $value        设置储存的value
     * @param int $expire   设置储存的时间
     * @return boolean      返回true
     */
    public static function set($key, $value, $expire=3600){
        // 将value的数据，用json格式转换

        $value = json_encode($value);
        // 添加key
        self::$redis->set($key, $value);
        // 设置该key的存储时间
        self::$redis->expire($key, $expire);
        return true;
    }

    public static function get($key){
        // 获得key
        $value = self::$redis->get($key);
        // 返回数据
        return json_decode($value);
    }

    // 清空缓存
    public static function clear(){
        // 清空所有的缓存
        self::$redis->flushAll();
    }
}