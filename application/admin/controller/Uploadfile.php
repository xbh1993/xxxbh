<?php
namespace  app\admin\controller;
use think\Controller;
use think\Db;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 下午 9:15
 */
class Uploadfile extends  Controller{
      public  function  uploadfile(){
          // 获取表单上传文件 例如上传了001.jpg
          $file = request()->file('image');
          // 移动到框架应用根目录/public/uploads/ 目录下
          if($file){
              $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
              if($info){
                  // 成功上传后 获取上传信息
                  // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                  $savefile='/public/uploads/'.$info->getSaveName();
                  return json_code(0,'success',['img_url'=>$savefile]);
              }else{
                  // 上传失败获取错误信息
                  echo $file->getError();
              }
          }
      }
}