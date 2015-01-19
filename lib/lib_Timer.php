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
	class Timer
	{
		var $start;
		var $end;

		function Timer()
		{
			$this->start=$this->_getMicroTime();
		}

		function stop()
		{
			$this->end=$this->_getMicroTime();
			return number_format($this->end-$this->start,4);
		}

		function _getMicroTime()
		{
			list($usec, $sec) = explode(" ",microtime());
		    return ((float)$usec + (float)$sec);
		}
	}
?>