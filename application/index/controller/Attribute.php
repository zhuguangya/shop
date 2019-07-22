<?php
namespace app\index\controller;
use Request;
use Db;
class Attribute extends Common
{    
	public function list()
    {     
        return $this->fetch();	
    }
    public function show()
    {
    	$arr=Db::query("select * from attr_category");

    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($json);
    	// return json($json);
    }
    public function show1()
    {
      $arr=Db::query("select id,name from attr_category");
      echo json_encode($arr);
    }
    

    public function addAction()
    {
    	$data=Request::post();
    	$name=Request::post('name');
    	$arr=Db::query("select * from attr_category where name='$name'");
    	if (empty($arr)) {
    		 $arr=Db::query("insert into attr_category(`name`)values('$name')");
    		 $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    		 echo json_encode($arr);
    	}else{
    		 $arr=['code'=>'1','status'=>'error','data'=>'属性名重复'];
    		 echo json_encode($arr);
    	}
    }
    public function updateAction()
    {
      $data=Request::post();
      $id=$data['up_id'];
      $name=$data['up_name'];
      $arr=Db::query("select * from attr_category where name='$name'");
      if (empty($arr)) {
         $arr=Db::query("update attr_category set name='$name' where id='$id'");
         $json=['code'=>'1','status'=>'ok','data'=>'修改成功'];
         echo json_encode($json);
      }else{
         $json=['code'=>'3','statua'=>'error','data'=>'属性名称重复'];
         return json($json);
      }
    }
    public function delete()
    {
        $data=Request::post();
        $id=Request::post('id');
        $arr=Db::query("select * from attribute where attrcate_id='$id' ");
        if (empty($arr)) {
            $arr=Db::table('attr_category')->where('id',$id)->delete();
            $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            return json($json);
        }else{
            $json=['code'=>'1','status'=>'error','data'=>'下面有属性不能删除'];
            return json($json);
        }
        
    }
}