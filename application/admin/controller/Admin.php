<?php
/**
 * Created by PhpStorm
 * Author Zhiyong Dong <dongzy@xinruiying.com>
 * Date:2020/6/11
 * Time:10:51
 */

namespace app\admin\controller;

use think\Controller;

class Admin extends Controller {

    //打开登录页面
    public function login(){

        return $this->fetch();
    }
}
