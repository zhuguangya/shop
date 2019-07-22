<?php
namespace app\index\controller;
use Request;
use Db;
class Attrdetails extends Common
{
    public function list()
    {  
       $attr_id=Request::get('id');
       $this->assign('attr_id',$attr_id);
       return $this->fetch();
    }
    
    public function addAction()
    {
    	$data=Request::post();
    	$name=Request::post('name');
    	$attr_id=Request::post('attr_id');
    	$arr=Db::query("select * from attr_details where name='$name' and attr_id='$attr_id'");
    	if (empty($arr)) {
    		$arr=Db::query("insert into attr_details(`name`,`attr_id`)values('$name','$attr_id')");
    	    $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    	    echo json_encode($json);
    	}else{
    		$json=['code'=>'1','status'=>'error','data'=>'属性细分重复'];
    		echo json_encode($json);
    	}
    }
    
    public function show()
    {
    	$attr_id=Request::post('attr_id');
    	$arr=Db::query("select attr_details.id,attr_details.name,attribute.name as uname from attr_details join attribute on attr_details.attr_id=attribute.id where attr_details.attr_id='$attr_id'");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	return json($json);
    }
    public function mydelete()
    {
    	$data=Request::post();
    	$id=Request::post('id');
    	$arr=Db::table('attr_details')->where('id',$id)->delete();
    	$json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
    	echo json_encode($json);
    }
    public function updateAction()
    {
    	$data=Request::post();
        $attr_id=Request::post('attr_id');
    	$id=$data['up_id'];
    	$name=$data['up_name'];
    	$arr=Db::query("select * from attr_details where name='$name' and attr_id='$attr_id'");
    	if (empty($arr)) {
    		 $arr=Db::query("update attr_details set name='$name' where id='$id'");
    		 $json=['code'=>'1','status'=>'ok','data'=>'修改成功'];
    		 echo json_encode($json);
    	}else{
    		 $json=['code'=>'3','statua'=>'error','data'=>'属性细分重复'];
    		 return json($json);
    	}
    }

}