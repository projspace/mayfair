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
	function name2username($name)
	{
		$badchars=" .!.\".£.$.%.^.&.*.(.).-.+.=.{.}.[.].;.'.:.@.<.>.,./.\\.|.¬";
		$bad=explode(".",$badchars);

		return str_replace($bad,"",strtolower($name));
	}
?>