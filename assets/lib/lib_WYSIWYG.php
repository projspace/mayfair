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
	class WYSIWYG
	{
		var $_config;
		var $_db;
		var $_simple;
		var $_count;

		function WYSIWYG(&$config,&$db)
		{
			$this->_config&=$config;
			$this->_db&=$db;
			$this->_simple=false;
			$this->_count=0;
			$this->_path=$path;
		}

		function head()
		{
			return;
		}

		function form()
		{
			return;
		}

		function editor($content="")
		{
			return $content;
		}
	}
	include("wysiwyg/lib_".$config['admin']['editor'].".php");
	$class="editor_".$config['admin']['editor'];
	$wysiwyg=new $class($config,$db);
?>