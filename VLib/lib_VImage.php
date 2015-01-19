<?
/**
* VImage library
* Author	: Plesnicute Marian
* Version	: 1.0
*/
?>
<?
	if(!isset($vcfg))
		require_once('config.php');
		
	abstract class VImage
	{
		protected $config;
		protected $src_info;
		
		protected $vimage_id;
		
		protected $temp;
		protected $temp_info;
		
		function __construct($src_image)
		{
			global $vcfg;
			
			$this->vimage_id = sprintf('%04x%04x%04x%04x',mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535)); 
			
			$this->config = $vcfg['vimage'];
			
			if(!is_dir($this->config['tmp_dir']))
				throw new Exception('Temporary directory isn\'t a directory', 1);
			
			if(!is_writeable($this->config['tmp_dir']))
				throw new Exception('Temporary directory doesn\'t have write permission', 2);
			
			$this->config['allowed_formats'] = array();
			foreach($vcfg['vimage']['allowed_formats'] as $format)
			{
				$format = strtolower(trim($format));
				if($format == 'jpeg')
					$format = 'jpg';
				$this->config['allowed_formats'][] = $format;
			}
			$this->config['allowed_formats'] = array_unique($this->config['allowed_formats']);
			
			if(!file_exists($src_image))
				throw new Exception('Image file does not exist', 3);
			$this->config['src_image'] = $src_image;
			$this->temp = $this->config['tmp_dir'].$this->vimage_id;
			if(!copy($this->config['src_image'], $this->temp))
				throw new Exception('Cannot copy image source file', 4);
			$this->src_info = $this->temp_info = $this->getImageInfo();
		}
		
		function __destruct() 
		{
			if(file_exists($this->temp))
				if(!unlink($this->temp))
					throw new Exception('Cannot delete temporary file', 5);
		}

		public function save($dest_image)
		{
			if(!copy($this->temp, $dest_image))
				throw new Exception('Cannot save final image', 6);
		}
		
		public function getImageInfo()
		{
			$img_info = getimagesize($this->temp);
			if(!$img_info)
				throw new Exception('Source file is not an image', 7);
			$img_type = $img_info[2];
			
			switch($img_info[2])
			{
				case IMAGETYPE_JPEG:
					//$type = IMG_JPG;
					$img_type = "jpg";
					break;
				case IMAGETYPE_GIF:
					//$type = IMG_GIF;
					$img_type = "gif";
					break;
				case IMAGETYPE_PNG:
					//$type = IMG_PNG;
					$img_type = "png";
					break;
				default:
					throw new Exception('Source file is not a valid image. Allowed image formats: '.implode(', ', $this->config['allowed_formats']), 8);
					break;
			}
			
			if(!in_array($img_type, $this->config['allowed_formats']))
				throw new Exception('Source file is not a valid image format. Allowed image formats: '.implode(', ', $this->config['allowed_formats']), 9);
				
			return $img_info;
		}
		
		protected function rectIntersect($rect1X, $rect1Y, $rect1Width, $rect1Height, $rect2X, $rect2Y, $rect2Width, $rect2Height)
		{
			$A['X1'] = $rect1X;
			$A['Y1'] = $rect1Y;
			$A['X2'] = $rect1X + $rect1Width;
			$A['Y2'] = $rect1Y + $rect1Height;
			
			$B['X1'] = $rect2X;
			$B['Y1'] = $rect2Y;
			$B['X2'] = $rect2X + $rect2Width;
			$B['Y2'] = $rect2Y + $rect2Height;
			
			if($A['X1'] < $B['X2'] && $A['X2'] > $B['X1'] 
			&& $A['Y1'] < $B['Y2'] && $A['Y2'] > $B['Y1'])  // if the 2 rects intersect
			{
				$C['X1'] = max($A['X1'], $B['X1']);
				$C['Y1'] = max($A['Y1'], $B['Y1']);
				$C['X2'] = min($A['X2'], $B['X2']);
				$C['Y2'] = min($A['Y2'], $B['Y2']);
				return array('x'=>$C['X1'], 'y'=>$C['Y1'], 'width'=>($C['X2']-$C['X1']), 'height'=>($C['Y2']-$C['Y1']));
			}
			else
				return false;
		}
		
		protected function translateCoordinates($width, $height, $start_x = 0, $start_y = 0)
		{
			if($width == 0 && $height == 0)
			{
				$new_width = $this->temp_info[0];
				$new_height = $this->temp_info[1];
			}
			else
			if($width != 0 && $height != 0)
			{
				$new_width = $width;
				$new_height = $height;
			}
			else
			{
				$ratio = $this->temp_info[0]/$this->temp_info[1];
				if($width == 0)
				{
					$new_width = $height*$ratio;
					$new_height = $height;
				}
				else
				{
					$new_width = $width;
					$new_height = $width/$ratio;
				}
			}
			
			if(in_array(strtolower(trim($start_x)), array('left', 'center', 'right')))
			{
				switch(strtolower(trim($start_x)))
				{
					case 'left':
						$start_x = 0;
						break;
					case 'right':
						$start_x = $this->temp_info[0] - $new_width;
						break;
					case 'center':
						$start_x = ($this->temp_info[0] - $new_width)/2;
						break;
				}
			}
			else
				$start_x = intval($start_x);
			
			if(in_array(strtolower(trim($start_y)), array('top', 'center', 'bottom')))
			{
				switch(strtolower(trim($start_y)))
				{
					case 'top':
						$start_y = 0;
						break;
					case 'bottom':
						$start_y = $this->temp_info[1] - $new_height;
						break;
					case 'center':
						$start_y = ($this->temp_info[1] - $new_height)/2;
						break;
				}
			}
			else
				$start_y = intval($start_y);
			return array('x'=>$start_x, 'y'=>$start_y, 'width'=>$new_width, 'height'=>$new_height);
		}
		
		/*
			resize($width, $height)
			$width	->	integer
			$height	->	integer
			
			if $width == 0 and height != 0 then the width  will be calculated so it preserves the aspect ratio
			if $width != 0 and height == 0 then the height will be calculated so it preserves the aspect ratio
			if $width == 0 and height == 0 then no resize will be performed
		*/
		abstract protected function resize($width, $height);
		
		
		/*
			crop($width, $height, $start_x, $start_y)
			$width		->	integer
			$height		->	integer
			$start_x	->	integer or one of the following values: left, center, right
			$start_y	->	integer or one of the following values: top, center, bottom
			
			if $width == 0 and height != 0 then the width  of the cropped area will be calculated so it preserves the aspect ratio
			if $width != 0 and height == 0 then the height of the cropped area will be calculated so it preserves the aspect ratio
			if $width == 0 and height == 0 then no crop will be performed
			
			if $start_x == 'left'   then the crop area will start from the left side of the source image
			if $start_x == 'right'  then the crop area will end on the right side of the source image
			if $start_x == 'center' then the crop area will fit in the center of the source image
			
			if $start_y == 'top'    then the crop area will start from the top side of the source image
			if $start_y == 'bottom' then the crop area will end on the bottom side of the source image
			if $start_y == 'center' then the crop area will fit in the center of the source image
		*/
		abstract protected function crop($width, $height, $start_x = 0, $start_y = 0);
		
		
		/*
			maxCropResize($width, $height, $start_x, $start_y)
			$width		->	integer
			$height		->	integer
			$start_x	->	integer or one of the following values: left, center, right
			$start_y	->	integer or one of the following values: top, center, bottom
			
			if $width == 0 or height == 0 then no crop will be performed, just the resize
			if $width == 0 and height == 0 then no action will be performed
			
			if $start_x == 'left'   then the crop area will start from the left side of the source image
			if $start_x == 'right'  then the crop area will end on the right side of the source image
			if $start_x == 'center' then the crop area will fit in the center of the source image
			
			if $start_y == 'top'    then the crop area will start from the top side of the source image
			if $start_y == 'bottom' then the crop area will end on the bottom side of the source image
			if $start_y == 'center' then the crop area will fit in the center of the source image
		*/
		public function maxCropResize($width, $height, $start_x = 0, $start_y = 0)
		{
			$coords = $this->translateCoordinates($width, $height, $start_x, $start_y);
			if($coords['width'] == $this->temp_info[0] && $coords['height'] == $this->temp_info[1])
				return $this;
			$new_width = $coords['width'];
			$new_height = $coords['height'];
			
			$image_ratio = $this->temp_info[0] / $this->temp_info[1];
			$crop_ratio = $new_width/$new_height;
			
			if($crop_ratio != $image_ratio) // do crop
			{
				$image_area = $this->temp_info[0] * $this->temp_info[1];
				$variants = array();
				
				$v_width = $this->temp_info[0];
				$v_height = floor($v_width/$crop_ratio);
				$v_area = $v_width*$v_height;
				if($v_area <= $image_area)
					$variants[] = array('width'=>$v_width, 'height'=>$v_height, 'area'=>$v_area);

				$v_height = $this->temp_info[1];
				$v_width = floor($v_height*$crop_ratio);
				$v_area = $v_width*$v_height;
				if($v_area <= $image_area)
					$variants[] = array('width'=>$v_width, 'height'=>$v_height, 'area'=>$v_area);
				
				if(count($variants) == 2)
				{
					if($variants[0]['area'] < $variants[1]['area'])
					{
						$crop_width = $variants[1]['width'];
						$crop_height = $variants[1]['height'];
					}
					else
					{
						$crop_width = $variants[0]['width'];
						$crop_height = $variants[0]['height'];
					}
				}
				else
				{
					$crop_width = $variants[0]['width'];
					$crop_height = $variants[0]['height'];
				}
				$this->crop($crop_width, $crop_height, $start_x, $start_y);
			}
			
			$this->resize($new_width, $new_height);
			return $this;
		}
	}
	
	function multiple_resize($src_filename, $dest_filename, $image_type, $start_x = 'center', $start_y = 'center')
	{
		global $vcfg, $config;
		try 
		{
			if(!class_exists($vcfg['vimage']['cls']))
				require_once("VImage/cls_".$vcfg['vimage']['cls'].".php");

			$image_type = strtolower(trim($image_type));
			foreach($config['size'][$image_type] as $img_type=>$size)
			{
				$img_type = strtolower(trim($img_type));
				if($img_type == '')
					continue;

				$vimage = new $vcfg['vimage']['cls']($src_filename);
				$img_info = $vimage->getImageInfo();
				switch($img_info[2])
				{
					case IMAGETYPE_JPEG:
						$img_ext = "jpg";
						break;
					case IMAGETYPE_GIF:
						$img_ext = "gif";
						break;
					case IMAGETYPE_PNG:
						$img_ext = "png";
						break;
				}
				$filename = $dest_filename.'.'.$img_ext;
				
				
				if($img_type != 'image')
					$dest = $config['path']."images/$image_type/$img_type/$filename";
				else
					$dest = $config['path']."images/$image_type/$filename";
					
				$vimage->maxCropResize($size['x'], $size['y'], $start_x, $start_y)->save($dest);
			}
			return $img_ext;
			
		} 
		catch (Exception $e) 
		{
			return false;
		}
	}
?>