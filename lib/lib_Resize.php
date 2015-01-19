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
	class ImageResize
	{
		var $config;
		var $input;

		function ImageResize($config)
		{
			$this->config=$config;
		}

		function setInput($filename)
		{
			$this->input=$filename;
		}

		function resize($id,$imageType)
		{
			return;
		}

		function resizeArray($id,$imageType,$count=0)
		{
			return;
		}

		function crop($id,$imageType)
		{
			return;
		}

		function _getFileType($mimetype)
		{
			switch(trim($mimetype))
			{
				case "image/jpeg":
					return "jpg";
					break;

				case "image/pjpeg":
					return "jpg";
					break;

				case "image/png":
					return "png";
					break;

				case "image/gif":
					return "gif";
					break;

				default:
					return false;
					break;
			}
		}

		function _doResize($input,$output,$imageType,$thumb)
		{
			return;
		}
	}

	include("resize/lib_".$config['admin']['resize'].".php");
	$resize=new $config['admin']['resize']($config);
?>
