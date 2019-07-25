<?php
namespace app\index\controller;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Request;
use Db;
class A  extends Common
{    
	public function list()
	{   
		return $this->fetch();	
	}
	public function test()
	{
	//查询数据库
		$arr=Db::query("select * from excle");
     $helper = new Sample();
     if ($helper->isCli()) {
     $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

     return;
	}
	$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'id')
    ->setCellValue('B1', 'state')
    ->setCellValue('C1', 'name')
    ->setCellValue('D1', 'describe')
    ->setCellValue('E1', 'price');
    foreach ($arr as $key => $value) {
    	$i=$key+2;
    	$spreadsheet->setActiveSheetIndex(0)
    	       ->setCellValue('A'.$i,$value['id'])
    	       ->setCellValue('B'.$i,$value['state'])
    	       ->setCellValue('C'.$i,$value['name'])
    	       ->setCellValue('D'.$i,$value['describe'])
    	       ->setCellValue('E'.$i,$value['price']);

    }
// Miscellaneous glyphs, UTF-8

// $spreadsheet->setActiveSheetIndex(0)
//     ->setCellValue('A4', 'Miscellaneous glyphs')
//     ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
}
   public function addAction()
   {
   	 $helper= new Sample();
   	 $myfile = request()->file('file');
   	 // var_dump($myfile);die;
   	 $info = $myfile->move('./excel/');
   	 // var_dump($info);
   	 if ($info) {
   	 	 $files=$info->getSaveName();
   	 	 $inputFileName="./excel/".$files;
   	 	 $helper->log('Loading file ' . pathinfo($inputFileName,PATHINFO_BASENAME).'using IOFactory to identify the fromat');
   	 	 $spreadsheet = IOFactory::load($inputFileName);
   	 	 $sheetDaTa = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
   	 	 foreach ($sheetDaTa as $key => $value) {
   	 	 	$data = ['state' => $value['B'],'name' => $value['C'],'describe'=>$value['D'],'price'=>$value['E']];
   	 	 	Db::name('excle')->insert($data);
   	 	 }
   	 	 $json=['code'=>'0','status'=>'ok','data'=>'上传成功'];
   	 	 echo json_encode($json);die;
   	 }
   }  

}