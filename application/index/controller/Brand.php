<?php
namespace app\index\controller;
// use gmars\rbac\Rbac;
use Request;
use Db;
class Brand extends Common
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
         // $rbac= new Rbac();
         $arr=Db::query("select * from brand");
         $json=['code'=>'0','status'=>'ok','data'=>$arr];
         return json($json);
    }
     public function show1()
        {
         $arr=Db::query("select brand_id,brand_name from brand");
          echo  json_encode($arr);
        }


  public function addaction()
    {   
        $data=Request::post();
        // $validate = new \app\index\validate\Brand;
        // if (!$validate->check($data)) {
        //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        //     return json($arr);
        // }

        $brand_name=Request::post('brand_name');
        $brand_desc=Request::post('brand_desc');
        $site_url=Request::post('site_url');
        $arr=Db::query("select * from brand where  brand_name='$brand_name' or brand_desc='$brand_desc' or site_url='$site_url'");
        if (!empty($arr)) {
           $json=['code'=>0,'suatus'=>'ok','data'=>'品牌名称或者网址已经存在'];
           return json($json);
        }
        $brand_logo=request()->file('brand_logo');
        // var_dump($brand_logo);
        if ($brand_logo) {
        $info = $brand_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move('./uploads');
        if ($info) {        
                // $brand_logo=$info->getSaveName();
                $brand_logo=str_replace("\\", "/",$info->getSaveName());
                Db::query("insert into brand (`brand_name`,`brand_desc`,`site_url`,`brand_logo`) values('$brand_name','$brand_desc','$site_url','$brand_logo')");
                $json=['code'=>0,'suatus'=>'ok','data'=>'添加成功'];
                 return json($json);
                // var_dump($data);
          }else{
                // 上传失败获取错误信息
                $json=['code'=>'1','status'=>'error','data'=>$brand_logo->getError()];
                echo $file->getError();
          }
        }else{
          $json=['code'=>'1','status'=>'error','data'=>'文件不能为空'];
          return json($json);
        }   
     }

    public function updateAction(){
        $data=Request::post();
        $brand_id=$data['brand_id'];
        $brand_name=$data['brand_name'];
        $brand_desc=$data['brand_desc'];
        $site_url=$data['site_url'];
        $arr=Db::query("select * from brand where brand_name='$brand_name' or brand_desc='$brand_desc' or site_url='$site_url'");
         if (empty($arr)) {
            $arr=Db::table('brand')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }else{
            foreach ($arr as $key => $value) {
                if ($value['brand_id']!=$data['brand_id']) {
                    $arr=['code'=>'2','status'=>'error','data'=>'品牌名称或者网址已经存在'];
                    return json($arr);
                }
            }
            $arr=Db::table('brand')->update($data);
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($arr);
        }

    }
   public function delete(){
      $data=Request::post();
      $brand_id=Request::post('brand_id');
      $brand_logo=Request::post('brand_logo');
      unlink("./uploads/". $brand_logo);
      $arr=Db::table('brand')->where('brand_id',$brand_id)->delete();  
      $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
      echo json_encode($arr);
      die;
    }
   public function delete2(){
         $data=Request::post();
         $id=$data['id'];
         
         if (empty($id)){
            $arr=['code'=>1,'status'=>'error','data'=>'未选择删除对象'];
            echo json_encode($arr);
            die;
          }
          $id=explode(",", $id);
          array_shift($id);
          $arr=Db::table('brand')->where('brand_id','in',$id)->delete();
          $arr=['code'=>'0','status'=>'ok', 'data'=>'删除成功'];
          echo json_encode($arr);
          die;
   }
    public function imgupdate()
    {
      $brand_logo = request()->file('brand_logo');
      $data=Request::post();
      $brand_id=$data['brand_id'];
      // var_dump($brand_id);
      // die;
      $old_logo=$data['old_logo'];
      $info = $brand_logo->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move('./uploads');
      if($info){
        $logo_path=str_replace("\\","/", $info->getSaveName());
        $arr = ['brand_logo'=>$logo_path];
        Db::name('brand')->where('brand_id',$brand_id)->update($arr);
        unlink(".".$old_logo);
        $arr1=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        echo json_encode($arr1);die;
      }else{
        $arr1=['code'=>'1','status'=>'error','data'=>'文件格式错误'];
        echo json_encode($arr1);die;
      }
    }

}