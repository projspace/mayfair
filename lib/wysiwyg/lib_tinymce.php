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
	class editor_tinymce extends WYSIWYG
	{
		var $_config;
		var $_db;
		var $_count;
		
		function editor_tinymce(&$config,&$db)
		{
			$this->_config = &$config;
			$this->_db = &$db;
			$this->_count=0;
		}

		function head()
		{
			echo '<script type="text/javascript" src="'.$this->_config['dir'].'lib/wysiwyg/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>';
			echo '
				<script type="text/javascript">
				/* <![CDATA[ */
					$(document).ready(function() {
						$("textarea.tinymce").tinymce({
							// Location of TinyMCE script
							script_url : "'.$this->_config['dir'].'lib/wysiwyg/tinymce/jscripts/tiny_mce/tiny_mce.js",

							// General options
							theme : "advanced",
							plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager,filemanager",

							// Theme options
							theme_advanced_buttons1 : "formatselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,|,undo,redo,|,link,unlink,anchor,image,insertimage,|,bullist,numlist,|,code",
							theme_advanced_buttons2 : "",
							theme_advanced_buttons3 : "",
							theme_advanced_buttons4 : "",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,

							// Example content CSS (should be your site CSS)
							content_css : "'.$this->_config['admin_layout_dir'].'css/main.css",

							// Drop lists for link/image/media/template dialogs
							template_external_list_url : "lists/template_list.js",
							external_link_list_url : "lists/link_list.js",
							external_image_list_url : "lists/image_list.js",
							media_external_list_url : "lists/media_list.js",

							extended_valid_elements : "iframe[src|width|height|name|id|class|align|style|frameborder|border|allowtransparency]",

							// Replace values for the template plugin
							template_replace_values : {
								username : "Some User",
								staffid : "991234"
							},
							
							relative_urls : false,
							remove_script_host : true,
							document_base_url : "'.$this->_config['protocol'].$this->_config['url'].$this->_config['dir'].'"
						});
					});
				/* ]]> */
				</script>
			';
		}

		function form()
		{
			return "";
		}

		function editor($content="", $width="100%", $height="500px")
		{
			$editor = '<textarea class="tinymce" style="width: '. $width.'; height: '.$height.';" name="content['.$this->_count.']">'.$content.'</textarea>';
			$this->_count++;
			return $editor;
		}
	}
?>
