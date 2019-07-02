<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
class Index extends Common
{
    public function index()
    {
      return $this->fetch();
    }
    public function control()
    {
    	// return $this->fetch();
    	$rbac = new Rbac();
    	$rbac->createTable();
    }

  

}
