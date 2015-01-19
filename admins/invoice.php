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

	include("../lib/lib_Payment.php");
	include("../lib/payment/lib_".$config['psp']['driver'].".php");
	include("../lib/payment/cfg_".$config['psp']['driver'].".php");
	$psp =& new $config['psp']['driver']($config,$smarty,$db);

	if(($check = $session->check()) || $_REQUEST['nL'] === 'callback')
	{
		if($check) $session->update();
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"invoice_".$id.".pdf\"");
        header('Pragma: no-cache');

		include("../lib/pdf/class.ezpdf.php");
		include("qry_Invoice.php");
		foreach($invoice as $key=>$value)
			$invoice[$key] = iconv("UTF-8", "ISO-8859-1", $value);
		include("qry_Order.php");
		$products = $products->GetRows();
		//595,842
		$pdf=new Cezpdf();

		//$pdf->selectFont('../lib/pdf/fonts/globe.afm');
		$pdf->selectFont('../lib/pdf/fonts/Helvetica.afm');
		
		$pdf->ezSetCmMargins(1,1,1,1);

		$pdf->setStrokeColor(0,0,0);
		$pdf->setLineStyle(1);

		$max_full_product_lines = 61;
		$max_last_product_lines = 38;
		$producty=602;
		$producth=$pdf->getFontHeight(8);
		$page=1;
		$product_lines = 0;
				
		/*$tmp = array();
		$index = 1;
		for($i=0;$i<60;$i++)
			foreach($products as $row)
			{
				$row['name'] .= ' - Testing pagination. will remove repeating products after I\'m done '.$index;
				$tmp[] = $row;
				$index++;
			}
		$products = $tmp;*/
		
		$lines = 0;
		$total_vat = 0;
		$sub_total = 0;
		foreach($products as $row)
		{
			$lines += 2;
			if(trim($row['size']) != '' || trim($row['width']) != '' || trim($row['color']) != '')
				$lines++;
			/*
			$options = unserialize($row['order_options']);
			if(is_array($options))
				$lines += count($options);
			*/
			if($row['vat_exempt'])
			{
				$price_without_vat = $row['order_price']-$row['order_discount'];
				$vat = 0;
			}
			else
			{
				$price_without_vat = ($row['order_price']-$row['order_discount'])*100/(100+$order['vat_rate']);
				$vat = $row['order_price']-$row['order_discount'] - round($price_without_vat, 2);
			}
			
			$total_vat += round($vat, 2)*$row['order_quantity'];
			$sub_total += round($price_without_vat, 2)*$row['order_quantity'];
		}
		$pages = ceil($lines / $max_full_product_lines);
		//var_dump($pages, $lines, $max_full_product_lines, $max_last_product_lines);exit;
		if($pages == 0)
			$pages = 1;
		elseif($lines % $max_full_product_lines > $max_last_product_lines)
			$pages ++;

		new_page(false);
		
		foreach($products as $row)
		{
		
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
				
			/*$options = array();
			$seloptions=unserialize($row['order_options']);
			if(is_array($seloptions))
			for($i=0;$i<count($seloptions);$i++)
				$options[] = "<b>{$seloptions[$i]['name']}</b> : {$seloptions[$i]['value']}";*/
			
			product(
				str_replace('?', ' ', mb_convert_encoding($row['code'].''.$row['name'], "ISO-8859-1", "UTF-8" ))
				,$options
				,$row['order_quantity']
				,$row['order_price']-$row['order_discount']
				,$row['vat_exempt']
			);
		}
		if($page < $pages)
			new_page();

		if($filename = trim($_REQUEST['filename']))
			file_put_contents($config['path'].$filename, $pdf->ezOutput());
		else
			$pdf->ezStream();

		include("../lib/act_CloseDB.php");
	}
	else
		header("location: /index.php?fuseaction=admin.accessDenied");

	function new_page($new_page = true)
	{
		global $pdf, $page, $pages, $order, $producty, $product_lines;
		
		if($new_page)
		{
			$pdf->ezNewPage();
			$page++;
		}
		heading();

		billing(
			$order['name']
			,$order['address']
			,$order['postcode']
			,$order['country']
			,$order['email']
            ,$order['tel']
		);

		delivery(
			$order['delivery_name']
			,$order['delivery_address']
			,$order['delivery_postcode']
			,$order['delivery_country']
            ,$order['delivery_email']
            ,$order['delivery_phone']
		);

		details(
			$config['companyshort'].$order['id']
			,date("m/d/Y",$order['time'])
			,$order['id']
			,$order['id']
			,date("m/d/Y",time())
		);

		fields($page == $pages);

		if($page == $pages)
		{
			notes($order['notes'],"");
			total($order['total'],$order['shipping'],20);

		}
		footer();
		$producty = 602;
		$product_lines = 0;

	}
		
	function notes($notes,$terms)
	{
		global $pdf;
		//$pdf->rectangle(25,105,545,-75);
		//$pdf->line(375,105,375,105-75);
		$y=105-$pdf->getFontHeight(16);
		$pdf->setColor(0,0,0);
		//$pdf->addText(30,$y,9,"<b>NOTES:</b>");
		$pdf->addText(380,$y,9,"<b>Thank you for your order.</b>");
		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(18);
		$pdf->ezSetY($y);
		$pdf->ezText($notes,8,array(
			"left"=>5
			,"right"=>200
			,"justification"=>full
			)
		);
		$pdf->ezSetY($y);
		$pdf->ezText($terms,8,array(
			"left"=>355
			,"right"=>5
			,"justification"=>full
			)
		);

	}
	
	function footer()
	{
		global $pdf,$config,$invoice;
		
		$pdf->line(25,25,570,25);
		$y=25-2-$pdf->getFontHeight(7);

		$pdf->addText(25,$y,7,$invoice['footer_left']);
		$pdf->addText(568-$pdf->getTextWidth(7,$invoice['footer_right']),$y,7,$invoice['footer_right']);
	}

	function total($total,$shipping,$vat)
	{
		global $pdf, $order, $total_vat, $sub_total;
		
		$h=$pdf->getFontHeight(9);
		
		$pdf->rectangle(375,245,195,-1*($h+9)*6);
		$pdf->line(450,245,450,245-($h+9)*6);
		
		$y=245-$h-3;
		$h=$h+9;
		$pdf->addText(380,$y,9,"<b>Discount</b>");
		$discount = $order['promotional_discount']+$order['discount']+$order['multibuy_discount'];
		if($discount)
			$discount = "-".price($discount);
		else
			$discount = price($discount);
			
		$pdf->addText(565-$pdf->getTextWidth(9,$discount),$y,9,$discount);
		$pdf->line(375,$y-6,570,$y-6);

		/*$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Gift Voucher</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($order['gift_voucher'])),$y,9,price($order['gift_voucher']));
		$pdf->line(375,$y-6,570,$y-6);*/
		
		$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Subtotal</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($total)),$y,9,price($total));
		$pdf->line(375,$y-6,570,$y-6);
		
		/*$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Including VAT</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($total_vat)),$y,9,price($total_vat));
		$pdf->line(375,$y-6,570,$y-6);*/
		
		//$y=$y-$h;
		//$pdf->addText(380,$y,9,"<b>Carrier Bag</b>");
		//$pdf->addText(565-$pdf->getTextWidth(9,price($order['packing'])),$y,9,price($order['packing']));
		//$pdf->line(375,$y-6,570,$y-6);
		
		$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Shipping</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($shipping)),$y,9,price($shipping));
		$pdf->line(375,$y-6,570,$y-6);
		
		$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Tax</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($order['tax'])),$y,9,price($order['tax']));
		$pdf->line(375,$y-6,570,$y-6);

		$y=$y-$h;
		$pdf->addText(380,$y,9,"<b>Total</b>");
		$pdf->addText(565-$pdf->getTextWidth(9,price($total+$shipping+$order['packing']+$order['tax'])),$y,9,price($total+$shipping+$order['packing']+$order['tax']));
		//$pdf->line(375,$y-6,570,$y-6);
		//$y=$y-$h;
		//$pdf->addText(380,$y,9,"<b>Paid</b>");
		//$pdf->addText(565-$pdf->getTextWidth(9,price($order['paid'])),$y,9,price($order['paid']));
		
	}

	function heading()
	{
		global $pdf,$config,$invoice;
		$pdf->addImage(imagecreatefromjpeg("../images/pdf_logo.jpg"),490,748,76);
		//$pdf->addImage(imagecreatefrompng("../images/pdf_logo.png"),490,755,79);

		$y=810-$pdf->getFontHeight(9);
		//$pdf->addText(25,$y,9,"<b>".$invoice['company']."</b>");
		
		$h=$pdf->getFontHeight(8);
		$y=$y-$h;
		$pdf->addText(25,$y,8,$invoice['address1']);
		$y=$y-$h;
		$pdf->addText(25,$y,8,$invoice['address2']);
		$y=$y-$h;
		$pdf->addText(25,$y,8,$invoice['address3']);
		$y=$y-$h;
		$pdf->addText(25,$y,8,$invoice['address4']);

		$y=842-25-$pdf->getFontHeight(16);
		$x=(595/2)-($pdf->getTextWidth(16,"<b>Sales Invoice</b>")/2);
		$pdf->addText($x,$y,16,"<b>Sales Invoice</b>");
		
		//$pdf->addImage(imagecreatefrompng($config['protocol'].$config['url'].$config['dir']."script/image/image.php?text=".urlencode("KA - CHING")."&size=100"),$x+40,$y,75);
		
		$pdf->setColor(0,0,0);
		
		$y=$y-($h*2);
		//$pdf->addText(170,$y,8,"Contact ".$invoice['company']." on");

		//$width = $pdf->getTextWidth(8,"Contact ".$invoice['company']." on");
        $width = 80;
		$pdf->addText(170+5+$width,$y,8,"<b>Tel:</b>");
		$pdf->addText(170+30+$width,$y,8,$invoice['phone']);

		$y=$y-$h;
		$pdf->addText(170+5+$width,$y,8,"<b>Fax:</b>");
		$pdf->addText(170+30+$width,$y,8,$invoice['fax']);

		$y=$y-$h;
		$pdf->addText(170+5+$width,$y,8,"<b>Email:</b>");
		$pdf->addText(170+30+$width,$y,8,$invoice['email']);
	}

	function billing($name,$address,$postcode,$country,$email, $phone)
	{
		global $pdf;
		$pdf->rectangle(25,750,265,-75);
		$y=750;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(0,0,0);
		$pdf->addText(28,$y,9,"<b>INVOICE TO:</b>");
		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(8);
		$y=$y-$h;
		$pdf->addText(35,$y,8,$name.' - '.$email);
		$address=explode("\n", iconv("UTF-8", "ISO-8859-1", $address));
		
		for($i=0;$i<3;$i++)
		{
			if (trim($address[$i]) == '')
                continue;

			$y=$y-$h;
			$pdf->addText(35,$y,8,$address[$i]);
		}
        $y=$y-$h;
        $pdf->addText(35,$y,8,$address[3].', '.$country);
		$y=$y-$h;
		$pdf->addText(35,$y,8,$postcode);
		$y=$y-$h;
		$pdf->addText(35,$y,8,$phone);
	}

	function delivery($name,$address,$postcode,$country,$email, $phone)
	{
		global $pdf;
		$pdf->rectangle(305,750,265,-75);
		$y=750;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(0,0,0);
		$pdf->addText(308,$y,9,"<b>DELIVER TO:</b>");
		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(8);
		$y=$y-$h;
		$pdf->addText(315,$y,8,$name.(trim($email)?' - '.$email:''));
		$address=explode("\n", iconv("UTF-8", "ISO-8859-1", $address));
		for($i=0;$i<3;$i++)
		{
			if (trim($address[$i]) == '')
                continue;
            
			$y=$y-$h;
			$pdf->addText(315,$y,8,$address[$i]);
		}
        $y=$y-$h;
        $pdf->addText(315,$y,8,$address[3].', '.$country);
		$y=$y-$h;
		$pdf->addText(315,$y,8,$postcode);
		$y=$y-$h;
		$pdf->addText(315,$y,8,$phone);
	}

	function details($order,$order_date,$delivery,$invoice,$invoice_date)
	{
		global $pdf,$page,$pages;
		$pdf->rectangle(25,670,545,-50);
		$y=670;
		$h=$pdf->getFontHeight(9);
		$y=$y-$h;
		$pdf->setColor(0,0,0);
		$pdf->addText(35,$y,9,"<b>YOUR ORDER NO. :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(140,$y,9,$order);

		$pdf->setColor(0,0,0);
		$pdf->addText(315,$y,9,"<b>OUR INVOICE NO. :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$invoice);


		$y=$y-$h-5;
		$pdf->setColor(0,0,0);
		$pdf->addText(35,$y,9,"<b>ORDERED ON :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(140,$y,9,$order_date);

		$pdf->setColor(0,0,0);
		$pdf->addText(315,$y,9,"<b>INVOICE DATE :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$invoice_date);

		$y=$y-$h-5;
		$pdf->setColor(0,0,0);
		$pdf->addText(35,$y,9,"<b>OUR DELIVERY NO. :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(140,$y,9,$delivery);

		$pdf->setColor(0,0,0);
		$pdf->addText(315,$y,9,"<b>PAGE :</b>");
		$pdf->setColor(0,0,0);
		$pdf->addText(420,$y,9,$page.' of '.$pages);
	}

	function fields($last_page)
	{
		global $pdf;
		$pdf->rectangle(25,615,545,-13);
		$y=615-$pdf->getFontHeight(8);

		$pdf->setColor(0,0,0);
		$pdf->addText(35,$y,8,"<b>DESCRIPTION</b>");

		$pdf->addText(460-$pdf->getTextWidth(8,"<b>QUANTITY</b>"),$y,8,"<b>QUANTITY</b>");
		
		$pdf->addText(510-$pdf->getTextWidth(8,"<b>PRICE</b>"),$y,8,"<b>PRICE</b>");

		$pdf->addText(565-$pdf->getTextWidth(8,"<b>TOTAL</b>"),$y,8,"<b>TOTAL</b>");
		$pdf->setColor(0,0,0);

		if($last_page)
			$pdf->rectangle(25,602,545,-357);
		else
			$pdf->rectangle(25,602,545,-575);
	}

	function product($name,$options,$quantity,$price,$vat_exempt)
	{
		global $pdf;
		global $producty,$producth,$product_lines, $max_full_product_lines, $max_last_product_lines, $page, $pages, $order;
		
		$lines = 2;
		if(is_array($options))
			$lines += count($options);
		if($lines+$product_lines > (($page == $pages)?$max_last_product_lines:$max_full_product_lines))
			new_page();

		if($vat_exempt)
		{
			$price_without_vat = $price;
			$vat = 0;
		}
		else
		{
			$price_without_vat = $price*100/(100+$order['vat_rate']);
			$vat = $price - round($price_without_vat, 2);
		}
		
			
		$producty=$producty-$producth;

		$pdf->addText(35,$producty,8,'<b>'.iconv("UTF-8", "ISO-8859-1", $name).'</b>');

		$pdf->addText(460-$pdf->getTextWidth(8,$quantity),$producty,8,$quantity);
		
		$pdf->addText(510-$pdf->getTextWidth(8,price($price)),$producty,8,price($price));

		$pdf->addText(565-$pdf->getTextWidth(8,price($price*$quantity)),$producty,8,price($price*$quantity));
		$product_lines += 2;
		
		$producty=$producty-$producth;
		if(is_array($options))
			foreach($options as $option)
			{
				$pdf->addText(55,$producty,8,$option);
				$product_lines++;
				$producty=$producty-$producth;
			}
	}

?>