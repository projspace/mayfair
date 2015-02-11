<?
	$vars['order_id']=$_REQUEST['order_id'];
	$vars['order']=$order;
	$vars['txnvars']=$txnvars;
	$vars['cart']=$products->GetRows();

	$mail->sendMessage($vars,"ProcessedConfirmation",$order['email'],$order['name']);
?>