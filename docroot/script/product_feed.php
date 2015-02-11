<?
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	set_time_limit(0);
	try 
	{
		include("../lib/cfg_Config.php");
		include("../lib/adodb/adodb.inc.php");
		include("../lib/act_OpenDB.php");
		include("../lib/lib_Common.php");
		
		$cfg_import['start_time'] = time();
		$cfg_import['import_id'] = uuid();

		$cfg_import['log_file'] = true;
		$cfg_import['filename_log'] = $config['path'].'script/logs/product_feed_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
		
		$cfg_import['xml_file'] = $config['path'].'product_feed.xml';
		
		function import_log($text, $print = false)
		{
			global $cfg_import;
			$date = date('d/m/Y H:i:s');
			
			if($cfg_import['log_file'])
			{
				if ($handle = @fopen($cfg_import['filename_log'], 'a'))
				{
					fwrite($handle, "\n".'===================='.$date.'===================='."\n".$text."\n");
					fclose($handle);
				}
				else
					throw new Exception('Unable to open log file "'.$cfg_import['filename_log'].'"');
			}
			if($print)
				echo "<br />\n".'===================='.$date.'===================='."<br />\n<pre>".$text."</pre><br />\n";
		}
		
		$log_url = str_replace($config['path'], $config['dir'], $cfg_import['filename_log']);
		echo "<br />\nLog file: <a href='".$log_url."'>".$log_url."</a><br />\n";
		
		$vat=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_variables
				WHERE
					name = 'vat'
			"
			)
		);
		$vat = $vat->FetchRow();
		$vat = $vat['value'];
		
		$area_prices=$db->Execute(
			$sql = sprintf("
				SELECT
					*
				FROM
					shop_area_prices
				WHERE
					area_id = %u
				ORDER BY
					weight ASC
			"
				,1
			)
		);
		$area_prices = $area_prices->GetRows();
		
		$country=$db->Execute(
			sprintf("
				SELECT
					shop_countries.name
					,shop_areas.name AS area_name
					,shop_areas.over_weight_unit
					,shop_areas.over_price
					,shop_areas.free_shipping
					,shop_countries.area_id
					,shop_countries.price
					,shop_countries.minimal_price
				FROM
					shop_countries
					,shop_areas
				WHERE
					shop_countries.id=%u
				AND
					shop_countries.area_id=shop_areas.id
			"
				,1
			)
		);
		$country = $country->FetchRow();
		
		$default_google_category=$db->Execute(
			sprintf("
				SELECT
					google_categories.name
				FROM
					google_categories
					,shop_variables
				WHERE
					shop_variables.name = 'google_category_id'
				AND
					google_categories.id = shop_variables.value
			"
			)
		);
		$default_google_category = $default_google_category->FetchRow();
		
		$products = $db->Execute(
			sprintf("
				SELECT
					shop_products.*
					,shop_brands.name brand
				FROM
					shop_products
				LEFT JOIN
					shop_brands
				ON
					shop_brands.id = shop_products.brand_id
				WHERE
					shop_products.id > 1
				AND
					shop_products.category_id > 0
				AND
					shop_products.parent_id = 0
				AND
					shop_products.hidden = 0
			"
			)
		);
		
		if(!($hXml = @fopen($cfg_import['xml_file'], 'w')))
			throw new Exception('Cannot open xml file');

		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		$xml .= '<channel>';
		$xml .= '<title><![CDATA[Bloch Shop]]></title>';
		$xml .= '<link><![CDATA['.$config['dir'].']]></link>';
		$xml .= '<description><![CDATA[Bloch Shop Description]]></description>';
		fwrite($hXml, $xml);
		
		while($product = $products->FetchRow())
		{
			$category=$db->Execute(
				sprintf("
					SELECT
						shop_categories.*
						,google_categories.name google_category
					FROM
						shop_categories
					LEFT JOIN
						google_categories
					ON
						google_categories.id = shop_categories.google_category_id
					WHERE
						shop_categories.id=%u
				"
					,$product['category_id']
				)
			);
			$category = $category->FetchRow();
			if(!$category)
			{
				import_log('Category not found for product id:'.$product['id'].' style:'.$product['code'].'. Skipping...');
				continue;
			}
			
			$google_category = $category['google_category'];
			if(trim($google_category) == '')
				$google_category = $default_google_category['name'];
			
			if(trim($google_category) == '')
			{
				import_log('Google category not found for product id:'.$product['id'].' style:'.$product['code'].'. Skipping...');
				continue;
			}
			
			$options = $db->Execute(
				sprintf("
					SELECT
						shop_product_options.*
						,shop_sizes.name size_name
						,shop_sizes.alt size_alt_name
						,shop_sizes.ord size_ord
						,shop_widths.name width_name
						,shop_widths.ord width_ord
						,shop_colors.name color_name
						,shop_colors.ord color_ord
						,shop_colors.hexa color_hexa
						,shop_colors.image_type
					FROM
						shop_product_options
					LEFT JOIN
						shop_sizes
					ON
						shop_sizes.id = shop_product_options.size_id
					LEFT JOIN
						shop_widths
					ON
						shop_widths.id = shop_product_options.width_id
					LEFT JOIN
						shop_colors
					ON
						shop_colors.id = shop_product_options.color_id
					WHERE
						shop_product_options.product_id = %u
				"
					,$product['id']
				)
			);
			
			$results = $db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_product_images
					WHERE
						product_id=%u
					ORDER BY
						id ASC
				"
					,$product['id']
				)
			);
			$images = array();
			while($row = $results->FetchRow()) 
				$images[] = $config['dir'].'images/product/view/'.$row['id'].'.'.$row['imagetype'];
			
			if(count($images) == 0)
			{
				import_log('Image not found for product id:'.$product['id'].' style:'.$product['code'].'. Skipping...');
				continue;
			}
			
			
			$trail = array();
			foreach(unserialize($category['trail']) as $index=>$row)
			{
				if($index < 2)
					continue;
				$trail[] = $row['name'];
			}
			$trail = implode(' > ', $trail);
			
			if(!$product['no_shipping'] && !$product['pick_up_only']) //shippable
			{
				$last_weight = -1;
				$last_price = 0;
				$found = false;
				foreach($area_prices as $row)
				{
					if($last_weight < $product['weight'] && $product['weight'] <= $row['weight'])
					{
						$shipping = $row['price'];
						$found = true;
						break;
					}
					else
					{
						$last_weight = $row['weight'];
						$last_price = $row['price'];
					}
				}
				if($last_weight < 0)
					$last_weight = 0;
					
				if(!$found)
				{
					$over_weight = $product['weight'] - $last_weight;
					$shipping = $last_price + $country['over_price']*ceil($over_weight / $country['over_weight_unit']);
				}
			}
			else
				$shipping = 0;
			
			while($option = $options->FetchRow())
			{
                $product_price = $product['price']+$option['price'];
                if($product['vat'])
                    $product_price = $product_price*(100+$vat)/100;

				$xml = array();
				
				$xml[] = '<title><![CDATA['.truncate($product['name'], 70).']]></title>';
				$xml[] = '<link><![CDATA['.product_url($product['id'], $product['name']).'?option_id='.$option['id'].']]></link>';
				$xml[] = '<description><![CDATA['.truncate($product['description'], 10000).']]></description>';
				$xml[] = '<g:id><![CDATA['.$product['id'].'-'.$option['id'].']]></g:id>';
				$xml[] = '<g:product_type><![CDATA['.$trail.']]></g:product_type>';
				$xml[] = '<g:google_product_category><![CDATA['.$google_category.']]></g:google_product_category>';
				$first = false;
				foreach($images as $image)
				{
					if(!$first)
					{
						$xml[] = '<g:image_link><![CDATA['.$image.']]></g:image_link>';
						$first = true;
					}
					else
						$xml[] = '<g:additional_image_link><![CDATA['.$image.']]></g:additional_image_link>';
				}
				$xml[] = '<g:condition><![CDATA[new]]></g:condition>';
				
				$xml[] = '<g:availability><![CDATA['.(($option['quantity']>0)?'in stock':'out of stock').']]></g:availability>';
				$xml[] = '<g:price><![CDATA['.number_format($product_price, 2, ".", "").' USD]]></g:price>';
				$xml[] = '<g:brand><![CDATA['.$product['brand'].']]></g:brand>';
				$xml[] = '<g:gtin><![CDATA['.$option['upc_code'].']]></g:gtin>';
				
				$xml[] = '<g:gender><![CDATA['.$product['gender'].']]></g:gender>';
				$xml[] = '<g:age_group><![CDATA['.$product['age'].']]></g:age_group>';
				$xml[] = '<g:color><![CDATA['.$option['color_name'].']]></g:color>';
				$size = array();
				$size[] = ($product['alt_size'] && trim($option['size_alt_name'])!='')?$option['size_alt_name']:$option['size_name'];
				if(trim($option['width_name']) != '')
					$size[] = $option['width_name'];
				$xml[] = '<g:size><![CDATA['.implode(' : ', $size).']]></g:size>';
				$xml[] = '<g:item_group_id><![CDATA['.$product['id'].']]></g:item_group_id>';
				$xml[] = '<g:adwords_grouping><![CDATA['.$category['name'].']]></g:adwords_grouping>';
				
				$xml[] = '<g:shipping><g:country>US</g:country><g:price><![CDATA['.number_format($shipping, 2, ".", "").' USD]]></g:price></g:shipping>';
				$xml[] = '<g:shipping_weight><![CDATA['.$product['weight'].' g]]></g:shipping_weight>';
				
				$xml[] = '<g:online_only>n</g:online_only>';
				
				fwrite($hXml, '<item>'.implode("\n", $xml).'</item>');
			}
		}
		
		$xml = '</channel></rss>';
		fwrite($hXml, $xml);
		fclose($hXml);
	}
	catch (Exception $e)
	{
		$msg = 'Caught exception: '."\n";
		$msg .= 'message: '.$e->getMessage()."\n";
		$msg .= 'code: '.$e->getCode()."\n";
		$msg .= 'file: '.$e->getFile()."\n";
		$msg .= 'line: '.$e->getLine()."\n";
		$msg .= 'trace string: '.$e->getTraceAsString()."\n";
		$msg .= 'trace array: '.var_export($e->getTrace(), true);
		import_log($msg, true);
	}
?>