<?php
namespace app\index\controller;
use Request;
use Db;
class Goodsattr extends Common
{    
	public function list()
    {   
    	$goods_id=Request::get('id');
        $this->assign('goods_id',$goods_id); 
        return $this->fetch();	
    }
    public function attributeShow()
    { 
         $attrs_id=Request::post('attrs_id');
         $arr=Db::query("select attribute.id,attribute.name as a_name,attr_details.id,attr_details.name as a_d_name from attribute join attr_details on attribute.id=attr_details.attr_id where attrcate_id='$attrs_id'");
         // var_dump($arr);
         // die;
         $newarr=[];
         foreach ($arr as $key => $value) {
           $newarr[$value['a_name']][]=$value;
         }
         $json=['code'=>'0','status'=>'ok','data'=>$newarr];
         $goods_id=Request::post('goods_id');
         $arr1=Db::query("select attr_details_id from goods_attr where goods_id='$goods_id'");
         $json['default']=$arr1;
         return json($json);
    }
    public function addAction()
    {   
    	$goods_id=Request::post('goods_id');
    	$attribute_id=Request::post('attribute_id');
        $attr_id=Request::post('attr_id');
        $arr1=Db::query("update goods set attr_cate=$attr_id where id='$goods_id'");
    	$attribute_id=explode(',', $attribute_id);
        array_shift($attribute_id);
        $arr2=Db::query("select * from goods where attr_cate='$attr_id'");
        if (!empty($arr2)) {
            $arr3=Db::table('goods_attr')->where('goods_id',$goods_id)->delete();
            foreach ($attribute_id as $key => $value) {
            $arr=Db::table('attr_details')->where('id',$value)->find();
            $attribute_id=$value;
            $attr_id=$arr['attr_id'];
            $arr4=Db::query("insert into goods_attr(`goods_id`,`attr_details_id`,`attr_id`)values('$goods_id','$attribute_id','$attr_id')");
        }
      }else{
            $arr5=Db::query("insert into goods_attr(`goods_id`,`attr_details_id`,`attr_id`)values('$goods_id','$attribute_id','$attr_id')");
      } 
    }


}