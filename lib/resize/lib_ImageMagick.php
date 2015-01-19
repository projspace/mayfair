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
	class ImageMagick extends ImageResize
	{
		function resize($id,$imageType)
		{
			$type=false;
			if(is_uploaded_file($_FILES['image']['tmp_name']))
			{
				$type=$this->_getFileType($_FILES['image']['type']);
				if($type)
				{
					$filename=$id.".".$type;

					if(file_exists("../images/$imageType/$filename"))
					{
						unlink("../images/$imageType/$filename");
						unlink("../images/$imageType/thumbs/$filename");
						unlink("../images/$imageType/medium/$filename");
					}

					if($this->config["size"][$imageType]["image"]["x"]>0)
						$this->_doResize($_FILES['image']['tmp_name'],"../images/$imageType/$filename",$imageType,false);

					if($this->config["size"][$imageType]["medium"]["x"]>0)
						$this->_doResize($_FILES['image']['tmp_name'],"../images/$imageType/medium/$filename",$imageType,"medium");

					if($this->config["size"][$imageType]["thumb"]["x"]>0)
						$this->_doResize($_FILES['image']['tmp_name'],"../images/$imageType/thumbs/$filename",$imageType,true);
				}
				unlink($_FILES['image']['tmp_name']);
			}

			return $type;
		}

		function resizeArray($id,$imageType,$count=0)
		{
			$type=false;
			if(is_uploaded_file($_FILES['image']['tmp_name'][$count]))
			{
				$type=$this->_getFileType($_FILES['image']['type'][$count]);
				if($type)
				{
					$filename=$id.".".$type;

					if(file_exists("../images/$imageType/$filename"))
					{
						unlink("../images/$imageType/$filename");
						unlink("../images/$imageType/thumbs/$filename");
						if($imageType=="product")
							unlink("../images/$imageType/medium/$filename");
					}

					if($this->config["size"][$imageType]["image"]["x"]>0)
						$this->_doResize($_FILES['image']['tmp_name'][$count],"../images/$imageType/$filename",$imageType,false);

					if($imageType=="product")
						$this->_doResize($_FILES['image']['tmp_name'][$count],"../images/$imageType/medium/$filename",$imageType,"medium");

					if($this->config["size"][$imageType]["thumb"]["x"]>0)
						$this->_doResize($_FILES['image']['tmp_name'][$count],"../images/$imageType/thumbs/$filename",$imageType,true);
				}
				unlink($_FILES['image']['tmp_name'][$count]);
			}

			return $type;
		}

		function _doResize($input,$output,$imageType,$thumb)
		{
			copy($input,$output);
			if($thumb===true)
			{
				$new_size = '';
				if($this->config["size"][$imageType]['thumb']['x']+0)
					$new_size += $this->config["size"][$imageType]['thumb']['x'];
				if($this->config["size"][$imageType]['thumb']['y']+0)
					$new_size += 'x'.$this->config["size"][$imageType]['thumb']['y'];
				$command=$this->config['prog']['mogrify']." -antialias -resize \"".$new_size.">\" -mattecolor \"#000000\" $output $output";
			}
			else if($thumb=="medium")
			{
				$new_size = '';
				if($this->config["size"][$imageType]['medium']['x']+0)
					$new_size += $this->config["size"][$imageType]['medium']['x'];
				if($this->config["size"][$imageType]['medium']['y']+0)
					$new_size += 'x'.$this->config["size"][$imageType]['medium']['y'];
				$command=$this->config['prog']['mogrify']." -antialias -resize \"".$new_size.">\" -mattecolor \"#000000\" $output $output";
			}
			else
			{
				$new_size = '';
				if($this->config["size"][$imageType]['image']['x']+0)
					$new_size += $this->config["size"][$imageType]['image']['x'];
				if($this->config["size"][$imageType]['image']['y']+0)
					$new_size += 'x'.$this->config["size"][$imageType]['image']['y'];
				$command=$this->config['prog']['mogrify']." -antialias -resize \"".$new_size.">\" $output $output";
			}
			passthru($command);
		}
	}
?>
