<?php

namespace app\index\validate;

use think\Validate;

class Goodscate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule =   [
        'name'  => 'require|max:50',
        'pid'   => 'require',
    
    ];
    
 
     
} 