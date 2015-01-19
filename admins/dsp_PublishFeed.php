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
<h1>Publish Feed</h1><hr />
<table class="values">
	<tr>
		<th>Feed Name</th>
		<th>Message</th>
	</tr>
<?
	$keys=array_keys($messages);
	foreach($keys as $key)
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
				<td class=\"$class\">{$key}</td>
				<td class=\"$class\">{$messages[$key]}</td>
			</tr>";
	}
?>
</table>