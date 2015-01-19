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
	
	$params['session_id']=$session->session_id;
	if($txnvars->RecordCount()>0)
		$params['txnvars']=get_txnvars($txnvars->GetRows());
	$params['vars']=$session->session->fields;
	$params['vars']['delivery_country']=$country['name'];
	$params['vars']['billing_country']=$billing['country'];
	$params['request']=$_REQUEST;
?>