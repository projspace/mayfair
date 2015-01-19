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
	$messages=array();
	while($row=$feeds->FetchRow())
	{
		$class=make_doubly_safe($row['class']."../");
		if(file_exists("../lib/datafeed/cfg_".$class.".php"))
			include("../lib/datafeed/cfg_".$class.".php");
		include("../lib/datafeed/lib_".$class.".php");
		$feed=new $class($db,$config);
		$feed->retrieve();
		$messages[$class]=$feed->publish();
	}
?>