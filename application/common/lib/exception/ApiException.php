<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/4/6
 * Time: 0:43
 */
namespace app\common\lib\exception;
use think\Exception;
class ApiException extends  Exception{
    public $message='';
    public $httpCode=500;
    public $code='';
    public function __construct($msg='',$httpCode=0,$code=0)
    {
      $this->message=$msg;
      $this->httpCode=$httpCode;
      $this->code=$code;
    }
}