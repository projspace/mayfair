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
	if($type == 'customers' || $type == 'idle')
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Value($)');
		$row_index = 2;
		while($row = $results->FetchRow())
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['email']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['value'], 2, '.', ','));
			$row_index++;
		}
	}
	else
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Price($)');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Quantity');
		$row_index = 2;
		while($row = $results->FetchRow())
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['price'], 2, '.', ','));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['count']);
			$row_index++;
		}
	}

	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($config['path'].'downloads/reports/full-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Location: '.$config['dir'].'downloads/reports/full-reports-'.safe($_REQUEST['src']).'-'.date('d.m.Y-H.i.s').'.xlsx');
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
	error("There was a problem whilst creating the report, please try again. If this persists please notify your designated support contact.".$e->getMessage(),"Report Error");
}
?>