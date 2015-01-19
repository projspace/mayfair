<?
	header('Content-type: application/force-download');
	header("Content-Disposition: attachment; filename=\"customers.csv\";" ); 
	
	$csv = array();
	$csv[] = "Email";
	$csv[] = "First name";
	$csv[] = "Last name";
	$csv[] = "Phone";
	$csv[] = "Date of birth";
	$csv[] = "Student";
	$csv[] = "Confirmed student";
	$csv[] = "Registered";
	echo '"'.implode('","', $csv).'"'."\n";
	
	while($row=$users->FetchRow())
	{
		$csv = array();
		$csv[] = $row['email'];
		$csv[] = $row['firstname'];
		$csv[] = $row['lastname'];
		$csv[] = $row['phone'];
		$csv[] = (($time = strtotime($row['dob']))>0)?date('d/m/Y', $time):'';
		$csv[] = $row['student']?'Yes':'No';
		$csv[] = $row['confirmed_student']?'Yes':'No';
		$csv[] = (($time = strtotime($row['created']))>0)?date('d/m/Y H:i:s', $time):'';

		echo '"'.implode('","', $csv).'"'."\n";
	}
	flush();
	exit;
?>