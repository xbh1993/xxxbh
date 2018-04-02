<?php
namespace app\admin\validate;

use think\Validate;
class Article extends Validate{
    protected $rule=[
        'title'          =>'require|min:4|max:60',
        'img_url'        =>'require',
        'is_top'         =>'number|max:2',
        'is_recommend'   =>'number|max:2',
        'status'         =>'number|max:2',
        'sort'           =>'number|max:999999',
    ];
    protected $message=[
        'title.require'       =>'文章标题不能为空',
        'title.min'           =>'文章标题不能少于4个字符',
        'title.max'           =>'文章标题不能多余60个字符',
        'img_url.require'     =>'文章封面图不能为空',
        'is_top.number'       =>'置顶参数必须为数字',
        'is_top.max'          =>'置顶参数错误',
        'is_recommend.number' =>'推荐参数必须为数字',
        'is_recommend.max'    =>'推荐参数错误',
        'status.number'       =>'审核状态必须为数字',
        'status.max'          =>'审核状态参数错误',
        'sort.number'         =>'排序必须为数字',
        'sort.max'            =>'排序最大为999999',
    ];
}