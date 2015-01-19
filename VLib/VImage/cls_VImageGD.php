<?
/**
* VImage library
* Author	: Plesnicute Marian
* Version	: 1.0
*/
?>
<?
	class VImageGD extends VImage
	{
		public function resize($width, $height)
		{
			$coords = $this->translateCoordinates($width, $height);
			if($coords['width'] == $this->temp_info[0] && $coords['height'] == $this->temp_info[1])
				return $this;
			$new_width = $coords['width'];
			$new_height = $coords['height'];
			
			switch($this->temp_info[2])
			{
				case IMAGETYPE_JPEG:
					$temp_image = imagecreatefromjpeg($this->temp);
					break;
				case IMAGETYPE_GIF:
					$temp_image = imagecreatefromgif($this->temp);
					break;
				case IMAGETYPE_PNG:
					$temp_image = imagecreatefrompng($this->temp);
					break;
			}
			if(!$temp_image)
				throw new Exception('Cannot load temporary image', 10);
				
			if(!($new_image = imagecreatetruecolor($new_width, $new_height)))
				throw new Exception('Cannot create temporary image', 11);
				
			if(!imagecopyresampled($new_image, $temp_image, 0, 0, 0, 0, $new_width, $new_height, $this->temp_info[0], $this->temp_info[1]))
				throw new Exception('Cannot resize temporary image', 12);

			switch($this->temp_info[2])
			{
				case IMAGETYPE_JPEG:
					$output = imagejpeg($new_image, $this->temp, 100);
					break;
				case IMAGETYPE_GIF:
					$output = imagegif($new_image, $this->temp);
					break;
				case IMAGETYPE_PNG:
					$output = imagepng($new_image, $this->temp, 0);
					break;
			}
			if(!$output)
				throw new Exception('Cannot output temporary image', 13);
				
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
			
			switch($this->temp_info[2])
			{
				case IMAGETYPE_JPEG:
					$temp_image = imagecreatefromjpeg($this->temp);
					break;
				case IMAGETYPE_GIF:
					$temp_image = imagecreatefromgif($this->temp);
					break;
				case IMAGETYPE_PNG:
					$temp_image = imagecreatefrompng($this->temp);
					break;
			}
			if(!$temp_image)
				throw new Exception('Cannot load temporary image', 14);
				
			if(!($new_image = imagecreatetruecolor($interesct['width'], $interesct['height'])))
				throw new Exception('Cannot create temporary image', 15);
				
			if(!imagecopyresampled($new_image, $temp_image, 0, 0, $interesct['x'], $interesct['y'], $interesct['width'], $interesct['height'], $interesct['width'], $interesct['height']))
				throw new Exception('Cannot crop temporary image', 16);

			switch($this->temp_info[2])
			{
				case IMAGETYPE_JPEG:
					$output = imagejpeg($new_image, $this->temp, 100);
					break;
				case IMAGETYPE_GIF:
					$output = imagegif($new_image, $this->temp);
					break;
				case IMAGETYPE_PNG:
					$output = imagepng($new_image, $this->temp, 0);
					break;
			}
			if(!$output)
				throw new Exception('Cannot output temporary image', 17);
				
			$this->temp_info = $this->getImageInfo();
				
			return $this;
		}
	}
?>