<?php

namespace app\controller;

use app\controller\base\CommonController;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Filesystem;

class GoodsController extends CommonController {
    public function index(){
        $cate_bool = Db::name('goods_cates')->count();
        return view('index',[
            'cate_bool'=>$cate_bool
        ]);
    }

    /*
     * 列表数据
     */
    public function goodsList(){
        try {
            $params = request()->get();
            if (!isset($params['page']) || $params['page'] <= 0){
                $params['page'] = 1;
            }
            if (!isset($params['pageSize']) || $params['pageSize'] <= 0){
                $params['page'] = 10;
            }
            $totalCount = Db::name('goods')->count();
            $lastPage = ceil($totalCount / $params['pageSize']);
            if ($params['page'] > $lastPage && $lastPage > 0){
                $params['page'] = $lastPage;
            }
            $nextLimit = ($params['page']-1) * $params['pageSize'];
            $goodsList = Db::name('goods')->order('create_time','desc')->limit($nextLimit,$params['pageSize'])->select()->toArray();
            if ($goodsList){
                foreach ($goodsList as $k=>$a){
                    $cate_one = Db::name('goods_cates')->where(['id'=>$a['goods_type']])->find();
                    if ($cate_one){
                        $goodsList[$k]['goods_type'] = $cate_one['cate_name'];
                    }
                }
            }
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
            $params['create_time'] = date('Y-m-d H:i:s');
            $params['update_time'] = date('Y-m-d H:i:s');
            return (Db::name('goods')->save($params)) ? json(['msg'=>'保存成功','code'=>0]) : json(['msg'=>'保存失败','code'=>-1]);
        }else {
            $cates = Db::name('goods_cates')->select();
            return view('add',[
                'cates'=>$cates
            ]);
        }
    }

    public function edit(){
        if (request()->isPost()){
            $params = array_filter(request()->post());
            $params['update_time'] = date('Y-m-d H:i:s');
            return (Db::name('goods')->update($params)) ? json(['msg'=>'保存成功','code'=>0]) : json(['msg'=>'保存失败','code'=>-1]);
        }else {
            $params = request()->get();
            $field = Db::name('goods')->where(['id'=>$params['id']])->find();

            $cates = Db::name('goods_cates')->select();
            return view('edit',[
                'field'=>$field,
                'cates'=>$cates
            ]);
        }
    }

    //删除
    public function del(){
        try{
            $params = request()->get();
            if (Db::name('goods')->where(['id'=>$params['id']])->delete()){
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