<?
	$error = false;
	if($_FILES['stock']['error'] !== 0)
	{
		switch($_FILES['stock']['error'])
		{
			case 1:
			case 2:
				$error = "The uploaded file is to big";
			break;
			case 3:
				$error = "The uploaded file was only partially uploaded.";
			break;
			case 4:
				$error = "No file was uploaded.";
			break;
			case 6:
				$error = "System error.";
			break;
			case 7:
				$error = "System error .";
			break;
		}
		error($error);
		return;
	}

	if(!@move_uploaded_file($_FILES['stock']['tmp_name'], $config['path'].'script/stock.csv'))
	{
		error('The was an error whilst moving the uploaded file.');
		return;
	}
	
	require("../script/stock.php");
	
	if($ok)
	{
		echo '<h1 class="pageTitle">Import Stock CSV</h1><p>Import ended succesfully.</p>';
	}
?>