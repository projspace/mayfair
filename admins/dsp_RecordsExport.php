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
	$objPHPExcel->getProperties()->setTitle($config['company']." Order Reports");
	$objPHPExcel->getProperties()->setSubject($config['company']." Order Reports");

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Orders'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Time');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Paid($)');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Refunded($)');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Shipped');
	$row_index = 2;
	while($row = $orders->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, date("H:i d/m/Y",$row['time']));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, price($row['paid']));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_index, price($row['refunded']));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_index, $row['dispatched']?'yes':'no');
		$row_index++;
	}
			
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter->save($config['path'].'downloads/reports/orders-'.date('d.m.Y-H.i.s').'.xlsx');
	
	//header('Location: '.$config['dir'].'downloads/reports/orders-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment;filename=\"orders-".date('d.m.Y-H.i.s').".xlsx\"");
	header('Cache-Control: max-age=0');
	$objWriter->save('php://output');
	
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