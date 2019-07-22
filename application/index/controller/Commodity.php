<?php
namespace app\index\controller;
use Request;
use Db;
class Commodity  extends Common
{    
	public function list()
	{   
		$goods_id=Request::get('id');
		$this->assign('goods_id',$goods_id);
		return $this->fetch();	
	}
	public function show()
	{
		$goods_id=Request::post('goods_id');
    	// var_dump($goods_id);
    	// die;
		$arr=Db::query("select attribute.id as a_id,attribute.name as a_name,goods_attr.attr_id as ga_attr_id,goods_attr.attr_details_id as ga_ad_id,attr_details.name as ad_name,attr_details.id as a_d_id from attribute join goods_attr on attribute.id=goods_attr.attr_id join attr_details on goods_attr.attr_details_id=attr_details.id where goods_attr.goods_id='$goods_id'");
		$newarr=[];
		foreach ($arr as $key => $value) {
			$newarr[$value['a_name']][]=$value;
		}
		$json=['code'=>'0','status'=>'ok','data'=>$newarr];
		return json($json);
	}
	public function addAction()
	{   
		$goods_id=Request::post('goods_id');
		$price=Request::post('price');
		$su_number=Request::post('su_number');
		$goods_attr_id=Request::post('str');
		$goods_attr_id=implode("-",$goods_attr_id);
		$arr=Db::query("select * from commodity where goods_attr_id=$goods_attr_id");
		if (empty($arr)) {
			$arr1=Db::query("insert into commodity (`goods_id`,`goods_attr_id`,`price`,`su_number`) value ('$goods_id','$goods_attr_id','$price','$su_number')");
			$json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
			return json($json);
		}else{
			$json=['code'=>'1','status'=>'error','data'=>'规格不能重复'];
			return json($json);
		}

	}
	public function showcate()
	{
		$goods_id=Request::post('goods_id');
		$comm=Db::query("select * from commodity where goods_id='$goods_id'");
    	// var_dump($comm);
    	// die;
		for ($i=0; $i <count($comm) ; $i++) { 
			$newarr=[];
			$attr_id=$comm[$i]['goods_attr_id'];
    		// var_dump($attr_id);
    		// die;
			$goods_attr_id=explode("-",$attr_id);
    		// var_dump($goods_attr_id);
    		// die;
			for ($j=0; $j <count($goods_attr_id) ; $j++) { 
				$attr_details_id=$goods_attr_id[$j];
				$arr=Db::query("select * from attr_details where id= '$attr_details_id'");
				$newarr[]=$arr[0]['name'];
			}
			$newarr=implode("-",$newarr);
			$comm[$i]['attr_name']=$newarr;
    		//var_dump($comm);
		}
		$json=['code'=>'0','status'=>'ok','data'=>$comm];
		return json($json);
	}
	public function delete()
	{
		$id=Request::post('id');
		$arr=Db::table('commodity')->where('id',$id)->delete();
		$json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		echo json_encode($json);
	}
    

}