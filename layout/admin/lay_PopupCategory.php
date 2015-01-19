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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= PRODUCT_NAME." ".PRODUCT_VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?= $config["dir"] ?>css/admin.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
	function confMove()
	{
		var answer = confirm ("Are you sure?")
		if (answer)
		{
			callback();
			window.close();
		}
	}

	function callback()
	{
		opener.location='<?= $config["dir"] ?>index.php?fuseaction=admin.moveCategory&category_id=<?=$_REQUEST['category_id'] ?>&parent_id=<?=$_REQUEST['parent_id'] ?>&act=move';
	}
-->
</script>
</head>

<body>
<? print trim($Fusebox["layout"]); ?>
</body>
</html>
