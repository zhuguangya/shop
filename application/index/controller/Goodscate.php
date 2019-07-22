<?php
namespace app\index\controller;
use Request;
use Db;
class Goodscate extends Common
{    


	public function list()
    {  
      
        return $this->fetch();
  
    }
    
    private function getTree($array, $pid =0, $level = 0){
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    //static $list = [];
    echo "<ul>";
    foreach ($array as $key => $value){
    	
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            //$flg = str_repeat('|--',$level);
            // 更新 名称值
            $value['name'] = $value['name'];
            $m_id=$value['id'];
            // 输出 名称
            echo "<li value='$m_id' id='id'".$m_id.">".$value['name']."<a onclick=modaldemo1(\"".$m_id."\",\"".$value['name']."\") class='Hui-iconfont'>&#xe6df;</a><a style='text-decoration:none'  onclick='modaldemo(".$m_id.")' title='删除'><i class='Hui-iconfont'>&#xe6e2;</i></a></li>";
            //把数组放到list中
            //$list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $this->getTree($array, $value['id'], $level+1);
        }
        
    }
    //return $list;
    echo "</ul>";
}
     public function addaction()
    {   
        $name=Request::post('name');
        $pid=Request::post('pid');
        $arr=Db::query("select id from goods_category where name='$name'");
        if (!empty($arr)) {
          $json=['code'=>0,'suatus'=>'error','data'=>'名称不能重复'];
           return json($json);
        }
          else{
          	  $data=['name'=>$name,'pid'=>$pid];
          	  $id = Db::name('goods_category')->insertGetId($data);
                // Db::query("insert into goods_category (`name`,`pid`) value('$name','$pid')");
                $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                return json($json);
    
        }   
     }


    public function show()
    {   
    	$arr=Db::query("select * from goods_category");
    	$this->getTree($arr);
    	
    	// $json=['code'=>'0','status'=>'ok','data'=>$arr];
    	// return json($json);
    }
     public function show1()
        {
         $arr=Db::query("select id,name from goods_category");
          echo  json_encode($arr);
        }
    
    public function delete(){
     
       $id=Request::post('id');
        $arr=Db::table('goods_category')->where('id',$id)->delete();
        $arr4=Db::query("select * from goods_category where id='$id'");
       $arr1=Db::query("select * from goods_category where pid='$id'");
        foreach ($arr1 as $key => $value) {
        	$pid=$value['pid'];
        	$arr2=Db::query("delete from goods_category where pid='$pid'");
        }
        // $arr3=Db::query("delete from goods_category where id='$id'");
       $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
       echo json_encode($arr);
       die;
    }
     public function updateAction(){
        $data=Request::post();
        $id=$data['id'];
        $name=$data['name'];
        $arr=Db::query("select * from goods_category where name='$name'");
         if (empty($arr)) {
            $arr=Db::table('goods_category')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }else{
            foreach ($arr as $key => $value) {
                if ($value['id']!=$data['id']) {
                    $arr=['code'=>'2','status'=>'error','data'=>'分类名称已经存在'];
                    return json($arr);
                }
            }
            $arr=Db::table('goods_category')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }

    }
}