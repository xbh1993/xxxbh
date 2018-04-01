<?php
namespace app\admin\controller;

use think\Db;
class FileSystem extends Main
{
    // 上传页面展示
    function index(){
        $data  = Db::name('file')
            ->order('id desc')
            ->paginate(10);
        $this->assign('data',$data);
        return $this->fetch();
    }
    //上传
    function upload(){
        if($this->request->isPost()){
             $file = $this->request->file('file');//file是传文件的名称，这是webloader插件固定写入的。因为webloader插件会写入一个隐藏input，不信你们可以通过浏览器检查页面
             $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
             $size = $info->getSize();
             $ext  = $info->getExtension();
             if($ext=='png'||$ext=='jpg'||$ext=='jpeg'||$ext=='gif'||$ext=='bmp'){
                $size = number_format($size/1024);
                Db::name('file')
                    ->insert(['size'=>$size.'k','url'=> DS.'public' . DS . 'uploads'.DS.$info->getSaveName()]);
                $this->success('上传成功');
             }else{
                 $this->error('莫非你想上传非法脚本?囧');
             } 
        }else{
            return $this->fetch();
        }
    }
    //文件删除
    function del(){
        $url =  $this->request->post('url');
        Db::name('file')
            ->delete($this->request->post('id'));
        unlink('.'.$url);
        $this->success('delete success');
    }

}