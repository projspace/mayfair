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
<link href="<?= $config['dir'] ?>css/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $config['dir'] ?>lib/cfg_Admin.js.php"></script>
<script type="text/javascript" src="<?= $config["dir"] ?>lib/lib_Admin.js"></script><script language="javascript">
<!--
	function confMove(name,pageid,parentid)
	{
		var answer = confirm ("Are you sure you want to move this page to be under "+name+"?")
		if (answer)
		{
			form=opener.document.getElementById('postback');
			form.action=dir+"index.php?fuseaction=admin.movePage&act=move";
			node=opener.document.createElement('input');
			node.setAttribute('type','hidden');
			node.setAttribute('name','pageid');
			node.setAttribute('value',pageid);
			form.appendChild(node);
			node=null;

			node=opener.document.createElement('input');
			node.setAttribute('type','hidden');
			node.setAttribute('name','parentid');
			node.setAttribute('value',parentid);
			form.appendChild(node);
			node=null;

			form.submit();
			form=null;
			window.close();
		}
	}
-->
</script>
</head>

<body>
<? print trim($Fusebox["layout"]); ?>
</body>
</html>