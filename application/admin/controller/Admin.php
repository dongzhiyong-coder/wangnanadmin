<?php
/**
 * Created by PhpStorm
 * Author Zhiyong Dong <dongzy@xinruiying.com>
 * Date:2020/6/11
 * Time:10:51
 */

namespace app\admin\controller;
use think\App;
use think\facade\Session;
use think\Controller;
class Admin extends Controller {

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        //把菜单设置到控制器的模板变量中
        $this->assign('menu_list',Session::get('menu_list'));
    }

    protected $middleware = ['auth'];

    //后台首页
    public function index(){
        return $this->fetch();
    }

    //登出功能
    public function logout(){
        Session::set('uid',null);
        Session::set('username',null);
        Session::set('menu_list',null);
        return redirect('/login/login');
    }

}
