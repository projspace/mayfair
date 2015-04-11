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
	class editor_FCKEditor extends WYSIWYG
	{
		function editor($content="")
		{
			include("fckeditor/fckeditor.php");
			$editor = new FCKeditor('content') ;
			$editor->BasePath="/lib/wysiwyg/fckeditor/";
			$editor->ToolbarSet="Shop";
			$editor->Height=400;
			$editor->Value=$content;
			$editor->Config['EditorAreaCSS']=$this->config['dir']."css/site_editor.css";
			return $editor->CreateHtml();
		}
	}
?>