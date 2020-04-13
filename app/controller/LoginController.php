<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;

class LoginController extends BaseController {
    public function index(){
        return view('index');
    }

    public function indexLogin(){
        try{
            if (request()->isPost()){
                $params = request()->post();
                $fieldRes = Db::name('admin')->where(['user_name'=>$params['user_name']])->find();
                if (!$fieldRes){
                    return json(['msg'=>'error','code'=>-1,'message'=>'用户名不存在']);
                }
                if (!password_verify($params['user_pass'],$fieldRes['user_pass'])){
                    return json(['msg'=>'error','code'=>-1,'message'=>'密码错误']);
                }
                if ($fieldRes['user_status'] != 0){
                    return json(['msg'=>'error','code'=>-1,'message'=>'用户已冻结，请联系管理员']);
                }
                //保存session
                $session = [
                    'uid'=>$fieldRes['id']
                ];
                session('user',$session);
                //记录登录日志
                $logs = [
                    'name'=>$fieldRes['user_name'],
                    'login_ip'=>request()->ip(),
                    'create_t'=>date('Y-m-d H:i:s'),
                    'content'=>'登录成功,帐号:'.$fieldRes['user_name'].',登录IP:'.request()->ip(),
                    'type'=>'用户登录'
                ];
                Db::name('login_log')->save($logs);
                //更新用户登录信息
                $update = [
                    'id'=>$fieldRes['id'],
                    'user_host'=>request()->ip(),
                    'login_time'=>date('Y-m-d H:i:s'),
                ];
                Db::name('admin')->update($update);
                return json(['msg'=>'ok','code'=>0,'message'=>'']);
            }
        }catch (\Exception $e){
            Log::error($e);
            return json(['msg'=>'error','code'=>-1,'message'=>$e->getMessage()]);
        }
    }
}