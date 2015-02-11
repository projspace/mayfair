<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	include("cfg_Config.php");
	header("Content-Type: image/png");
	$foo=$config['navimg'];
	$foo['color']=implode("",$foo['color']['bg']).implode("",$foo['color']['fg']);
	$filename=implode("",$foo).$text;
	if($type=="on")
		$append="on";
	else
		$append="";
	if(file_exists("../images/nav/".md5($filename).$append))
		readfile("../images/nav/".md5($filename).$append);
	else
	{
		$size=imagettfbbox($config['navimg']['size'],0,"fonts/".$config['navimg']['font'].".ttf",$text);
		$image=imagecreate($size[2]-$size[0]+($config['navimg']['padding']*2),$config['navimg']['height']);
		$bg=imagecolorallocate($image
			,$config['navimg']['color']['bg']['r']
			,$config['navimg']['color']['bg']['g']
			,$config['navimg']['color']['bg']['b']);
		$fg=imagecolorallocate($image
			,$config['navimg']['color']['fg']['r']
			,$config['navimg']['color']['fg']['g']
			,$config['navimg']['color']['fg']['b']);
		if($type=="on")
		{
			$bar=$bg;
			$bg=$fg;
			$fg=$bar;
		}
		imagefilledrectangle($image,0,0,$size[2]-$size[0]+($config['navimg']['padding']*2),$config['navimg']['height'],$bg);
		imagettftext($image,$config['navimg']['size'],0,$config['navimg']['padding'],$config['navimg']['y'],$fg,"fonts/".$config['navimg']['font'].".ttf",$text);
		imagepng($image,"../images/nav/".md5($filename).$append);
		readfile("../images/nav/".md5($filename).$append);
	}
?>