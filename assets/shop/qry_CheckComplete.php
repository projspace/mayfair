<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$ok=true;
	if(trim($country_id)!="" &&
		trim($name)!="" &&
		trim($address)!="" &&
		trim($postcode)!="" &&
		trim($country)!="" &&
		trim($delivery_address)!="" &&
		trim($delivery_postcode)!="" &&
		trim($delivery_country)!="" &&
		trim($email)!="" &&
		trim($card_type)!="" &&
		trim($card_no)!="" &&
		trim($card_end)!="")
	{
		/* Check CC No */
		include ("../lib/lib_CreditCard.php");
		$data=credit_card::check($card_no,$card_start,$card_end);
		if (!$data['valid'])
		{
			$ok=false;
			$reason="Invalid Card Details";
		}
	}
	else
	{
		$ok=false;
		$reason="Not all required fields completed";
	}
?>
