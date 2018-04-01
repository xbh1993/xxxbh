<?php
namespace app\admin\controller;

use think\Db;

class DbManage extends Main
{
  //数据库维护页面展示 
  function index(){
    $db_names =  Db::getTables();
    $count = count($db_names);
    $this->assign('db_names',$db_names);
    $this->assign('count',$count);
    return $this->fetch();
  }
  
}
