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
<form id="postback" method="post" action="none"></form>
<h1>Current Sessions</h1><hr />
<table class="values">
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Remote Address</th>
		<th>Idle Time</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$sessions->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "
			<tr>
				<td class=\"$class\">{$row['username']}</td>
				<td class=\"$class\"><a href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class\">{$row['hostname']} ({$row['remote_addr']})</td>
				<td class=\"$class\">".idle(time()-$row['lastaccess'])."</td>
				<td class=\"$class right\">";
				
		if($acl->check("endSession"))
			echo "<button class=\"form\" title=\"End Session\" onclick=\"return postbackConf(
					this
					,'endSession'
					,['session_id']
					,[{$row['id']}]
					,'end'
					,'session')\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Delete\" /></button>";
		echo "</td>
			</tr>";
	}
?>
</table>