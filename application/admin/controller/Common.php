<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 13:41
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Common extends Controller{
    public $_user = [];// 保存用户信息，相对应的导航栏菜单
    public $is_check_rule = true;// 是否检查权限

    public  function __construct()
    {

        // 调用父类的构造函数
        parent::__construct();

        // 如果开启了全局 token 校验，并且是Post提交
        if (config('is_check_token')){
            // 得到token
            $token = input('__token__');
            $session_token = session('__token__');
            // 对比token
            if (!isset($token) || !isset($session_token) || $token != $session_token){
                $this->error('表单令牌错误');
            }
            // 销毁session中的令牌，以免重复提交表单
            session('__token__',null);
        }

        // 防跳墙
        if (!cookie('admin_info')){
            return $this->error('请先登录','login/index');
            exit;
        }

        // 给该角色初始化，菜单、访问菜单的权限
        $this->getRoleMenu();
        // 检测权限
        $this->checkRoleRule();

    }
    // 得到角色 导航栏菜单，方法访问
    private function getRoleMenu(){
        $this->_user = cookie('admin_info');
        // 得到用户的角色权限
        $this->_user['role_info'] = Db::name('role')->find($this->_user['role_id']);

        if ($this->_user['id'] == 1){
            // (1) 如果是超级管理员，则不检查访问权限
            $this->is_check_rule = false;
            // (2) 获得所有权限
            $rules = Db::name('rule')->select();
        }else{// 如果不是超级管理员
            // (1) 获得普通用户的权限
            $rules = Db::name('rule')->where('id',$this->_user['role_info']['rule_ids'])->select();
        }
        // 从已有的权限信息中获取内容
        $this->_user['rules'] = [];
        foreach ($rules as $key => $value){
            // 只拿 允许显示的导航栏菜单
            if ($value['is_show'] == 1){
                $this->_user['menus'][] = $value;
            }
            // 得到 控制器，方法 的权限组合
            $key = strtolower($value['controller_name'].'/'.$value['action_name']);
            // 该组合是否重复
            if (!in_array($key, $this->_user['rules'])){
                // 不重复，将组合放入 权限集合
                $this->_user['rules'][] = $key;
            }
        }
    }

    // 检测角色权限，于访问的方法是否匹配
    private function checkRoleRule(){
        // 是否检测权限
        if ($this->is_check_rule){
            // 添加公开的方法
            $this->_user['rules'][] = 'index/index';
            $this->_user['rules'][] = 'index/top';
            $this->_user['rules'][] = 'index/menu';
            $this->_user['rules'][] = 'index/main';
            // 得到用户访问的 控制器、方法
            $url = strtolower(request()->controller().'/'.request()->action());
            // 检查该用户，是否具有权限
            if (!in_array($url,$this->_user['rules'])){
                if (request()->isAjax()){// 是否是ajax方式访问
                    return json(['status'=>0,'message'=>'没有权限访问']);
                }else{
                    return $this->error('没有权限访问');
                    exit;
                }
            }
        }
        return true;
    }
}