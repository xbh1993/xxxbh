<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
use app\api\controller\Base;
class Index extends Base
{


    //获取首页文章推荐列表
  public  function index()
    {
        $article=Db::name('Article')->order('is_top desc,is_recommend desc,sort desc,update_time desc ,create_time desc ')->paginate(6);//获取首页推荐的文章
       return show(0,'success',$article,200);
    }
}
