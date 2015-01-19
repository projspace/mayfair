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
	$objPHPExcel->getProperties()->setTitle($config['company']." Reports");
	$objPHPExcel->getProperties()->setSubject($config['company']." Reports");

	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle($title); // max 31 characters

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Code');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Average Price($)');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Sold');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Total($)');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Average Discount($)');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Discount($)');
	$row_index = 2;
	while($row = $products->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['code']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, number_format($row['price'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, $row['quantity']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_index, number_format($row['total'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_index, number_format($row['avg_discount'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_index, number_format($row['total_discount'], 2, '.', ','));
		$row_index++;
	}


	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($config['path'].'downloads/reports/sales-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Location: '.$config['dir'].'downloads/reports/sales-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
	exit;
}
catch (Exception $e)
{
    /*$msg = 'Caught exception: '."\n";
	$msg .= 'message: '.$e->getMessage()."\n";
	$msg .= 'code: '.$e->getCode()."\n";
	$msg .= 'file: '.$e->getFile()."\n";
	$msg .= 'line: '.$e->getLine()."\n";
	$msg .= 'trace string: '.$e->getTraceAsString()."\n";
	$msg .= 'trace array: '.var_export($e->getTrace(), true);
	echo $msg;*/
	error("There was a problem whilst creating the report, please try again. If this persists please notify your designated support contact.","Report Error");
}
?>