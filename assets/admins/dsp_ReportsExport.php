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

	// Total sales
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Summary'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Total sales:');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', $sales['total']);

	// Best Sellers by Quantity
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setTitle('Best Sellers by Quantity'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Price($)');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Quantity');
	$row_index = 2;
	while($row = $best_sellers_quantity->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['price'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['count']);
		$row_index++;
	}

	// Best Sellers by Value
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setTitle('Best Sellers by Value'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Price($)');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Value($)');
	$row_index = 2;
	while($row = $best_sellers_value->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['price'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, number_format($row['value'], 2, '.', ','));
		$row_index++;
	}

	// Worst Sellers by Quantity
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(3);
	$objPHPExcel->getActiveSheet()->setTitle('Worst Sellers by Quantity'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Price($)');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Quantity');
	$row_index = 2;
	while($row = $worst_sellers_quantity->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['price'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, $row['count']);
		$row_index++;
	}

	// Worst Sellers by Value
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(4);
	$objPHPExcel->getActiveSheet()->setTitle('Worst Sellers by Value'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Price($)');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Value($)');
	$row_index = 2;
	while($row = $worst_sellers_value->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['price'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_index, number_format($row['value'], 2, '.', ','));
		$row_index++;
	}

	// Best Customers
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(5);
	$objPHPExcel->getActiveSheet()->setTitle('Best Customers'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Value($)');
	$row_index = 2;
	while($row = $best_customers->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['value'], 2, '.', ','));
		$row_index++;
	}

	// Worst Customers
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(6);
	$objPHPExcel->getActiveSheet()->setTitle('Worst Customers'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Value($)');
	$row_index = 2;
	while($row = $worst_customers->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['value'], 2, '.', ','));
		$row_index++;
	}

	// Customers who have not purchased in over a year
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(7);
	$objPHPExcel->getActiveSheet()->setTitle('Idle Customers for over a year'); // max 31 characters
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Value($)');
	$row_index = 2;
	while($row = $idle_customers->FetchRow())
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_index, $row['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_index, number_format($row['value'], 2, '.', ','));
		$row_index++;
	}

			
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($config['path'].'downloads/reports/reports-'.date('d.m.Y-H.i.s').'.xlsx');
	
	header('Location: '.$config['dir'].'downloads/reports/reports-'.date('d.m.Y-H.i.s').'.xlsx');
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