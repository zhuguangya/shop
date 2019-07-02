<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
class Permission extends Common
{
    public function list()
    {
      return $this->fetch();
    }
    public function show()
    { 
         $rbac= new Rbac();
         $arr=$rbac->getPermissionCategory([['']]);
         echo json_encode($arr);
    	
    }
  

}