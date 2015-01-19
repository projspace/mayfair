<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class Canvas
	{
		var $image;
		var $title;
		var $color;
		var $width;
		var $height;
		var $x;
		var $y;
		var $chart;
		var $key;
		var $size;

		function Canvas($width,$height)
		{
			$this->image=imagecreate($width,$height);
			$this->width=$width-5;
			$this->height=$height-5;
			$this->x=0;
			$this->y=0;
			$this->title="New Chart";

			$this->chart=false;
			$this->key=false;

			$skin=new Skin($this->image,$width);
			$this->color=$skin->getSkin();
			$this->font=$skin->getFont();
			$this->size=$skin->getSize();
		}

		function setTitle($title)
		{
			$this->title=$title;
		}

		function setKey($key)
		{
			$this->key=$key;
		}

		function setChart($chart)
		{
			$this->chart=$chart;
		}

		function _textSize($size,$font,$text)
		{
			$box=imagettfbbox($size,0,$this->font[$font],$text);
			return array(
				"width"=>($box[2]-$box[0])
				,"height"=>($box[1]-$box[7])
			);
		}

		function _drawTitle()
		{
			$size=$this->_textSize($this->size["title"],"title",$this->title);
			imagettftext(
				$this->image
				,$this->size["title"]
				,0
				,($this->width/2)-($size["width"]/2)
				,$this->y+$size["height"]+$this->size["toppadding"]
				,$this->color["black"]
				,$this->font["title"]
				,$this->title
			);
			$this->y+=$size["height"]+$this->size["toppadding"]+$this->size["titlepadding"];
		}

		function _drawBorder()
		{
			imagefilledrectangle(
				$this->image
				,5
				,5
				,$this->width+5
				,$this->height+5
				,$this->color["shadow"]
			);
			imagefilledrectangle(
				$this->image
				,0
				,0
				,$this->width
				,$this->height
				,$this->color["white"]
			);
			imagerectangle(
				$this->image
				,0
				,0
				,$this->width
				,$this->height
				,$this->color["black"]
			);
		}

		function _drawChart($width,$height)
		{
			imagecopy(
				$this->image
				,$this->chart->draw($width-$this->size["itempadding"],$height,$this)
				,5
				,$this->y
				,0
				,0
				,$width
				,$height
			);
		}

		function _drawKey($width,$height)
		{
			$key=new Key($width,$height,$this);
			$key->setKey($this->key);
			imagecopy(
				$this->image
				,$key->draw()
				,$this->width-$this->size["itempadding"]-$width
				,$this->y
				,0
				,0
				,$width
				,$height
			);
		}

		function draw()
		{
			$this->_drawBorder();
			$this->_drawTitle();
			if($this->key)
			{
				$width=$this->width-($this->size["itempadding"]*3);
				$keywidth=min(125,intval($width*0.2));
				$this->_drawKey(
					$keywidth
					,$this->height-$this->y-$this->size["itempadding"]
				);
				$this->_drawChart(
					$width-$keywidth+$this->size["itempadding"]
					,$this->height-$this->y-$this->size["itempadding"]
				);
			}
			else
				$this->_drawChart(
					intval($this->width-10)
					,$this->height-$this->y-5
				);

			header("Content-type: image/png");
			imagepng($this->image);
		}
	}
?>