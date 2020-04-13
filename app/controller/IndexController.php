<?php
namespace app\controller;

use app\controller\base\CommonController;
use think\App;
use think\facade\Db;

class IndexController extends CommonController {
    public function index(){
        $user = Db::name('admin')->where(['id'=>$this->user['uid']])->find();
        return view('index',[
            'user'=>$user
        ]);
    }
    //欢迎页
    public function console(){
        $logs = Db::name('login_log')->order('create_t','desc')->limit(9)->select();
        return view('console',[
            'logs'=>$logs
        ]);
    }

    public function clear(){
        $logs = Db::name('login_log')->delete(true);
        return ($logs) ? json(['msg'=>'操作日志已清空','code'=>0]) : json(['msg'=>'操作日志清空失败','code'=>-1]);
    }

    //退出
    public function logout(){
        session('user',null);
        // 清除session
        session(null);
        return redirect((string) url('login/index'));
    }
}
