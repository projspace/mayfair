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
	
	/**
	 * Sanitation function for user-submitted data
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
				$text=stripslashes_if($text);
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
	 * Shortens block of text by breaking at nearest word boundary to desired length
	 */
	function truncate($text,$length)
	{
		if(mb_strlen($text)<=$length)
			return $text;
		else
		{
			if(strstr(trim($text)," "))
				return trim(strrev(strstr(strrev(mb_substr(strip_tags($text),0,$length))," ")));
			else
				return trim(mb_substr(strip_tags($text),0,$length));
		}
	}

	/**
	 * Return filetype based on extension
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
	 * Remove slashes from a multi-dimensional array
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
						$array[$key]=htmlentities(stripslashes($array[$key]));
				}
			}
		}
		return $array;
	}

	/**
	 * Remove unused options from an array
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
	 * Return number of items in a multi-dimensional array
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
	 * Convert old-style carriage return delimmited options to new style
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
	 * Convert cart options into actual options
	 */
	function get_options($seloptions,$prodoptions)
	{
		$seloptions=unserialize($seloptions);
		$prodoptions=unserialize($prodoptions);

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

	function error($message,$heading="Error")
	{
		echo "<div class=\"error\"><h3>{$heading}</h3><hr /><p>{$message}</p></div>";
	}

	/**
	 * Check if an order already exists by checking for a specified transaction variable
	 */
	function check_txnvar($var,$val)
	{
		global $db;
		$check=$db->Execute(
			sprintf("
				SELECT
					shop_orders.id
				FROM
					shop_orders
					,shop_order_txnvars
				WHERE
					shop_order_txnvars.name=%s
				AND
					shop_order_txnvars.value=%s
				AND
					shop_orders.id=shop_order_txnvars.order_id
			"
				,$db->Quote($var)
				,$db->Quote($val)
			)
		);
		if($check->RecordCount()==0)
			return false;
		else
			return true;
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

	/**
	 * Add sessionid to links in content if not using cookies
	 */
	function add_sessionid($content)
	{
		global $config;
		if(!USECOOKIE && !SEARCHENGINE)
		{
			//Get list of links
			preg_match_all("/<a[^>]*href=\"([^\"]*)\"[^>]*>/",$content,$links);
			$tag=$links[0];
			$url=$links[1];
			$count=count($url);
			for($i=0;$i<$count;$i++)
			{
				//Check if internal link
				if(!stristr($url[$i],"href=\"http://"))
				{
					//Check for query string
					$sid=$config['shop']['session_id']."=".$_REQUEST[$config['shop']['session_id']];
					if(stristr($url[$i],"?"))
						$newurl=$url[$i]."&amp;".$sid;
					else
						$newurl=$url[$i]."?".$sid;
					$content=str_replace($tag[$i],str_replace($url[$i],$newurl,$tag[$i]),$content);
				}
			}
		}
		return $content;
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
	
	function price($price)
	{
		return (($price < 0)?'-':'')."$".number_format(abs($price), 2, ".", "");
	}
	
	function category_url($category_id, $category_name)
	{
		global $config;
		
		$category_name = name2page(trim($category_name));
		if($category_name == '')
			$category_name = '-';
		
		return $config['dir'].'category/'.$category_name.'/'.($category_id+0);
	}
	
	function product_url($product_id, $product_name)
	{
		global $config;
		
		$product_name = name2page(trim($product_name));
		if($product_name == '')
			$product_name = '-';
		
		//return $config['dir'].'product/'.$product_name.'/'.($product_id+0);
		return $config['dir'].'product/'.$product_name;
	}
	
	function quick_product_url($product_id, $product_name)
	{
		global $config;
		
		$product_name = name2page(trim($product_name));
		if($product_name == '')
			$product_name = '-';
		
		return $config['dir'].'quick-product/'.$product_name;
	}
	
	function safe($var)
	{
		return strip_tags(trim($var));
	}
	
	function subcategories_ids($category_id)
	{
		global $db;
		$category_ids = array($category_id+0);
		$ids = array($category_id+0);
		while(count($ids))
		{
			$results=$db->Execute(
				sprintf("
					SELECT
						id
					FROM
						shop_categories
					WHERE
						parent_id IN (%s)
				"
					,implode(',', $ids)
				)
			);
			$ids = array();
			while($row = $results->FetchRow())
			{
				$ids[] = $row['id'];
				$category_ids[] = $row['id'];
			}
		}
		return $category_ids;
	}
	
	function uuid()
	{
	    return sprintf('%04x%04x%04x%04x',mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535),mt_rand(0, 65535)); 
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

    function get_date($date)
    {
        $date = explode('/', $date);
        $date = array($date[1], $date[0], $date[2]);
        return implode('-', array_reverse($date));
    }
?>
