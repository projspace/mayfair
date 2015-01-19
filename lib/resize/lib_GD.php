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
	class GD extends ImageResize
	{
		function resize($id,$dir,$imageType)
		{
			global $config;

			$type=$this->_getFileType($_FILES['image']['type']);

			if($type)
			{
				$filename=$id.".".$type;

				if($config["size"][$imageType]["image"]["x"]>0)
					$this->_doResize($_FILES['image']['tmp_name'],$imageType,false);

				if(file_exists("../images/$dir/".$filename))
					unlink("../images/$dir/".$filename);

				//copy($_FILES['image']['tmp_name'].".mgk","../images/$dir/".$filename);
				copy($_FILES['image']['tmp_name'],"../images/$dir/".$filename);

				if($config["size"][$imageType]["thumb"]["x"]>0)
				{
					$this->_doResize($_FILES['image']['tmp_name'],$imageType,true);
					if(file_exists("../images/$dir/thumbs/".$filename))
						unlink("../images/$dir/thumbs/".$filename);
					//rename($_FILES['image']['tmp_name'].".mgk~","../images/$dir/thumbs/".$filename);
					rename($_FILES['image']['tmp_name'],"../images/$dir/thumbs/".$filename);
				}

				return $filename;
			}
			else
				return false;
		}

		function _doResize($filename,$imageType,$thumb)
		{
			global $config;
			if($thumb==true)
				$command=$config["mogrifypath"]."mogrify -antialias -resize \"".$config["size"][$imageType]['thumb']['x']."x".$config["size"][$imageType]['thumb']['y'].">\" -mattecolor \"#000000\" $filename";
			else
				$command=$config["mogrifypath"]."mogrify -antialias -resize \"".$config["size"][$imageType]['image']['x']."x".$config["size"][$imageType]['image']['y'].">\" $filename";
			echo exec($command);
		}
	}
?>
