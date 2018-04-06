<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/4/5
 * Time: 23:33
 */
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\common\lib\exception\ApiException;
class Base extends Controller{
    public $header='';
    public function _initialize(){
        $this->checkAuthRequest();
    }
    protected function checkAuthRequest(){
        $header=request()->header();
        if(empty($header['sign'])) throw  new ApiException('sign不存在',400);
        if(empty($header['did'])) throw new ApiException('did不存在',400);
        if(empty($header['types'])) throw new ApiException('types不存在',400);
        //加密字段
//        $arr=['appid'=>$header['appid'],'version'=>$header['version'],'types'=>$header['types'],'did'=>$header['did']];
//        $sign=encrypt($arr);
//         halt($sign);
        if(!checksign($header)) throw  new ApiException('sign不合法',400);
         $this->header=$header;
    }


}