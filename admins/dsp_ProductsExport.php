<?php
ini_set('memory_limit','128M');
set_time_limit(0);
try 
{
	include '../lib/lib_PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getProperties()->setCreator($config['company']);
	$objPHPExcel->getProperties()->setLastModifiedBy($config['company']);
	$objPHPExcel->getProperties()->setTitle($config['company']." Product Reports");
	$objPHPExcel->getProperties()->setSubject($config['company']." Product Reports");

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Products'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'PLU');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Product Name');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Total Value');
	$row_index = 2;
	$sum = 0;
	while($row = $products->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['code']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['quantity']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, price($row['total']));
		$sum += $row['total'];
		$row_index++;
	}
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, 'Postage');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, price($total['shipping']));
	$row_index++;
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, 'TOTAL');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, price($total['paid']+$total['shipping']));
	$row_index++;
			
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter->save($config['path'].'downloads/reports/products-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment;filename=\"products-".date('d.m.Y-H.i.s').".xlsx\"");
	header('Cache-Control: max-age=0');
	$objWriter->save('php://output');
	
	//header('Location: '.$config['dir'].'downloads/reports/products-'.date('d.m.Y-H.i.s').'.xlsx');
	exit;
}
catch (Exception $e)
{
    $msg = 'Caught exception: '."\n";
	$msg .= 'message: '.$e->getMessage()."\n";
	$msg .= 'code: '.$e->getCode()."\n";
	$msg .= 'file: '.$e->getFile()."\n";
	$msg .= 'line: '.$e->getLine()."\n";
	$msg .= 'trace string: '.$e->getTraceAsString()."\n";
	$msg .= 'trace array: '.var_export($e->getTrace(), true);
	echo "<pre>$msg</pre>";
	error("There was a problem whilst creating the report, please try again. If this persists please notify your designated support contact.","Report Error");
}
?>