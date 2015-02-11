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
	if(!$id)
		$id=1;
	$page=$db->Execute("SELECT * FROM util_help WHERE id='$id'");
	$contents=$db->Execute("SELECT * FROM util_help ORDER BY ord");
	$keys=$page->GetKeys();
	$row=$page->FetchRow();
	$ord=$row[$keys['util_help.ord']];
	$next=$db->Execute("SELECT id FROM util_help WHERE ord>'$ord' ORDER BY ord ASC LIMIT 1");
	$prev=$db->Execute("SELECT id FROM util_help WHERE ord<'$ord' ORDER BY ord DESC LIMIT 1");
?>