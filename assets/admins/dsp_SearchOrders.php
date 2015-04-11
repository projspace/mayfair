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
<style type="text/css">@import url(<?= $config['dir'] ?>css/calendar-blue.css);</style>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/calendar.js"></script>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/calendar-setup.js"></script>
<h1>Search Orders</h1><hr />
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.search&amp;act=search">
<div class="legend">Order Details</div>
<div class="form">
	<label for="id">Order ID</label>
	<input type="text" id="id" name="id" size="4" /><br />

	<label for="date">Order Date</label>
	<input type="text" id="date" name="date" /><a id="trigger" href="null();"><img src="<?= $config['dir'] ?>images/admin/calendar.png" alt="Calendar" width="20" height="14" /></a><br />

	<label for="name">Customer Name</label>
	<input type="text" id="name" name="name" /><br />

	<label for="address">Customer Address</label>
	<input type="text" id="address" name="address" /><br />

	<label for="postcode">Customer Postcode</label>
	<input type="text" id="postcode" name="postcode" size="8" /><br />

	<label for="email">Customer Email</label>
	<input type="text" id="email" name="email" /><br />
</div>
<div class="formRight">
	<input class="submit" type="submit" value="Search" />
</div>
<script type="text/javascript">Calendar.setup({inputField:"date",ifFormat:"%d/%m/%Y",button:"trigger"});</script>
</form>