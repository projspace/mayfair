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
<?php
	$h = $_REQUEST['h'];
	$w = $_REQUEST['w'];

	$image=imagecreate($w, $h);

	$bg=imagecolorallocate($image, 255, 255, 255);
	imagecolortransparent($image, $bg);

	$col_poly=imagecolorallocate($image, 0, 0, 0);

	imagesetthickness($image, 5);

	imagepolygon($image,array(
				0, 0,
				$w-1, 0,
				$w-1, $h-1,
				0, $h-1
			),4,$col_poly);

	header("Content-type: image/gif");
	imagepng($image);
?>