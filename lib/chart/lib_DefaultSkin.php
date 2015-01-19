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
	class Skin
	{
		var $skin;
		var $font;
		var $size;
		var $width;

		function Skin($image,$width)
		{
			$this->skin=array(
				"white"=>imagecolorallocate($image,255,255,255)
				,"chartbg"=>imagecolorallocate($image,250,250,250)
				,"black"=>imagecolorallocate($image,0,0,0)
				,"border"=>imagecolorallocate($image,127,127,127)
				,"shadow"=>imagecolorallocate($image,150,150,150)
				,"grid"=>imagecolorallocate($image,190,190,190)
				,"data"=>array(
					0=>imagecolorallocate($image,165,42,42)
					,1=>imagecolorallocate($image,255,228,196)
					,2=>imagecolorallocate($image,205,198,115)
					,3=>imagecolorallocate($image,238,197,145)
					,4=>imagecolorallocate($image,255,246,143)
					)
				,"databorder"=>array(
					0=>imagecolorallocate($image,165,42,42)
					,1=>imagecolorallocate($image,255,228,196)
					,2=>imagecolorallocate($image,205,198,115)
					,3=>imagecolorallocate($image,238,197,145)
					,4=>imagecolorallocate($image,255,246,143)
					)
			);

			$this->width=$width;

			$this->font=array(
				"title"=>"../fonts/vera.ttf"
				,"key"=>"../fonts/vera.ttf"
				,"xaxis"=>"../fonts/vera_sans.ttf"
				,"xaxis_title"=>"../fonts/vera.ttf"
				,"yaxis"=>"../fonts/vera_mono.ttf"
				,"yaxis_title"=>"../fonts/vera_sans.ttf"
			);

			$this->size=array(
				"title"=>$this->_minmax(10,20,14)
				,"key"=>$this->_minmax(6,8,8)
				,"axistitle"=>$this->_minmax(8,10,8)
				,"scale"=>$this->_minmax(8,12,7)
				,"yaxis"=>$this->_minmax(5,10,7)
				,"keypadding"=>$this->_minmax(2,10,10)
				,"itempadding"=>5
				,"toppadding"=>5
				,"titlepadding"=>10
			);
		}

		function _minmax($min,$max,$val)
		{
			return min($max,max($min,ceil($val/640*$this->width)));
		}

		function getSkin()
		{
			return $this->skin;
		}

		function getFont()
		{
			return $this->font;
		}

		function getSize()
		{
			return $this->size;
		}
	}
?>