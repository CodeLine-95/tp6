<?php

namespace app\controller;

use app\controller\base\CommonController;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Filesystem;

class WarehousingController extends CommonController{
    public function index(){
        $cate_bool = Db::name('goods')->count();
        return view('index',[
            'cate_bool'=>$cate_bool
        ]);
    }

    public function outputIndex(){
        $cate_bool = Db::name('goods')->count();
        return view('outputindex',[
            'cate_bool'=>$cate_bool
        ]);
    }

    /*
     * 列表数据
     */
    public function warehousingList(){
        try {
            $params = request()->get();
            if (!isset($params['state']) || $params['state'] <= 0){
                return json(['msg'=>'state 不能为空','code'=>-1]);
            }
            if (!isset($params['page']) || $params['page'] <= 0){
                $params['page'] = 1;
            }
            if (!isset($params['pageSize']) || $params['pageSize'] <= 0){
                $params['page'] = 10;
            }
            $totalCount = Db::name('warehousing')->count();
            $lastPage = ceil($totalCount / $params['pageSize']);
            if ($params['page'] > $lastPage && $lastPage > 0){
                $params['page'] = $lastPage;
            }
            $nextLimit = ($params['page']-1) * $params['pageSize'];
            $goodsList = Db::name('warehousing')->where(['state'=>$params['state']])->order('create_time','desc')->limit($nextLimit,$params['pageSize'])->select();
            return json(['code'=>0,'msg'=>'','count'=>$totalCount,'data'=>$goodsList]);
        }catch (\Exception $e){
            return json(['msg'=>$e->getMessage(),'code'=>-1]);
        }
    }

    /**
     * 添加
     */
    public function add(){
        if (request()->isPost()){
            $params = array_filter(request()->post());
            $goods_one = Db::name('goods')->where(['id'=>$params['goods_id']])->find();
            $params['goods_name'] = $goods_one['goods_name'];
            $user_one = Db::name('admin')->where(['id'=>$params['uid']])->find();
            $params['user_name'] = $user_one['user_name'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $params['update_time'] = date('Y-m-d H:i:s');
            $goods_stock = (isset($params['state']) && $params['state'] == 1)?$goods_one['goods_stock']+$params['num'] : $goods_one['goods_stock']-$params['num'];
            $params['orderid'] = (isset($params['state']) && $params['state'] == 1)? CreateOrderId('RK') : CreateOrderId('CK');
            if ($params['state'] == 2 && $goods_stock < 0){
                return json(['msg'=>'出库数量不能大于存储量','code'=>-1]);
            }
            if(Db::name('warehousing')->save($params)){
                Db::name('goods')->where(['id'=>$params['goods_id']])->update(['goods_stock'=>$goods_stock]);
                return json(['msg'=>'保存成功','code'=>0]);
            }else{
                return json(['msg'=>'保存失败','code'=>-1]);
            }
        }else {
            $params = request()->get();
            $params['state'] = (!isset($params['state'])) ? 1 : $params['state'];
            $goods = Db::name('goods')->select();
            return view('add',[
                'goods'=>$goods,
                'uid' => $this->user['uid'],
                'params'=>$params
            ]);
        }
    }

    public function output(){
        try{
            $params = request()->get();
            $goods_one = Db::name('goods')->where(['id'=>$params['goods_id']])->find();
            $goods_stock = $goods_one['goods_stock']-$params['num'];
            if ($goods_stock < 0){
                return json(['msg'=>'error','code'=>-1,'message'=>'库存量']);
            }
            if (Db::name('warehousing')->where(['id'=>$params['id']])->delete()){
                $goods = Db::name('goods')->where(['id'=>$ware['goods_id']])->find();
                $goods_stock = $ware['num']+$goods['goods_stock'];
                Db::name('goods')->where(['id'=>$ware['goods_id']])->update(['goods_stock'=>$goods_stock]);
                return json(['msg'=>'ok','code'=>0]);
            }else{
                return json(['mssg'=>'error','code'=>-1,'message'=>'删除失败']);
            }
        }catch (\Exception $e){
            return json(['mssg'=>'error','code'=>-1,'message'=>$e->getMessage()]);
        }
    }

    public function show(){
        $params = request()->get();
        $field = Db::name('warehousing')->where(['id'=>$params['id']])->find();
        return view('show',[
            'field'=>$field,
        ]);
    }

    //删除
    public function del(){
        try{
            $params = request()->get();
            $ware = Db::name('warehousing')->where(['id'=>$params['id']])->find();
            if (Db::name('warehousing')->where(['id'=>$params['id']])->delete()){
                $goods = Db::name('goods')->where(['id'=>$ware['goods_id']])->find();
                $goods_stock = $ware['num']+$goods['goods_stock'];
                Db::name('goods')->where(['id'=>$ware['goods_id']])->update(['goods_stock'=>$goods_stock]);
                return json(['msg'=>'ok','code'=>0]);
            }else{
                return json(['mssg'=>'error','code'=>-1,'message'=>'删除失败']);
            }
        }catch (\Exception $e){
            return json(['mssg'=>'error','code'=>-1,'message'=>$e->getMessage()]);
        }
    }

    /**
     * 图片上传
     * @return \think\response\Json
     */
    public function uploadImg(){
        try{
            if (request()->isPost()) {
                // 获取表单上传文件 例如上传了001.jpg
                $file = request()->file('file');
                try {
                    validate([
                        'file'=>[
                            'fileSize' => 52428800,
                            'fileExt' => 'jpg,jpeg,png,gif',
                            'fileMime' => 'image/jpeg,image/png,image/gif', //这个一定要加上，很重要我认为！
                        ]
                    ])->check(['file' => $file]);
                    //获取磁盘保存目录
                    $disk = Filesystem::getDiskConfig('public');
                    //保存图片到本地服务器
                    $savename = Filesystem::disk('public')->putFile( 'admin', $file);
                    return json(['code'=>0,'msg'=>'上传成功','src'=>$disk['url'].'/'.$savename]);
                } catch (ValidateException $v) {
                    return json(['code'=>-1,'msg'=>$v->getMessage()]);
                }
            }else{
                return json(['code'=>-1,'msg'=>'请求错误']);
            }
        }catch (\Exception $e){
            return json(['code'=>-1,'msg'=>$e->getMessage()]);
        }
    }
}