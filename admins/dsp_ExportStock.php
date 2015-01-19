<?
	header('Content-type: application/force-download');
	header("Content-Disposition: attachment; filename=\"stock.csv\";" ); 
	
	$csv = array();
	$csv[] = "Name";
	$csv[] = "Style";
	$csv[] = "Hidden";
	$csv[] = "UPC Code";
	$csv[] = "Color";
	$csv[] = "Option";
	$csv[] = "Size";
	$csv[] = "Quantity";
	echo '"'.implode('","', $csv).'"'."\n";
	
	while($row=$stock->FetchRow())
	{
		$csv = array();
		$csv[] = $row['name'];
		$csv[] = $row['code'];
		$csv[] = ($row['hidden']+0)?'Y':'N';
		$csv[] = $row['upc_code'];
		$csv[] = $row['color'];
		$csv[] = $row['width'];
		$csv[] = $row['size'];
		$csv[] = $row['quantity']+0;

		echo '"'.implode('","', $csv).'"'."\n";
	}
	flush();
	exit;
?>