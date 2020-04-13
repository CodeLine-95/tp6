<?php
declare (strict_types = 1);

namespace app\middleware;

use app\BaseController;

class LoginCheck extends BaseController
{
    /**
     * 验证登录session
     * @var \think\Request $request
     * @var mixed $next
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, \Closure $next){
        $auth_action = [
            'captcha','login'
        ];
        $utype = session("user");
        $server = $request->server();
        //如果session值为空
        if (empty($utype)) {
            $url_arr = array_filter(explode('/',$server['REQUEST_URI']));
            // 如果 $url_arr  为空时，访问的是域名，赋予默认值
            if (empty($url_arr)) {
                $url_arr[1] = 'index';
            }
            $url_params = explode('?',$url_arr[1]);
            $url_acition = ($url_params) ? $url_params[0] : $url_arr[1];
            //过滤 验证码和登录本身链接
            if (!in_array(str_replace('.html', '', $url_acition), $auth_action)) {
                return redirect((string)url('login/index'));
            } else {
                return $next($request);
            }
        }else{
            return $next($request);
        }
    }
}
