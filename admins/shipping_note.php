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

		include("qry_ShippingNote.php");

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"shipping_note_".$_REQUEST['list_id'].".pdf\"");
        header('Pragma: no-cache');

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
				$row['purchaser']
				,$row['name']
				,$row['gift_message']
                ,$row['quantity']
                ,$options
			);
        }

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
		global $pdf, $gift_list;

        if($new_page)
			$pdf->ezNewPage();

		heading();

		billing(array(
			'Name: '.$gift_list['name']
			,'Type: '.(($gift_list['public']+0)?'public':'private')
			,'Status: '.$gift_list['status']
			,'Delivery after: '.date('m/d/Y', strtotime($gift_list['delivery_after']))
			,'Product no.: '.$gift_list['count']
		));

		delivery(
			$gift_list['delivery_name'].((trim($gift_list['delivery_email']) != '')?' - '.$gift_list['delivery_email']:'')
			,$gift_list['delivery_line1']."\n".$gift_list['delivery_line2']."\n".$gift_list['delivery_line3']."\n".$gift_list['delivery_line4']
            ,$gift_list['delivery_postcode']
			,$gift_list['delivery_country']
			,$gift_list['delivery_phone']
		);

		fields();
		footer();
        
		$pdf->y = 630;
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

		$y=842-25-$pdf->getFontHeight(16);
		$x=(595/2)-($pdf->getTextWidth(16,"<b>Gift Registry</b>")/2);
		$pdf->addText($x,$y,16,"<b>Gift Registry</b>");

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

	function billing($details)
	{
		global $pdf;
		$pdf->rectangle(25,750,265,-78);
		$y=750;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(0,0,0);
		$pdf->addText(28,$y,9,"<b>GIFT LIST:</b>");
		$pdf->setColor(0,0,0);
		$h=$pdf->getFontHeight(8);
        foreach($details as $detail)
        {
            $y=$y-$h;
            $pdf->addText(35,$y,8,$detail);
        }
	}

	function delivery($name,$address,$postcode,$country,$phone)
	{
		global $pdf;
		$pdf->rectangle(305,750,265,-78);
		$y=750;
		$y=$y-$pdf->getFontHeight(9);
		$pdf->setColor(0,0,0);
		$pdf->addText(308,$y,9,"<b>DELIVER TO:</b>");
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

		$pdf->rectangle(25,660,545,-13);
		$y=660-$h;

		$pdf->setColor(0,0,0);

		$pdf->addText(35,$y,8,"<b>PURCHASER</b>");
    	$pdf->addText(169,$y,8,"<b>PRODUCT</b>");
    	$pdf->addText(340,$y,8,"<b>QTY</b>");
        $pdf->addText(370,$y,8,"<b>GIFT MESSAGE</b>");

        $pdf->rectangle(25,647,545,-618);
    }

	function product($purchaser, $name, $message, $quantity, $options)
	{
		global $pdf;

        $h=$pdf->getFontHeight(8);
        $y = $pdf->y;

        //$purchaser = $name = $message = '1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0';

        $purchaser = iconv("UTF-8", "ISO-8859-1", $purchaser);
        $name = '<b>'.iconv("UTF-8", "ISO-8859-1", $name).'</b>';
        if($options = trim($options))
            $name .= "\n    ".$options;
        $message = iconv("UTF-8", "ISO-8859-1", $message);

        $left1 = 140;
        $right1 = 230;

        $left2 = 340;
        $right2 = 5;

        $left3 = 5;
        $right3 = 410;

        if ( $pdf->y >= $pdf->ez['bottomMargin'])
        {
            $sTest = $name;
            $pdf->y = $y + $h;
            $new_page1 = $pdf->ezText($sTest,8,array("left"=>$left1,"right"=>$right1), true);

            $sTest = trim($message)?:"Lorem ipsum";
            $pdf->y = $y + $h;
            $new_page2 = $pdf->ezText($sTest,8,array("left"=>$left2,"right"=>$right2,"justification"=>full), true);

            $sTest = $purchaser;
            $pdf->y = $y + $h;
            $new_page3 = $pdf->ezText($sTest,8,array("left"=>$left3,"right"=>$right3), true);
            
            $new_page = $new_page1 || $new_page2 || $new_page3;
        }
        else
            $new_page = true;

        if($new_page)
        {
            new_page();
            $y = $pdf->y;
        }

        $pdf->y = $y + $h;
        $new_y1 = $pdf->ezText($name,8,array("left"=>$left1, "right"=>$right1));
        $pdf->y = $y + $h;
		$new_y2 = $pdf->ezText($message,8,array("left"=>$left2, "right"=>$right2, "justification"=>'full'));
        $pdf->y = $y + $h;
		$new_y3 = $pdf->ezText($purchaser,8,array("left"=>$left3, "right"=>$right3));

		$pdf->addText(355-$pdf->getTextWidth(8,$quantity),$y,8,$quantity);

        $pdf->y = min($new_y1, $new_y2, $new_y3) - 2*$h;
	}

?>