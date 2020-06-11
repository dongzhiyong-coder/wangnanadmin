<?php

namespace app\http\middleware;
use think\facade\Session;
class Auth
{
    public function handle($request, \Closure $next)
    {
        if(empty(Session::get('uid'))){
            return redirect('/login/login');
        }
        //把用户菜单保存在session里面
        $db_config = require  "../config/db_config.php";
        $mysql = new \mysql($db_config);
        $menus = $mysql->table('pc_menu')->select();
        Session::set('menu_list',json_encode($menus));
        return $next($request);
    }
}
