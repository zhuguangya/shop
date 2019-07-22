<?php
namespace app\index\controller;
use Request;
use Db;
class Goods extends Common
{    
	public function list()
    {     
        return $this->fetch();	
    }
    public function add()
    {     
        return $this->fetch();	
    }
    public function show()
    {
        $arr=Db::query("select goods.id,goods.name,brand.brand_name as brand_name,goods_category.name as cate_name,online_status,price from goods join brand on goods.brand_id=brand.brand_id join goods_category on goods_category.id=goods.cate_id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        return json($json);
    }

    public function showCate()
    {
    	$arr=Db::query("select * from goods_category");
    	$this->getTree($arr);
    }

    private function getTree($array, $pid =0, $level = 0){
      foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $flg = str_repeat('|--',$level);
            $value['name'] = $flg.$value['name'];
            $name=$value['name'];
            $id=$value['id'];
            // 输出 名称
            echo "<option value='$id'>$name</option>";
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
           $this->getTree($array,$value['id'],$level+1);
        }  
      }
   }

    public function addaction()
    {   
        $data=Request::post();
        unset($data['__token__']);
        $userId = Db::name('goods')->insertGetId($data);
        $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        return json($json);
     }
  
}