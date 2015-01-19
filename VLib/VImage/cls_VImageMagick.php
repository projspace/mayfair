<?
/**
* VImage library
* Author	: Plesnicute Marian
* Version	: 1.0
*/
?>
<?
	class VImageMagick extends VImage
	{
		public function resize($width, $height)
		{
			$coords = $this->translateCoordinates($width, $height);
			if($coords['width'] == $this->temp_info[0] && $coords['height'] == $this->temp_info[1])
				return $this;
			$new_width = $coords['width'];
			$new_height = $coords['height'];
			
			$command = $this->config['cls_cfg']['mogrify'].' -antialias -resize "'.$new_width.'x'.$new_height.'!" '.$this->temp.' '.$this->temp;
			passthru($command);
				
			$this->temp_info = $this->getImageInfo();
				
			return $this;
		}
		
		public function crop($width, $height, $start_x = 0, $start_y = 0)
		{
			$coords = $this->translateCoordinates($width, $height, $start_x, $start_y);
			if($coords['width'] == $this->temp_info[0] && $coords['height'] == $this->temp_info[1])
				return $this;
			$start_x = $coords['x'];
			$start_y = $coords['y'];
			$new_width = $coords['width'];
			$new_height = $coords['height'];
			
			$interesct = $this->rectIntersect(0, 0, $this->temp_info[0], $this->temp_info[1], $start_x, $start_y, $new_width, $new_height);
			
			$command = $this->config['cls_cfg']['mogrify'].' -antialias -crop "'.$interesct['width'].'x'.$interesct['height'].'!+'.$interesct['x'].'+'.$interesct['y'].'" '.$this->temp.' '.$this->temp;
			passthru($command);
				
			$this->temp_info = $this->getImageInfo();
				
			return $this;
		}
	}
?>