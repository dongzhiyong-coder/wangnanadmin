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
        //按层级关系组织菜单数组
        $page_menu = [];
        foreach ($menus as $menu){
            if($menu['parent_menu_id']==0){
                $page_menu[] = $menu;
            }
        }
        foreach ($page_menu as $key=>$page_one){
            foreach ($menus as $menu){
                if($menu['parent_menu_id']==$page_one['menu_id']){
                    $page_menu[$key]['child'][] = $menu;
                }
            }
        }
        Session::set('menu_list',$page_menu);
        return $next($request);
    }
}
