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
         $rbac= new Rbac();
         $arr=Db::query("select user.id,user.user_name,user.password,user.mobile,role.name,create_time from user join user_role on user.id=user_role.user_id join role on role.id=user_role.role_id");
         $json=['code'=>'0','status'=>'ok','data'=>$arr];
         return json($json);
    }
    public function addAction()
    {   
        $data=Request::post();
        $validate = new \app\index\validate\User;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $data=Request::post();
        $rbac=new Rbac();
        $user_name=$data['user_name'];
        $password=md5($data['password']);
        $mobile=$data['mobile'];
        $role_id=$data['role_id'];
        $arr=Db::query("select * from user where  user_name='$user_name' or mobile='$mobile'");
      
        if(empty($arr)){
        	$datatime=date("Y-m-d H:i:s" ,time());
        	$arr=Db::query("insert into user (`user_name`,`password`,`mobile`,`create_time`) values('$user_name','$password','$mobile','$datatime')");
        
        	$user_id=Db::query("select id from user where user_name='$user_name'");
        	$user_id=$user_id[0]['id'];
        	$arr=$rbac->assignUserRole($user_id,[$role_id]);
        	$arr=['code'=>0,'suatus'=>'ok','data'=>'添加成功'];
        	 echo json_encode($arr);
        }else{
        	$arr=['code'=>1,'status'=>'error','data'=>'用户名重复'];
        	echo json_encode($arr);
        }

    }   

    public function delete(){
    	$data=Request::post();
    	$validate = new \app\index\validate\Delete;
        if (!$validate->check($data)) {
            $data=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($data);
            die;
        }
    	$id=Request::post('id');
    	$arr=Db::table('user')->where('id',$id)->delete();
    	
    	$arr=Db::table('user_role')->where('user_id',$id)->delete();
    	$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
    	echo json_encode($arr);
    	die;
    }
    public function datade2()
        {
          $data=Request::post();
          $id=Request::post('id');
 
         $validate = new \app\index\validate\Delete;
            if (!$validate->check($data)) {
          $data=['code'=>'1','status'=>'error', 'data'=>$validate->getError()];
            echo $json=json_encode($data);
            die;
        }
         if (empty($id)){
            $arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
            echo json_encode($arr);
          }
          $id=explode(",", $id);
          array_shift($id);
          $id=implode(',', $id);
          $arr=Db::table('user')->where('id','in',$id)->delete();
          $arr=Db::table('user_role')->where('id','in',$id)->delete();
          $arr=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
          echo json_encode($arr);
          die;
        }  
     

     public function updateAction(){
     
        $data=Request::post();
        $validate = new \app\index\validate\Delete;
        if (!$validate->check($data)) {
            $data=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($data);
            die;
        }
        $rbac= new Rbac;
        $name=$data['up_user_name'];
        $password=$data['up_password'];
        $mobile=$data['up_mobile'];
        $id=$data['up_id'];
        $role_id=$data['up_role_id'];
        $arr=Db::query("select * from user where user_name='$name' or mobile='$mobile'");
         if (empty($arr)||!empty($arr)&&$arr[0]['id']==$id) {
            $where=['user_name'=>$name,'password'=>$password,'mobile'=>$mobile];
            $arr=Db::name('user')->where('id',$id)->update($where);
            $arr1=['role_id'=>$role_id];
            $arr=Db::table('user_role')->where('user_id',$id)->update($arr1);
            $arr=['code'=>1,'status'=>'ok','data'=>'修改成功'];
            echo json_encode($arr);die;
        }else{
          $arr=['code'=>3,'status'=>'error','data'=>'名称重复'];
          return json($arr);
        }

    
     }
 }  