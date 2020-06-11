<?php

namespace app\http\middleware;
use think\facade\Session;
class Auth
{
    public function handle($request, \Closure $next)
    {
        if(empty(Session::get('uid'))){
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
