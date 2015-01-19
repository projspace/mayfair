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
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_AdminSession.php");
	include("../lib/lib_CommonAdmin.php");
	
	
	if($session)
	{
		$session->update();

		$shipping = $_POST['shipping']+0;
		$process = $_POST['process']+0;
		if(!$shipping && !$process)
		{
			header("location: ".$config['dir']."index.php?fuseaction=admin.orders");
			exit;
		}
		
		if($shipping)
		{
			include("../lib/pdf/class.ezpdf.php");
			include("qry_ShippingOrders.php");
			//595,842
			$pdf=new Cezpdf();

			$pdf->selectFont('../lib/pdf/fonts/Helvetica.afm');

			$pdf->ezSetCmMargins(1,1,1,1);
			$pdf->setStrokeColor(0,0,0);
			$pdf->setLineStyle(1);
			$page=1;
			$producth = $pdf->getFontHeight(8);
			$max_pap_product_lines = 60; //59;
			$max_sn_product_lines = 64; //63;
			$producty = 640;
			$product_lines = 0;
			
			// Pick and Pack List
			/*$tmp = array();
			for($i=0;$i<=15;$i++)
				foreach($products as $row)
					$tmp[] = $row;
			$products = $tmp;*/
			
			$pap_page = 1;
			$pap_pages = 0;
			foreach($products as $row)
			{
				$pap_pages += 2;
				if(trim($row['size']) != '' || trim($row['width']) != '' || trim($row['color']) != '')
					$pap_pages++;
				/*
				$options = unserialize($row['options']);
				if(is_array($options))
					$pap_pages += count($options);
				*/
			}
			$pap_pages = ceil($pap_pages / $max_pap_product_lines);
			if($pap_pages == 0)
				$pap_pages = 1;

			new_pap_page(false);
			
			foreach($products as $row)
			{
				/*
				$options = array();
				$seloptions=unserialize($row['options']);
				if(is_array($seloptions))
				for($i=0;$i<count($seloptions);$i++)
					$options[] = "<b>{$seloptions[$i]['name']}</b> : {$seloptions[$i]['value']}";
				*/
				$options = array();
                if(trim($row['upc_code']) != '')
                    $options[] = 'SKU: '.$row['upc_code'];
				if(trim($row['size']) != '')
					$options[] = 'Size: '.$row['size'];
				if(trim($row['width']) != '')
					$options[] = 'Option: '.$row['width'];
				if(trim($row['color']) != '')
					$options[] = 'Color: '.$row['color'];
				if(count($options))
					$options = array(implode(', ', $options));
					
				pap_product(
					$row['code']
					,str_replace('?', ' ', mb_convert_encoding($row['name'], "ISO-8859-1", "UTF-8" ))
					,$options
					,$row['count']
				);
			}
			
			// Shipping Notes
			foreach($orders as $order)
			{
				/*$tmp = array();
				for($i=0;$i<=15;$i++)
					foreach($order['products'] as $row)
					{
						$tmp[] = $row;
					}
				$order['products'] = $tmp;*/
				
				$sn_page = 0;
				$sn_pages = 0;
				foreach($order['products'] as $row)
				{
					$sn_pages += 2;
					if(trim($row['size']) != '' || trim($row['width']) != '' || trim($row['color']) != '')
						$sn_pages++;
					/*
					$options = unserialize($row['options']);
					if(is_array($options))
						$sn_pages += count($options);
					*/
				}
				$sn_pages = ceil($sn_pages / $max_sn_product_lines);
				if($sn_pages == 0)
					$sn_pages = 1;
					
				new_sn_page();
				
				foreach($order['products'] as $row)
				{
					/*
					$options = array();
					$seloptions=unserialize($row['options']);
					if(is_array($seloptions))
					for($i=0;$i<count($seloptions);$i++)
						$options[] = "<b>{$seloptions[$i]['name']}</b> : {$seloptions[$i]['value']}";
					*/
					$options = array();
					if(trim($row['size']) != '')
						$options[] = 'Size: '.$row['size'];
					if(trim($row['width']) != '')
						$options[] = 'Option: '.$row['width'];
					if(trim($row['color']) != '')
						$options[] = 'Color: '.$row['color'];
					if(count($options))
						$options = array(implode(', ', $options));
						
					sn_product(
						$row['code']
						,str_replace('?', ' ', mb_convert_encoding($row['name'], "ISO-8859-1", "UTF-8" ))
						,$options
						,$row['count']
					);
				}
			}
			
			$pdf_content = $pdf->ezOutput();
			$filename = 'shipping_'.date('Y-m-d_H_i_s').'_'.uuid().'.pdf';
			$pdf_file = $config['path'].'downloads/pick_and_pack/'.$filename;
			if(@file_put_contents($pdf_file, $pdf_content))
			{
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_pick_and_pack
						SET
							filename = %s
							,time = NOW()
					"
						,$db->Quote($filename)
					)
				);
				$pap_id=$db->Insert_ID();
				if($pap_id && count($order_ids))
				{
					$sql = array();
					foreach($order_ids as $order_id)
						$sql[] = sprintf("(%u, %u)", $pap_id, $order_id);
					
					$db->Execute(
						sprintf("
							INSERT INTO
								shop_pick_and_pack_orders
							(
								pap_id
								,order_id
							)
							VALUES
							%s
						"
							,implode(',', $sql)
						)
					);
				}
			}
			$pdf->ezStream();
		}
		
		if($process)
		{
			include("act_ProcessOrders.php");
			if(!$shipping)
			{
				header("location: ".$config['dir']."index.php?fuseaction=admin.orders");
				exit;
			}
		}
		include("../lib/act_CloseDB.php");
	}
	else
		header("location: ".$config['dir']."index.php?fuseaction=admin.accessDenied");

	function heading($title)
	{
		global $pdf,$config;
		$pdf->addImage(imagecreatefromgif("../images/pdf_logo.gif"),490,755,79);
		//$pdf->addImage(imagecreatefrompng("../images/pdf_logo.png"),490,755,79);

		$y=842-25-$pdf->getFontHeight(16);
		$x=(595/2)-($pdf->getTextWidth(16,"<b>".$title."</b>")/2);
		//$pdf->setColor(255,0,0);
		//$pdf->addText($x,$y,16,"<b>".$title."</b>");
		//$pdf->setColor(0,0,0);
		
		$pdf->addImage(imagecreatefrompng($config['dir']."script/image/image.php?text=".urlencode($title)."&size=100"),$x,$y-20,100);
		
		$pdf->line(25,740,570,740);
	}
		
	function footer()
	{
		global $pdf,$config;
		
		$pdf->line(25,25,570,25);
		$y=25-2-$pdf->getFontHeight(7);

		$pdf->addText(25,$y,7,$config['invoice']['footer_left']);
		$pdf->addText(568-$pdf->getTextWidth(7,$config['invoice']['footer_right']),$y,7,$config['invoice']['footer_right']);
	}
	
	function details($order_ids, $order_count, $order_value)
	{
		global $pdf,$config;
		$y=740;
		$h=$pdf->getFontHeight(9);
		
		
		//$orders = implode(', ', array_map(create_function('$a', 'return "'.$config['companyshort'].'".$a;'), $order_ids));
		$orders = implode(', ', $order_ids);
		$y=$y-2*$h;
		$pdf->addText(25,$y,9,"<b>ORDER NO.:</b>");
		$pdf->addText(140,$y,9,$orders);
		
		$y=$y-2*$h;
		$pdf->addText(25,$y,9,"<b>Total number of orders:</b>");
		$pdf->addText(140,$y,9,$order_count);
		
		$y=$y-2*$h;
		$pdf->addText(25,$y,9,"<b>Total value of orders:</b>");
		$pdf->addText(140,$y,9,'�'.price($order_value));
	}
	
	function pap_fields()
	{
		global $pdf;
		$pdf->rectangle(25,660,545,-13);
		$y=660-$pdf->getFontHeight(8);

		$pdf->addText(35,$y,8,"<b>STYLE</b>");
		$pdf->addText(110,$y,8,"<b>PRODUCT</b>");
		$pdf->addText(430,$y,8,"<b>QUANTITY</b>");
		$pdf->addText(565-$pdf->getTextWidth(8,"<b>AVAILABLE Y/N</b>"),$y,8,"<b>AVAILABLE Y/N</b>");

		$pdf->rectangle(25,647,545,-560);
	}

	function pap_notes($page, $pages)
	{
		global $pdf;
		
		$y=55-$pdf->getFontHeight(9);
		$pdf->addText(25,$y,9,"<b>Packed By:</b>");
		$w = $pdf->getTextWidth(9,"<b>Packed By:</b>");
		$pdf->line(25+10+$w,$y,25+10+200+$w,$y);
		
		$pdf->addText(570-$pdf->getTextWidth(9,'Page '.$page.' of '.$pages),$y,9,'Page '.$page.' of '.$pages);
	}
	
	function pap_product($sku,$name,$options,$quantity)
	{
		global $pdf;
		global $producty,$producth,$product_lines, $max_pap_product_lines;
		
		$lines = 2;
		if(is_array($options))
			$lines += count($options);
		if($lines+$product_lines > $max_pap_product_lines)
			new_pap_page();
		
		$producty=$producty-$producth;
		$pdf->addText(35,$producty,8,$sku);
		$pdf->addText(110,$producty,8,"<b>$name</b>");
		$pdf->addText(470-$pdf->getTextWidth(8,$quantity),$producty,8,$quantity);
		$product_lines += 2;
		
		$producty=$producty-$producth;
		if(is_array($options))
			foreach($options as $option)
			{
				$pdf->addText(130,$producty,8,$option);
				$product_lines++;
				$producty=$producty-$producth;
			}
	}
	
	function new_pap_page($new_page = true)
	{
		global $pdf, $page, $pap_page, $pap_pages, $order_ids, $order_count, $order_value, $producty, $product_lines;
		
		if($new_page)
		{
			$pdf->ezNewPage();
			$page++;
			$pap_page++;
		}
		//heading('Pick and Pack List');
		heading('YAY! NEW STUFF');
		footer();
		details($order_ids, $order_count, $order_value);
		pap_fields();
		pap_notes($pap_page, $pap_pages);
		$producty = 640;
		$product_lines = 0;
	}
	
	function new_sn_page($new_page = true)
	{
		global $config, $pdf, $page, $producty, $product_lines, $order, $sn_page, $sn_pages;
		
		if($new_page)
		{
			$pdf->ezNewPage();
			$page++;
			$sn_page++;
		}
		//heading('Shipping Note');
		heading('YAY! NEW STUFF');
		footer();
		delivery($order['delivery_name'], $order['delivery_address'], $order['delivery_postcode'], $order['delivery_country'], $order['delivery_email']);
		sn_details($config['companyshort'].$order['id'], date("d/m/Y",$order['time']), $sn_page, $sn_pages);
		sn_fields();
		$producty = 625;
		$product_lines = 0;
	}
	
	function delivery($name,$address,$postcode,$country,$email)
	{
		global $pdf;
		$pdf->rectangle(25,730,265,-75);
		$y=730;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(255,0,0);
		$pdf->addText(28,$y,9,"<b>DELIVER TO:</b>");
		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(8);
		$y=$y-$h;
		$pdf->addText(35,$y,8,$name.' - '.$email);
		$address=explode("\n",$address);
		for($i=0;$i<3;$i++)
		{
			$y=$y-$h;
			$pdf->addText(35,$y,8,$address[$i]);
		}
		$y=$y-$h;
		$pdf->addText(35,$y,8,$postcode);
		$y=$y-$h;
		$pdf->addText(35,$y,8,$country);
	}
	
	function sn_details($order_no,$order_date,$page,$pages)
	{
		global $pdf;
		$pdf->rectangle(305,730,265,-75);
		$y=730;
		$h=$pdf->getFontHeight(9);
		$y=$y-$h;
		$pdf->setColor(255,0,0);
		$pdf->addText(315,$y,9,"<b>ORDER NO. :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$order_no);
		
		$y=$y-$h-5;
		$pdf->setColor(255,0,0);
		$pdf->addText(315,$y,9,"<b>ORDERED ON :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$order_date);
		
		$y=$y-$h-5;
		$pdf->setColor(255,0,0);
		$pdf->addText(315,$y,9,"<b>PAGE :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$page.' of '.$pages);
	}
	
	function sn_fields()
	{
		global $pdf;
		$pdf->rectangle(25,645,545,-13);
		$y=645-$pdf->getFontHeight(8);

		$pdf->addText(35,$y,8,"<b>STYLE</b>");
		$pdf->addText(110,$y,8,"<b>PRODUCT</b>");
		$pdf->addText(565-10-$pdf->getTextWidth(8,"<b>QUANTITY</b>"),$y,8,"<b>QUANTITY</b>");

		$pdf->rectangle(25,632,545,-595);
	}
	
	function sn_product($sku,$name,$options,$quantity)
	{
		global $pdf;
		global $producty,$producth,$product_lines, $max_sn_product_lines;
		
		$lines = 2;
		if(is_array($options))
			$lines += count($options);
		if($lines+$product_lines > $max_sn_product_lines)
			new_sn_page();
		
		$producty=$producty-$producth;
		$pdf->addText(35,$producty,8,$sku);
		$pdf->addText(110,$producty,8,"<b>$name</b>");
		$pdf->addText(560-10-$pdf->getTextWidth(8,$quantity),$producty,8,$quantity);
		$product_lines += 2;
		
		$producty=$producty-$producth;
		if(is_array($options))
			foreach($options as $option)
			{
				$pdf->addText(130,$producty,8,$option);
				$product_lines++;
				$producty=$producty-$producth;
			}
	}
?>