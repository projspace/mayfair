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
/*
<fusedoc fuse="fbx_Circuits.php">
	<responsibilities>
		I define the Circuits structure used with Fusebox 3.0.  Use slashes ("/") to delimit the circuit mapping, i.e.: $Fusebox['circuits']['red'] = "home/folder/redCircuit";
	</responsibilities>
	<io>
		<out>
			<string name="$Fusebox['circuits'][*]" comments="set a variable for each circuit name" />
		</out>
	</io>
</fusedoc>
*/

//
	$Fusebox['circuits']['home'] = "home";
	$Fusebox['circuits']['shop'] = "home/shop";
	#$Fusebox['circuits']['wishlist'] = "home/wishlist";
	$Fusebox['circuits']['telesale'] = "home/telesale";
	$Fusebox['circuits']['user'] = "home/users";
	$Fusebox['circuits']['admin'] = "home/admins";
?>