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
	class BarChart
	{
		var $x;
		var $y;
		var $width;
		var $height;
		var $image;
		var $color;
		var $font;
		var $data;
		var $xaxis;
		var $yaxis;
		var $data;

		function BarChart()
		{

		}

		function addData($data)
		{
			$this->data[count($this->data)]=$data;
		}

		function setXAxis($axis,$vals)
		{
			$this->xaxis["title"]=$axis;
			$this->xaxis["vals"]=$vals;
		}

		function setYAxis($axis)
		{
			$this->yaxis["title"]=$axis;
		}

		function _drawCanvas()
		{
			imagefilledrectangle(
				$this->image
				,$this->x+$this->size["itempadding"]
				,0
				,$this->width
				,$this->y
				,$this->color["chartbg"]
			);
		}

		function _textSize($size,$font,$text)
		{
			$box=imagettfbbox($size,0,$this->font[$font],$text);
			return array(
				"width"=>($box[2]-$box[0])
				,"height"=>($box[1]-$box[7])
			);
		}

		function _calcYScale()
		{
			$data=$this->data[0];
			for($i=1;$i<count($this->data);$i++)
				$data=array_merge($data,$this->data[$i]);
			rsort($data);
			$maxval=$data[0];
			$scale=pow(10,strlen(intval($maxval))-1);
			$max=ceil($maxval/$scale)*$scale;
			if($max-$maxval>=$scale/2)
			{
				$max-=$scale/2;
				$scale/=2;
			}
			$this->yaxis["max"]=$max;
			$this->yaxis["scale"]=$scale;

			if($data[count($data)-1]<0)
			{
				$minval=$data[count($data)-1];
				$min=floor($minval/$scale)*$scale;
				$this->yaxis["min"]=$min;
			}
			else
				$this->yaxis["min"]=0;
		}

		function _drawXAxisTitle()
		{
			$size=$this->_textSize($this->size["axistitle"],"xaxis_title",$this->xaxis["title"]);
			imagettftext(
				$this->image
				,$this->size["axistitle"]
				,0
				,($this->width/2)-($size["width"]/2)
				,$this->height-$this->size["toppadding"]
				,$this->color["black"]
				,$this->font["xaxis_title"]
				,$this->xaxis["title"]
			);
			$this->y=$this->height-$this->size["toppadding"]-$size["height"];
		}

		function _drawXAxis()
		{
			$size=$this->_textSize($this->size["scale"],"yaxis",$this->yaxis["max"]);
			$this->x+=$this->size["itempadding"]*2+$size["width"];
			$xadd=($this->width-$this->x)/count($this->xaxis["vals"]);
			$this->xaxis["width"]=$xadd;
			$x=$this->x+($xadd/2);
			for($i=0;$i<count($this->xaxis["vals"]);$i++)
			{
				$size=$this->_textSize($this->size["scale"],"xaxis",$this->xaxis["vals"][$i]);
				imagettftext(
					$this->image
					,$this->size["scale"]
					,0
					,$x-($size["width"]/2)+$this->size["itempadding"]
					,$this->y-$this->size["itempadding"]
					,$this->color["black"]
					,$this->font["xaxis"]
					,$this->xaxis["vals"][$i]
				);
				$x+=$xadd;
			}
			$this->y-=($size["height"]+$this->size["itempadding"]*2);
		}

		function _drawYAxisTitle()
		{
			$size=$this->_textSize($this->size["axistitle"],"yaxis_title",$this->yaxis["title"]);
			imagettftext(
				$this->image
				,$this->size["axistitle"]
				,90
				,$size["height"]+$this->size["toppadding"]
				,($this->y/2)+($size["width"]/2)
				,$this->color["black"]
				,$this->font["yaxis_title"]
				,$this->yaxis["title"]
			);
			$this->x=$size["height"]+$this->size["toppadding"];
		}

		function _drawYAxis()
		{
			$num=$this->yaxis["max"]/$this->yaxis["scale"];
			$size=$this->_textSize($this->size["scale"],"yaxis",$this->yaxis["max"]);
			$yadd=($this->y)/$num;
			$y=$this->y+$size["height"];

			for($i=0;$i<=$num;$i++)
			{
				$size=$this->_textSize($this->size["scale"],"yaxis",$i*$this->yaxis["scale"]);
				imagettftext(
					$this->image
					,$this->size["scale"]
					,0
					,$this->x-ceil($size["width"])
					,$y+1
					,$this->color["black"]
					,$this->font["yaxis"]
					,$i*$this->yaxis["scale"]
				);
				$asdf=$y;
				imageline(
					$this->image
					,$this->x+$this->size["itempadding"]
					,$y-$size["height"]
					,$this->width
					,$y-$size["height"]
					,$this->color["grid"]
				);
				imageline(
					$this->image
					,$this->x+$this->size["itempadding"]
					,$y-$size["height"]
					,$this->x+$this->size["itempadding"]+$this->size["yaxis"]
					,$y-$size["height"]
					,$this->color["black"]
				);
				$y-=$yadd;
			}
			$this->x+=$this->size["itempadding"];

		}

		function _drawLines()
		{
			imageline(
				$this->image
				,$this->x
				,0
				,$this->x
				,$this->y
				,$this->color["black"]
			);
			imageline(
				$this->image
				,$this->x
				,$this->y
				,$this->width
				,$this->y
				,$this->color["black"]
			);
			imageline(
				$this->image
				,$this->width-1
				,0
				,$this->width-1
				,$this->y
				,$this->color["black"]
			);
		}

		function _drawData()
		{
			$width=floor($this->xaxis["width"]-$this->size["itempadding"])/count($this->data);
			$scale=$this->y/$this->yaxis["max"];

			for($i=0;$i<count($this->data);$i++)
			{
				$x=$this->x+($width*$i)+1;
				for($j=0;$j<count($this->data[$i]);$j++)
				{
					imagefilledrectangle(
						$this->image
						,$x
						,$this->y-intval($this->data[$i][$j]*$scale)
						,$x+$width-1
						,$this->y-1
						,$this->color["data"][$i % 5]
					);
					imagerectangle(
						$this->image
						,$x
						,$this->y-intval($this->data[$i][$j]*$scale)
						,$x+$width-1
						,$this->y-1
						,$this->color["databorder"][$i % 5]
					);
					$x+=$this->xaxis["width"];
				}
			}
		}

		function draw($width,$height,$canvas)
		{
			$this->image=imagecreate($width,$height);
			$this->width=$width;
			$this->height=$height;
			$skin=new Skin($this->image,$canvas->width);
			$this->color=$skin->getSkin();
			$this->font=$skin->getFont();
			$this->size=$skin->getSize();

			$this->_calcYScale();
			$this->_drawXAxisTitle();
			$this->_drawYAxisTitle();

			$this->_drawXAxis();
			$this->_drawCanvas();
			$this->_drawYAxis();

			$this->_drawData();
			$this->_drawLines();
			return $this->image;
		}
	}
?>