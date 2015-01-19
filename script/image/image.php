<?
	include("../../lib/cfg_Config.php");

	$text = trim($_REQUEST['text']);

	$size = $_REQUEST['size']+0;
	if(!$size)
		$size = 20;

	if(isset($_REQUEST['color']))
		$color = explode(":",$_REQUEST['color']);
	else
		$color = array(255,0,0);
		
	if(isset($_REQUEST['background-color']))
		$background_color = explode(":",$_REQUEST['background-color']);
	else
		$background_color = array(255,255,255);
		
	$filename = md5($text.$size.$color.$$background_color).'.png';
	if(file_exists($config['path'].'images/text/'.$filename))
	{
		header("Location: ".$config['dir'].'images/text/'.$filename);
		exit;
	}
	
	$font = 'shakespearesglobe.otf';

	// image dimesnsions
	$box = imagettfbbox ( $size, 0, $font, $text);
	$width = abs($box[2] - $box[0])+2;
	$height = abs($box[5] - $box[3])+2;

	// Create the image
	$im = @imagecreatetruecolor($width, $height) or exit;

	// Create some colors
	$color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
	$background_color = imagecolorallocate($im, $background_color[0], $background_color[1], $background_color[2]);

	imagefilledrectangle($im, 0, 0, $width-1, $height-1, $background_color);

	// Add the text
	imagettftext($im, $size, 0, 1, $height-1, $color, $font, $text);

	// Set the content-type
	//header("Content-type: image/png");
	imagepng($im, $config['path'].'images/text/'.$filename);
	imagedestroy($im);
	
	header("Location: ".$config['dir'].'images/text/'.$filename);
	exit;
?>