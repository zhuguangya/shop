<?php
namespace app\index\controller;
use Request;
use Db;
class Alignment extends Common
{    
	public function list()
  {   
    $attrcate_id=Request::get('id');
    $this->assign('attrcate_id',$attrcate_id); 
    return $this->fetch();	
  }
  public function addAction()
  {
    $data=Request::post();
    $name=Request::post('name');
    $attrcate_id=Request::post('attrcate_id');
    $arr=Db::query("select * from attribute where name='$name' and attrcate_id='$attrcate_id'");
    if (empty($arr)) {
      $arr=Db::query("insert into attribute(`name`,`attrcate_id`)values('$name','$attrcate_id')");
      $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
    }else{
      $json=['code'=>'1','status'=>'ok','data'=>'属性重复'];
      return json($json);
    }
    
  }
  public function show()
  {    
    $attrcate_id=Request::post('attrcate_id');
    $arr=Db::query("select attribute.id,attribute.name,attr_category.name as uname from attr_category join attribute on attr_category.id=attribute.attrcate_id where attribute.attrcate_id='$attrcate_id'");
    $json=['code'=>'0','status'=>'ok','data'=>$arr];
    return json($json);
  }
  public function updateAction()
  {
    $data=Request::post();
    $attrcate_id=Request::post('attrcate_id');
    $id=$data['up_id'];
    $name=$data['up_name'];
    $arr=Db::query("select * from attribute where name='$name' and attrcate_id='$attrcate_id'");
    if (empty($arr)) {
     $arr=Db::query("update attribute set name='$name' where id='$id'");
     $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
     echo json_encode($json);
   }else{
     $json=['code'=>'1','statua'=>'error','data'=>'属性名称重复'];
     return json($json);
   }
 }
 public function delete()
 {
  $data=Request::post();
  $id=Request::post('id');
  $arr=Db::query("select * from attr_details where attr_id='$id' ");
  if (empty($arr)) {
    $arr=Db::table('attribute')->where('id',$id)->delete();
    $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
    return json($json);
  }else{
    $json=['code'=>'1','status'=>'error','data'=>'下面有属性细分不能删除'];
    return json($json);
  }
  
}

}