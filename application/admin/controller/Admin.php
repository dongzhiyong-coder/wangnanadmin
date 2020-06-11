<?php
/**
 * Created by PhpStorm
 * Author Zhiyong Dong <dongzy@xinruiying.com>
 * Date:2020/6/11
 * Time:10:51
 */

namespace app\admin\controller;

use think\App;
use think\Controller;
class Admin extends Controller {

    public function __construct(App $app = null)
    {
        parent::__construct($app);

    }

    //打开登录页面
    public function login(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $db_config = require  "../config/db_config.php";
        $mysql = new \mysql($db_config);
        $user_name = $_POST['user_name'];
        $user_password = $_POST['user_password'];
        $user = $mysql->table('pc_user')->where("user_name='".$user_name."'")->find();
        if(empty($user)){
            show_json('500','用户不存在');
        }
        if(md5($user_password.'djhh#@22**hh777&')!=$user['user_password']){
            show_json('500','密码错误');
        }

        /**
         *用户登录后 需要把他持有的菜单返回给前端页面做显示
         */
        return redirect('/admin/index');
    }

    //后台首页
    public function index(){
        return $this->fetch();
    }
    
}
