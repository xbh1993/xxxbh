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
        if(empty($header['did'])) throw new ApiException('did不合法',400);
        if(empty($header['types'])) throw new ApiException('types不合法',400);
        $iv='1234567890123456';
        $key='1234567891234567';
        $sign=encrypt('xbh19931129',$iv,$key);
        halt($sign);
    }


}