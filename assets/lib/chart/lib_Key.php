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
	class Key
	{
		var $width;
		var $height;
		var $x;
		var $y;
		var $key;
		var $color;
		var $font;
		var $image;
		var $size;

		function Key($width,$height,$canvas)
		{
			$this->image=imagecreate($width,$height);
			$this->width=$width-3;
			$this->height=$height-2;
			$this->x=0;
			$this->y=0;
			$skin=new Skin($this->image,$canvas->width);
			$this->color=$skin->getSkin();
			$this->font=$skin->getFont();
			$this->size=$skin->getSize();
			imagefilledrectangle(
				$this->image
				,0
				,0
				,$this->width
				,$this->height
				,$this->color["white"]
			);
		}

		function setKey($key)
		{
			$this->key=$key;
		}

		function _textSize($size,$font,$text)
		{
			$box=imagettfbbox($size,0,$this->font[$font],$text);
			return array(
				"width"=>($box[2]-$box[0])
				,"height"=>($box[1]-$box[7])
			);
		}

		function _drawBorder()
		{
			imagefilledrectangle(
				$this->image
				,$this->width
				,2
				,$this->width+2
				,$this->y+2
				,$this->color["shadow"]
			);
			imagefilledrectangle(
				$this->image
				,2
				,$this->y
				,$this->width+2
				,$this->y+2
				,$this->color["shadow"]
			);
			imagerectangle(
				$this->image
				,0
				,0
				,$this->width
				,$this->y
				,$this->color["black"]
			);
		}

		function _drawKey()
		{
			$this->y+=$this->size["keypadding"];
			$size=$this->_textSize($this->size["key"],"key",$this->key[0]);
			for($i=0;$i<count($this->key);$i++)
			{
				$this->_drawBox(
					$size["height"]
					,$size["height"]
					,$this->size["keypadding"]
					,$this->y
					,$i % 5
				);
				imagettftext(
					$this->image
					,$this->size["key"]
					,0
					,$this->size["keypadding"]+($size["height"]*1.5)
					,$this->y+$size["height"]
					,$this->color["black"]
					,$this->font["key"]
					,$this->key[$i]
				);
				$this->y+=$size["height"]+$this->size["keypadding"];
			}
		}

		function _drawBox($width,$height,$x,$y,$set)
		{
			imagefilledrectangle(
				$this->image
				,$x
				,$y
				,$width+$x
				,$height+$y
				,$this->color["data"][$set]
			);
			imagerectangle(
				$this->image
				,$x
				,$y
				,$width+$x
				,$height+$y
				,$this->color["black"]
			);
		}

		function draw()
		{
			$this->_drawKey();
			$this->_drawBorder();
			return $this->image;
		}
	}
?>