<?
	/**
	 * e-Commerce System WYSIWYG Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class editor_textarea extends WYSIWYG
	{
		function editor($content="")
		{
			return "<textarea style=\"width: 600px;\" rows=\"30\" name=\"content\" id=\"content\">{$content}</textarea>";
		}
	}
?>