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
	$objPHPExcel->getProperties()->setTitle($config['company']." Voucher Reports");
	$objPHPExcel->getProperties()->setSubject($config['company']." Voucher Reports");

	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle($title); // max 31 characters

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Customer');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'First name');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Last name');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Total($)');
	$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Date');
	$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Voucher');
	$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Total - excluding shipping and packing($)');
	$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Commission($)');
	$row_index = 3;
	while($row = $reports->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['customer']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, $row['firstname']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['lastname']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_index, round($row['total'], 2));
		$row_index++;
		foreach($report_orders as $order)
			if($order['account_id'] == $row['id'])
			{
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_index, $order['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_index, date('d/m/Y H:i', $order['time']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_index, $order['discount_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_index, round($order['total'], 2));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_index, round($order['commission'], 2));
				$row_index++;
			}
	}
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($config['path'].'downloads/reports/voucher-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Location: '.$config['dir'].'downloads/reports/voucher-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
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