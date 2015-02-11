<?
	if(!$order_id)
		return;
		
	$vars['cart']=$products;
	$vars['params']=$params;
	$vars['order']=$order;
	$vars['order_id']=$order_id;

	$attachment = 'cache/temp/invoice_'.$order_id.'.pdf';
	file_get_contents($config['dir'].'admins/invoice.php?nL=callback&order_id='.$order_id.'&filename='.urlencode($attachment));
	
	$sent=$mail->sendMessage($vars,"OrderConfirmation",$order['email'],$order['name'], array($config['path'].$attachment));
	$sent2=$mail->sendMessage($vars,"OrderNotification",$config['mail']['notify'],"");
	@unlink($config['path'].$attachment);
    if ($handle = fopen($config['path'].'debug.txt', 'a'))
    {
        fwrite($handle, "\n".'===== Order Confirmation =====');
        fwrite($handle, "\n".'sent: '.var_export($sent, true));
        fwrite($handle, "\n".'sent2: '.var_export($sent2, true));
        fwrite($handle, "\n".'vars: '.var_export($vars, true));
        fclose($handle);
    }
?>
