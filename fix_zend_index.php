<?
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	include("lib/cfg_Config.php");
	
	$path = $config['path'].'cache/search/';
	$dir=new DirectoryIterator($path);
	
	$dirs=array();
	$files=array();
	
	foreach($dir as $item)
	{
		if($item->isFile())
		{
			$file=$item->getFileInfo();
			$filename = trim($file->getFilename());
			$duplicate = 'tmp_'.$filename;
			if(!copy($path.$filename, $path.$duplicate))
				continue;
				
			unlink($path.$filename);
			rename($path.$duplicate, $path.$filename);
		}
	}
?>