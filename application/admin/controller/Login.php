<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Login extends Controller
{
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
        //把用户信息保存到session
        Session::set('uid',$user['user_id']);
        return redirect('/admin/index');
    }

}
