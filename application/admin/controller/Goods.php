<?php
namespace  app\admin\controller;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 下午 7:42
 */
use think\Db;
use think\Validate;
use app\admin\model\Goods as GoodsModel;
class Goods extends Main{


    public function lists(){
        $lists=Db::name('goods')->select();//获取所有的商品列表
        $this->assign('lists',$lists);
        return $this->fetch();
    }
    public  function addGoods(){
        if(request()->isPost()){
            $d=request()->post();
            $validate=validate('Goods');//检验Goods表的字段验证规则是否通过
            if(!$validate->check($d)){
                $msg=$validate->getError();
                return json_code(0,$msg);
            }
            if(isset($d['img_urls'])&& !empty($d['img_urls'])){
                $d['img_urls']=serialize($d['img_urls']);
            }
            $model=new GoodsModel($d);
            try{//新增商品
                $res=$model->allowField(true)->save();
                return json_code(200,'新增商品成功');
            }catch (\Exception $e){
                $msg=$e->getMessage();
                return json_code(0,$msg);
            }

        }
        $category = Db::name('goodsCategory')->where('types',1)->field('id,title,pid')->order('sort desc')->select();
        $category = array2level($category);
        $this->assign('cates', $category);
        return $this->fetch();
    }

    public function  editGoods(){
        dump(1231231231231213);exit;
    }

    public  function deleteGoods(){
        dump(121);exit;
    }
}