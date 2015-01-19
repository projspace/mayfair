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
    //error_reporting(E_ALL);
    //ini_set('display_errors', '1');

	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_AdminSession.php");
	include("../lib/lib_CommonAdmin.php");

	include("../lib/lib_Payment.php");
	include("../lib/payment/lib_".$config['psp']['driver'].".php");
	include("../lib/payment/cfg_".$config['psp']['driver'].".php");
	$psp =& new $config['psp']['driver']($config,$smarty,$db);

	if(($check = $session->check()))
	{
		if($check) $session->update();

		include("../lib/pdf/class.ezpdf.php");
		include("qry_Invoice.php");
		foreach($invoice as $key=>$value)
			$invoice[$key] = iconv("UTF-8", "ISO-8859-1", $value);

		include("qry_Order.php");
        $products = $products->GetRows();
        if($order['gift_payment']+0)
        {
            $list_item_id = 0;
            foreach($products as $row)
                if($row['gift_list_item_id']+0)
                {
                    $list_item_id = $row['gift_list_item_id']+0;
                    break;
                }
            $gift_list=$db->Execute(
                sprintf("
                    SELECT
                        gl.*
                        ,addr.name delivery_name
                        ,addr.email delivery_email
                        ,addr.phone delivery_phone
                        ,addr.line1 delivery_line1
                        ,addr.line2 delivery_line2
                        ,addr.line3 delivery_line3
                        ,addr.line4 delivery_line4
                        ,addr.postcode delivery_postcode
                        ,addr.country_id delivery_country_id
                        ,sc.name delivery_country
                        ,SUM(gli.quantity) count
                    FROM
                        gift_lists gl
                    LEFT JOIN
                        shop_user_addresses addr
                    ON
                        addr.account_id = gl.account_id
                    AND
                        addr.id = gl.delivery_address_id
                    LEFT JOIN
                        shop_countries sc
                    ON
                        sc.id = addr.country_id
                    LEFT JOIN
                        gift_list_items gli
                    ON
                        gli.list_id = gl.id
                    WHERE
                        gli.id=%u
                    GROUP BY
                        gl.id
                "
                    ,$list_item_id
                )
            );
            $gift_list = $gift_list->FetchRow();
            $order['delivery_name'] = $gift_list['delivery_name'];
            $order['delivery_email'] = $gift_list['delivery_email'];
			$order['delivery_address'] = $gift_list['delivery_line1']."\n".$gift_list['delivery_line2']."\n".$gift_list['delivery_line3']."\n".$gift_list['delivery_line4'];
			$order['delivery_country'] = $gift_list['delivery_country'];
            $order['delivery_postcode'] = $gift_list['delivery_postcode'];
			$order['delivery_phone'] = $gift_list['delivery_phone'];
        }

		//595,842
		$pdf=new Cezpdf();

		//$pdf->selectFont('../lib/pdf/fonts/globe.afm');
		$pdf->selectFont('../lib/pdf/fonts/Helvetica.afm');

		$pdf->ezSetCmMargins(1,1,1,1);

		$pdf->setStrokeColor(0,0,0);
		$pdf->setLineStyle(1);

        /*$products = array(array('name'=>'Kate Spade Two of a Kind "His & Hers" Double Old Fashioned Set by Lenox', 'gift_message'=>"", 'quantity'=>1));
		$tmp = array();
		$index = 1;
		for($i=0;$i<70;$i++)
			foreach($products as $row)
			{
				$row['name'] .= ' - '.$index;
				$tmp[] = $row;
				$index++;
			}
		$products = $tmp;*/

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
				$options = implode(', ', $options);

			product(
				$row['name']
                ,$row['order_quantity']
                ,$options
			);
        }

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"shipping_note_order_".$order['id'].".pdf\"");
        header('Pragma: no-cache');

		if($filename = trim($_REQUEST['filename']))
			file_put_contents($config['path'].$filename, $pdf->ezOutput());
		else
			$pdf->ezStream();

		include("../lib/act_CloseDB.php");
	}
	else
		header("location: {$config['dir']}index.php?fuseaction=admin.accessDenied");

	function new_page($new_page = true)
	{
		global $pdf, $order;

        if($new_page)
			$pdf->ezNewPage();

		heading();

        billing(
			$order['name'].((trim($order['email']) != '')?' - '.$order['email']:'')
			,$order['address']
			,$order['postcode']
			,$order['country']
			,$order['tel']
		);

		delivery(
			$order['delivery_name'].((trim($order['delivery_email']) != '')?' - '.$order['delivery_email']:'')
			,$order['delivery_address']
			,$order['delivery_postcode']
			,$order['delivery_country']
			,$order['delivery_phone']
		);

        $pdf->y = 660;
        gift_message($order['gift_message']);

		fields();
		footer();
	}

	function footer()
	{
		global $pdf,$config,$invoice;

		//$pdf->line(25,25,570,25);
		$y=25-2-$pdf->getFontHeight(7);

		$pdf->addText(25,$y,7,$invoice['footer_left']);
		$pdf->addText(568-$pdf->getTextWidth(7,$invoice['footer_right']),$y,7,$invoice['footer_right']);
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

        $text = "<b>Packing List</b>";
		$y=842-25-$pdf->getFontHeight(16);
		$x=(595/2)-($pdf->getTextWidth(16,$text)/2);
		$pdf->addText($x,$y,16,$text);

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

	function billing($name,$address,$postcode,$country,$phone)
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
		$pdf->addText(35,$y,8,$name);
		$address=explode("\n", iconv("UTF-8", "ISO-8859-1", $address));
		for($i=0;$i<3;$i++)
		{
			if (trim($address[$i]) == '') continue;
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

	function delivery($name,$address,$postcode,$country,$phone)
	{
		global $pdf, $order;
		$pdf->rectangle(305,750,265,-75);
		$y=750;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(0,0,0);
		$pdf->addText(308,$y,9,"<b>DELIVER TO:</b>");

        if($order['gift_payment']+0)
            $pdf->addText(450,$y,9,"This is a gift registry order");

		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(8);
		$y=$y-$h;
		$pdf->addText(315,$y,8,$name);
		$address=explode("\n", iconv("UTF-8", "ISO-8859-1", $address));
		for($i=0;$i<3;$i++)
		{
			if (trim($address[$i]) == '') continue;
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

	function fields()
	{
		global $pdf;
        $h=$pdf->getFontHeight(8);

		$pdf->rectangle(25,$pdf->y,545,-13);
		$y=$pdf->y-$h;

		$pdf->setColor(0,0,0);

		$pdf->addText(35,$y,8,"<b>DESCRIPTION</b>");
        $pdf->addText(550-$pdf->getTextWidth(8,"<b>QUANTITY</b>"),$y,8,"<b>QUANTITY</b>");

        $pdf->rectangle(25,$pdf->y - 13,545,$pdf->ez['bottomMargin'] + 13 - $pdf->y);

        $pdf->y -= 13 + $h;
    }

	function gift_message($message)
	{
		global $pdf, $order;

        $message = iconv("UTF-8", "ISO-8859-1", $message);
        $left = 5;
        $right = 5;

        $h8=$pdf->getFontHeight(8);
        $h9=$pdf->getFontHeight(9);
        $initial_y = $pdf->y;

        if ( $initial_y >= $pdf->ez['bottomMargin'])
        {
            $sTest = trim($message)?:"Lorem ipsum";
            $pdf->y = $initial_y - $h9 - $h8;
            $new_page = $pdf->ezText($sTest,8,array("left"=>$left,"right"=>$right,"justification"=>full), true);
        }
        else
            $new_page = true;

        if($new_page)
        {
            new_page();
            $initial_y = $pdf->y;
        }

        $y = $initial_y - $h9;
        $pdf->addText(28,$y,9,"<b>GIFT MESSAGE</b>");
        $text = "<b>GIFT WRAP: ".(($order['packing'] !== null)?'YES':'NO')."</b>";
        $pdf->addText(565-$pdf->getTextWidth(9,$text),$y,9,$text);

        $pdf->y = $y;
		$new_y = $pdf->ezText($message,8,array("left"=>$left, "right"=>$right, "justification"=>full));

        $pdf->rectangle(25,$initial_y,545,$new_y - $initial_y - $h8);

        $pdf->y = $new_y - 25;
	}

    function product($name,$quantity,$options)
	{
		global $pdf;

        $h=$pdf->getFontHeight(8);
        $y = $pdf->y;

        $name = '<b>'.iconv("UTF-8", "ISO-8859-1", $name).'</b>';
        if($options = trim($options))
            $name .= "\n         ".$options;

        $left = 5;
        $right = 60;

        if ( $pdf->y >= $pdf->ez['bottomMargin'])
        {
            $sTest = $name;
            $pdf->y = $y + $h;
            $new_page = $pdf->ezText($sTest,8,array("left"=>$left,"right"=>$right), true);
        }
        else
            $new_page = true;

        if($new_page)
        {
            new_page();
            $y = $pdf->y;
        }

        $pdf->y = $y + $h;
        $new_y = $pdf->ezText($name,8,array("left"=>$left, "right"=>$right));

		$pdf->addText(550-$pdf->getTextWidth(8,$quantity),$y,8,$quantity);

        $pdf->y = $new_y - 2*$h;
	}

?>