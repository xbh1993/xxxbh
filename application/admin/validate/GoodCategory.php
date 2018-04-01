<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/3/29
 * Time: 20:50
 */
namespace app\admin\validate;
use think\Validate;

class GoodCategory extends Validate
{
    protected $rule = [
        'title' => 'require|min:2|max:50|unique:GoodsCategory',
    ];
    protected $message = [
        'title.require' => '分类名称不能为空',
        'title.min' => '分类名称不能少于2个字符',
        'title.max' => '分类名称不能超过50个字符',
        'title.unique' => '分类名称已经存在',
    ];
    protected $scene = [
        'add' => ['title' => 'require|min:2|max:50|unique:GoodsCategory'],
        'edit' => ['title' => 'require|min:2|max:50|unique:GoodsCategory']
    ];
}