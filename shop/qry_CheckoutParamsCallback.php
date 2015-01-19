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
	$country=$db->Execute(
		sprintf("
			SELECT
				name
			FROM
				shop_countries
			WHERE
				id=%u
		"
			,$session->session->fields['delivery_country_id']
		)
	);
	$country = $country->FetchRow();
	
	$billing_country=$db->Execute(
		sprintf("
			SELECT
				name
			FROM
				shop_countries
			WHERE
				id=%u
		"
			,$session->session->fields['billing_country_id']
		)
	);
	$billing_country = $billing_country->FetchRow();
	
	$params['session_id']=$session->session_id;
	if($txnvars->RecordCount()>0)
		$params['txnvars']=get_txnvars($txnvars->GetRows());
	$params['vars']=$session->session->fields;
	$params['vars']['delivery_country']=$country['name'];
	if($billing_country)
		$params['vars']['billing_country']=$billing_country['name'];
	$params['request']=$_REQUEST;
?>