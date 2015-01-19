<?
	$path = '/var/www/vhosts/bloch-usa/httpdocs/';
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	set_time_limit(0);

	define('SEARCHENGINE',false);
	define('USECOOKIE',false);
	define('TESTCOOKIE',false);
	
	include($path."lib/cfg_Config.php");
	include($path."lib/adodb/adodb.inc.php");
	include($path."lib/act_OpenDB.php");
	include($path."lib/lib_Common.php");
	include($path."lib/lib_Session.php");
	include($path."lib/lib_Smarty.php");
	include($path."lib/lib_WSTax.php");
	include($path."lib/lib_Shipping.php");
	include($path."lib/lib_Payment.php");
	include($path."lib/payment/cfg_".$config['psp']['driver'].".php");
	include($path."lib/payment/lib_".$config['psp']['driver'].".php");
	include($path."lib/lib_Executor.php");
	include($path."lib/lib_Email.php");
	
	function import_log($text, $print = false)
	{
		global $cfg_import;
		$date = date('d/m/Y H:i:s');
		
		if($cfg_import['log_file'])
		{
			if ($handle = @fopen($cfg_import['filename_log'], 'a'))
			{
				fwrite($handle, "\n".'===================='.$date.'===================='."\n".$text."\n");
				fclose($handle);
			}
			else
				throw new Exception('Unable to open log file "'.$cfg_import['filename_log'].'"');
		}
		if($print)
			echo "<br />\n".'===================='.$date.'===================='."<br />\n<pre>".$text."</pre><br />\n";
	}
	
	$smarty->assign("config",$config);
	
	$dir=new DirectoryIterator($config['path'].'confirmation/');
	$file_count = 0;
	foreach($dir as $item)
	{
		if(!$item->isFile())
			continue;
		$file=$item->getFileInfo();
		$filename = trim($file->getFilename());
		if(preg_match("/OrderStatus/i", $filename))
			include("fc_order_status.php");
		else
		if(strtolower($filename) == '20_products.txt')
			include("fc_products.php");
		else
		if(strtolower($filename) == '20_inventory.txt')
			include("fc_inventory.php");
		else
			continue;
		
		$file_count++;
		if($file_count > 10)
			break;
	}
?>