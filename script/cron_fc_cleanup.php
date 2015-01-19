<?
	include("../lib/cfg_Config.php");
	include("../lib/lib_CommonAdmin.php");
	
	function delete_files($path)
	{
		global $time_limit;
		
		$dir=new DirectoryIterator($path);
		foreach($dir as $item)
		{
			if($item->isDot())
				continue;
				
			if($item->isDir())
			{
				$file=$item->getFileInfo();
				$dirname = trim($file->getFilename());
				if($dirname != '')
					delete_files($path.$dirname.'/');
			}
			
			if($item->isFile())
			{
				$file=$item->getFileInfo();
				$time = $file->getMTime();
				if($time <= $time_limit)
				{
					$filename = trim($file->getFilename());
					if($filename != '' && (stripos($filename, 'products.txt') !== false || stripos($filename, 'inventory.txt') !== false))
					{
						echo 'deleting: '.$path.$filename."<br />\n";
						unlink($path.$filename);
					}
				}
			}
		}
	}
	
	$time_limit = time() - 86400*15;
	delete_files($config['path'].'script/archive/fc/');
	echo 'Done.';
?>