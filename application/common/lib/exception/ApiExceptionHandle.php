<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/4/6
 * Time: 0:13
 */
namespace  app\common\lib\exception;
use think\exception\Handle;
class ApiExceptionHandle extends  Handle{
    protected $httpcode=500;
    public  function render(\Exception $e){
        if(config('app_debug')==true){
            return parent::render($e);
        }
        if($e instanceof ApiException){
            $this->httpcode=$e->httpCode;
        }
         return show(0,$e->getMessage(),[],$this->httpcode);
    }
}