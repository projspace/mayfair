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
<h1><?= $report->fields['name'] ?></h1><hr>
<?
	include("../lib/reports/rpt_".strtolower(ereg_replace("[^A-Za-z0-9]","",$report->fields['name'])).".php");
?>