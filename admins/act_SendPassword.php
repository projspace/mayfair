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
	$row=$account->FetchRow();
	$vars['loginurl']=$config['protocol'].$config['url'].$config['dir']."admin";
	$vars['username']=$row['username'];
	$vars['password']=$row['password'];
	$mail->sendMessage($vars,"AdminSendPassword",$row['email'],$row['username']);
?>