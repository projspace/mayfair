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
	//width and height of the sizing frame
	$sizerHeight = 100;
	$sizerWidth = 125;

	//original maximum zoom dimension for the picture to display
	$origSize = 300;

	//absolute directory to work with
	$dir = "c:/wwwroot";

	//directory to read the pictures from, from the server's root
	$sourceDir = "/sizer/source/";

	//tmp directory to save scaled pictures
	$tempDir = "/sizer/tmp/";

	//final directory to save cropped images
	$finalDir = "/sizer/final/";

	function loadPic($picName)
	{
		global $src_img;
		$system=explode(".",$picName);
		//open the image file
		if (preg_match("/jpg|jpeg|JPG|JPEG/",$system[1])){
			$src_img=imagecreatefromjpeg($picName);
		}
		if (preg_match("/png/",$system[1])){
			$src_img=imagecreatefrompng($picName);
		}
	}

	function picWidth()
	{
		global $src_img;
		return imageSX($src_img);
	}

	function picHeight()
	{
		global $src_img;
		return imageSY($src_img);
	}

	function resizePic($newName, $new_w, $new_h)
	{
		global $src_img;
		global $sizerHeight;
		global $sizerWidth;
		global $newSize;

		//calculate the new width/height
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		if (($thumb_w < $sizerWidth) || ($thumb_h < $sizerHeight))
		{
			$ratio = $old_x/$old_y;
			$thumb_w = $sizerWidth;
			$thumb_h = $sizerHeight;
			if (($thumb_w/$thumb_h) > $ratio + .05)
				$thumb_h = $sizerWidth / $ratio;
			else
				if (($thumb_w/$thumb_h) < $ratio - .05)
					$thumb_w = $sizerHeight * $ratio;


			$newSize = max($thumb_w, $thumb_h);

		}

		//$gd2 should always be blank, but leave this here in case installing on a system
		//that has an old version of php
		if ($gd2=="nonexistant"){
			$dst_img=ImageCreate($thumb_w,$thumb_h);
			imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		}else{
			//create a new image, and copy the old image to it, scaled down
			$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		}

		//save the thumbnail
		if (preg_match("/png/",$system[1])){
			imagepng($dst_img,$newName);
		} else {
			imagejpeg($dst_img,$newName);
		}

		//and destroy the in-memory pictures
	}

	function finalCrop($finalFile, $new_w, $new_h, $x, $y)
	{
		global $src_img;
		global $sizerHeight;
		global $sizerWidth;
		global $newSize;

		//calculate the new width/height
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		if (($thumb_w < $sizerWidth) || ($thumb_h < $sizerHeight))
		{
			$ratio = $old_x/$old_y;
			$thumb_w = $sizerWidth;
			$thumb_h = $sizerHeight;


			if (($thumb_w/$thumb_h) > $ratio + .05)
			{
				$thumb_h = $sizerWidth / $ratio;
			}
			else
			if (($thumb_w/$thumb_h) < $ratio - .05)
			{
				$thumb_w = $sizerHeight * $ratio;
			}

			$newSize = max($thumb_w, $thumb_h);

		}

		//$gd2 should always be blank, but leave this here in case installing on a system
		//that has an old version of php
		if ($gd2=="nonexistant"){
			$dst_img=ImageCreate($thumb_w,$thumb_h);
			imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		}else{
			//create a new image, and copy the old image to it, scaled down
			$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		}

		$final = ImagecreateTrueColor($sizerWidth, $sizerHeight);
		imagecopyresampled($final, $dst_img, 0,0,$x, $y, $sizerWidth, $sizerHeight, $sizerWidth, $sizerHeight);

		//save the thumbnail
		if (preg_match("/png/",$system[1])){
			imagepng($final,$finalFile);
		} else {
			imagejpeg($final,$finalFile);
		}

		//and destroy the in-memory pictures
	}
?>