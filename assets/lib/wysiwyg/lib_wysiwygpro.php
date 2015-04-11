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
	class editor_wysiwygpro extends WYSIWYG
	{
		var $_config;
		var $_db;
		var $_count;
		
		function editor_wysiwygpro(&$config,&$db)
		{
			$this->_config = &$config;
			$this->_db = &$db;
			$this->_count=0;
			//$this->WYSIWYG($config,$db,$path);
			// include the config file and editor class:
			include("wysiwygpro/wysiwygPro.class.php");
		}

		function head()
		{
			echo "<script type=\"text/javascript\">
				function enableEditor(id)
				{
					re=new RegExp('[^a-zA-Z0-9]*','g');
					//id=id.replace(re,'');
					if (!document.all)
					{
	    				editor=document.getElementById(id);
	    				if(editor.edit_object)
	    				{
							if(editor.edit_object.document.body)
							{
								editor.edit_object.document.designMode = 'on';
							}
						}
					}
				}

				function updateWYSIWYG(editor)
				{
					editor.updateWysiwyg();
				}
			</script>";
			echo "<script type=\"text/javascript\" src=\"{$this->config['dir']}lib/spellerpages/spellChecker.js\"></script>";
		}

		function form()
		{
			return "";
			//return " name=\"wysiwygproForm\" onSubmit=\"submit_form()\"";
		}

		function editor($content="")
		{
			global $session,$db,$config;
			// create a new instance of the wysiwygPro class:
			$editor = new wysiwygPro();
			
			//Allowed extensions
			
			$editor->editorURL=$this->_config['dir']."lib/wysiwyg/wysiwygpro/";
			
			$editor->allowedDocExtensions=".doc,.docx,.odf,.pdf,.xls,.xlsx,.ppt,.pptx,.rtf,.txt,.zip";
			$editor->allowedImageExtensions=".gif,.jpg,.png,.jpeg";
			
			$editor->documentDir=$this->_config['path']."downloads/website/";
			$editor->documentURL=$this->_config['dir']."downloads/website/";

			$editor->imageDir=$this->_config['path']."images/website/";
			$editor->imageURL=$this->_config['dir']."images/website/";

			$editor->editImages = true;
			$editor->renameFiles = true;
			$editor->renameFolders = true;
			$editor->deleteFiles = true;
			$editor->deleteFolders = true;
			$editor->copyFiles = true;
			$editor->copyFolders = true;
			$editor->moveFiles = true;
			$editor->moveFolders = true;
			$editor->upload = true;
			$editor->overwrite = true;
			$editor->createFolders = true;
			$editor->maxImageHeight=1280;
			$editor->maxImageWidth=1024;
			$editor->maxImageSize="1 MB";
			$editor->maxDocSize="8 MB";
			$editor->thumbnails=true;
			$editor->theme = 'blue';
			
			$editor->htmlVersion="XHTML 1.0 Transitional";
			$editor->htmlCharset = 'UTF-8';
			
			$editor->loadmethod("inline");
			$editor->name="content[".$this->_count."]";
			$editor->addStylesheet("/css/site-editor.css");

			/*//Add spellcheck button
			$editor->addbutton("Spell Check", "after:spacer8", "spellCheck('##name##')", $this->config['dir']."lib/wysiwyg/wysiwygpro/images/spelling.gif",22,22);*/

			/*	$editor->set_classmenu(array(
				'homepage' => 'Homepage',
			));*/

			if(!$simple)
			{
				$editor->disableFeatures(array(
					"print"
					//,"font"
					//,"size"
					//,"fontcolor"
					,"highlight"
					,"emoticon"
					,"media"
					,"fullwindow"
					,"spelling"
				));
			}
			else
			{

			}

			$editor->value = $content;
			$this->_count++;
			return $editor->fetch("100%", 400);
		}
	}
?>
