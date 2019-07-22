<?php

namespace app\index\validate;

use think\Validate;

class Brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule =   [
        'name'  => 'require|max:50|min:1|token',
        'brand_logo' => 'file',
    
    ];
    
  
}