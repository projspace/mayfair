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
<h1>Search Results</h1><hr />
<table class="values">
	<tr>
		<th>ID</th>
		<th>Time</th>
		<th>Name</th>
		<th>Address</th>
		<th>Country</th>
		<th>Email</th>
		<th>Total</th>
		<th>Shipping</th>
		<th>Paid</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$orders->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr>
				<td class=\"$class\">{$row['id']}</td>
				<td class=\"$class\">".date("H:i d/m/Y",$row['time'])."</td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\">{$row['address']} {$row['postcode']}</td>
				<td class=\"$class\">{$row[$keys['shop_countries.name']]}</td>
				<td class=\"$class\"><a class=\"$linkclass\" href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class right\">$".price($row['total'])."</td>
				<td class=\"$class right\">$".price($row['shipping'])."</td>
				<td class=\"$class right\">$".price($row['paid'])."</td>
				<td class=\"$class\"><a href=\"{$config['dir']}index.php?fuseaction=admin.viewOrder&amp;order_id={$row['id']}\"><img src=\"{$config['dir']}images/admin/view.png\" width=\"16\" height=\"16\" alt=\"View\" /></a></td>
			</tr>";
	}
?>
	</table>
</div>