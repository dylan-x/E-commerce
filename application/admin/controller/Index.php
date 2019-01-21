<?php
/**
 * Created by PhpStorm.
 * User: 24063
 * Date: 2019/1/8
 * Time: 13:38
 */
namespace app\admin\controller;


class Index extends Common{
    public function index(){
        return $this->fetch();
    }
    public function main(){
        return $this->fetch();
    }
    public function menu(){
        $this->assign('menus', $this->_user['menus']);
        return $this->fetch();
    }
    public function top(){
        return $this->fetch();
    }

}