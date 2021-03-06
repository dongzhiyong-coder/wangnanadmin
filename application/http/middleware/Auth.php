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
        //找到登录用户所有的角色
        if(Session::get('username')!='admin') {
            //超级管理员 拥有最大的权限
            $roles = $mysql->table('pc_user_role')->where("user_id=" . Session::get('uid'))->select();
            if (empty($roles)) {
                die('用户权限不足');
            }
            $role_list = [];
            foreach ($roles as $role) {
                $role_list[] = $role['role_id'];
            }
            //通过角色找到对应的菜单集合 用In
            $sql = "SELECT menu_id from pc_role_menu where role_id in(" . implode(',', $role_list) . ")";
            $role_menus = $mysql->query($sql);
            $page_menu = [];
            if (!empty($role_menus)) {
                //获取页面菜单
                $page_menu = $this->getPageMenu($role_menus);
            }
            Session::set('menu_list', $page_menu);
        }
        else{
            $db_config = require  "../config/db_config.php";
            $mysql = new \mysql($db_config);
            $menus_list = $mysql->table('pc_menu')->select();
            $page_menu = $this->getlistMenu($menus_list);
            Session::set('menu_list', $page_menu);
        }
        return $next($request);
    }



    /**
     * 返回页面菜单 按层级关系显示的
     * 角色的菜单id集合
     * @param $role_menus
     */
    private function getPageMenu($role_menus){
        $role_menu_list = $page_menu = [];
        foreach ($role_menus as $role_menu){
            $menu = $this->getMenu($role_menu['menu_id']);
            $role_menu_list[] = $menu;
        }
        $page_menu = $this->getlistMenu($role_menu_list);
        return $page_menu;
    }

    /**
     * 返回页面菜单
     * @param $menus
     * @param $pid
     * @return array
     */
    public function getlistMenu($menus,$pid=0)
    {
        $array = [];
        //重置key 这样前端好遍历
        $mk = 0;
        foreach ($menus as $key => $item) {
            if( $item['parent_menu_id'] == $pid )
            {
                $array[$mk] = $item;
                //提升性能 处理过的就剔除 不用让他下次去循环
                unset($menus[$key]);
                $array[$mk]['son'] = $this->getlistMenu( $menus, $item['menu_id']);
                $mk++;
            }
        }
        return $array;
    }



    //根据id获取到菜单实体数据
    private function getMenu($menu_id){
        $db_config = require  "../config/db_config.php";
        $mysql = new \mysql($db_config);
        $menu = $mysql->table('pc_menu')->where("menu_id=".$menu_id)->find();
        if(!empty($menu)){
            return $menu;
        }
        return null;
    }
}
