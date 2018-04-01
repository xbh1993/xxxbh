<?php
/**
 * Created by PhpStorm.
 * User: xiebh
 * Date: 2018/3/31
 * Time: 22:59
 */
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
    protected $rule=[
        'title'=>'require|min:2|max:120',
        'cid'=>'require|number',
        'img_url'=>'require',
        'img_urls'=>'require',
        'num'=>'require|number',
    ];
    protected $message=[
        'title.require'=>'商品名称不能为空',
        'title.min'=>'商品名称不能少于两个字符',
        'title.max'=>'商品名称不能超过120个字符',
        'cid.require'=>'商品分类不能为空',
        'cid.number'=>'商品分类必须为数字',
        'num.require'=>'商品库存不能为空',
        'num.number'=>'商品库存必须数字',
        'img_url.require'=>'请上传商品主图',
        'img_urls.require'=>'请上传商品图集'
    ];
}