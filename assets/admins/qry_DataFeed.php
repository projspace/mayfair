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
	$query="
		SELECT
			class
		FROM
			shop_datafeeds
		WHERE";
	$count=0;
	foreach($_POST['feedid'] AS $id)
	{
		if($count>0)
		{
			$query.="
				OR";
		}
		$query.="
			id=%u";
		$count++;
	}
	$feeds=$db->Execute(vsprintf($query,$_POST['feedid']));
?>