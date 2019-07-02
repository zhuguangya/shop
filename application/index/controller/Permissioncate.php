<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Session;
use Request;
use Db;
class Permissioncate extends Common
{
    public function list()
    {   
      $token=uniqid();
          Session::set('token',$token);
          $this->assign('token',$token);
      return $this->fetch();
    }
    public function show()
    {
    	 $rbac= new Rbac();
    	 $arr=$rbac->getPermissionCategory([['status','=',1]]);
    	 echo json_encode($arr);
    }
    public function addAction()
    {    
    	$name=Request::post('name');
    	 $description=Request::post('description');
    	 $data=Request::post();
    	 $validate = new \app\index\validate\Permissioncate;
    	 if(!$validate->check($data)){
    	 	$arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
    	  	    echo json_encode($arr);
    	  	    die;
    	 }
    	 
    	 $rbac= new Rbac();
    	  $getarr=$rbac->getPermissionCategory([['name','=',$data['name']]]);
    	  if (empty($getarr)) {
    	  	 $arr=$rbac->savePermissionCategory([
    	
    	   	 'name' => $data['name'],
             'description' => $data['description'],
             'status' => 1            
    	  ]);
            	$arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    	  	    echo json_encode($arr);
    	  }else{
    	  	  $arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
    	  	  echo json_encode($arr);
    	  	  die;
    	  }	
       }
    public function delete()
    {    
         $data=Request::post();
         $validate= new \app\index\validate1\Permissioncate; 
         if (!$validate->check($data)) {
             $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
             echo $json=json_encode($arr);
             die;
         }
         $id=$data['id'];
         $rbac= new Rbac();
         $rbac->delPermissionCategory([$id]);
         $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
         echo json_encode($arr);
    }
     public function deleteMore()
    {    
         $id=Request::post('id');
         if (empty($id)) {
             $arr=['code'=>'0','status'=>'ok','data'=>'不能为空'];
             echo json_encode($arr);
             die;
         }

         $arr=explode(',', $id);
         array_shift($arr);
         $rbac= new Rbac();
         $rbac->delPermissionCategory($arr);
         $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
         echo json_encode($arr);
    }
    public function updateAction(){
         $data=Request::post();
         $validate = new \app\index\validate\Permissioncate;
         if(!$validate->check($data)){
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
                echo json_encode($arr);
                die;
         }
          $rbac= new Rbac();
          $getarr=$rbac->getPermissionCategory([['name','=',$data['name']]]);
          
              if (empty($getarr)) {
                Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo json_encode($arr);
              }else{
                 if ($getarr[0]['id']!=$data['id']) {
                      $arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
                      echo json_encode($arr);
                      die;
                 }else{
                    Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                    $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                    echo json_encode($arr);
                 }
              
             }  
          
         
    }
}
