<?php
namespace app\index\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Role extends Common
{
    public function list()
    {
      return $this->fetch();
    }
    public function show()
    { 
         $rbac= new Rbac();
         $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
         $json=['code'=>'0','status'=>'ok','data'=>$arr];
         return json($json);

    	
    }
     public function permissionShow()
    { 
         $rbac= new Rbac();
         $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
         // var_dump($arr);
         // die;
         $newarr=[];
         foreach ($arr as $key => $value) {
         	 $newarr[$value['p_c_name']][]=$value;
         }
         // var_dump($arr);
         // die;
         $json=['code'=>'0','status'=>'ok','data'=>$newarr];
         return json($json);

    	
    }
    // public function updateAction(){
    //      $data=Request::post();
    //     $validate = new \app\index\validate\Permission;
    //     if (!$validate->check($data)) {
    //         $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
    //         return json($arr);
    //     }
    //     unset($data['__token__']);
    //     $rbac= new Rbac;
    //     //$getname=$rbac->getPermission([['name','=',$data['name']]]);
    //     $name=$data['name'];
    //     $path=$data['path'];
    //     $arr=Db::query("select * from permission where name='$name' or path='$path'");
    //      if (empty($arr)) {
    //         $arr=Db::table('permission')->update($data);
    //         $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
    //         return json($arr);
    //     }else{
    //         foreach ($arr as $key => $value) {
    //             if ($value['id']!=$data['id']) {
    //                 $arr=['code'=>'2','status'=>'error','data'=>'name或者path已经存在'];
    //                 return json($arr);
    //             }
    //         }
    //         $arr=Db::table('permission')->update($data);
    //         $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
    //         return json($arr);
    //     }

    // }
    public function addAction()
    {   
        $data=Request::post();
        $validate = new \app\index\validate\Role;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }

        $rbac= new Rbac();
        $name=$data['name'];
        $description=$data['description'];
        $permission_id=$data['permission_id'];
        $getname=Db::query("select * from role where name='$name'");
        $arr=explode(',', $permission_id);
        array_shift($arr);
        $permission_id=implode(',',$arr);

        if (empty($getname)) {
          $rbac->createRole([
          	'name' => $name,
          	'description' => $description,
          	'status' => 1
          ],$permission_id); 
         $json=['code'=>'0','status'=>'ok','data'=>$arr];
         return json($json);
 
        }else{
         $json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
         return json($json);
        }
       
    }
   // function delete(){
   //      $data=Request::post();
   //      $validate = new \app\index\validate\Delete;
   //      if (!$validate->check($data)) {
   //          $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
   //          return json($arr);
   //      }
   //      $rbac= new Rbac();
   //      $id=$data['id'];
   //      $rbac->delPermission([$id]);
   //      $arr=['code'=>'1','status'=>'ok','data'=>'删除成功'];
   //      return json($arr);
   // }
   //  public function deleteMore()
   //  {    
   //      $data=Request::post();
   //      $validate = new \app\index\validate\Delete;
   //      if (!$validate->check($data)) {
   //          $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
   //          // return json($arr);
   //          echo json_encode($arr);
   //          die;
   //      }
   //       $id=Request::post('id');
   //       if (empty($id)) {
   //           $arr=['code'=>'2','status'=>'error','data'=>'不能为空'];
   //           echo json_encode($arr);
   //           die;
   //       }

   //       $arr=explode(',', $id);
   //       array_shift($arr);
   //       $rbac= new Rbac();
   //       $rbac->delPermission($arr);
   //       $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
   //       echo json_encode($arr);
   //  }
     public function add()
    {
      return $this->fetch();
    }
}