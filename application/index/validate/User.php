<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule =   [
        'user_name'  => 'require|max:50|min:1|token',
        'password' => 'require|min:1',
        'mobile' => 'require|min:1',
       
    
    ];
    
    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过50个字符',
        'name.min'   => '名称最少不能少于1个字符',
         'mobile' => '电话必须',
     ];
}
