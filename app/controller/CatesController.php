<?php

namespace app\controller;

use app\controller\base\CommonController;
use think\facade\Db;

class CatesController extends CommonController{
    public function index(){
        return view('index',[

        ]);
    }
    /*
     * 列表数据
     */
    public function catesList(){
        try {
            $params = request()->get();
            if (!isset($params['page']) || $params['page'] <= 0){
                $params['page'] = 1;
            }
            if (!isset($params['pageSize']) || $params['pageSize'] <= 0){
                $params['page'] = 10;
            }
            $totalCount = Db::name('goods_cates')->count();
            $lastPage = ceil($totalCount / $params['pageSize']);
            if ($params['page'] > $lastPage && $lastPage > 0){
                $params['page'] = $lastPage;
            }
            $nextLimit = ($params['page']-1) * $params['pageSize'];
            $cates = Db::name('goods_cates')->limit($nextLimit,$params['pageSize'])->select();
            if ($cates){
                return json(['code'=>0,'msg'=>'获取成功','count'=>$totalCount,'data'=>$cates]);
            }else{
                return json(['code'=>-1,'msg'=>'获取失败','count'=>$totalCount,'data'=>$cates]);
            }
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
            $params['create_time'] = date('Y-m-d H:i:s');
            $field = Db::name('goods_cates')->where(['cate_name'=>$params['cate_name']])->find();
            if ($field) return json(['msg'=>'该分类已存在','code'=>-1]);
            return (Db::name('goods_cates')->save($params)) ? json(['msg'=>'保存成功','code'=>0]) : json(['msg'=>'保存失败','code'=>-1]);
        }else {
            return view('add');
        }
    }

    public function edit(){
        if (request()->isPost()){
            $params = array_filter(request()->post());
            $params['update_time'] = date('Y-m-d H:i:s');
            return (Db::name('goods_cates')->update($params)) ? json(['msg'=>'保存成功','code'=>0]) : json(['msg'=>'保存失败','code'=>-1]);
        }else {
            $params = request()->get();
            $field = Db::name('goods_cates')->where(['id'=>$params['id']])->find();
            return view('edit',[
                'field'=>$field
            ]);
        }
    }

    //删除
    public function del(){
        try{
            $params = request()->get();
            if (Db::name('goods_cates')->where(['id'=>$params['id']])->delete()){
                return json(['msg'=>'ok','code'=>0]);
            }else{
                return json(['mssg'=>'error','code'=>-1,'message'=>'删除失败']);
            }
        }catch (\Exception $e){
            return json(['mssg'=>'error','code'=>-1,'message'=>$e->getMessage()]);
        }
    }

}