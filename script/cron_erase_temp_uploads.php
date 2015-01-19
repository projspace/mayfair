<?
	include("../lib/cfg_Config.php");
	include("../lib/lib_CommonAdmin.php");
	$path = $config['path'].'cache/temp/';
	$dir=new DirectoryIterator($path);
	
	$dirs=array();
	$files=array();
	
	$yesterday = time() - 0;
	foreach($dir as $item)
	{
		$count++;
		if(!$item->isDot())
		{
			if(!$item->isDir())
			{
				$file=$item->getFileInfo();
				$time = $file->getMTime();
				if($time <= $yesterday)
				{
					$filename = trim($file->getFilename());
					if($filename != '' && $filename != '.htaccess')
						unlink($path.$filename);
				}
			}
			else
			{
				$file=$item->getFileInfo();
				$time = $file->getMTime();
				if($time <= $yesterday)
				{
					$dirname = trim($file->getFilename());
					if($dirname != '')
						delete_structure($path.$dirname.'/', true);
				}
			}
		}
	}
?>