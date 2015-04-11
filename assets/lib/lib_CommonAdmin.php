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
	function disp($var1,$var2)
	{
		if(isset($var1))
			return $var1;
		else
			return $var2;
	}
	
	function disp3($var1,$var2,$var3)
	{
		if(isset($var1))
			return $var1;
		elseif(isset($var2))
			return $var2;
		else
			return $var3;
	}
	
	/**
	 * Check if the last operation produced an error and if so display a message
	 */
	function DBCheck($n=0)
	{
		global $db;
		if($n==0)
		{
			if($db->ErrorNo()>1)
				error($db->ErrorMsg(),"Database Error No. ".$db->ErrorNo());
		}
		else
		{
			if($db->ErrorNo()>1)
				error($db->ErrorMsg(),"Database Error No. ".$db->ErrorNo()." in query {$n}");
		}
	}
	
	/**
	 * Display formatted error message
	 */
	function error($message,$heading="Error")
	{
		echo "<div class=\"custom_error\"><h3>{$heading}</h3><hr /><p>{$message}</p></div>";
	}

	/**
	 * Display formatted alert
	 */
	
	$messagesBuffer = array();
	function alert($message,$heading="Special Note")
	{
		$GLOBALS['messagesBuffer'] []= array(
			'message' => $message,
			'heading' => $heading,
		);
	}
	
	
	function alertRender($messages = false) {
		
		if( $messages === false ) {
			$messages = $GLOBALS['messagesBuffer'];
		}
		
		if( !empty($messages) ) {
			echo '<div class="center-align-float-wrapper">';
				echo '<div class="center-align-float">';
					echo '<div class="header-meta flash-message error"><span class="wrapper">';
						$msgs = array();
						foreach($messages as $item) {
							$msgs []= $item['message'];
						}
						echo implode('. ', $msgs);
					echo '</span></div>';
				echo '</div>';
			echo '</div>';
		}
		
	}
	
	

	/**
	 * Format an SQL Date and output an english date
	 */
	function format_date($date)
	{
		ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})",$date,$regs);
		if(count($regs)>0)
			if(intval($regs[1])==0 && intval($regs[2]==0) && intval($regs[3])==0)
				return "";
			else
				return $regs[3]."/".$regs[2]."/".$regs[1];
		else
			return $date;
	}
	
	/**
	 * Multi-level input sanitation
	 */
	function safe($text,$level=3)
	{
		switch($level)
		{
			case 1:
				$text=mb_ereg_replace("[^a-zA-Z0-9]*","",$text);
			case 1:
				if(mb_strlen($text)>255)
					$text=mb_substr($text,0,255);
			case 2:
				$text=urldecode($text);
			case 3:
				$text=stripslashes($text);
				$text=str_replace("\"","",$text);
				$text=str_replace("'","",$text);
			case 4:
				$text=strip_tags($text);
				$text=htmlentities($text);
			case 5:
				$text=trim($text);
		}
		return $text;
	}
	
	/**
	 * Multi-level input sanitation
	 */
	function make_safe($text,$level=2)
	{
		switch($level)
		{
			case 1:
				if(strlen($text)>255)
					$text=substr($text,0,255);
			case 2:
				$text=urldecode($text);
			case 3:
				$text=stripslashes($text);
				$text=str_replace("\"","",$text);
				$text=str_replace("'","",$text);
			case 4:
				$text=strip_tags($text);
				$text=htmlentities($text);
			case 5:
				$text=trim($text);
		}
		return $text;
	}

	/**
	 * Display formatted error message
	 */
	function make_doubly_safe($text)
	{
		return ereg_replace("[^a-zA-Z]*","",$text);
	}

	/**
	 * Format a price
	 */
	function price($price)
	{
		return (($price < 0)?'-':'')."$".number_format(abs($price), 2, ".", "");
	}

	/**
	 * Truncate text at full word boundary
	 */
	function truncate($text,$length,$end_char="...")
	{
		if(mb_strlen($text)<=$length)
			return $text;
		else
		{
			if(strstr(trim($text)," "))
				return trim(strrev(strstr(strrev(mb_substr(strip_tags($text),0,$length))," "))).$end_char;
			else
				return trim(mb_substr(strip_tags($text),0,$length)).$end_char;
		}
	}

	/**
	 * Return filetype based on file extension
	 */
	function getFileType($filename)
	{
		if(stristr($filename,".jpg"))
			return "jpg";
		else if(stristr($filename,".gif"))
			return "gif";
		else if($stristr($filename,".png"))
			return "png";
		else
			return false;
	}

	/**
	 * Strip slashes and convert html entities for a multi-dimensional array
	 */
	function array_stripslashes($array)
	{
		if(is_array($array))
		{
			if(get_magic_quotes_gpc())
			{
				$keys=array_keys($array);
				foreach($keys as $key)
				{
					if(is_array($array[$key]))
						$array[$key]=array_stripslashes($array[$key]);
					else
					{
						if(get_magic_quotes_gpc())
							$array[$key]=htmlentities(stripslashes($array[$key]),ENT_QUOTES,"UTF-8");
						else
							$array[$key]=htmlentities($array[$key],ENT_QUOTES,"UTF-8");
					}
				}
			}
		}
		return $array;
	}

	/**
	 * Reduce unused product options
	 */
	function reduce_options($array)
	{
		$len=count($array);
		for($i=0;$i<$len;$i++)
		{
			if(array_count($array[$i])==0)
				unset($array[$i]);
		}
		return $array;
	}

	/**
	 * count how many elemens are in a branch of a multi-dimensional array
	 */
	function array_count($array)
	{
		$count=0;
		foreach($array as $item)
		{
			if(is_array($item))
				$count+=array_count($item);
			else if(trim($item)!="")
				$count++;
		}
		return $count;
	}

	/**
	 * Convert old style options to new style
	 */
	function fix_options($options)
	{
		$options=reduce_options($options);
		for($i=0;$i<count($options);$i++)
		{
			$options[$i]['options']=str_replace("\r","\n",trim($options[$i]['options']));
			$options[$i]['options']=str_replace("\n\n","\n",$options[$i]['options']);
			$options[$i]['value']=explode("\n",$options[$i]['options']);

			unset($options[$i]['options']);
			$options[$i]['price']=str_replace("\r","\n",trim($options[$i]['price']));
			$options[$i]['price']=str_replace("\n\n","\n",$options[$i]['price']);
			$options[$i]['price']=explode("\n",$options[$i]['price']);
			for($j=0;$j<count($options[$i]['value']);$j++)
			{
				$options[$i]['weight'][$j]="";
				$options[$i]['stock'][$j]="";
			}
		}
		return $options;
	}

	/**
	 * Convert product specs to save format
	 */
	function fix_specs($specs)
	{
		$num=max(count($specs['name']),count($specs['value']));
		$ret=array();
		for($i=0;$i<$num;$i++)
		{
			$foo['name']=$specs['name'][$i];
			$foo['value']=$specs['value'][$i];
			array_push($ret,$foo);
		}
		return $ret;
	}

	/**
	 * Retrieve option values from option array
	 */
	function get_options($seloptions,$prodoptions)
	{
		$len=count($prodoptions);
		for($i=0;$i<$len;$i++)
		{
			$options[$i]['name']=$prodoptions[$i]['name'];
			$options[$i]['value']=$prodoptions[$i]['value'][$seloptions[$i]];
			$options[$i]['price']=$prodoptions[$i]['price'][$seloptions[$i]];
			$options[$i]['weight']=$prodoptions[$i]['weight'][$seloptions[$i]];
			$options[$i]['stock']=$prodoptions[$i]['stock'][$seloptions[$i]];
		}
		return $options;
	}

	/**
	 * Convert seconds to d h m s (day hour minute seconds)
	 * Uses a "seive" method with progressibely smaller units
	 * if nothing is set to be returned then return the number of seconds only (even if 0)
	 */

	function idle($sec)
	{
		if($sec/86400>1)
		{
			$ret.=floor($sec/86400)." d ";
			$sec=$sec-floor($sec/86400)*86400;
		}

		if($sec/3600>1)
		{
			$ret.=floor($sec/3600)." h ";
			$sec=$sec-floor($sec/3600)*3600;
		}

		if($sec/60>1)
		{
			$ret.=floor($sec/60)." m ";
			$sec=$sec-floor($sec/60)*60;
		}

		if($sec>0)
			$ret.=$sec." s";
		else if($ret=="")
			$ret.=$sec." s";

		return trim($ret);
	}
	
	function makeurl($parentid,$name)
	{

	}

	/**
	 * Format TxnVars array from ADODb GetRows record
	 */
	function get_txnvars($rows)
	{
		$vars=array();
		foreach($rows as $row)
		{
			$vars[$row['name']]=$row['value'];
		}
		return $vars;
	}
	
	/**
	 * Strip slashes if required
	 */
	function stripslashes_if($string)
	{
		if(get_magic_quotes_gpc())
			return stripslashes($string);
		else
			return $string;
	}

	function checkdelivery($item)
	{
		$keys=array_keys($item);
		$present=false;
		foreach($keys as $key)
		{
			if(trim($item[$key])!="" && $key!="cart_id")
				$present=true;
		}
		return $present;
	}
	
	function trail2array($trail)
	{
		$count=0;
		$trail=explode("\n",$trail);
		foreach($trail as $item)
		{
			$item=explode(":",trim($item));
			$array[$count]=$item[1];
			$count++;
		}
		return $array;
	}

	function tail($array)
	{
		return array_slice($array,1);
	}

	function datetotime($str)
	{
		if(trim($str)!="")
		{
			mb_ereg("([0-9]+)/([0-9]+)/([0-9]+)",$str,$regs);
			return mktime(0,0,0,$regs[2],$regs[1],$regs[3]);
		}
		else
			return 0;
	}

	function name2page($name)
	{
		$name=str_replace(" ","-",trim($name));
		$name=mb_ereg_replace(
			"[^a-z0-9-]*"
			,""
			,iconv(
				"UTF-8"
				,"UTF-7//TRANSLIT"
				,mb_strtolower($name)
			)
		);
		while(strstr($name,"--"))
			$name=str_replace("--","-",$name);
		return $name;
	}
	
	function uuid()
	{
	    return sprintf('%04x%04x%04x%04x',mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535)); 
	}
	
	function delete_structure($path, $delete_this_path=false)
	{
		$path = trim($path);
		if($path[strlen($path)-1] != '/')
			$path .= '/';
		
		$dir=new DirectoryIterator($path);
		
		$dirs=array();
		$files=array();
		
		$yesterday = time() - 86400;
		foreach($dir as $item)
		{
			$count++;
			if(!$item->isDot())
			{
				if(!$item->isDir())
				{
					$file=$item->getFileInfo();
					$filename = trim($file->getFilename());
					if($filename != '')
						@unlink($path.$filename);
				}
				else
				{
					$file=$item->getFileInfo();
					$dirname = trim($file->getFilename());
					if($dirname != '')
						$dirs[] = $path.$dirname."/";
				}
			}
		}
		foreach($dirs as $dir)
		{
			delete_structure($dir);
			@rmdir($dir);
		}
		
		if($delete_this_path)
			@rmdir($path);
	}
	
	function isDateValid($date)
	{
		$time = strtotime($date);
		if($time === false)
			return false;
		if($time === strtotime('0000-00-00'))
			return false;
			
		return true;
	}
	
	function get_google_coords($address)
	{
		$googleapirequest = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
		if(!($data = @file_get_contents($googleapirequest)))
			return false;
		$data = json_decode($data, true);
		if($data['status'] != 'OK')
			return false;
			
		return array(
			'lat' => $data['results'][0]['geometry']['location']['lat']+0
			,'long' => $data['results'][0]['geometry']['location']['lng']+0
		);
	}
?>
