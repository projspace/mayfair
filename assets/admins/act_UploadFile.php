<?
	function result($bool, $message)
	{
		$ret = array();
		$ret['result'] = $bool?'SUCCESS':'ERROR';
		$ret['message'] = $message;
		die(implode(":", $ret));
	}
	
	try
	{
		include("../lib/cfg_Config.php");
		include("../lib/adodb/adodb.inc.php");
		include("../lib/act_OpenDB.php");
		include("../lib/lib_CommonAdmin.php");

		if($_FILES['document']['error'] !== 0)
		{
			$error = '';
			switch($_FILES['document']['error'])
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
				default:
					$error = "Upload Error";
				break;
			}
			throw new Exception($error, 10001);
		}

/*
		do
		{
			$file_guid = uniqid(rand(), true);
			$filename = $config['path'].'resources/temp/'.$file_guid;
		}
		while(file_exists($filename));
*/
		$file_guid=uuid();
		$filename = $config['path'].'downloads/temp/'.$file_guid;
		
		if(!@move_uploaded_file($_FILES['document']['tmp_name'], $filename))
			throw new Exception("The was a problem whilst saving the file.", 10002);
		chmod($filename, 0777);
		
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();

		$db->Execute(
			sprintf("
				INSERT INTO
					temporary_uploads
				SET
					guid = %s
					,name = %s
					,type = %s
					,size = %u
					,`time` = NOW()
			"
				,$db->Quote($file_guid)
				,$db->Quote($_FILES['document']['name'])
				,$db->Quote($_FILES['document']['type'])
				,$_FILES['document']['size']
			)
		);
		$ok=$db->CompleteTrans();
		if(!$ok)
		{
			@unlink($filename);
			throw new Exception("The was a problem whilst registering the file.", 10003);
		}
		result(true, $file_guid);
	}
	catch(Exception $e)
	{
		if($e->getCode() > 10000)
			result(false, $e->getMessage());
		else
			result(false, "Unknown internal error.");
	}
?>
