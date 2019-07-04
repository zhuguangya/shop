<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class User extends Common
{
    public function list()
    {
      return $this->fetch();
    }
    public function show()
    {
    	return $this->fetch();
    }
    public function addAction()
    {   

       
    }
}