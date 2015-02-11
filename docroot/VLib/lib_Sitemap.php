<?
	/**
	 * Author	: Marian Plesnicute
	 * Version	: 1.0
	 */
?>
<?
	class Sitemap
	{
		var $config;
		var $data;
		var $db;
		var $exclude;
		var $manual;
		var $filename;
		
		var $parser;
		var $current_tag;
		var $temp_url;
		
		function Sitemap($config, &$db)
		{
			$this->config = $config;
			$this->db =& $db;
			
			$this->data = array();
			
			include("cfg_Sitemap.php");
			if(is_array($exclude))
				$this->exclude = $exclude;
			else
				$this->exclude = array();
				
			if(is_array($manual))
				$this->manual = $manual;
			else
				$this->manual = array();
				
			$this->filename = $sitemap_file;
		}
		
		function load()
		{
			$this->parser = xml_parser_create();
			xml_set_object($this->parser, $this);
			xml_set_element_handler($this->parser, "tag_open", "tag_close");
			xml_set_character_data_handler($this->parser, "content_data");
			
			$file = $this->config['path'].$this->filename;
			
			unset($this->data);
			$this->data = array();
			$this->temp_url = false;
			$this->current_tag = false;
			
			if ($fp = fopen($file, "r"))
			{
				while ($data = fread($fp, 4096)) 
				{
					if (!xml_parse($this->parser, $data, feof($fp))) 
					{
						die(sprintf("XML error: %s at line %d",
						xml_error_string(xml_get_error_code($this->parser)),
						xml_get_current_line_number($this->parser)));
					}
				}
				xml_parser_free($this->parser);
				fclose($fp);
			}
			//var_dump($this->data);
		}
		
		function tag_open($parser, $tag, $attributes) 
	    {
	        switch(strtoupper($tag))
			{
				case 'URL':
					$this->temp_url = array();
					break;
				case 'LOC':
					break;
				case 'PRIORITY':
					break;
				case 'LASTMOD':
					break;
				case 'CHANGEFREQ':
					break;
			}
			$this->current_tag = strtoupper($tag);
			//echo 'OPEN '.$this->current_tag."\n";
	    }

	    function content_data($parser, $cdata) 
	    {
			switch($this->current_tag)
			{
				case 'URL':
					break;
				case 'LOC':
					$this->temp_url['loc'] = str_replace($this->config['dir'], '', $cdata);
					break;
				case 'PRIORITY':
					$this->temp_url['priority'] = $cdata;
					break;
				case 'LASTMOD':
					$this->temp_url['lastmod'] = $cdata;
					break;
				case 'CHANGEFREQ':
					$this->temp_url['changefreq'] = $cdata;
					break;
			}
			//echo $this->current_tag.' -> '.$cdata."\n";
	    }

	    function tag_close($parser, $tag) 
	    {
	        switch(strtoupper($tag))
			{
				case 'URL':
					$this->data[] = $this->temp_url;
					unset($this->temp_url);
					$this->temp_url = false;
					break;
				case 'LOC':
					break;
				case 'PRIORITY':
					break;
				case 'LASTMOD':
					break;
				case 'CHANGEFREQ':
					break;
			}
			$this->current_tag = false;
			//echo 'CLOSE '.$tag."\n";
	    }
		
		function save()
		{
			$file = $this->config['path'].$this->filename;
			if ($fp = fopen($file, "w"))
			{
				$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
				//$xml .= '<urlset'."\n";
				//$xml .= '	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n";
				//$xml .= '	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n";
				//$xml .= '	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n";
				//$xml .= '	url="http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";
				$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
				fwrite($fp, $xml);
				
				foreach($this->data as $url)
				{
					$xml = '<url>'."\n";
					foreach($url as $tag=>$value)
					{
						if(strtoupper($tag) == 'LOC')
							$value = $this->config['dir'].$value;
						$xml .= '<'.strtolower($tag).'>'.$value.'</'.strtolower($tag).'>'."\n";
					}
					$xml .= '</url>'."\n";
					fwrite($fp, $xml);
				}
				$xml = '</urlset>';
				fwrite($fp, $xml);
				fclose($fp);
			}
		}

		function getPageInfo($pageid)
		{
			$page_log = $this->db->Execute(
				sprintf("
					SELECT
						id
						,time
					FROM
						cms_pages_log
					WHERE
						pageid = %u
					ORDER BY
						revision DESC
					LIMIT 3
				"
					,mysql_real_escape_string($pageid)
				)
			);
			
			$page_log = $page_log->GetRows();
			if(!$page_log)
			{
				$last_mod = time();
				$changefreq = 'daily';
			}
			else
			{
				if(is_array($page_log))
				{
					if(count($page_log) == 0)
					{
						$last_mod = time();
						$changefreq = 'daily';
					}
					else
					{
						$last_mod = strtotime($page_log[0]['time']);
						
						$freq = array(0 => time());
						foreach($page_log as $log)
							$freq[] = strtotime($log['time']);
							
						$frequency = 0;
						for($i = 1; $i < count($freq);$i++)
						{
							$frequency += floor(($freq[$i-1] - $freq[$i])/3600);
						}
						$frequency = floor($frequency/(count($freq)-1));

						if($frequency >= 24*365)
							$changefreq = 'yearly';
						else
						if($frequency >= 24*30)
							$changefreq = 'monthly';
						else
						if($frequency >= 24*7)
							$changefreq = 'weekly';
						else
						if($frequency >= 24)
							$changefreq = 'daily';
						else
							$changefreq = 'hourly';
					}
				}
			}
			
			return array('last_mod'=>$last_mod, 'changefreq'=>$changefreq);
		}
			
		function add_page($pageid)
		{
			$page = $this->db->Execute(
				sprintf("
					SELECT
						id
						,url
					FROM
						cms_pages
					WHERE
						id = %u
				"
					,mysql_real_escape_string($pageid)
				)
			);
			
			$page = $page->FetchRow();
			if($page)
			{
				if($pageid != 1)
					$url = $page['url'];
				else
					$url = '';
					
				if(in_array($url, $this->exclude))
					return false;

				$pageinfo = $this->getPageInfo($pageid);
				
				if(isset($this->manual[$url]['last_mod']))
					$lastmod = $this->manual[$url]['last_mod'];
				else
					$lastmod = date('Y-m-d',$pageinfo['last_mod']).'T'.date('H:i:s',$pageinfo['last_mod']).'+00:00';
				
				if(isset($this->manual[$url]['changefreq']))	
					$changefreq = $this->manual[$url]['changefreq'];
				else
					$changefreq = $pageinfo['changefreq'];
					
				if(isset($this->manual[$url]['priority']))
					$priority = $this->manual[$url]['priority'];
				else
					$priority = 0.5;
				
				$this->data[] = array('loc'=>$url, 'priority'=>$priority, 'lastmod'=>$lastmod, 'changefreq'=>$changefreq);
				return true;
			}
			return false;
		}
		
		function update_page($pageid, $old_url = false)
		{
			$page = $this->db->Execute(
				sprintf("
					SELECT
						id
						,url
					FROM
						cms_pages
					WHERE
						id = %u
				"
					,mysql_real_escape_string($pageid)
				)
			);
			
			$page = $page->FetchRow();
			if($page)
			{
				if($pageid != 1)
					$url = $page['url'];
				else
					$url = '';

				if(in_array($url, $this->exclude))
					return false;

				if(is_string($old_url))
					$compare_url = $old_url;
				else
					$compare_url = $url;
					
				$pageinfo = $this->getPageInfo($pageid);
				foreach($this->data as $key=>$row)
					if($row['loc'] == $compare_url)
					{
						if(isset($this->manual[$url]['last_mod']))
							$lastmod = $this->manual[$url]['last_mod'];
						else
							$lastmod = date('Y-m-d',$pageinfo['last_mod']).'T'.date('H:i:s',$pageinfo['last_mod']).'+00:00';
						
						if(isset($this->manual[$url]['changefreq']))	
							$changefreq = $this->manual[$url]['changefreq'];
						else
							$changefreq = $pageinfo['changefreq'];
							
						if(isset($this->manual[$url]['priority']))
							$priority = $this->manual[$url]['priority'];
						else
							$priority = 0.5;
						
						$this->data[$key] = array('loc'=>$url, 'priority'=>$priority, 'lastmod'=>$lastmod, 'changefreq'=>$changefreq);
						return true;
					}
			}
			return false;
		}
		
		function remove_page($pageid)
		{
			$page = $this->db->Execute(
				sprintf("
					SELECT
						id
						,url
					FROM
						cms_pages
					WHERE
						id = %u
				"
					,mysql_real_escape_string($pageid)
				)
			);
			
			$page = $page->FetchRow();
			if($page)
			{
				if($pageid != 1)
					$url = $page['url'];
				else
					$url = '';

				foreach($this->data as $key=>$row)
					if($row['loc'] == $url)
					{
						unset($this->data[$key]);
						return true;
					}
			}
			return false;
		}
		
		function import()
		{
			if(count($this->exclude))
			{
				$pages = $this->db->Execute(
					sprintf("
						SELECT
							id
							,url
						FROM
							cms_pages
						WHERE
							deleted = 0
						AND
							url NOT IN (%s)
					"
						,"'".implode("','", $this->exclude)."'"
					)
				);
			}
			else
				$pages = $this->db->Execute(
					sprintf("
						SELECT
							id
							,url
						FROM
							cms_pages
						WHERE
							deleted = 0
					"
					)
				);
			$this->data = array();
			while($page = $pages->FetchRow())
			{
				if($page['id'] != 1)
					$url = $page['url'];
				else
					$url = '';
				$pageinfo = $this->getPageInfo($page['id']);
				
				if(isset($this->manual[$url]['last_mod']))
					$lastmod = $this->manual[$url]['last_mod'];
				else
					$lastmod = date('Y-m-d',$pageinfo['last_mod']).'T'.date('H:i:s',$pageinfo['last_mod']).'+00:00';
				
				if(isset($this->manual[$url]['changefreq']))	
					$changefreq = $this->manual[$url]['changefreq'];
				else
					$changefreq = $pageinfo['changefreq'];
					
				if(isset($this->manual[$url]['priority']))
					$priority = $this->manual[$url]['priority'];
				else
					$priority = 0.5;
				
				$this->data[] = array('loc'=>$url, 'priority'=>$priority, 'lastmod'=>$lastmod, 'changefreq'=>$changefreq);
			}
			$this->save();
		}

		function update()
		{
			if(count($this->exclude))
			{
				$pages = $this->db->Execute(
					sprintf("
						SELECT
							id
							,url
						FROM
							cms_pages
						WHERE
							deleted = 0
						AND
							url NOT IN (%s)
					"
						,"'".implode("','", $this->exclude)."'"
					)
				);
			}
			else
				$pages = $this->db->Execute(
					sprintf("
						SELECT
							id
							,url
						FROM
							cms_pages
						WHERE
							deleted = 0
					"
					)
				);
			$this->data = array();
			while($page = $pages->FetchRow())
			{
				if($page['id'] != 1)
					$url = $page['url'];
				else
					$url = '';
				$pageinfo = $this->getPageInfo($page['id']);
				
				if(isset($this->manual[$url]['last_mod']))
					$lastmod = $this->manual[$url]['last_mod'];
				else
					$lastmod = date('Y-m-d',$pageinfo['last_mod']).'T'.date('H:i:s',$pageinfo['last_mod']).'+00:00';
				
				if(isset($this->manual[$url]['changefreq']))	
					$changefreq = $this->manual[$url]['changefreq'];
				else
					$changefreq = $pageinfo['changefreq'];
					
				if(isset($this->manual[$url]['priority']))
					$priority = $this->manual[$url]['priority'];
				else
					$priority = 0.5;
					
				$found = false;
				foreach($this->data as $key=>$row)
					if($row['loc'] == $url)
					{
						$found = true;
						$this->data[$key] = array('loc'=>$url, 'priority'=>$priority, 'lastmod'=>$lastmod, 'changefreq'=>$changefreq);
					}
				if(!$found)
					$this->data[] = array('loc'=>$url, 'priority'=>$priority, 'lastmod'=>$lastmod, 'changefreq'=>$changefreq);
			}
			
			$products = $this->db->Execute(
				sprintf("
					SELECT
						id
						,guid
						,updated
					FROM
						shop_products
					WHERE
						id > 1
					AND
						category_id > 0
				"
				)
			);
			while($row = $products->FetchRow())
			{
				$lastmod = date('Y-m-d', strtotime($row['updated'])).'T'.date('H:i:s', strtotime($row['updated'])).'+00:00';
				$this->data[] = array('loc'=>$this->product_url($row['id'], $row['guid']), 'priority'=>0.5, 'lastmod'=>$lastmod, 'changefreq'=>'weekly');
			}
			
			$categories = $this->db->Execute(
				sprintf("
					SELECT
						id
						,name
					FROM
						shop_categories
					WHERE
						id > 1
				"
				)
			);
			while($row = $categories->FetchRow())
			{
				$lastmod = date('Y-m-01').'T00:00:00+00:00';
				$this->data[] = array('loc'=>$this->category_url($row['id'], $row['name']), 'priority'=>0.5, 'lastmod'=>$lastmod, 'changefreq'=>'monthly');
			}
		}
		
		function product_url($product_id, $product_name)
		{
			$product_name = $this->name2page(trim($product_name));
			if($product_name == '')
				$product_name = '-';
			
			//return 'product/'.$product_name.'/'.($product_id+0);
			return 'product/'.$product_name;
		}
		
		function category_url($category_id, $category_name)
		{
			$category_name = name2page(trim($category_name));
			if($category_name == '')
				$category_name = '-';
			
			return 'category/'.$category_name.'/'.($category_id+0);
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
	}
?>