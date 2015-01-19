<?
	if(!isset($config))
		require_once("../lib/cfg_Config.php");
	$vcfg = array();
	
	$vcfg['vimage']['cls'] = 'VImageGD';								// case sensitive
	//$vcfg['vimage']['cls'] = 'VImageMagick';								// case sensitive
	$vcfg['vimage']['cls_cfg'] = array('mogrify' => $config['prog']['mogrify']);
	$vcfg['vimage']['tmp_dir'] = $config['path'].'VLib/temp/';		// Must end with a '/'
	$vcfg['vimage']['allowed_formats'] = array('jpg', 'png', 'gif');	// Possible values: jpg, png AND/OR gif
?>