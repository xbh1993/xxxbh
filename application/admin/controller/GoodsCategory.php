<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/3/29
 * Time: 21:03
 */

namespace app\admin\controller;

use think\Db;
use think\Validate;

class GoodsCategory extends Main
{
    public function lists()
    {
        $where = [];
        $pid = 0;
        $d = request()->get();
        if (isset($d['id']) && !empty($d['id'])) {
            $where['pid'] = $d['id'];
            $pid = $d['id'];
        };
        $where['types']=1;//商品分类
        $lists = Db::name('GoodsCategory')
            ->where($where)
            ->order('sort desc,add_time desc')
            ->field('id,title,sort,update_time,pid,status')
            ->select();

        $lists = arrayCounts($lists, $pid);
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    public function addCategory()
    {
        if (request()->isGet()) {
            $category = Db::name('goodsCategory')->where('types',1)->field('id,title,pid')->order('sort desc')->select();
            $category = array2level($category);
            $this->assign('cates', $category);
            return $this->fetch();
        }
        if (request()->isPost()) {
            $d = request()->post();
            $validate = validate('GoodCategory');
            $validate->scene('add');
            if (!$validate->check($d)) {
                $msg = $validate->getError();
                return json_code(0, $msg);
            }
            try {
                $d['add_time'] = time();
                $d['update_time'] = time();
                $d['types']=1;
                Db::name('GoodsCategory')->insert($d);
                return json_code(200, '新增成功');
            } catch (\Exception $e) {
                return json_code(0, '系统错误');
            }


        }
    }

    public function Categoryinfo(){
        return $this->fetch();
    }


    public  function getJson(){
        $category = Db::name('GoodsCategory')->field('id,title,pid')->order('sort desc')->select();
        $checke_array=[1,5,7,9,12,13,15,18];
        foreach ($category as $k=> $v){
            in_array($v['id'],$checke_array) && $category[$k]['checked']=true;
        }
        return json_code(200,'success',$category);
    }

    public function updateCategory(){
        if(request()->isPost()){
            $d=request()->post();
            $str=implode(',',$d['auth_rule_ids']);
            return json_code(1,$str);
        }
    }

    //文章分类
    public function articleCateLists()
    {
        $where = [];
        $pid = 0;
        $d = request()->get();
        if (isset($d['id']) && !empty($d['id'])) {
            $where['pid'] = $d['id'];
            $pid = $d['id'];
        };
        $where['types']=2;//商品分类
        $lists = Db::name('GoodsCategory')
            ->where($where)
            ->order('sort desc,add_time desc')
            ->field('id,title,sort,update_time,pid,status')
            ->select();

        $lists = arrayCounts($lists, $pid);
        $this->assign('lists', $lists);
        return $this->fetch();
    }
}