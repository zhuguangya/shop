<?php
namespace app\index\controller;
use Request;
use Db;
class Goodsimg extends Common
{    
	
    public function goods_img()
    {
    	$goods_id=Request::get('id');
 
    	$this->assign('goods_id',$goods_id);
    	return $this->fetch();
    }
    public function goodsimgshow()
    {    
        $goods_id=Request::post('goods_id');
        // var_dump($goods_id);
        // die;
    	$arr=Db::query("select goods_img.id, goods_img.big_img,goods_img.middle_img,goods_img.samll_img,goods_img.goods_id from goods_img join goods on goods_img.goods_id=goods.id where goods_img.goods_id='$goods_id'");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	return json($json);
    }
    public function goodsimgadd()
    {
    	// var_dump($_FILES);
        $goods_id=Request::post('goods_id');

        $files = request()->file('file');

        foreach ($files as $file) {
        	$info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move('./uploads/goodsimg');
        	if($info){
        		$name=$info->getFileName();
        		$date=date("Ymd");
        		$path="$date/$name";
        		$image = \think\Image::open("./uploads/goodsimg/$path");
        		$big_img="$date/big_$name";
        		$image->thumb(150,150)->save('./uploads/goodsimg/'.$big_img);
        		$middle_img="$date/middle_$name";
        		$image->thumb(100,100)->save('./uploads/goodsimg/'.$middle_img);
        		$samll_img="$date/samll_$name";
        		$image->thumb(50,50)->save('./uploads/goodsimg/'.$samll_img);
        		$arr=Db::query("insert into goods_img (`big_img`,`middle_img`,`samll_img`,`goods_id`,`img_origin`) values ('$big_img','$middle_img','$samll_img','$goods_id','$path')");
        		$arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        	}else{
        		$json=['code'=>1,'sttatus'=>'error','data'=> $file->getError()];
                return json($json);
        	}
        }
     }
    
}