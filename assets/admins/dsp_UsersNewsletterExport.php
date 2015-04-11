<?php
ini_set('memory_limit','128M');
set_time_limit(0);
try 
{
	ob_end_clean();
	header("Content-Type: text/csv");
	header("Cache-Control: no-store, no-cache");
	header(sprintf("Content-Disposition: attachment; filename=newsletter_export_%s.csv",date('d_m_Y_G_i_s')));

    $csv = array();
    $csv[1] = 'Email';
    $csv[2] = 'First name';
    $csv[3] = 'Middle name';
    $csv[4] = 'Last name';

    echo '"'.implode('","', $csv).'"'."\n";

	while($row = $users->FetchRow())
	{

		$csv = array();
		$csv[1] = $row['email'];
		$csv[2] = $row['firstname'];
		$csv[3] = $row['middlename'];
		$csv[4] = $row['lastname'];

		echo '"'.implode('","', $csv).'"'."\n";
	}
	exit;
}
catch (Exception $e)
{
	error("There was a problem whilst creating the export, please try again. If this persists please notify your designated support contact.","Report Error");
}
?>