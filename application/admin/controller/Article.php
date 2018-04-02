<?php
namespace app\admin\controller;
use think\Db;
use think\Validate;
use app\admin\model\Article as ArticleModel;
class Article extends Main{
    public function lists(){
        $lists=Db::name('Article')
            ->field('id,title,cid,is_top,is_recommend,sort,views,status,create_time,update_time')
            ->paginate(8);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    //新增加文章
    public function addArticle(){
        if(request()->isPost()){
            $d=request()->post();
            if(empty($d)) return json_code(0,'添加失败');
            $validate=validate('Article');
            if(!$validate->check($d)){
                $message=$validate->getError();
                return json_code(0,$message);
            }
            try{
                $model=new ArticleModel($d);
                $model->allowField(true)->save();
                return json_code(200,'新增文章成功');
            }catch (\Exception $e){
                return json_code(0,'系统错误');
            }
        }
        $where['types']=2;
        $cate=Db::name('GoodsCategory')->where($where)->field('id,pid,title')->order('sort desc')->select();
        $catelist=array2level($cate);
        $this->assign('catelist',$catelist);
        return $this->fetch();
    }
}